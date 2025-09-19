<?php

function getData($conn){
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['userId'];

    $stmt = $conn->prepare('SELECT bio, gender, photo, badges FROM profiles WHERE user_id = ?');
    $stmt->execute([$id]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'profile' => $profile]);
}

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

function changePhotoProfile($conn){
    if (!isset($_POST["user_id"]) || !isset($_FILES["pp"])) {
        echo json_encode(["success" => false, "message" => "Data tidak lengkap"]);
        exit;
    }

    $user_id = intval($_POST["user_id"]);

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

    if (move_uploaded_file($_FILES["pp"]["tmp_name"], $targetFile)) {
        $stmt = $conn->prepare("UPDATE profiles SET photo = ? WHERE user_id = ?");
        $stmt->execute([$urlFile, $user_id]);

        echo json_encode([
            "success" => true,
            "file_url" => $urlFile
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal upload file"]);
    }
}

function changeBio($conn){
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['userId'];
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

function changeGender($conn){
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['userId'];
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

function updateBadges($conn){
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['userId'];
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