<?php
$request = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '', '/');


require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../vendor/autoload.php';

if (str_starts_with($request, "api/")) {
    header("Content-Type: application/json");

    switch ($request) {
        case "api/login":
            require __DIR__ . '/../includes/auth.php';
            login($conn, $jwt_token);
            break;
        
        case "api/sendOTP":
            require __DIR__ . '/../includes/register.php';
            sendOTP($conn, $mailconfig);
            break;

        case "api/verifyOTP":
            require __DIR__ . '/../includes/register.php';
            verifyOTP($conn);
            break;

        case "api/resetPassword":
            require __DIR__ . '/../includes/resetPassword.php';
            resetPassword($base_url,$conn, $mailconfig);
            break;

        case "api/verifyToken":
            require __DIR__ . '/../includes/resetPassword.php';
            checkTokenResetPassword($conn);
            break;

        case "api/changeResetPassword":
            require __DIR__ . '/../includes/resetPassword.php';
            changePassword($conn);
            break;

        case "api/changePassword":
            require __DIR__ . '/../includes/changePassword.php';
            changePassword($conn);
            break;

        case "api/auth":
            require __DIR__ . '/../includes/checkAuth.php';
            break;

        case "api/logout":
            require __DIR__ . '/../includes/logout.php';
            break;

        case "api/deleteAccount":
            require __DIR__ . '/../includes/auth.php';
            deleteAccount($conn);
            break;

        case "api/changeUsername":
            require __DIR__ . '/../includes/profileData.php';
            changeUsername($conn);
            break;

        default:
            http_response_code(404);
            echo json_encode(["error" => "API route not found"]);
            break;
    }

    exit;
}

switch ($request) {
    case '':
        require __DIR__ . '/pages/dashboard.php';
        break;
    case 'login':
        require __DIR__ . '/pages/login.php';
        break;
    case 'register':
        require __DIR__ . '/pages/register.php';
        break;
    case 'verification':
        require __DIR__ . '/pages/email_verification.php';
        break;
    case 'resetPassword':
        require __DIR__ . '/pages/reset_password.php';
        break;
    case 'changePassword':
        require __DIR__ . '/pages/change_password.php';
        break;
    case 'BJLobby':
        require __DIR__ . '/pages/BJLobby.php';
        break;
    case 'profile':
        require __DIR__ . '/pages/profile.php';
        break;
    case 'casual_black_jack':
        require __DIR__ . '/pages/casual_black_jack.php';
        break;
    case 'BJLobbyCS':
        require __DIR__ . '/pages/BJLobby_cs.php';
        break;
    case 'contact':
        require __DIR__ . '/pages/contact.php';
        break;
    default:
        require __DIR__ . '/pages/not_found.php';
        break;
}