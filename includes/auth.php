<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function createAccessToken($userId, $secret) {
    $ACCESS_EXPIRE = 60 * 15;
    $payload = [
        "iss" => "luminara-app",
        "iat" => time(),
        "exp" => time() + $ACCESS_EXPIRE,
        "user_id" => $userId
    ];
    return JWT::encode($payload, $secret, 'HS256');
}

function createRefreshToken($userId, $secret) {
    $REFRESH_EXPIRE = 60 * 60 * 24 * 30;
    $payload = [
        "iss" => "luminara-app",
        "iat" => time(),
        "exp" => time() + $REFRESH_EXPIRE,
        "user_id" => $userId,
        "rand" => bin2hex(random_bytes(10))
    ];
    return JWT::encode($payload, $secret, 'HS256');
}

function refreshToken ($conn, $jwt_token) {
    if (!isset($_COOKIE['refreshToken'])) {
        http_response_code(401);
        echo json_encode(["message" => "Refresh token hilang"]);
        exit;
    }

    $refreshToken = $_COOKIE['refreshToken'];

    try {
        $decoded = JWT::decode($refreshToken, new Key($jwt_token, 'HS256'));
        $userId = $decoded->user_id;

        // cek apakah token ada di DB
        $stmt = $conn->prepare("SELECT * FROM users WHERE token = ? AND id = ?");
        $stmt->execute([$refreshToken, $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new Exception("Token tidak ada di DB");
        }

        if (strtotime($row['token_expired']) < time()) {
            throw new Exception("Refresh token expired");
        }

        // buat access & refresh token baru
        $accessToken = createAccessToken($userId, $jwt_token);
        $newRefreshToken = createRefreshToken($userId, $jwt_token);

        // simpan refresh token baru
        $stmt = $conn->prepare("UPDATE users SET token = ?, token_expired = ? WHERE id = ?");
        $stmt->execute([$refreshToken, date("Y-m-d H:i:s", time() + 60 * 60 * 24 * 30), $userId]);

        // set cookie refresh token baru
        setcookie("refreshToken", $newRefreshToken, [
            "expires" => time() + 60 * 60 * 24 * 30,
            "httponly" => true,
            "secure" => false, // set true kalau https
            "samesite" => "Strict",
            "path" => "/auth"
        ]);

        echo json_encode(["token" => $accessToken]);

    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["message" => "Refresh gagal", "error" => $e->getMessage()]);
    }
}

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
    try{
        $decoded = JWT::decode($jwt, new Key($jwt_token, 'HS256'));
        return $decoded;
    } catch (Exception $e){
        http_response_code(401);
    }
}

function login($conn, $jwt_token) {
    $data = json_decode(file_get_contents("php://input"), true);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if(password_verify($data['password'], $user['password'])) {
            $accessToken = createAccessToken($user['id'], $jwt_token);
            $refreshToken = createRefreshToken($user['id'], $jwt_token);

            $stmt = $conn->prepare("UPDATE users SET token = ?, token_expired = ? WHERE id = ?");
            $stmt->execute([$refreshToken, date("Y-m-d H:i:s", time() + 60 * 60 * 24 * 30), $user['id']]);

            setcookie("refreshToken", $refreshToken, [
                "expires" => time() + 60 * 60 * 24 * 30,
                "httponly" => true,
                "secure" => false, // set true kalau sudah https
                "samesite" => "Strict",
                "path" => "/api"
            ]);

            echo json_encode([
                "success" => true,
                "message" => "Login berhasil",
                "token" => $accessToken
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