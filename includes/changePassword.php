<?php
function changePassword($conn){
    $data = json_decode(file_get_contents("php://input"), true);
    $user_id = $data['userId'] ?? null;
    $oldPassword = $data['oldPassword'] ?? null;
    $newPassword = $data['newPassword'] ?? null;

    if(!$user_id || !$oldPassword || !$newPassword){
        echo json_encode(['success' => false, 'message' => 'Ada kesalahan saat mengganti password']);
        exit;
    }

    try{
        $stmt = $conn->prepare('SELECT password FROM users WHERE id = ?');
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user){
            echo json_encode(['success' => false, 'message' => 'Akun tidak ditemukan']);
            exit;
        }

        if(!password_verify($oldPassword, $user['password'])){
            echo json_encode(['success' => false, 'message' => 'Password salah']);
            exit;
        }

        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
        $stmt->execute([$hashed, $user_id]);

        echo json_encode(['success' => true, 'message' => 'Ganti password berhasil']);
    } catch (Exception $e){
        die(json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]));
    }
}