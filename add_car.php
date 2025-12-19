<?php
// add_car.php
include 'connection.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $section = $_POST['section'];

    // Handle file upload
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $imagePath = $uploadDir . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $stmt = $pdo->prepare("INSERT INTO cars (name, image_path, price, description, section) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $imagePath, $price, $description, $section]);
            $message = '<div class="alert alert-success">Car added successfully!</div>';
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
    <title>Add Car</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Add New Car</h1>
    <?php echo $message; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Price</label>
            <input type="text" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label>Section</label>
            <select name="section" class="form-control" required>
                <option value="most_searched">Most Searched</option>
                <option value="latest">Latest</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Car</button>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </form>
</body>
</html>