<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
header('Content-Type: application/json');

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
        $stmt = $conn->prepare("SELECT id, username, email, `role` FROM users WHERE id = ?");
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

function deleteAccount($conn){
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['userId'];
    $password = $data['password'];

    $stmt = $conn->prepare('SELECT password FROM users WHERE id = ?');
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$user){
        echo json_encode(['success' => false, 'message' => 'Akun tidak ditemukan']);
        exit;
    }

    if(!password_verify($password, $user['password'])){
        echo json_encode(['success' => false, 'message' => 'Password salah']);
        exit;
    } else {
        //Hapus akun
        $stmt = $conn->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);

        echo json_encode(['success' => true, 'message' => 'Berhasil hapus akun']);
    }
}