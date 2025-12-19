<?php
// edit_banner.php
include 'connection.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM banners WHERE id = ?");
$stmt->execute([$id]);
$banner = $stmt->fetch(PDO::FETCH_ASSOC);

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $link = $_POST['link'];
    $imagePath = $banner['image_path'];

    if (!empty($_FILES['image']['name'])) {
        $uploadDir = 'uploads/';
        $imagePath = $uploadDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    $stmt = $pdo->prepare("UPDATE banners SET image_path = ?, title = ?, subtitle = ?, link = ? WHERE id = ?");
    $stmt->execute([$imagePath, $title, $subtitle, $link, $id]);
    $message = '<div class="alert alert-success">Banner updated successfully!</div>';
    // Refresh banner data
    $stmt = $pdo->prepare("SELECT * FROM banners WHERE id = ?");
    $stmt->execute([$id]);
    $banner = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Banner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Edit Banner</h1>
    <?php echo $message; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control">
            <img src="<?php echo htmlspecialchars($banner['image_path']); ?>" alt="Current Image" style="width:200px; margin-top:10px;">
        </div>
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($banner['title']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Subtitle</label>
            <input type="text" name="subtitle" value="<?php echo htmlspecialchars($banner['subtitle']); ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label>Link</label>
            <input type="url" name="link" value="<?php echo htmlspecialchars($banner['link']); ?>" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update Banner</button>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </form>
</body>
</html>