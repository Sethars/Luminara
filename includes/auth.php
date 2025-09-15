<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function authenticate($conn, $jwt_token) {
    // Ambil token dari header Authorization
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

    if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Token tidak ditemukan, silakan login terlebih dahulu"
        ]);
        exit;
    }

    $jwt = $matches[1]; // token nya

    try {
        // decode token
        $decoded = JWT::decode($jwt, new Key($jwt_token, 'HS256'));

        // cek apakah user masih ada di DB
        $stmt = $conn->prepare("SELECT id, username, email, `role`, gender FROM users WHERE id = ?");
        $stmt->execute([$decoded->user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            http_response_code(401);
            echo json_encode([
                "status" => "error",
                "message" => "User tidak ditemukan"
            ]);
            exit;
        }

        // return data user
        return $user;

    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Token tidak valid atau sudah expired",
            "error" => $e->getMessage()
        ]);
        exit;
    }
}