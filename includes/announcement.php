<?php
function getAnnouncements($conn, $jwt_token){
    
    try {
        $stmt = $conn->prepare("SELECT id, pesan, created_at 
            FROM announcements 
            ORDER BY created_at DESC LIMIT 10");
        $stmt->execute();
        $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($announcements){
            echo json_encode([
                "success" => true,
                "data" => $announcements
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Tidak ada pengumuman"
            ]);
        }
    } catch (Exception $e){
        die(json_encode([
            "success" => false,
            "message" => "Terjadi kesalahan di server",
            "error"   => $e->getMessage()
        ]));
    }
}

function getRandomUser($conn, $jwt_token){
    try {
        $stmt = $conn->prepare("
            SELECT 
                users.id AS user_id,
                users.username,
                profiles.photo,
                profiles.gender,
                profiles.cash
            FROM users
            LEFT JOIN profiles ON profiles.user_id = users.id
            ORDER BY RAND()
            LIMIT 5
        ");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($users){
            echo json_encode([
                "success" => true,
                "data" => $users
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Tidak ada user"
            ]);
        }
    } catch (Exception $e){
        die(json_encode([
            "success" => false,
            "message" => "Terjadi kesalahan di server",
            "error"   => $e->getMessage()
        ]));
    }
}


function getEvents($conn, $jwt_token) {
    // ambil user_id dari token
    $user = auth($jwt_token); 
    $userId = $user->user_id;

    try {
        $stmt = $conn->prepare("
            SELECT 
                e.id, e.nama, e.pesan, e.reward_chips, e.end_at, e.vip, e.created_at,
                CASE WHEN uec.id IS NOT NULL THEN 1 ELSE 0 END AS claimed
            FROM events e
            LEFT JOIN user_event_claims uec
                ON uec.event_id = e.id AND uec.user_id = :user_id
            ORDER BY e.created_at DESC
        ");
        $stmt->execute(['user_id' => $userId]);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'data' => $events]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal mengambil event',
            'error' => $e->getMessage()
        ]);
    }
}


function claimEvent($conn, $jwt_token) {
    header('Content-Type: application/json; charset=utf-8');

    $userId = auth($jwt_token)->user_id ?? null;

    if (!$userId) {
        echo json_encode([
            'success' => false,
            'error' => 401,
            'message' => 'Anda tidak memiliki akses'
        ]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    $eventId = intval($data['id'] ?? 0);

    if (!$eventId) {
        echo json_encode(['success' => false, 'message' => 'Event ID tidak valid']);
        return;
    }

    try {
        // ambil info event
        $stmt = $conn->prepare("SELECT id, reward_chips, end_at, vip FROM events WHERE id = ?");
        $stmt->execute([$eventId]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            echo json_encode(['success' => false, 'message' => 'Event tidak ditemukan']);
            return;
        }

        // cek expired
        if (new DateTime($event['end_at']) < new DateTime()) {
            echo json_encode(['success' => false, 'message' => 'Event sudah berakhir']);
            return;
        }

        // cek VIP jika event membutuhkan
        if ((int)$event['vip'] === 1) {
            $stmt = $conn->prepare("SELECT isVip FROM economy WHERE user_id = ?");
            $stmt->execute([$userId]);
            $userVip = $stmt->fetch(PDO::FETCH_ASSOC)['isVip'] ?? 0;

            if (!(int)$userVip) {
                echo json_encode(['success' => false, 'message' => 'Hanya untuk member VIP!']);
                return;
            }
        }

        // cek sudah claim sebelumnya
        $stmt = $conn->prepare("SELECT id FROM user_event_claims WHERE user_id = ? AND event_id = ?");
        $stmt->execute([$userId, $eventId]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Event sudah diklaim sebelumnya']);
            return;
        }

        // mulai transaction
        $conn->beginTransaction();

        // insert klaim
        $stmt = $conn->prepare("INSERT INTO user_event_claims(user_id, event_id, claimed_at) VALUES(?, ?, NOW())");
        $stmt->execute([$userId, $eventId]);

        // update reward chips
        $stmt = $conn->prepare("UPDATE profiles SET chip = chip + ? WHERE user_id = ?");
        $stmt->execute([$event['reward_chips'], $userId]);

        $conn->commit();

        echo json_encode(['success' => true, 'message' => 'Event berhasil diklaim!']);
    } catch (Exception $e) {
        if ($conn->inTransaction()) $conn->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'Gagal klaim event',
            'error' => $e->getMessage()
        ]);
    }
}
