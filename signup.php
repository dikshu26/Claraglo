<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'website');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Check if username or email already exists
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE name = ? OR email = ?");
        $checkStmt->bind_param("ss", $name, $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $error = "Username or Email already exists.";
        } else {
            // Hash password and insert new user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insertStmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $insertStmt->bind_param("sss", $name, $email, $hashedPassword);

            if ($insertStmt->execute()) {
                $success = "Signup successful! Redirecting to login...";
                header("refresh:2;url=login.php");
            } else {
                $error = "User registration failed. Please try again.";
            }

            $insertStmt->close();
        }

        $checkStmt->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Clara Glo</title>
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
        h3 {
            font-size: 18px;
            color:#29923d;
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
        .message {
            margin-top: 10px;
            font-size: 14px;
            color: green;
        }
        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create account</h2>
        <h3>Sign up to get started</h3>

        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if (!empty($success)) echo "<p class='message'>$success</p>"; ?>

        <form action="signup.php" method="POST">
            <input type="text" name="name" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
            <button type="submit">Sign Up</button>
            <p class="switch">Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>
</html>
