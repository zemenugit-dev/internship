<?php
// Set the plain-text password you want to hash
$plain_password = "1234";

// Generate the secure BCrypt hash
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

// Display the result on the screen
echo "<h2>Password Hash Generator</h2>";
echo "<p><strong>Plain Password:</strong> " . htmlspecialchars($plain_password) . "</p>";
echo "<p><strong>Hashed Password (copy this for SQL):</strong></p>";
echo "<textarea rows='3' cols='80' onclick='this.select()'>" . htmlspecialchars($hashed_password) . "</textarea>";
?>