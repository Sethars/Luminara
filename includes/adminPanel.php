<?php

function isAdmin($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;

    $stmt = $conn->prepare("SELECT `role` FROM users WHERE id = ? AND `role` IN ('admin', 'owner')");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$user){
        //UNAUTHORIZED
        return false;
    }

    return true;
}

function recentLottery($conn) {
    $stmt = $conn->query("
        SELECT 
            event_name, 
            ticket_price, 
            reward, 
            DATE_FORMAT(started_at, '%b %e, %Y %H:%i:%s') AS start_date, 
            DATE_FORMAT(ended_at, '%b %e, %Y %H:%i:%s') AS end_date,
            CASE
                WHEN NOW() < started_at THEN 'Pending'
                WHEN NOW() BETWEEN started_at AND ended_at THEN 'Active'
                WHEN NOW() > ended_at THEN 'Inactive'
            END AS status,
            ticket_sold
        FROM lottery 
        ORDER BY started_at DESC 
        LIMIT 5
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAdminData($conn, $jwt_token){
    if(!isAdmin($conn, $jwt_token)){
        echo json_encode(['success' => false, "error" => 401, "message" => "Anda tidak memiliki akses"]);
        exit;
    }

    $id = auth($jwt_token)->user_id;

    try{

        //Total users
        $stmt = $conn->query("SELECT COUNT(*) as total FROM users");
        $totalUser = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        //Total event lottery
        $stmt = $conn->query("SELECT COUNT(*) as total FROM lottery");
        $totalLottery = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        //5 User Terbaru
        $stmt = $conn->query("SELECT username AS `name`, email, `role`, DATE_FORMAT(created_at, '%b %e, %Y') AS joined FROM users ORDER BY created_at DESC LIMIT 5");
        $recentUser = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //5 Event lottery terbaru
        $recentLottery = recentLottery($conn);

        echo json_encode([
            "success" => true,
            "totalUser" => $totalUser,
            "totalLottery" => $totalLottery,
            "users" => $recentUser,
            "lottery" => $recentLottery
        ]);
    } catch(Exception $e){
        die(json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]));
    }
}

function addLotteryEvent($conn, $jwt_token){
    if(!isAdmin($conn, $jwt_token)){
        echo json_encode(['success' => false, "error" => 401, "message" => "Anda tidak memiliki akses"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    try{
        $conn->beginTransaction();

        $stmt = $conn->prepare("INSERT INTO lottery (event_name, ticket_price, reward, started_at, ended_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['eventName'], $data['ticketPrice'], $data['prizes'], $data['startDate'], $data['endDate']]);

        $lotteryId = $conn->lastInsertId();

        $recentLottery = recentLottery($conn);

        $conn->commit();

        // Nama event
        $eventName = "lottery_" . $lotteryId . "_pick_winner";

        // Query create event
        $endedAt = date("Y-m-d H:i:s", strtotime($data['endDate']));
        $sql = "
            CREATE EVENT `$eventName`
            ON SCHEDULE AT '$endedAt'
            ON COMPLETION NOT PRESERVE
            DO
            BEGIN
                UPDATE lottery l
                JOIN (
                    SELECT lt.lottery_id, lt.user_id, lt.id
                    FROM lottery_ticket lt
                    WHERE lt.lottery_id = $lotteryId
                    ORDER BY RAND()
                    LIMIT 1
                ) r ON r.lottery_id = l.id
                SET l.winner_id = r.user_id,
                    l.winner_ticket_id = r.id;
                    
                UPDATE profiles p
                JOIN lottery l ON l.id = $lotteryId
                SET p.cash = p.cash + l.reward
                WHERE p.id = l.winner_id;
            END;
        ";
        $conn->exec($sql);

        echo json_encode([
            "success" => true,
            "lottery" => $recentLottery
        ]);
    } catch (Exception $e){
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        die(json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]));
    }
}

// includes/announcement.php

function addAnnouncement($conn, $jwt_token) {
    if(!isAdmin($conn, $jwt_token)){
        echo json_encode(['success' => false, "error" => 401, "message" => "Anda tidak memiliki akses"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    $pesan = trim($data['pesan'] ?? '');

    if ($pesan === '') {
        echo json_encode(['success' => false, 'message' => 'Pesan tidak boleh kosong']);
        return;
    }

    try {
        $stmt = $conn->prepare("INSERT INTO announcements (pesan) VALUES (?);");
        $stmt->execute([$pesan]);

        echo json_encode(['success' => true, 'message' => 'Announcement berhasil ditambahkan']);
    } catch (Exception $e) {
        die(json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan saat menambah announcement',
            'error'   => $e->getMessage()
        ]));
    }
}

function editAnnouncement($conn, $jwt_token) {
    if(!isAdmin($conn, $jwt_token)){
        echo json_encode(['success' => false, "error" => 401, "message" => "Anda tidak memiliki akses"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? null;
    $pesan = trim($data['pesan'] ?? '');

    if (!$id || $pesan === '') {
        echo json_encode(['success' => false, 'message' => 'ID dan Pesan wajib diisi']);
        return;
    }

    try {
        $stmt = $conn->prepare("UPDATE announcements SET pesan = ? WHERE id = ?;");
        $stmt->execute([$pesan, $id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Announcement berhasil diupdate']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Tidak ada data yang diupdate']);
        }
    } catch (Exception $e) {
        die(json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan saat update announcement',
            'error'   => $e->getMessage()
        ]));
    }
}

function deleteAnnouncement($conn, $jwt_token) {
    if(!isAdmin($conn, $jwt_token)){
        echo json_encode(['success' => false, "error" => 401, "message" => "Anda tidak memiliki akses"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? null;

    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID wajib diisi']);
        return;
    }

    try {
        $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?;");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Announcement berhasil dihapus']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal hapus, data tidak ditemukan']);
        }
    } catch (Exception $e) {
        die(json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan saat hapus announcement',
            'error'   => $e->getMessage()
        ]));
    }
}

function getAnnouncements($conn) {
    try {
        $stmt = $conn->query("SELECT id, pesan, created_at FROM announcements ORDER BY created_at DESC;");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'data' => $rows]);
    } catch (Exception $e) {
        die(json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan saat ambil announcement',
            'error'   => $e->getMessage()
        ]));
    }
}
