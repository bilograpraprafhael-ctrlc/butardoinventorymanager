<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="flex flex-col min-h-screen">
    <header class="bg-indigo-700 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col sm:flex-row justify-between items-center">
            <a href="dashboard.php" class="text-2xl font-bold tracking-wide flex items-center gap-2">
             StockMaster
            </a>
            
            <?php if (isset($_SESSION['user_id'])): ?>
            <nav class="mt-3 sm:mt-0 flex items-center space-x-4">
                <a href="dashboard.php" class="hover:text-indigo-200 text-sm font-medium transition">Dashboard</a>
                <a href="inventory.php" class="hover:text-indigo-200 text-sm font-medium transition">Inventory</a>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
                    <a href="admin.php" class="bg-indigo-900 hover:bg-indigo-800 px-3 py-1 rounded text-sm font-semibold text-yellow-300 transition">Admin Panel</a>
                <?php endif; ?>
                <a href="logout.php" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-xs font-semibold transition">Logout</a>
            </nav>
            <?php endif; ?>
        </div>
    </header>
    <main class="flex-grow max-w-7xl w-full mx-auto p-4 sm:p-6">