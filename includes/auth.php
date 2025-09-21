<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function auth($jwt_token){
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
    return JWT::decode($jwt, new Key($jwt_token, 'HS256'));
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

function deleteAccount($conn, $jwt_token){
    $data = json_decode(file_get_contents("php://input"), true);
    $id = auth($jwt_token)->user_id;
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