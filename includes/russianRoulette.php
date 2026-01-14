<?php

function getLobbiesList($conn){
    try{
        $sql = "SELECT l.id, 
            l.name, 
            l.bet, 
            l.created_at AS created, 
            u.username AS host,
            COUNT(lp.id) AS players
            FROM lobbies l 
            JOIN users u ON u.id = l.created_by
            LEFT JOIN lobby_players lp ON lp.lobby_id = l.id
            WHERE l.status = 'waiting' AND l.max_players = 2
            GROUP BY l.id, l.name, u.username, l.bet, l.created_at
            ORDER BY 
            CASE WHEN COUNT(lp.id) < l.max_players THEN 0 ELSE 1 END ASC,
            l.created_at DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $lobbies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($lobbies as &$lobby){
            $lobby['mode'] = "pvp";
            $lobby['maxPlayers'] = 2;
            $lobby['status'] = $lobby['players'] === 2 ? "Penuh" : "Menunggu";
        }

        echo json_encode(["success" => true, "lobbies" => $lobbies]);
    } catch (Exception $e){
        die(json_encode([
            "success" => false,
            "message" => "Gagal membuat lobby"
        ]));
    }
}

function addLobby($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $gameMode = $data['gameMode'];
    $bet = (int)$data['lobbyBet'];
    $password = $data['password'];
    $maxPlayer = $gameMode === "bot" ? 1 : 2;

    $bullets = array_fill(0, 6, 0);
    $randomIndex = rand(0, 5);
    $bullets[$randomIndex] = 1;

    $lobby = [];

    try{
        $conn->beginTransaction();

        $stmt = $conn->prepare("INSERT INTO lobbies (`name`, `created_by`, `status`, `bet`, `password`, `max_players`, `bullets`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $id, "waiting", $bet, $password, $maxPlayer, json_encode($bullets)]);

        $lobbiesId = $conn->lastInsertId();

        $stmt = $conn->prepare("INSERT INTO lobby_players (lobby_id, user_id, is_host) VALUES (?, ?, ?)");
        $stmt->execute([$lobbiesId, $id, 1]);

        if($gameMode === "pvp"){
            $sql = "SELECT l.id, 
                l.name, 
                l.bet, 
                l.created_at AS created, 
                u.username AS host,
                COUNT(lp.id) AS players
                FROM lobbies l 
                JOIN users u ON u.id = l.created_by
                LEFT JOIN lobby_players lp ON lp.lobby_id = l.id
                WHERE l.status = 'waiting' AND l.max_players = 2 AND l.id = ?
                GROUP BY l.id, l.name, u.username, l.bet, l.created_at
                LIMIT 1";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([$lobbiesId]);
            $lobby = $stmt->fetch(PDO::FETCH_ASSOC);

            $lobby['mode'] = "pvp";
            $lobby['maxPlayers'] = 2;
            $lobby['status'] = $lobby['players'] === 2 ? "Penuh" : "Menunggu";
        }

        $conn->commit();

        echo json_encode(["success" => true, "message" => "Berhasil membuat lobby", "lobby" => $lobby ?: $lobbiesId]);
    } catch (Exception $e) {
        die(json_encode([
            "success" => false,
            "message" => "Gagal membuat lobby"
        ]));
    }
}

function validateJoinLobby($conn){
    $data = json_decode(file_get_contents("php://input"), true);
    
    $stmt = $conn->prepare("SELECT 
        l.max_players, 
        l.password, 
        COUNT(lp.id) AS players
        FROM lobbies l
        LEFT JOIN lobby_players lp ON lp.lobby_id = l.id
        WHERE l.id = ?
        GROUP BY l.max_players, l.password
    ");
    $stmt->execute([(int)$data['id']]);
    $data_lobby = $stmt->fetch(PDO::FETCH_ASSOC);

    // cek ada lobi gak
    if(!$data_lobby){
        echo json_encode([
            "success" => false,
            "message" => "Lobby tidak ditemukan"
        ]);
        return;
    }

    //cek penuh belum
    if($data_lobby['players'] === $data_lobby['max_players']){
        echo json_encode([
            "success" => false,
            "message" => "Lobby sudah penuh"
        ]);
        return;
    }

    echo json_encode([
        "success" => true,
        "message" => "Berhasil masuk ke lobby",
        "password" => $data_lobby['password']
    ]);
}

function joinLobby($conn, $jwt_token) {
    $id = auth($jwt_token)->user_id;
    $lobbyId = json_decode(file_get_contents("php://input"), true)['id'];

    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("SELECT COUNT(*) as players, l.max_players 
                                FROM lobby_players lp
                                JOIN lobbies l ON l.id = lp.lobby_id
                                JOIN profiles p ON p.user_id = p.user_id
                                WHERE lp.lobby_id = ? AND l.status = 'waiting' AND lp.user_id != ? AND p.user_id = ? AND p.chip >= l.bet
                                FOR UPDATE");
        $stmt->execute([$lobbyId, $id, $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['players'] < $row['max_players']) {
            $stmt = $conn->prepare("INSERT INTO lobby_players (lobby_id, user_id) VALUES (?, ?)");
            $stmt->execute([$lobbyId, $id]);
            $conn->commit();
            echo json_encode([
                "success" => true,
                "message" => "Berhasil join"
            ]);
        } else {
            $conn->rollBack();
            echo json_encode([
                "success" => false,
                "message" => "Lobby sudah penuh atau dalam permainan atau chip Anda kurang"
            ]);
        }
    } catch (Exception $e) {
        $conn->rollBack();
        die(json_encode([
            "success" => false,
            "message" => "Gagal join ke lobby",
        ]));
    }
}

function getLobbyData($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;
    $lobbyId = json_decode(file_get_contents("php://input"), true)['id'];

    try{
        // cek host apa bukan
        $stmt = $conn->prepare("SELECT is_host FROM lobby_players WHERE user_id = ? AND lobby_id = ?");
        $stmt->execute([$id, $lobbyId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $isHost = $row ? (bool)$row['is_host'] : false;

        //cek player atau bukan
        $stmt = $conn->prepare("SELECT id FROM lobby_players WHERE user_id = ? AND lobby_id = ?");
        $stmt->execute([$id, $lobbyId]);
        $isPlayer = $stmt->fetch(PDO::FETCH_ASSOC);

        //ambil data host dan lobby
        $stmt = $conn->prepare("SELECT l.bet, 
            u.username, 
            p.photo, 
            lp.is_ready
            FROM lobbies l
            JOIN users u ON u.id = l.created_by
            LEFT JOIN lobby_players lp ON lp.user_id = l.created_by AND lp.lobby_id = l.id
            LEFT JOIN profiles p ON p.user_id = l.created_by
            WHERE l.id = ?
            LIMIT 1");
        $stmt->execute([$lobbyId]);
        $dataHost = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        if(!$dataHost){
            throw new Exception("Lobby tidak ditemukan");
        }

        $bet = $dataHost['bet'];
        unset($dataHost['bet']);

        //ambil data player 2
        $stmt = $conn->prepare("SELECT u.username,
            p.photo,
            lp.is_ready
            FROM lobby_players lp
            JOIN users u ON u.id = lp.user_id
            LEFT JOIN profiles p ON p.user_id = lp.user_id
            WHERE lp.lobby_id = ? AND lp.is_host = 0
            LIMIT 1");
        $stmt->execute([$lobbyId]);
        $dataPlayer = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        //total player ready
        $stmt = $conn->prepare("SELECT COUNT(id) AS ready_players FROM lobby_players WHERE lobby_id = ? AND is_ready = 1");
        $stmt->execute([$lobbyId]);
        $readyPlayers = $stmt->fetch(PDO::FETCH_ASSOC)['ready_players'] ?: 0;
        
        echo json_encode([
            "success" => true,
            "bet" => $bet,
            "is_host" => (bool)$isHost,
            "is_player" => (bool)$isPlayer,
            "data_host" => $dataHost,
            "data_player" => $dataPlayer,
            "ready_players" => $readyPlayers
        ]);
    } catch (Exception $e) {
        die(json_encode([
            "success" => false,
            "message" => "Gagal memuat lobby",
            "error" => $e->getMessage()
        ]));
    }
}

function updateStatusReady($conn, $jwt_token) {
    $id = auth($jwt_token)->user_id;
    $lobbyId = json_decode(file_get_contents("php://input"), true)['id'];

    try{
        $conn->beginTransaction();

        $stmt = $conn->prepare("UPDATE lobby_players SET is_ready = 1 WHERE lobby_id = ? AND user_id = ?");
        $success = $stmt->execute([$lobbyId, $id]);

        if(!$success){
            $conn->rollBack();
            echo json_encode(["success" => false, "message" => "Gagal siap"]);
            return;
        }

        $conn->commit();
        
        echo json_encode(["success" => true, "message" => "Pemain siap"]);
    } catch (Exception $e) {
        $conn->rollBack();
        die(json_encode([
            "success" => false,
            "message" => "Gagal siap",
            "error" => $e->getMessage()
        ]));
    }
}

function sendMessageLobby($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;
    $lobby = json_decode(file_get_contents("php://input"), true);

    try{
        $conn->beginTransaction();

        $stmt = $conn->prepare("INSERT INTO lobby_chats (lobby_id, user_id, `message`) VALUES (?, ?, ?)");
        $stmt->execute([$lobby['id'], $id, $lobby['message']]);

        $conn->commit();

        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        $conn->rollBack();
        die(json_encode([
            "success" => false,
            "message" => "Gagal mengirim pesan",
        ]));
    }
}

function updateAndGetDataLobby($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;
    $lobbyId = json_decode(file_get_contents("php://input"), true)['id'];
    $lastChatId = json_decode(file_get_contents("php://input"), true)['lastChatId'];
    
    try{
        //update data
        $stmt = $conn->prepare("UPDATE lobby_players SET last_seen_lobby = CURRENT_TIMESTAMP WHERE user_id = ? AND lobby_id = ?");
        $stmt->execute([$id, $lobbyId]);

        //data host
        $stmt = $conn->prepare("SELECT l.bet, 
            u.username, 
            p.photo, 
            lp.is_ready
            FROM lobbies l
            JOIN users u ON u.id = l.created_by
            LEFT JOIN lobby_players lp ON lp.user_id = l.created_by AND lp.lobby_id = l.id
            LEFT JOIN profiles p ON p.user_id = l.created_by
            WHERE l.id = ?
            LIMIT 1");
        $stmt->execute([$lobbyId]);
        $dataHost = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        if(!$dataHost){
            throw new Exception("Lobby tidak ditemukan");
        }

        //ambil data player 2
        $stmt = $conn->prepare("SELECT u.username,
            p.photo,
            lp.is_ready
            FROM lobby_players lp
            JOIN users u ON u.id = lp.user_id
            LEFT JOIN profiles p ON p.user_id = lp.user_id
            WHERE lp.lobby_id = ? AND lp.is_host = 0
            LIMIT 1");
        $stmt->execute([$lobbyId]);
        $dataPlayer = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        //data messages
        $stmt = $conn->prepare("SELECT lc.id, u.username AS sender, lc.user_id, lc.message 
            FROM lobby_chats lc
            JOIN users u ON u.id = lc.user_id 
            WHERE lc.lobby_id = ? AND lc.id > ?
            ORDER BY lc.id ASC");
        $stmt->execute([$lobbyId, $lastChatId]);
        $dataMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($dataMessages as &$dataMessage){
            if($dataMessage['user_id'] === $id){
                $dataMessage['sender'] = "Anda";
            }
        }

        // cek kalo mulai gitu
        $dataGame = [];
        $stmt = $conn->prepare("SELECT created_by, `status` FROM lobbies WHERE id = ?");
        $stmt->execute([$lobbyId]);
        $lobby = $stmt->fetch(PDO::FETCH_ASSOC);

        if($lobby["created_by"] != $id && $lobby["status"] == "waiting"){
            $stmt = $conn->prepare("SELECT last_seen_ingame, joined_at, NOW() AS `now` FROM lobby_players WHERE lobby_id = ? AND user_id != ?");
            $stmt->execute([$lobbyId, $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if(strtotime($data["last_seen_ingame"]) > strtotime($data["joined_at"])){
                $conn->prepare("UPDATE lobbies SET status = 'starting' WHERE id = ?")->execute([$lobbyId]);

                $dataGame["status"] = "starting";
                $dataGame["countdown"] = max(0, 5 - (strtotime($data["now"]) - strtotime($data["last_seen_ingame"])));
            }
        }

        echo json_encode([
            "success" => true, 
            "dataMessages" => $dataMessages, 
            "host" => $dataHost,
            "player" => $dataPlayer,
            "dataGame" => $dataGame
        ]);
    } catch (Exception $e) {
        die(json_encode([
            "success" => false,
            "message" => "Lobby tidak ditemukan",
        ]));
    }
}

function beforeStartGame($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;
    $lobbyId = json_decode(file_get_contents("php://input"), true)['id'];

    try{
        $conn->beginTransaction();

        //cek lobby tersedia gak
        $stmt = $conn->prepare("SELECT l.created_by, l.`status`, l.bet, l.bullets, l.chamber_index, lp.user_id AS enemy
            FROM lobbies l 
            LEFT JOIN lobby_players lp ON lp.lobby_id = l.id AND lp.user_id != l.created_by AND l.max_players = 2
            WHERE l.id = ?
        ");
        $stmt->execute([$lobbyId]);
        $dataLobby = $stmt->fetch(PDO::FETCH_ASSOC);

        if((int)$dataLobby['created_by'] !== $id && (int)$dataLobby['enemy'] !== $id){
            echo json_encode([
                "success" => false,
                "message" => "Anda bukan pemilik lobby ini",
                "reddirect_modal" => true
            ]);
            return;
        }

        if($dataLobby['status'] === "playing" || $dataLobby['status'] === "finished"){
            echo json_encode([
                "success" => false,
                "message" => $dataLobby['status'] === "playing" ? "Lobby sedang dalam permainan" : "Pertandingan telah selesai",
                "reddirect_modal" => true
            ]); 
            return;
        }

        $stmt = $conn->prepare("UPDATE lobby_players SET last_seen_ingame = CURRENT_TIMESTAMP WHERE user_id = ? AND lobby_id = ?");
        $stmt->execute([$id, $lobbyId]);

        $conn->commit();

        echo json_encode([
            "success" => true,
            "message" => "Memulai permainan",
            "reddirect_modal" => false,
            "game" => [
                "bet" => $dataLobby['bet'], 
                "bullets" => $dataLobby['bullets'],
                "chamber_index" => $dataLobby['chamber_index']
            ]
        ]);
    } catch (Exception $e) {
        $conn->rollBack();
        die(json_encode([
            "success" => false,
            "message" => "Lobby tidak ditemukan",
            "reddirect_modal" => true,
        ]));
    }
}

function startGamePvp($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;
    $lobbyId = json_decode(file_get_contents("php://input"), true)['id'];

    try{
        $conn->beginTransaction();

        $stmt = $conn->prepare("SELECT 
            l.status, 
            l.bet,
            lh.user_id AS id_host,
            lp.user_id AS id_player
            FROM lobbies l 
            LEFT JOIN lobby_players lh ON lh.lobby_id = l.id AND lh.is_host = 1 AND lh.is_ready = 1
            LEFT JOIN lobby_players lp ON lp.lobby_id = l.id AND lp.is_host = 0 AND lp.is_ready = 1
            WHERE l.id = ?
        ");
        $stmt->execute([$lobbyId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$data["id_host"] || !$data["id_player"] || $data["status"] == "playing" || $data["status"] == "finished"){
            $message = !$data["id_host"] || !$data["id_player"] ? "Salah satu pemain belum siap" : "Gagal memulai";
            $conn->rollBack();
            echo json_encode([
                "success" => false,
                "message" => $message
            ]);
            return;
        }

        if($data["id_host"] == $id){
            $stmt = $conn->prepare("UPDATE lobbies SET `status` = 'playing' WHERE id = ?");
            $stmt->execute([$lobbyId]);

            $sql = "
                UPDATE profiles p 
                JOIN lobbies l ON l.id = l.id
                SET p.chip = p.chip - l.bet
                WHERE l.id = ? AND p.user_id = ? AND p.chip >= l.bet
            ";


            $stmtHost = $conn->prepare($sql);
            $stmtHost->execute([$lobbyId, $data["id_host"]]);

            $stmtPlayer = $conn->prepare($sql);
            $stmtPlayer->execute([$lobbyId, $data["id_player"]]);

            if($stmtHost->rowCount() === 0 || $stmtPlayer->rowCount() === 0){
                $msg = $stmtHost->rowCount() === 0 ? "Player 1" : "Player 2";
                $conn->rollBack();
                echo json_encode([
                    "success" => false,
                    "message" =>  $msg . ' tidak memiliki chip yang cukup',
                    "leave" => true
                ]);
                return;
            }
        } else {
            $stmt = $conn->prepare("SELECT p.chip, l.bet FROM profiles p JOIN lobbies l ON l.id = l.id WHERE p.user_id = ? AND l.id = ?");
            $stmt->execute([$id, $lobbyId]);
            $player = $stmt->fetch(PDO::FETCH_ASSOC);

            if($player["chip"] < $player["bet"]){
                echo json_encode([
                    "success" => false,
                    "message" => "Anda tidak memiliki chip yang cukup untuk bertaruh. Keluar dari lobby.",
                    "leave" => true
                ]);
                return;
            }
        }

        $conn->commit();
        echo json_encode([
            "success" => true,
            "message" => "Permainan dimulai"
        ]);
    } catch (Exception $e) {
        $conn->rollBack();
        die(json_encode([
            "success" => false,
            "message" => "Gagal memulai game",
            "error" => $e->getMessage()
        ]));
    }
}

function startGameVsBot($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;
    $lobbyId = json_decode(file_get_contents("php://input"), true)['id'];

    try{
        $conn->beginTransaction();

        $stmt = $conn->prepare("UPDATE lobbies SET `status` = 'playing' WHERE id = ? AND created_by = ?");
        $stmt->execute([$lobbyId, $id]);

        $stmt = $conn->prepare("UPDATE profiles p 
            JOIN lobbies l ON l.created_by = p.user_id AND l.id = ?
            SET p.chip = p.chip - l.bet
            WHERE p.user_id = ? AND p.chip >= l.bet");
        $stmt->execute([$lobbyId, $id]);
        if($stmt->rowCount() === 0){
            $conn->rollBack();
            echo json_encode([
                "success" => false,
                "message" => "Anda tidak memiliki chip yang cukup"
            ]);
            return;
        }

        $conn->commit();

        echo json_encode([
            "success" => true
        ]);
    } catch (Exception $e) {
        $conn->rollBack();
        die(json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]));
    }
}

function gameOverVsBot($conn, $jwt_token) {
    $id = auth($jwt_token)->user_id;
    $data = json_decode(file_get_contents("php://input"), true);
    $lobbyId = (int)$data['id'];
    $victim = $data['victim'];
    $win = $victim === "bot";

    try{
        $conn->beginTransaction();

        $stmt = $conn->prepare("SELECT bet FROM lobbies WHERE id = ? AND created_by = ?");
        $stmt->execute([$lobbyId, $id]);
        $bet = (int)$stmt->fetch(PDO::FETCH_ASSOC)['bet'];
        $totalBet = 2 * $bet;

        $sql = "
            UPDATE lobbies l
            JOIN lobby_players lp ON lp.lobby_id = l.id AND lp.user_id = l.created_by
            JOIN profiles p ON p.user_id = l.created_by
            JOIN history h ON h.user_id = l.created_by
            SET 
                l.status = 'finished', 
                lp.is_winner = :win, 
                p.chip = p.chip + CASE WHEN :win = 1 THEN :bet ELSE 0 END,
                h.win = h.win + CASE WHEN :win THEN 1 ELSE 0 END,
                h.lose = h.lose + CASE WHEN :win THEN 0 ELSE 1 END
            WHERE l.id = :lobby_id AND l.created_by = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ":win" => (int)$win,
            ":bet" => (int)($totalBet * 0.95),
            ":lobby_id" => $lobbyId,
            ":user_id" => $id
        ]);

        $conn->commit();

        echo json_encode([
            "success" => true,
            "win" => $win,
            "getOrLose" => $win ? (int)($totalBet * 0.95) : $bet
        ]);
    } catch (Exception $e) {
        $conn->rollBack();
        die(json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]));
    }
}

function updateLastSeenInGame($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;
    $lobbyId = json_decode(file_get_contents("php://input"), true)['id'];
    $stmt = $conn->prepare("UPDATE lobby_players SET last_seen_ingame = CURRENT_TIMESTAMP WHERE user_id = ? AND lobby_id = ?");
    $stmt->execute([$id, $lobbyId]);
}