<?php

function changeUsername($conn, $jwt_token){
    $data = json_decode(file_get_contents("php://input"), true);
    $id = auth($jwt_token)->user_id;
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

function getMoneyData($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;

    try{
        $stmt = $conn->prepare('SELECT cash FROM profiles WHERE user_id = ?');
        $stmt->execute([$id]);
        $money = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($money) {
            echo json_encode([
                'success' => true,
                'money'   => (int)$money['cash'] // langsung ambil angka
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

function updateBadges($conn, $jwt_token){
    $data = json_decode(file_get_contents("php://input"), true);
    $id = auth($jwt_token)->user_id;
    $badges = $data['badgeConfig'];

    try{
        $stmt = $conn->prepare('UPDATE profiles SET badges = ? WHERE user_id = ?');
        $stmt->execute([json_encode($badges), $id]);

        echo json_encode(['success' => true, 'message' => 'Berhasil ganti badges']);
    } catch (Exception $e){
        die(json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]));
    }
}