<?php
session_start();
require_once 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@gmail\.com$/', $email)) {
        $error = "Email must end with @gmail.com";
    } elseif (empty($password)) {
        $error = "Password is required";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, full_name, password, role FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];

                // Route based on user role
                if ($user['role'] === 'Admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No user found with that email.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login-Inventory Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="twilight-bg">

        <header class="p-4 sm:p-6 max-w-6xl mx-auto w-full">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <i class="text-3xl text-cyan-400"></i>
                    <span class="font-bold text-xl sm:text-2xl tracking-wide">InventoryManager</span>
                </div>
                <nav class="hidden md:flex items-center space-x-4">
                    <a href="login.php" class="text-sm font-medium hover:text-cyan-300 transition">Login</a>
                    <a href="register.php" class="text-sm bg-cyan-500 text-slate-950 font-semibold px-4 py-2 rounded-full hover:bg-cyan-400 transition">Sign Up</a>
                </nav>
                <button id="menu-btn" class="md:hidden text-slate-200 hover:text-cyan-400 focus:outline-none p-1" aria-label="Toggle Navigation">
                    <svg id="menu-icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg id="menu-icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <nav id="mobile-menu" class="hidden md:hidden flex-col space-y-3 pt-4 mt-3 border-t border-slate-800">
                <a href="login.php" class="text-sm font-medium hover:text-cyan-300 transition py-1">Login</a>
                <a href="register.php" class="text-sm bg-cyan-500 text-slate-950 font-semibold px-4 py-2 rounded-full hover:bg-cyan-400 transition text-center w-full block">Sign Up</a>
            </nav>
        </header>
        <script>
            const menuBtn = document.getElementById('menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const openIcon = document.getElementById('menu-icon-open');
            const closeIcon = document.getElementById('menu-icon-close');

            menuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                openIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
            });
        </script>

    <main class="flex-grow flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md text-center">
            
            <div class="mx-auto mb-6 w-16 h-16 rounded-full glass-card flex items-center justify-center power-icon-glow">
                <i class="fa-solid fa-power-off text-2xl text-white"></i>
            </div>

            <h2 class="text-2xl font-semibold mb-6">Welcome back!</h2>

            <?php if (!empty($error)): ?>
                <div class="mb-4 text-sm bg-red-500/30 border border-red-400 text-red-200 px-4 py-2 rounded-lg">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form id="loginForm" action="login.php" method="POST" onsubmit="return validateLoginForm()" class="space-y-4">
                <div class="text-left">
                    <input type="email" id="email" name="email" placeholder="Email Address"
                        class="w-full glass-input text-sm rounded-full px-5 py-3">
                    <span id="emailError" class="text-xs text-red-300 ml-4 hidden"></span>
                </div>

                <div class="text-left">
                    <input type="password" id="password" name="password" placeholder="Password"
                        class="w-full glass-input text-sm rounded-full px-5 py-3">
                    <span id="passwordError" class="text-xs text-red-300 ml-4 hidden"></span>
                </div>

                <button type="submit" 
                    class="w-full bg-white text-blue-900 font-bold py-3 rounded-full hover:bg-cyan-100 transition duration-300 shadow-lg mt-4">
                    Login
                </button>
            </form>

            <div class="mt-12 pt-6 border-t border-white/10">
                <p class="text-sm text-gray-300 mb-4">No account?</p>
                <a href="register.php" 
                   class="inline-block w-full bg-blue-600/60 hover:bg-blue-600 border border-white/30 text-white font-semibold py-3 rounded-full transition duration-300 shadow">
                    Sign up
                </a>
            </div>
        </div>
    </main>

    <footer class="py-6 text-center text-xs text-gray-400 border-t border-white/5">
        <p>&copy; <?php echo date("Y"); ?> Inventory Management System. All rights reserved.</p>
    </footer>

    <script src="validation.js"></script>
</body>
</html>