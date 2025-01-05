<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f3f4f6;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .verification-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .icon-container {
            margin-bottom: 30px;
        }

        .email-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            fill: #4CAF50;
        }

        .title {
            color: #333;
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .message {
            color: #555;
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.7;
        }

        .resend-link {
            display: inline-block;
            margin-top: 20px;
            background-color: #4299e1;
            color: #fff;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .resend-link:hover {
            background-color: #2b7de9;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: 500;
        }

        .alert-success {
            background-color: #c6f6d5;
            color: #2f855a;
        }
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
