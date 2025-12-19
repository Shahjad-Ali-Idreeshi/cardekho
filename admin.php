<?php
// add_banner.php
include 'connection.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $link = $_POST['link'];

    // Handle file upload
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $imagePath = $uploadDir . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $stmt = $pdo->prepare("INSERT INTO banners (image_path, title, subtitle, link) VALUES (?, ?, ?, ?)");
            $stmt->execute([$imagePath, $title, $subtitle, $link]);
            $message = '<div class="alert alert-success">Banner added successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">Failed to upload image.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Banner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Add New Banner</h1>
    <?php echo $message; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Subtitle</label>
            <input type="text" name="subtitle" class="form-control">
        </div>
        <div class="mb-3">
            <label>Link</label>
            <input type="url" name="link" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Add Banner</button>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </form>
</body>
</html>