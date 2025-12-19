<?php
// delete_car.php
include 'connection.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
$stmt->execute([$id]);
header("Location: admin.php");
exit;
?>