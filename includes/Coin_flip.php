<?php


function Coinflip($conn, $jwt_token){
    header('Content-Type: application/json');

    $id = auth($jwt_token)->user_id;
    $input = json_decode(file_get_contents('php://input'), true);
    $newChip = $input['chip'] ?? null;

    if ($newChip === null || !is_numeric($newChip)) {
        echo json_encode([
            'success' => false,
            'message' => 'Nilai chip tidak valid'
        ]);
        exit;
    }

    try {
        $sql = 'UPDATE profiles SET chip = ? WHERE user_id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$newChip, $id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Chip berhasil diperbarui',
                'data' => ['chip' => (int)$newChip]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Tidak ada perubahan chip'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]);
    }
    exit;
}
