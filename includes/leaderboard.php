<?php

function getLeaderboard($conn) {
    try {
        $sql = "SELECT u.id, u.username AS name, p.cash AS money, p.photo, p.badges
                FROM users u
                JOIN profiles p ON u.id = p.user_id
                ORDER BY p.cash DESC LIMIT 100";

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
                "id"     => (int) $row["id"],
                "rank"   => $rank++,
                "name"   => $row["name"],
                "money"  => (int) $row["money"],
                "avatar" => $row["photo"] ?? "/assets/img/photo_profile/ppkosong.jpg",
                "badges" => $badges 
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


//online pindah function inih welee ke UpdateTopRank.php
function updateTopRank($conn) {
    try {
        // Ambil semua user untuk hapus TOP_x
        $stmt = $conn->prepare("SELECT user_id, badges FROM profiles");
        $stmt->execute();
        $allUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($allUsers as $user) {
            $badges = json_decode($user['badges'], true) ?: ["used" => [], "unused" => []];

            // Hapus hanya TOP_1, TOP_2, TOP_3
            $badges["used"] = array_values(
                array_filter($badges["used"], fn($b) => !in_array($b, ["TOP_1", "TOP_2", "TOP_3"]))
            );
            $badges["unused"] = array_values(
                array_filter($badges["unused"], fn($b) => !in_array($b, ["TOP_1", "TOP_2", "TOP_3"]))
            );

            $update = $conn->prepare("UPDATE profiles SET badges = :badges WHERE user_id = :uid");
            $update->execute([
                ":badges" => json_encode($badges),
                ":uid"    => $user["user_id"]
            ]);
        }

        // Ambil top 3 berdasarkan cash
        $stmt = $conn->prepare("SELECT user_id, badges FROM profiles ORDER BY cash DESC LIMIT 3");
        $stmt->execute();
        $top3 = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $rank = 1;
        foreach ($top3 as $user) {
            $badges = json_decode($user['badges'], true) ?: ["used" => [], "unused" => []];

            if (!in_array("TOP_$rank", $badges["used"])) {
                $badges["used"][] = "TOP_$rank";
            }

            $update = $conn->prepare("UPDATE profiles SET badges = :badges WHERE user_id = :uid");
            $update->execute([
                ":badges" => json_encode($badges),
                ":uid"    => $user["user_id"]
            ]);

            $rank++;
        }

        echo json_encode([
            "success" => true,
            "message" => "Badges TOP_1, TOP_2, TOP_3 berhasil diperbarui"
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Gagal update top badges",
            "error"   => $e->getMessage()
        ]);
    }
}

//  ||===========================||
//  ||kalau online ganti di bawah||
//  ||===========================||

// if (!in_array("TOP_$rank", $badges["unused"]) && !in_array("TOP_$rank", $badges["used"])) {
//     $badges["unused"][] = "TOP_$rank";
// }

//pakai corn
// 0 0 * * * /usr/bin/php /path/to/project/cron/update_top_rank.php >> /path/to/project/logs/cron.log 2>&1

