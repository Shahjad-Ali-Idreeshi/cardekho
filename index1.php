<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Your Dream Car</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .header {
            background: linear-gradient(90deg, #007bff, #0056b3);
            color: white;
            padding: 60px 20px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header h1 {
            font-weight: 700;
            font-size: 3rem;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        .form-container {
            max-width: 700px;
            margin: 50px auto;
            padding: 40px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        .form-container::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('https://images.unsplash.com/photo-1493238792000-8113da705763?ixlib=rb-4.0.3&auto=format&fit=crop&q=80') no-repeat center center;
            background-size: cover;
            opacity: 0.05;
            z-index: 0;
        }
        .form-content {
            position: relative;
            z-index: 1;
        }
        .section-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60px;
            height: 4px;
            background: #007bff;
            border-radius: 2px;
        }
        .form-check {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .form-check:hover {
            background: #e3f2fd;
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,123,255,0.1);
        }
        .form-check-input:checked + .form-check-label {
            color: #007bff;
            font-weight: 600;
        }
        .form-check-label {
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }
        .form-check-label i {
            font-size: 1.8rem;
            margin-right: 15px;
            color: #007bff;
        }
        .btn-submit {
            background: linear-gradient(90deg, #dc3545, #c82333);
            border: none;
            padding: 15px;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(220,53,69,0.3);
        }
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(220,53,69,0.4);
        }
        .form-control, .form-control:focus {
            border-radius: 12px;
            padding: 12px 15px;
            box-shadow: none;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.15);
        }
        .alert {
            border-radius: 12px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Find Your Perfect Car</h1>
        <p>Tell us your preferences and we'll help you choose the best match!</p>
    </div>

    <div class="form-container">
        <div class="form-content">
            <?php
            $host = 'localhost';
            $dbname = 'car_preferences';
            $username = 'root';  // Change if you have different MySQL credentials
            $password = '';      // Change if your MySQL has a password

            $message = '';
            $messageType = '';

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (empty($_POST['car_types'])) {
                    $message = 'Please select at least one car type.';
                    $messageType = 'danger';
                } else {
                    try {
                        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Create database and table if not exists
                        $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
                        $pdo->exec("USE $dbname");
                        $pdo->exec("CREATE TABLE IF NOT EXISTS customer_responses (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(255) NOT NULL,
                            phone VARCHAR(20) NOT NULL,
                            email VARCHAR(255) NOT NULL,
                            address TEXT NOT NULL,
                            car_types VARCHAR(255) NOT NULL,
                            submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                        )");

                        $name = trim($_POST['name']);
                        $phone = trim($_POST['phone']);
                        $email = trim($_POST['email']);
                        $address = trim($_POST['address']);
                        $car_types = implode(', ', $_POST['car_types']);

                        $stmt = $pdo->prepare("INSERT INTO customer_responses (name, phone, email, address, car_types) 
                                               VALUES (:name, :phone, :email, :address, :car_types)");
                        $stmt->execute([
                            ':name' => $name,
                            ':phone' => $phone,
                            ':email' => $email,
                            ':address' => $address,
                            ':car_types' => $car_types
                        ]);

                        $message = 'Thank you! Your preferences have been saved successfully. We\'ll get back to you soon!';
                        $messageType = 'success';
                    } catch (Exception $e) {
                        $message = 'Error: ' . $e->getMessage();
                        $messageType = 'danger';
                    }
                }
            }
            ?>

            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <strong><?php echo $messageType === 'success' ? 'Success!' : 'Oops!'; ?></strong> <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <h4 class="section-title">Select Your Preferred Car Types</h4>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="hatchback" name="car_types[]" value="Hatchback">
                            <label class="form-check-label" for="hatchback">
                                <i class="fas fa-car-side"></i> Hatchback
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sedan" name="car_types[]" value="Sedan">
                            <label class="form-check-label" for="sedan">
                                <i class="fas fa-car"></i> Sedan
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="suv" name="car_types[]" value="SUV">
                            <label class="form-check-label" for="suv">
                                <i class="fas fa-truck-monster"></i> SUV
                            </label>
                        </div>
                    </div>
                </div>

                <hr class="my-5">

                <h4 class="section-title">Your Contact Details</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" required>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                    </div>
                    <div class="col-12 mb-4">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="4" required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-danger btn-submit w-100">
                    <i class="fas fa-paper-plane me-2"></i> Submit Preferences
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>