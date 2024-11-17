<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the WebCodeGenie Family</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
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
            margin: 0 0 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
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
        <h1>Welcome to the WebCodeGenie Family, {{ $firstName }}!</h1>
        <p>
            We're thrilled to have you join the WebCodeGenie team! Your skills and enthusiasm will be a valuable asset to our company.
            We've created a welcoming environment where you can grow, learn, and contribute to our success.
        </p>
        <p>
            Please log in to your account and complete your profile. Your password is <strong>{{ $password }}</strong>.
            This will help us get to know you better and provide you with the necessary information to get started.
        </p>
        <p>
            We're excited to see what you'll achieve at WebCodeGenie!
        </p>
        <a href="https://your-website.com/login" class="button">Log In</a>
        <div class="footer">
            <p>Best regards,</p>
            <p>The WebCodeGenie Team</p>
            <p><a href="https://your-website.com">Visit our website</a></p>
        </div>
    </div>
</body>
</html>
