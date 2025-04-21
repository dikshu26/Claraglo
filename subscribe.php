<?php
// Database credentials
$servername = "localhost"; // or your server name
$username = "root"; // your MySQL username
$password = ""; // your MySQL password (leave empty if none)
$dbname = "website"; // replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the email from the form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Validate email (basic check)
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

        // Prepare and bind (to prevent SQL injection)
        $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->bind_param("s", $email); // "s" means string

        // Execute the statement
        if ($stmt->execute() === TRUE) {
            echo "New record created successfully";
            header("Location: " . $_SERVER["HTTP_REFERER"]); // Redirect back to the previous page
            exit(); // Make sure to exit after redirecting
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid email format";
    }
}

$conn->close();
?>
