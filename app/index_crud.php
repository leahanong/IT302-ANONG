<?php
require_once 'config/database.php';

$pdo = getDBConnection();

// Handle DELETE request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: index.php?msg=deleted");
    exit();
}

// Fetch all users
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple CRUD App</title>
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
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #1a73e8;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
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
        .btn-success {
            background: #28a745;
        }
        .btn-success:hover {
            background: #218838;
        }
        .btn-danger {
            background: #dc3545;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .btn-warning {
            background: #ffc107;
            color: #333;
        }
        .btn-warning:hover {
            background: #e0a800;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
        }
        .table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }
        .table tr:hover {
            background: #f8f9fa;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .search-box {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            width: 200px;
        }
        .empty-state {
            text-align: center;
            padding: 50px 0;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 50px;
            margin-bottom: 20px;
            display: block;
        }
        .badge {
            background: #e9ecef;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            color: #495057;
        }
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .count-badge {
            background: #1a73e8;
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 User Management System</h1>
        
        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] == 'added'): ?>
                <div class="alert alert-success">✅ User added successfully!</div>
            <?php elseif ($_GET['msg'] == 'updated'): ?>
                <div class="alert alert-success">✅ User updated successfully!</div>
            <?php elseif ($_GET['msg'] == 'deleted'): ?>
                <div class="alert alert-success">✅ User deleted successfully!</div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="top-bar">
            <div>
                <a href="create.php" class="btn btn-success">➕ Add New User</a>
            </div>
            <div>
                <span class="count-badge">Total: <?= count($users) ?> users</span>
            </div>
        </div>

        <?php if (count($users) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><strong><?= htmlspecialchars($user['name']) ?></strong></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['phone']) ?></td>
                            <td>
                                <span class="badge">
                                    <?= date('M d, Y', strtotime($user['created_at'])) ?>
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
                                    <a href="index.php?delete=<?= $user['id'] ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Are you sure you want to delete this user?')">🗑️ Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <i>📭</i>
                <h3>No users found</h3>
                <p>Start by adding your first user!</p>
                <a href="create.php" class="btn btn-success">➕ Add New User</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
