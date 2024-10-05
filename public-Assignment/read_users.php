<?php

include '../config/Database.php';

// Database connection
$database = new Database();
$conn = $database->connect();

// Fetch all users
$query = "SELECT userId, username, email, created_at, updated_at FROM users";
$stmt = $conn->prepare($query);
$stmt->execute();

// Display the data in a table
echo "<table class='table table-striped'>";
echo "<thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Created At</th><th>Updated At</th></tr></thead><tbody>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['userId']) . "</td>";
    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
    echo "<td>" . htmlspecialchars($row['updated_at']) . "</td>";
    echo "</tr>";
}

echo "</tbody></table>";