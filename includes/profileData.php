<?php

function changeUsername($conn){
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['userId'];
    $username = $data['newUsername'];

    if(!$id || !$username){
        echo json_encode(['success' => false, 'message' => 'Form tidak boleh kosong']);
        exit;
    }

    try{
        $stmt = $conn->prepare('UPDATE users SET username = ? WHERE id = ?');
        $stmt->execute([$username, $id]);

        echo json_encode(['success' => true, 'message' => 'Berhasil ganti username']);
    } catch (Exception $e){
        die(json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]));
    }
}