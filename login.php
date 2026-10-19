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

    <header class="p-6 flex justify-between items-center max-w-6xl mx-auto w-full">
        <a href="index.php" class="flex items-center space-x-2">
            <i class="text-2xl text-cyan-400"></i>
            <span class="font-bold text-xl tracking-wide">InventoryManager</span>
        </a>
        <nav>
            <a href="register.php" class="text-sm bg-white text-cyan-400 hover:text-white hover:bg-cyan-400 transition p-3 rounded-2xl">Register</a>
        </nav>
    </header>

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