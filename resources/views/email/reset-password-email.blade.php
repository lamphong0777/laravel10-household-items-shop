<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            background-color: #ffffff;
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            color: #333;
            font-size: 24px;
            text-align: center;
            border-bottom: 2px solid #f4f4f4;
            padding-bottom: 10px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 10px 0;
            font-size: 16px;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .content {
            color: #333;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
<div class="email-container">
    <h1>{{ $formData['subject'] }}</h1>
    <ul>
        <li><span class="label">Hello: </span>{{ $formData['user']->name }}</li>
        <li>Please click the link given below to reset your password.</li>
        <li><a href="{{ route('user.email-reset-password', $formData['token']) }}" class="label">Click here</a></li>
    </ul>
    <div class="footer">
        This email was sent from THE GIOI GIA DUNG, thanks.
    </div>
</div>
</body>
</html>
