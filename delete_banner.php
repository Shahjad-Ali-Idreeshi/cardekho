<?php
// delete_banner.php
include 'connection.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM banners WHERE id = ?");
$stmt->execute([$id]);
header("Location: admin.php");
exit;
?>