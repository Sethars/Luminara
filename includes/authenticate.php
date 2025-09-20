<?php

function authenticate($conn, $jwt_token) {
    try {
        $decoded = auth( $jwt_token);
        // cek apakah user masih ada di DB
        $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
        $stmt->execute([$decoded->user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("SELECT bio, gender, photo, badges FROM profiles WHERE user_id = ?");
        $stmt->execute([$decoded->user_id]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            http_response_code(401);
            echo json_encode([
                "status" => "error",
                "message" => "User tidak ditemukan"
            ]);
            exit;
        }

        // return data user
        echo json_encode([
            "status" => "success",
            "message" => "User authenticated successfully",
            "user" => $user,
            "profile" => $profile
        ]);

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