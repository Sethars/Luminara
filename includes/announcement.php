<?php
function getAnnouncements($conn, $jwt_token){
    $id = auth($jwt_token)->user_id; // kalau perlu filter berdasarkan user
    
    try {
        $stmt = $conn->prepare("SELECT id, pesan, created_at 
            FROM announcements 
            ORDER BY created_at DESC LIMIT 10");
        $stmt->execute();
        $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($announcements){
            echo json_encode([
                "success" => true,
                "data" => $announcements
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Tidak ada pengumuman"
            ]);
        }
    } catch (Exception $e){
        die(json_encode([
            "success" => false,
            "message" => "Terjadi kesalahan di server",
            "error"   => $e->getMessage()
        ]));
    }
}
