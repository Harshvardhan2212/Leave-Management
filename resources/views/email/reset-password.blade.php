<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            margin: 0 0 15px;
        }
        .footer {
            font-size: 14px;
            color: #777;
            margin-top: 20px;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Hello {{ $firstName }},</h1>
        <p>
            Your password has been reset successfully. Here is your new password:
        </p>
        <p>
            <strong>{{ $password }}</strong>
        </p>
        <p>
            Please log in and change your password as soon as possible to ensure the security of your account.
        </p>
        <div class="footer">
            <p>Best regards,<br>The WebCodeGenie Team</p>
            <p><a href="{{ url('/') }}">Visit our website</a></p>
        </div>
    </div>
</body>

</html>
