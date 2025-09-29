<?php

function isAdmin($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;

    $stmt = $conn->prepare("SELECT `role` FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user['role'] === "member"){
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
        echo json_encode(['success' => false, "message" => "Anda tidak memiliki akses"]);
        exit;
    }

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
    } catch(Exception $e){}
}

function addLotteryEvent($conn, $jwt_token){
    if(!isAdmin($conn, $jwt_token)){
        echo json_encode(['success' => false, "message" => "Anda tidak memiliki akses"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    try{
        $conn->beginTransaction();

        $stmt = $conn->prepare("INSERT INTO lottery (event_name, ticket_price, reward, started_at, ended_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['eventName'], $data['ticketPrice'], $data['prizes'], $data['startDate'], $data['endDate']]);

        $recentLottery = recentLottery($conn);

        $conn->commit();

        echo json_encode([
            "success" => true,
            "lottery" => $recentLottery
        ]);
    } catch (Exception $e){}
}