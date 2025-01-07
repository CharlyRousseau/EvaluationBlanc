<?php
require_once "../src/database/db.php";
$conn = connectDB();

$id = $_GET["id"];
$conn->query("DELETE FROM vehicules WHERE id = $id");

header("Location: dashboard.php"); // Rediriger après suppression
?>
