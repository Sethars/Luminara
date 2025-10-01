<?php

function getLotteryData($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;

    try{
        $stmt = $conn->prepare("
            SELECT l.*, u.username, lt.ticket, lt.lottery_number
            FROM lottery l
            LEFT JOIN users u ON u.id = l.winner_id
            LEFT JOIN lottery_ticket lt ON lt.id = l.winner_ticket_id
            WHERE CURRENT_TIMESTAMP BETWEEN started_at AND ended_at 
            LIMIT 1
        ");
        $stmt->execute();
        $lotteryData = $stmt->fetch(PDO::FETCH_ASSOC);

         if(!$lotteryData){
            $stmt = $conn->prepare("
                SELECT l.*, u.username, lt.ticket AS winner_ticket, lt.lottery_number AS winner_number
                FROM lottery l
                LEFT JOIN users u ON u.id = l.winner_id
                LEFT JOIN lottery_ticket lt ON lt.id = l.winner_ticket_id
                WHERE l.ended_at <= NOW()
                ORDER BY ended_at DESC 
                LIMIT 1");
            $stmt->execute();
            $lotteryData = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $isActive = false;
        } else {
            $isActive = true;
        }
        
        if($lotteryData){
            $lotteryData['reward'] = (int)$lotteryData['reward'];
            $lotteryData['ticket_price'] = (int)$lotteryData['ticket_price'];
            $lotteryData['ticket_sold'] = (int)$lotteryData['ticket_sold'];

            $stmt = $conn->prepare("SELECT ticket, lottery_number, purchased_at 
                FROM lottery_ticket 
                WHERE lottery_id = ? AND user_id = ? 
                ORDER BY purchased_at DESC");
            $stmt->execute([$lotteryData["id"], $id]);
            $ticketBought = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($ticketBought as &$ticket) {
                $ticket['purchased_at'] = date('Y-m-d', strtotime($ticket['purchased_at']));
            }
            unset($ticket);

            echo json_encode([
                "success" => true,
                "lottery" => $lotteryData,
                "user_tickets" => $ticketBought,
                "is_active" => $isActive
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

function buyTicketLottery($conn, $jwt_token){
    $data = json_decode(file_get_contents("php://input"), true);
    $id = auth($jwt_token)->user_id;
    do{
        $rand = random_int(10000, 99999);
        $ticket = "TIX-" . (string)$rand;
        $number = json_encode(str_split((string)$rand));

        $stmt = $conn->prepare("SELECT COUNT(*) FROM lottery_ticket WHERE lottery_id = ? AND ticket = ?");
        $stmt->execute([$data['id'], $ticket]);
        $exists = $stmt->fetchColumn();
    } while($exists > 0);

    try{
        $conn->beginTransaction();

        $stmt = $conn->prepare("SELECT ticket_price FROM lottery WHERE id = ?");
        $stmt->execute([$data['id']]);
        $ticketPrice = $stmt->fetchColumn();

        if (!$ticketPrice) {
            throw new Exception("Lottery tidak ditemukan");
        }

        $stmt = $conn->prepare("
            UPDATE profiles
            SET cash = cash - :price
            WHERE user_id = :id AND cash >= :price
        ");
        $stmt->execute([
            ':price'   => $ticketPrice,
            ':id' => $id
        ]);
        if($stmt->rowCount() === 0){
            $conn->rollBack();
            echo json_encode([
                "success" => false,
                "message" => "Cash Anda tidak mencukupi untuk membeli"
            ]);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO lottery_ticket (lottery_id, user_id, ticket, lottery_number) VALUES (?, ?, ?, ?)");
        $success = $stmt->execute([$data["id"], $id, $ticket, $number]);
        
        if($success){
            $stmt = $conn->prepare("
                UPDATE lottery 
                SET ticket_sold = ticket_sold + 1, reward = reward + ?
                WHERE id = ?
            ");
            $stmt->execute([$ticketPrice/2, $data["id"]]);
        } else{
            $conn->rollBack();
            echo json_encode([
                "success" => false,
                "message" => "Gagal membeli tiket"
            ]);
            exit;
        }

        $conn->commit();

        $newTicket = [
            "ticket" => $ticket,
            "lottery_number" => $number,
            "purchased_at" => date("Y-m-d")
        ];

        echo json_encode([
            "success" => true,
            "message" => "Berhasil membeli tiket",
            "new_ticket" => $newTicket
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