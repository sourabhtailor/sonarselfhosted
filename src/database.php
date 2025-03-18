<?php
// SQL Injection Vulnerability
$conn = new mysqli("localhost", "root", "", "test_db");
$userInput = $_GET['user'];  // Unsafe input
$sql = "SELECT * FROM users WHERE username = '$userInput'";  // Unsafe query
$result = $conn->query($sql);
?>
