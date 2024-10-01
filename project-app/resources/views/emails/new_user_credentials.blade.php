<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>New User Credentials</title>
    </head>
    <body>
        <h1>Welcome to FAMAS</h1>
        <h4>Fixed Asset Management System</h4>
        <p>System: {INSERT SYSTEM LINK HERE}</p>

        <br>

        <p>You are now registered in the sytem:</p>
        <p><strong>Email:</strong> {{ $email }}</p>
        <p><strong>Password:</strong> {{ $password }}</p>

        <br>

        <p>Change your password here: {INSERT CHANGE PASSWORD LINK HERE}</p>

        <br>

        <p>Note: Do not share your credentials with anyone</p>
    </body>
</html>
