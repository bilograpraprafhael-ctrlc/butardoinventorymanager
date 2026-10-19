<?php
session_start();
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'Admin') {
        header("Location: admin.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="twilight-bg">

    <header class="p-6 flex justify-between items-center max-w-6xl mx-auto w-full">
        <div class="flex items-center space-x-2">
            <i class="text-3xl text-cyan-400"></i>
            <span class="font-bold text-2xl tracking-wide">InventoryManager</span>
        </div>
        <nav class="space-x-4">
            <a href="login.php" class="text-sm font-medium hover:text-cyan-300 transition">Login</a>
            <a href="register.php" class="text-sm bg-cyan-500 text-slate-950 font-semibold px-4 py-2 rounded-full hover:bg-cyan-400 transition">Sign Up</a>
        </nav>
    </header>

    <main class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-xl text-center space-y-8 glass-card p-10 rounded-3xl">
            

            <div class="space-y-3">
                <h1 class="text-4xl font-extrabold tracking-tight">Inventory Manager</h1>
                <p class="text-gray-300 text-sm max-w-md mx-auto">
                    Manage stocks, items, and inventory effortlessly.<br>Choose an option below to continue.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                <a href="login.php" 
                   class="w-full sm:w-1/2 bg-white text-blue-900 font-bold py-3.5 px-6 rounded-full hover:bg-cyan-100 transition shadow-lg text-center">
                    <i class="fa-solid fa-right-to-bracket mr-2"></i> Log In
                </a>
                <a href="register.php" 
                   class="w-full sm:w-1/2 bg-blue-600/60 hover:bg-blue-600 border border-white/30 text-white font-semibold py-3.5 px-6 rounded-full transition shadow text-center">
                    <i class="fa-solid fa-user-plus mr-2"></i> Create Account
                </a>
            </div>

        </div>
    </main>

    <footer class="py-6 text-center text-xs text-gray-400 border-t border-white/5">
        <p>&copy; <?php echo date("Y"); ?> Butardo Inventory Manager. All Rights Reserved.</p>
    </footer>

</body>
</html>