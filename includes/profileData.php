<?php

require_once 'functions.php';
function changeUsername($conn, $jwt_token){
    $data = json_decode(file_get_contents("php://input"), true);
    $id = auth($jwt_token)->user_id;
    $username = $data['newUsername'];

    if(!$id || !$username){
        echo json_encode(['success' => false, 'message' => 'Username tidak boleh kosong']);
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

function getProfileData($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;

    try{
        $stmt = $conn->prepare('
            SELECT u.created_at, p.cash, h.win, h.lose
            FROM users u
            LEFT JOIN profiles p ON p.user_id = u.id
            LEFT JOIN history h ON h.user_id = u.id
            WHERE u.id = ?
        ');
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("
            SELECT pc.id, pc.comment, pc.created_at, cu.id AS commenter_id, cu.username AS commenter_username, cp.photo AS commenter_photo
            FROM profile_comments pc
            JOIN users cu ON cu.id = pc.commenter_id
            LEFT JOIN profiles cp ON cp.user_id = cu.id
            WHERE pc.user_id = ?
            ORDER BY pc.created_at DESC
        ");
        $stmt->execute([$id]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($comments as &$comment) {
            $comment['created_at'] = timeAgo($comment['created_at']);
        }

        //Hitung total matches
        $user['total_matches'] = ($user['win'] ?? 0) + ($user['lose'] ?? 0);

        // Hitung winrate
        $user['win_rate'] = $user['total_matches'] > 0 
            ? number_format(round(($user['win'] / $user['total_matches']) * 100, 2), 2, '.', '')
            : number_format(0, 2, '.', '');

        if ($user) {
            echo json_encode([
                'success' => true,
                'created_at' => date("d F Y", strtotime($user['created_at'])),
                'money'   => (int)$user['cash'],
                'win' => (int)$user['win'],
                'lose' => (int)$user['lose'],
                'total_matches' => (int)$user['total_matches'],
                'winrate' => $user['win_rate'],
                'comments' => $comments
            ]);
        }
    } catch (Exception $e){
        die(json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]));
    }
}

function changePhotoProfile($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;

    if (!isset($id) || !isset($_FILES["pp"])) {
        echo json_encode(["success" => false, "message" => "Data tidak lengkap"]);
        exit;
    }

    $targetDir = "../public/assets/img/photo_profile/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES["pp"]["name"]);
    $targetFile = $targetDir . $fileName;
    $urlFile = "/assets/img/photo_profile/" . $fileName;

    $allowed = ["jpg", "jpeg", "png", "webp"];
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (!in_array($imageFileType, $allowed)) {
        echo json_encode(["success" => false, "message" => "Format tidak didukung"]);
        exit;
    }

    // Ambil foto lama
    $stmt = $conn->prepare("SELECT photo FROM profiles WHERE user_id = ?");
    $stmt->execute([$id]);
    $oldPhoto = $stmt->fetchColumn();

    if (move_uploaded_file($_FILES["pp"]["tmp_name"], $targetFile)) {
        // Hapus foto lama kalau ada dan bukan default
        if ($oldPhoto && file_exists("../public" . $oldPhoto)) {
            unlink("../public" . $oldPhoto);
        }

        // Update database dengan foto baru
        $stmt = $conn->prepare("UPDATE profiles SET photo = ? WHERE user_id = ?");
        $stmt->execute([$urlFile, $id]);

        echo json_encode([
            "success" => true,
            "file_url" => $urlFile
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal upload file"]);
    }
}


function changeBio($conn, $jwt_token){
    $data = json_decode(file_get_contents("php://input"), true);
    $id = auth($jwt_token)->user_id;
    $newBio = $data['newBio'];

    try{
        $stmt = $conn->prepare('UPDATE profiles SET bio = ? WHERE user_id = ?');
        $stmt->execute([$newBio, $id]);

        echo json_encode(['success' => true, 'message' => 'Berhasil ganti bio']);
    } catch (Exception $e){
        die(json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]));
    }
}

function changeGender($conn, $jwt_token){
    $data = json_decode(file_get_contents("php://input"), true);
    $id = auth($jwt_token)->user_id;
    $newGender = $data['newGender'];

    try{
        $stmt = $conn->prepare('UPDATE profiles SET gender = ? WHERE user_id = ?');
        $stmt->execute([$newGender, $id]);

        echo json_encode(['success' => true, 'message' => 'Berhasil ganti bio']);
    } catch (Exception $e){
        die(json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]));
    }
}

function addBadges($conn, $jwt_token){
    $data = json_decode(file_get_contents("php://input"), true);
    $id = auth($jwt_token)->user_id;
    $badgeName = $data['badgeName'];

    $stmt = $conn->prepare("SELECT badges FROM profiles WHERE user_id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(["success" => false, "message" => "User not found"]);
        exit;
    }

    $badges = json_decode($row['badges'], true);

    if (in_array($badgeName, $badges['used']) || in_array($badgeName, $badges['unused'])) {
        echo json_encode(["success" => false, "message" => "Badge already exists"]);
        exit;
    }

    $badges['unused'][] = $badgeName;

    $newJson = json_encode($badges);
    $stmt = $conn->prepare("UPDATE profiles SET badges = ? WHERE user_id = ?");
    $stmt->execute([$newJson, $id]);

    echo json_encode(["success" => true, "message" => "Badge berhasil ditambahkan"]);
}

function updateBadges($conn, $jwt_token){
    $data = json_decode(file_get_contents("php://input"), true);
    $id = auth($jwt_token)->user_id;
    $newUsed = $data['used'] ?? [];
    $newUnused = $data['unused'] ?? [];

    // Ambil badges dari DB
    $stmt = $conn->prepare("SELECT badges FROM profiles WHERE user_id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row){
        echo json_encode(["success" => false, "message" => "User not found"]);
        exit;
    }

    $badges = json_decode($row['badges'], true);
    $dbUsed = $badges['used'] ?? [];
    $dbUnused = $badges['unused'] ?? [];

    $allBadges = array_merge($dbUsed, $dbUnused);

    // Validasi input FE
    $validUsed   = array_intersect($newUsed, $allBadges);
    $validUnused = array_intersect($newUnused, $allBadges);

    foreach ($allBadges as $b) {
        if (!in_array($b, $validUsed) && !in_array($b, $validUnused)) {
            $validUnused[] = $b;
        }
    }

    // Hapus duplikat dan reset index
    $validUsed   = array_values(array_unique($validUsed));
    $validUnused = array_values(array_unique($validUnused));

    $newBadges = [
        "used"   => $validUsed,
        "unused" => $validUnused
    ];

    try {
        $stmt = $conn->prepare('UPDATE profiles SET badges = ? WHERE user_id = ?');
        $stmt->execute([json_encode($newBadges), $id]);

        echo json_encode(['success' => true, 'message' => 'Berhasil ganti badges', "badges" => $newBadges]);
    } catch (Exception $e){
        die(json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]));
    }
}


function deleteComment($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;
    $data = json_decode(file_get_contents("php://input"), true);
    $commentId = (int)$data['commentId'];

    try{
        $stmt = $conn->prepare("DELETE FROM profile_comments WHERE id = ? AND user_id = ?");
        $stmt->execute([$commentId, $id]);

        echo json_encode(['success' => true, 'message' => 'Berhasil menghapus komentar']);
    } catch (Exception $e){
        die(json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]));
    }
}