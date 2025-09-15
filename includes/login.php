<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
header('Content-Type: application/json');

function login($conn, $jwt_token) {
    $data = json_decode(file_get_contents("php://input"), true);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if(password_verify($data['password'], $user['password'])) {
            $payload = [
                "iss" => "luminara-app",
                "iat" => time(),
                "exp" => time() + 3600 * 24 * 30, // 30 hari
                "user_id" => $user['id'],
                "email" => $user['email']
            ];

            $jwt = JWT::encode($payload, $jwt_token, 'HS256');

            echo json_encode([
                "success" => true,
                "message" => "Login berhasil",
                "token" => $jwt
            ]);
            
            
        } else {
            echo json_encode(["success" => false, "message" => "Email atau password salah"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Email tidak ditemukan"]);
    }
}