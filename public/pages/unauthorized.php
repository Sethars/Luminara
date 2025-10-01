<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Tidak Diizinkan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        .error-icon {
            font-size: 80px;
            margin-bottom: 20px;
            color: #ff6b6b;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .message {
            font-size: 1.2rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .actions {
            display: flex;
            flex-direction: column;
            gap: 15px;
            align-items: center;
        }

        .btn {
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            min-width: 200px;
        }

        .btn-primary {
            background: #ff6b6b;
            color: white;
        }

        .btn-primary:hover {
            background: #ff5252;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }

            .error-icon {
                font-size: 60px;
            }

            h1 {
                font-size: 2rem;
            }

            .message {
                font-size: 1rem;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                max-width: 300px;
            }
        }

        /* Desktop Specific Styles */
        @media (min-width: 769px) {
            .actions {
                flex-direction: row;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-icon">
            <img src="../assets/img/reset_password/png.png" alt="" style="width:250px; height:auto;">
        </div>
        <h1>Eitss ga bolehh</h1>
        <p class="message">
            ade balik ade, ngapain ke sini
        </p>
        <div class="actions">
            <a href="/login" class="btn btn-primary">Login</a>
            <a href="/" class="btn btn-secondary">Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>