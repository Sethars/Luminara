<?php
require __DIR__ . "/mailer.php";

function resetPassword($base_url ,$conn, $mailconfig) {
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'] ?? null;

    if(!$email){
        echo json_encode(["success" => false, "message" => "Email tidak boleh kosong"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$existing) {
        echo json_encode(["success" => false, "message" => "Email tidak terdaftar"]);
        exit;
    } else {
        $token = bin2hex(random_bytes(32)); // Generate a random token

        $identifier = substr(hash('sha256', $token), 0, 16); // Create a unique identifier
        $hash = password_hash($token, PASSWORD_DEFAULT); // Hash the token for security
        $tokenExpired = time() + (10 * 60); // 10 menit dari sekarang

        $stmt = $conn->prepare("SELECT * FROM password_resets WHERE user_id = ?");
        $stmt->execute([$existing['id']]);
        $existingReset = $stmt->fetch(PDO::FETCH_ASSOC);

        if($existingReset) {
            $stmt = $conn->prepare("UPDATE password_resets SET identifier = ?, token = ?, expired_at = ?, created_at = ? WHERE user_id = ?");
            $stmt->execute([$identifier, $hash, $tokenExpired, time(), $existing['id']]);
        } else {
            $stmt = $conn->prepare("INSERT INTO password_resets (user_id, identifier, token, expired_at, created_at) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$existing['id'], $identifier, $hash, $tokenExpired, time()]);
        }

        $template = file_get_contents("../public/components/mailbox.php");
        $link = "$base_url/changePassword?identifier=$identifier&token=$token"; // Link to reset password

        $subject = "Reset Password Anda";
        $body = str_replace(
            ["{{TITLE}}", "{{HEADER}}", "{{CONTENTS}}"],
            ["Permintaan Reset Password", "Gunakan link berikut untuk mengatur ulang password Anda:", "<a href='$link'>Klik di sini untuk reset password</a>"],
            $template
        );

        $send = sendEmail($email, $subject, $body, $mailconfig);
        if($send === true){
            echo json_encode(["success" => true, "message" => "Email reset password telah dikirim"]);
        } else {
            echo json_encode(["success" => false, "message" => "Gagal mengirim email: $send"]);
        }
    }
}

function checkTokenResetPassword($conn) {
    $data = json_decode(file_get_contents("php://input"), true);
    $token = $data['token'] ?? null;
    $identifier = $data['identifier'] ?? null;

    if (!$token || !$identifier) {
        echo json_encode(["valid" => false, "message" => "Token tidak valid"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, token FROM password_resets WHERE identifier = ? AND expired_at > ?");
    $stmt->execute([$identifier, time()]);
    $resetRequest = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resetRequest && password_verify($token, $resetRequest['token'])) {
        echo json_encode(["valid" => true, "message" => "Token valid"]);
    } else {
        echo json_encode(["valid" => false, "message" => "Token tidak valid atau sudah kedaluwarsa"]);
    }
}

function changePassword($conn) {
    $data = json_decode(file_get_contents("php://input"), true);

    $stmt = $conn->prepare("SELECT id, user_id, token FROM password_resets WHERE identifier = ? AND expired_at > ?");
    $stmt->execute([ $data['identifier'], time()]);
    $resetRequest = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resetRequest) {
        echo json_encode(["success" => false, "message" => "Token tidak valid atau sudah kedaluwarsa"]);
        exit;
    }

    if(!password_verify($data['token'], $resetRequest['token'])) {
        echo json_encode(["success" => false, "message" => "Token tidak valid"]);
        exit;
    }

    if (!$data['newPassword'] || !$data['confirmPassword']) {
        echo json_encode(["success" => false, "message" => "Password dan konfirmasi password tidak boleh kosong"]);
        exit;
    } 
    
    if ($data['newPassword'] !== $data['confirmPassword']) {
        echo json_encode(["success" => false, "message" => "Password dan konfirmasi password tidak sesuai"]);
        exit;
    }


    $hashedPassword = password_hash($data['newPassword'], PASSWORD_DEFAULT);

    // Update the user's password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hashedPassword, $resetRequest['user_id']]);

    // Delete the used reset token
    $stmt = $conn->prepare("DELETE FROM password_resets WHERE id = ?");
    $stmt->execute([$resetRequest['id']]);

    echo json_encode(["success" => true, "message" => "Password berhasil diubah"]);
}