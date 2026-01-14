<?php
function getChip($conn, $jwt_token){
    header('Content-Type: application/json');

    $id = auth($jwt_token)->user_id;

    try{
        $sql = 'SELECT chip FROM profiles WHERE user_id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if($data){
            echo json_encode([
                'success' => true,
                'message' => 'Berhasil mendapatkan data chip',
                'data'    => $data
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal mendapatkan data chip'
            ]);
        }
        
    } catch (Exception $e){
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]);
    }
    exit;
}

function getwinlose($conn, $jwt_token) {
    header('Content-Type: application/json');

    $id = auth($jwt_token)->user_id;

    try {
        $sql = 'SELECT win, lose FROM history WHERE user_id = ? LIMIT 1';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            echo json_encode([
                'success' => true,
                'message' => 'Berhasil mendapatkan data win/lose',
                'data'    => [
                    'win' => (int)$data['win'],
                    'lose' => (int)$data['lose']
                ]
            ]);
        } else {
            // Jika belum ada data, kembalikan default 0
            echo json_encode([
                'success' => true,
                'message' => 'Data belum tersedia',
                'data'    => ['win' => 0, 'lose' => 0]
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

