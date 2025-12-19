<?php
// edit_car.php
include 'connection.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $section = $_POST['section'];
    $imagePath = $car['image_path'];

    if (!empty($_FILES['image']['name'])) {
        $uploadDir = 'uploads/';
        $imagePath = $uploadDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    $stmt = $pdo->prepare("UPDATE cars SET name = ?, image_path = ?, price = ?, description = ?, section = ? WHERE id = ?");
    $stmt->execute([$name, $imagePath, $price, $description, $section, $id]);
    $message = '<div class="alert alert-success">Car updated successfully!</div>';
    // Refresh car data
    $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->execute([$id]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Car</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Edit Car</h1>
    <?php echo $message; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($car['name']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control">
            <img src="<?php echo htmlspecialchars($car['image_path']); ?>" alt="Current Image" style="width:200px; margin-top:10px;">
        </div>
        <div class="mb-3">
            <label>Price</label>
            <input type="text" name="price" value="<?php echo htmlspecialchars($car['price']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($car['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label>Section</label>
            <select name="section" class="form-control" required>
                <option value="most_searched" <?php if ($car['section'] == 'most_searched') echo 'selected'; ?>>Most Searched</option>
                <option value="latest" <?php if ($car['section'] == 'latest') echo 'selected'; ?>>Latest</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Car</button>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </form>
</body>
</html>