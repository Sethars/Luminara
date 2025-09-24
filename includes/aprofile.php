<?php

require_once 'functions.php';
function getAnotherUserData($conn, $jwt_token) {
    $ownerId = auth($jwt_token)->user_id;
    $data = json_decode(file_get_contents("php://input"), true);
    $id = (int)$data['id'];

    if (!$id) {
        echo json_encode([
            "success" => false,
            "message" => "User ID tidak ditemukan."
        ]);
        exit;
    }

    if ($ownerId === $id) {
        echo json_encode([
            "success" => false,
            "message" => "Redirect ke akun Anda..",
            "redirect" => true
        ]);
        exit;
    }

    try{
        $stmt = $conn->prepare("
            SELECT 
                u.username, 
                u.created_at, 
                p.bio,
                p.badges,
                p.chip,
                p.cash,
                p.photo,
                p.gender,
                e.isVip,
                h.win,
                h.lose
            FROM users u
            LEFT JOIN profiles p ON p.user_id = u.id
            LEFT JOIN economy e ON e.user_id = u.id
            LEFT JOIN history h ON h.user_id = u.id
            WHERE u.id = ?
            LIMIT 1
        ");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode([
                "success" => false,
                "message" => "User tidak ditemukan."
            ]);
            exit;
        }
        //Return tipe data
        $user['created_at'] = date("d F Y", strtotime($user['created_at']));
        $user['chip'] = (int)$user['chip'];
        $user['cash'] = (int)$user['cash'];
        $user['isVip'] = (bool)$user['isVip'];
        $user['win'] = (int)$user['win'];
        $user['lose'] = (int)$user['lose'];

        //Hitung total matches
        $user['total_matches'] = ($user['win'] ?? 0) + ($user['lose'] ?? 0);

        // Hitung winrate
        $user['win_rate'] = $user['total_matches'] > 0 
            ? number_format(round(($user['win'] / $user['total_matches']) * 100, 2), 2, '.', '')
            : number_format(0, 2, '.', '');

        // Decode badges JSON
        $user['badges'] = $user['badges'] ? json_decode($user['badges'], true) : ["used" => [], "unused" => []];

        //Comments
        $stmt = $conn->prepare("
            SELECT 
                c.id,
                c.user_id,
                c.commenter_id,
                c.comment,
                c.created_at,
                u.username AS commenter_username,
                p.photo AS commenter_photo
            FROM profile_comments c
            LEFT JOIN users u ON u.id = c.commenter_id
            LEFT JOIN profiles p ON p.user_id = c.commenter_id
            WHERE c.user_id = ?
            ORDER BY c.created_at DESC
        ");
        $stmt->execute([$id]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $user['comments'] = $comments;

        foreach ($user['comments'] as &$comment) {
            $comment['created_at'] = timeAgo($comment['created_at']);
        }
        unset($comment);

        echo json_encode([
            "success" => true,
            "user" => $user
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]);
    }
}

function addComment($conn, $jwt_token){
    $ownerId = auth($jwt_token)->user_id;
    $data = json_decode(file_get_contents("php://input"), true);
    $id = (int)$data['id'];
    $comment = $data['comment'];

    try{
        $conn->beginTransaction();

        $stmt = $conn->prepare("INSERT INTO profile_comments (user_id, comment, commenter_id) VALUES (?, ?, ?)");
        $stmt->execute([$id, $comment, $ownerId]);

        $conn->commit();

        echo json_encode(['success' => true, 'message' => 'Berhasil menambahkan komentar']);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]);
    }
}