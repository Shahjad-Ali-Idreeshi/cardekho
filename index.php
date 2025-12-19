<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarDekho Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background: linear-gradient(180deg, #007bff, #0056b3); color: white; padding-top: 20px; }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); font-weight: 500; border-radius: 8px; margin: 5px 15px; padding: 12px 20px; transition: all 0.3s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,0.2); color: white; }
        .sidebar .nav-link i { width: 25px; }
        .main-content { padding: 30px; }
        .stat-card { background: white; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); padding: 25px; text-align: center; transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-10px); }
        .stat-card i { font-size: 3rem; color: #007bff; margin-bottom: 15px; }
        .stat-card h3 { font-weight: 700; margin: 0; }
        .stat-card p { color: #6c757d; margin: 0; }
        .table { box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .btn-action { padding: 5px 12px; font-size: 0.85rem; }
        .navbar-admin { background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar col-md-3 col-lg-2 d-md-block">
            <div class="text-center mb-5">
                <h4 class="fw-bold">CarDekho Admin</h4>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link active" href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a class="nav-link" href="add_banner.php"><i class="fas fa-images"></i> Add Banner</a>
                <a class="nav-link" href="add_car.php"><i class="fas fa-car"></i> Add Car</a>
                <a class="nav-link" href="index.php" target="_blank"><i class="fas fa-home"></i> View Website</a>
                <a class="nav-link" href="#"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <nav class="navbar navbar-admin navbar-expand-lg">
                <div class="container-fluid">
                    <span class="navbar-brand mb-0 h1">Welcome, Admin</span>
                    <div class="ms-auto">
                        <span class="me-3"><?php echo date('F j, Y'); ?></span>
                    </div>
                </div>
            </nav>

            <div class="main-content">
                <?php include 'connection.php'; ?>

                <!-- Stats Cards -->
                <div class="row mb-5">
                    <?php
                    $bannerCount = $pdo->query("SELECT COUNT(*) FROM banners")->fetchColumn();
                    $carCount = $pdo->query("SELECT COUNT(*) FROM cars")->fetchColumn();
                    $mostSearchedCount = $pdo->query("SELECT COUNT(*) FROM cars WHERE section = 'most_searched'")->fetchColumn();
                    $latestCount = $pdo->query("SELECT COUNT(*) FROM cars WHERE section = 'latest'")->fetchColumn();
                    ?>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="fas fa-images"></i>
                            <h3><?php echo $bannerCount; ?></h3>
                            <p>Total Banners</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="fas fa-car"></i>
                            <h3><?php echo $carCount; ?></h3>
                            <p>Total Cars Listed</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="fas fa-fire"></i>
                            <h3><?php echo $mostSearchedCount; ?></h3>
                            <p>Most Searched Cars</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="fas fa-clock"></i>
                            <h3><?php echo $latestCount; ?></h3>
                            <p>Latest Cars</p>
                        </div>
                    </div>
                </div>

                <!-- Banners Management -->
                <h3 class="mb-4">Banners Management</h3>
                <div class="mb-3 text-end">
                    <a href="add_banner.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Banner</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Subtitle</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("SELECT * FROM banners ORDER BY created_at DESC");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr>
                                    <td>' . $row['id'] . '</td>
                                    <td><img src="' . htmlspecialchars($row['image_path']) . '" alt="Banner" style="width:120px; border-radius:8px;"></td>
                                    <td>' . htmlspecialchars($row['title'] ?? 'No Title') . '</td>
                                    <td>' . htmlspecialchars($row['subtitle'] ?? '-') . '</td>
                                    <td>
                                        <a href="edit_banner.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm btn-action"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="delete_banner.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm btn-action" onclick="return confirm(\'Delete this banner?\')"><i class="fas fa-trash"></i> Delete</a>
                                    </td>
                                </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Cars Management -->
                <h3 class="mb-4 mt-5">Cars Management</h3>
                <div class="mb-3 text-end">
                    <a href="add_car.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Car</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Price</th>
                                <th>Section</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("SELECT * FROM cars ORDER BY created_at DESC");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr>
                                    <td>' . $row['id'] . '</td>
                                    <td>' . htmlspecialchars($row['name']) . '</td>
                                    <td><img src="' . htmlspecialchars($row['image_path']) . '" alt="Car" style="width:120px; border-radius:8px;"></td>
                                    <td>' . htmlspecialchars($row['price']) . '</td>
                                    <td>' . ucfirst($row['section']) . '</td>
                                    <td>
                                        <a href="edit_car.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm btn-action"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="delete_car.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm btn-action" onclick="return confirm(\'Delete this car?\')"><i class="fas fa-trash"></i> Delete</a>
                                    </td>
                                </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>