<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "website";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);

$email = $conn->real_escape_string($data['email']);
$phone = $conn->real_escape_string($data['phone']);
$name = $conn->real_escape_string($data['name']);
$street = $conn->real_escape_string($data['street']);
$city = $conn->real_escape_string($data['city']);
$state = $conn->real_escape_string($data['state']);
$pincode = $conn->real_escape_string($data['pincode']);
$method = $conn->real_escape_string($data['method']);
$cart = $conn->real_escape_string(json_encode($data['cart']));

$sql = "INSERT INTO payments (email, phone, name, street, city, state, pincode, method, cart)
        VALUES ('$email', '$phone', '$name', '$street', '$city', '$state', '$pincode', '$method', '$cart')";

if ($conn->query($sql)) {
  echo "Payment successful! Thank you, $name.";
} else {
  echo "Error: " . $conn->error;
}
$conn->close();
?>