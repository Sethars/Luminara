<?php

function createEventLottery($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['started_at'])) {
        echo json_encode(["success" => false, "message" => "Tanggal mulai wajib diisi"]);
        exit;
    }
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE id = ? AND `role` IN ('admin', 'owner')");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$user){
        //UNAUTHORIZED
        echo json_encode(["success" => false, "message" => "Anda tidak mempunyai akses"]);
        exit;
    }

    //Cek ada event lottery ada yang aktif atau gak
    $stmt = $conn->prepare("
        SELECT id 
        FROM lottery 
        WHERE winner_id IS NULL 
        AND ended_at > NOW()
        LIMIT 1
    ");
    $stmt->execute();
    $activeLottery = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($activeLottery) {
        echo json_encode([
            "success" => false,
            "message" => "Masih ada lottery aktif (ID #" . $activeLottery['id'] . "), tunggu selesai dulu"
        ]);
        exit;
    }

    try{
        $startedAt = date("Y-m-d 00:00:00", strtotime($data['started_at']));
        $endedAt   = date("Y-m-d 00:00:00", strtotime($data['ended_at']));

        $conn->beginTransaction();

        $stmt = $conn->prepare("INSERT INTO lottery (started_at, ended_at) VALUES (?, ?)");
        $stmt->execute([$startedAt, $endedAt]);

        $lotteryId = $conn->lastInsertId();
        $conn->commit();

        // Nama event
        $eventName = "lottery_" . $lotteryId . "_pick_winner";

        // Query create event
        $sql = "
        CREATE EVENT $eventName
        ON SCHEDULE AT '$endedAt'
        ON COMPLETION NOT PRESERVE
        DO
        UPDATE lottery l
        JOIN (
            SELECT lt.lottery_id, lt.user_id, lt.ticket
            FROM lottery_ticket lt
            WHERE lt.lottery_id = $lotteryId
            ORDER BY RAND()
            LIMIT 1
        ) r ON r.lottery_id = l.id
        SET l.winner_id = r.user_id,
            l.winner_ticket = r.ticket;
        ";
        $conn->exec($sql);


        echo json_encode([
            "success" => true,
            "message" => "Lottery #$lotteryId berhasil dibuat",
            "started_at" => $startedAt,
            "ended_at" => $endedAt
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

function getLotteryData($conn){
    try{
        $stmt = $conn->prepare("SELECT * FROM lottery WHERE CURRENT_TIMESTAMP BETWEEN started_at AND ended_at LIMIT 1");
        $stmt->execute();
        $lotteryData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($lotteryData){
            $lotteryData['reward'] = (int)$lotteryData['reward'];

            echo json_encode([
                "success" => true,
                "lottery" => $lotteryData
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Event belum dimulai"
            ]);
        }
    } catch (Exception $e){
        die(json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]));
    }
}