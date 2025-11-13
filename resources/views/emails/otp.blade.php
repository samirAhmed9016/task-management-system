<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Magical OTP</title>
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
            text-align: center;
            padding: 40px;
        }

        .container {
            background: rgba(0, 0, 0, 0.5);
            padding: 30px;
            border-radius: 15px;
            display: inline-block;
        }

        h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .otp {
            font-size: 3em;
            font-weight: bold;
            margin: 20px 0;
            padding: 15px;
            border-radius: 10px;
            background: #fff;
            color: #2575fc;
            letter-spacing: 5px;
            display: inline-block;
        }

        p {
            font-size: 1.1em;
            margin: 10px 0;
        }

        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #ddd;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🔮 Magical OTP 🔮</h1>
        <p>Hello! You requested to reset your password.</p>
        <p>Use the OTP below to verify your account:</p>
        <div class="otp">{{ $otp }}</div>
        <p>This OTP will expire in 10 minutes.</p>
        <p class="footer">If you didn’t request this, you can safely ignore this email.<br>{{ config('app.name') }}</p>
    </div>
</body>

</html>
