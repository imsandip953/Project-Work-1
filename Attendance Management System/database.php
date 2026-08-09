<?php
// Enforce session framework boundaries cleanly across all loaded scripts
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "campus_attendance";

// Open connection channel link layer
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Fail-fast error handler flag boundaries
if ($conn->connect_error) {
    die("Database communication framework failed: " . $conn->connect_error);
}

// Enforce modern clean UTF-8 string encoding across the connection pipe
$conn->set_charset("utf8mb4");
?>