<?php
function getAprofile($conn) {
    $id = $_GET['id'] ?? 0;

    if (!$id) {
        echo json_encode([
            "success" => false,
            "message" => "User ID tidak ditemukan."
        ]);
        exit;
    }

    $stmt = $conn->prepare("
        SELECT 
            u.username, 
            u.created_at, 
            p.bio,
            p.badges,
            p.chips,
            p.cash,
            p.photo,
            p.gender,
            e.isVip,
            e.total_wins,
            e.total_matches
        FROM users u
        LEFT JOIN profiles p ON p.user_id = u.id
        LEFT JOIN economy e ON e.user_id = u.id
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

    // Hitung winrate
    $user['win_rate'] = ($user['total_matches'] ?? 0) > 0 
        ? round(($user['total_wins'] / $user['total_matches']) * 100, 2)
        : 0;

    // Decode badges JSON
    $user['badges'] = $user['badges'] ? json_decode($user['badges'], true) : ["used" => [], "unused" => []];

    echo json_encode([
        "success" => true,
        "data" => $user
    ]);
}
