<?php
header('Content-Type: application/json');

function changePassword($conn){
    $data = json_decode(file_get_contents("php://input"), true);
    $user_id = $data['id'] ?? null;
    $oldPassword = $data['oldPassword'] ?? null;
    $newPassword = $data['newPassword'] ?? null;

    if(!$user_id || !$oldPassword || !$newPassword){
        echo json_encode(['success' => false, 'message' => 'Ada kesalahan saat mengganti password']);
        exit;
    }

    $stmt = $conn->prepare('SELECT password FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $password = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$password){
        echo json_encode(['success' => false, 'message' => 'Akun tidak ditemukan']);
        exit;
    }

    if(!password_verify($oldPassword, $password)){
        echo json_encode(['success' => false, 'message' => 'Password salah']);
        exit;
    }

    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmt = $conn->prepare('UPDATE password FROM users WHERE id = ?');
    $stmt->execute([$user_id]);

    echo json_encode(['success' => true, 'message' => 'Ganti password berhasil']);
}