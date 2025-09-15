<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔃loading</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }

        .spinner-border {
            /* border: 0.5em solid rgba(0, 0, 0, 0.1); */
            /* border-top: 0.5em solid #3498db; */
            border-radius: 50%;
            width: 3rem;
            height: 3rem;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        p {
            font-size: 1.2rem;
            margin-top: 1rem;
        }

        .vh-100 {
            height: 100vh;
        }

        .d-flex {
            display: flex;
        }

        .justify-content-center {
            justify-content: center;
        }

        .align-items-center {
            align-items: center;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status" style="width: 5rem; height: 5rem;">
                <img src="../assets/img/404/1.jpg" alt="Loading..." style="width: 10rem; height: 10rem; margin-top: 50px; margin-right: 0px; position: absolute;">
            </div>
            <div class="spinner-border text-primary" role="status" style="width: 5rem; height: 5rem;">
                <img src="../assets/img/404/2.jpg" alt="Loading..." style="width: 10rem; height: 10rem; margin-top: -150px; margin-right: 0px; position:absolute;   rotate: -180deg;">
            </div>
        </div>
    </div>
</body>
<script>
                // <img src="../assets/img/404/2.jpg" alt="Loading..." style="width: 10rem; height: 10rem; margin-top: -150px; margin-right: 0px; position:absolute;   rotate: -180deg;">

</script>
</html>