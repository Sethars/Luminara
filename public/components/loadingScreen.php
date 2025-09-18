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
      background-color: transparent;
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
      position: relative; /* penting untuk jadi parent positioning */
      display: inline-block;
    }

    /* Animasi putar berat */
 @keyframes heavy-spin {
  0%   { transform: rotate(0deg); }

  /* 90° */
  15%  { transform: rotate(-15deg); }
  20%  { transform: rotate(95deg); }   /* overshoot */
  21%  { transform: rotate(85deg); }   /* balik dikit */
  28%  { transform: rotate(90deg); }   /* settle */

  /* 180° */
  40%  { transform: rotate(75deg); }
  45%  { transform: rotate(185deg); }
  46%  { transform: rotate(175deg); }
  53%  { transform: rotate(180deg); }

  /* 270° */
  65%  { transform: rotate(165deg); }
  70%  { transform: rotate(275deg); }
  71%  { transform: rotate(265deg); }
  78%  { transform: rotate(270deg); }

  /* 360° */
  94%  { transform: rotate(255deg); }
  95%  { transform: rotate(365deg); }
  96%  { transform: rotate(355deg); }
  100% { transform: rotate(360deg); }
}



    .spinner-img {
      width: auto;
      height: 30rem;
      display: block;
      margin: auto;
    }

    .putar {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      margin: auto;
      animation: heavy-spin 10s infinite;
      transform-origin: center center;
    }

    .diam {
      position: relative;
      z-index: 2; /* selalu di atas */
    }
  </style>
</head>
<body>
  <div class="d-flex justify-content-center align-items-center vh-100">
    <div class="text-center">
      <img src="../assets/loadimg/diam.png" alt="Loading..." class="spinner-img diam">
      <img src="../assets/loadimg/putar.png" alt="Loading..." class="spinner-img putar">
    </div>
  </div>
</body>

</html>
