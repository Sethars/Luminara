<?php
function usersearch($conn, $jwt_token) {
    // Ambil user_id dari token
    $currentUserId = auth($jwt_token)->user_id ?? 0;

    try {
        $q = trim($_GET['q'] ?? '');
        if ($q === '' || strlen($q) < 2) {
            echo json_encode([]);
            exit;
        }

        $stmt = $conn->prepare("
            SELECT 
                u.id, 
                u.username, 
                p.photo, 
                p.badges
            FROM users u
            LEFT JOIN profiles p ON p.user_id = u.id
            WHERE u.username LIKE :q
              AND u.id != :currentUser
            ORDER BY u.username ASC
            LIMIT 10
        ");

        $stmt->execute([
            ':q' => "%$q%",
            ':currentUser' => $currentUserId
        ]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($users as &$u) {
            if (!empty($u['badges'])) {
                $decoded = json_decode($u['badges'], true);
                $u['badges'] = $decoded ?: ["used" => [], "unused" => []];
            } else {
                $u['badges'] = ["used" => [], "unused" => []];
            }
        }

        header('Content-Type: application/json');
        echo json_encode($users);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}
