<?php

function getLeaderboard($conn) {
    try {
        $sql = "SELECT u.username AS name, p.cash AS money, p.photo, p.badges
                FROM users u
                JOIN profiles p ON u.id = p.user_id
                ORDER BY p.cash DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $leaderboard = [];
        $rank = 1;

        foreach ($rows as $row) {
            $badges = ["used" => [], "unused" => []];
            if (!empty($row["badges"])) {
                $decoded = json_decode($row["badges"], true);
                if (is_array($decoded)) {
                    $badges = $decoded;
                }
            }

            $leaderboard[] = [
                "rank"   => $rank++,
                "name"   => $row["name"],
                "money"  => (int) $row["money"],
                "avatar" => $row["photo"] ?? "/assets/img/photo_profile/ppkosong.jpg",
                "badges" => $badges // ✅ bukan badge string lagi
            ];
        }


        echo json_encode([
            "success" => true,
            "message" => "Data leaderboard berhasil diambil",
            "data"    => $leaderboard
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Terjadi kesalahan di server",
            "error"   => $e->getMessage()
        ]);
    }
}
