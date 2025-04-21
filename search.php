<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "website";

// Database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize keyword with a default value
$keyword = '';

// Check if 'search' parameter exists in the query string
if (isset($_GET['search'])) {
    $keyword = htmlspecialchars($_GET['search']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Products</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('https://media.istockphoto.com/id/1289621756/photo/cream-cosmetic-sheet-on-colored-background.jpg?s=612x612&w=0&k=20&c=nzcG4Fl_W1ExIEZUD5gPcfihxlpavtwLs_wmNH6pnjY=') no-repeat center center/cover;
            padding: 200px;
            color: black;
        }

        .results {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #29923d;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

    <div class="results">
        <?php
        if (!empty($keyword)) {
            // Prepare and execute the SQL query
            $sql = "SELECT * FROM products WHERE name LIKE ? OR category LIKE ?";
            $stmt = $conn->prepare($sql);
            $searchTerm = "%" . $keyword . "%";
            $stmt->bind_param("ss", $searchTerm, $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<h3>Search Results for '<strong>" . htmlspecialchars($keyword) . "</strong>':</h3>";
                echo "<table>";
                echo "<tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Description</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                    echo "<td>₹" . htmlspecialchars($row['price']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No results found for '<strong>" . htmlspecialchars($keyword) . "</strong>'.</p>";
            }
            
            // Close the statement
            $stmt->close();
        } else {
            // Display a message if no search term is entered
            echo "<p>Please enter a search term.</p>";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>
</body>
</html>
