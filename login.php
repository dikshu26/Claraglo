<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'website');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            header("Location: home.html"); // Redirect on successful login
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found. Please sign up.";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Clara Glo</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('https://static.vecteezy.com/system/resources/thumbnails/040/246/621/small/ai-generated-mockup-of-plastic-packaging-and-bottles-with-natural-organic-cosmetics-on-the-light-background-top-view-photo.jpg') no-repeat center center/cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            backdrop-filter: blur(4px); /* Blurs the background */
            z-index: -1;
        }
        .container {
            background: rgba(255, 255, 255, 0.85);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            width: 350px;
            text-align: center;
        }
        h2 {
            font-size: 28px;
            color: #444;
        }
        input[type="text"], input[type="password"], input[type="email"] {
            width: calc(100% - 24px);
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #bbb;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #29923d;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background:rgb(98, 157, 90);
        }
        .switch {
            margin-top: 12px;
            font-size: 14px;
        }
        .switch a {
            color: #29923d;
            text-decoration: none;
            font-weight: bold;
        }
        .switch a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <form action="login.php" method="POST" id="loginForm">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <p class="switch">Don't have an account? <a href="signup.php">Create one</a></p>
        </form>
    </div>
</body>
</html>
