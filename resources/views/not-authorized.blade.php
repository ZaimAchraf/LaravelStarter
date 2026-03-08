<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Not Authorized</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        h1 { color: #e74c3c; }
        p { font-size: 18px; }
        a { color: #3498db; text-decoration: none; }
    </style>
</head>
<body>
    <h1>Access Denied</h1>
    <p>You do not have permission to access this page.</p>
    <p><a href="{{ route('home') }}">Go back to Home</a></p>
</body>
</html>
