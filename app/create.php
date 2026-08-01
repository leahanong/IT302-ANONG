<?php
require_once 'config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    // Validation
    if (empty($name) || empty($email)) {
        $error = 'Name and email are required!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format!';
    } else {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("INSERT INTO users (name, email, phone) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $phone]);
            header("Location: index.php?msg=added");
            exit();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $error = 'Email already exists!';
            } else {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #1a73e8;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus {
            outline: none;
            border-color: #1a73e8;
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
        }
        .btn {
            display: inline-block;
            padding: 10px 25px;
            background: #1a73e8;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #1557b0;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .required {
            color: #dc3545;
        }
        .help-text {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>➕ Add New User</h1>
        
        <?php if ($error): ?>
            <div class="error">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="name">Full Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" placeholder="Enter full name" required 
                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="email">Email Address <span class="required">*</span></label>
                <input type="email" id="email" name="email" placeholder="Enter email address" required
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <div class="help-text">Email must be unique</div>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" placeholder="Enter phone number"
                       value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                <div class="help-text">Optional field</div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">💾 Save User</button>
                <a href="index.php" class="btn btn-secondary">↩️ Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>