<?php
session_start();
require_once 'db.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $role = 'Employee'; // Automatically assigned as Employee

    if (preg_match('/[0-9]/', $full_name)) {
        $error = "Name must not contain numbers.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@gmail\.com$/', $email)) {
        $error = "Email must end with @gmail.com";
    } elseif (!preg_match('/^09\d{9}$/', $phone)) {
        $error = "Phone number must start with 09 and contain exactly 11 numbers.";
    } elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $error = "Password must contain letters, numbers, special characters, and be at least 8 characters.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $check_stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $error = "Email address is already registered.";
        } else {
            $stmt = mysqli_prepare($conn, "INSERT INTO users (full_name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssss", $full_name, $email, $phone, $hashed_password, $role);
            if (mysqli_stmt_execute($stmt)) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($check_stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - StockMaster</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="twilight-bg">

    <header class="p-6 flex justify-between items-center max-w-6xl mx-auto w-full">
        <a href="index.php" class="flex items-center space-x-2">
            <i class=" text-2xl text-cyan-400"></i>
            <span class="font-bold text-xl tracking-wide">InventoryManager</span>
        </a>
        <nav>
            <a href="login.php" class="text-sm bg-white text-cyan-400 hover:text-white hover:bg-cyan-400 transition p-3 rounded-2xl">Login</a>
        </nav>
    </header>

    <main class="flex-grow flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md text-center">

            <h2 class="text-2xl font-semibold mb-6">Create Employee Account</h2>

            <?php if (!empty($error)): ?>
                <div class="mb-4 text-sm bg-red-500/30 border border-red-400 text-red-200 px-4 py-2 rounded-lg">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="mb-4 text-sm bg-green-500/30 border border-green-400 text-green-200 px-4 py-2 rounded-lg">
                    <?php echo htmlspecialchars($success); ?> <a href="login.php" class="underline font-bold">Login here</a>
                </div>
            <?php endif; ?>

            <form id="regForm" action="register.php" method="POST" onsubmit="return validateRegisterForm()" class="space-y-4">
                
                <div class="text-left">
                    <input type="text" id="full_name" name="full_name" placeholder="Full Name"
                        class="w-full glass-input text-sm rounded-full px-5 py-3">
                    <span id="nameError" class="text-xs text-red-300 ml-4 hidden"></span>
                </div>

                <div class="text-left">
                    <input type="email" id="email" name="email" placeholder="Email"
                        class="w-full glass-input text-sm rounded-full px-5 py-3">
                    <span id="emailError" class="text-xs text-red-300 ml-4 hidden"></span>
                </div>

                <div class="text-left">
                    <input type="text" id="phone" name="phone" placeholder="Phone Number" maxlength="11"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="w-full glass-input text-sm rounded-full px-5 py-3">
                    <span id="phoneError" class="text-xs text-red-300 ml-4 hidden"></span>
                </div>

                <div class="text-left">
                    <input type="password" id="password" name="password" placeholder="Password"
                        class="w-full glass-input text-sm rounded-full px-5 py-3">
                    <span id="passwordError" class="text-xs text-red-300 ml-4 hidden"></span>
                </div>

                <div class="text-left bg-white/10 px-5 py-3 rounded-full text-xs text-gray-300 border border-white/20">
                    Role Assigned: <strong class="text-cyan-300">Employee</strong>
                </div>

                <button type="submit" 
                    class="w-full bg-cyan-400 text-blue-950 font-bold py-3 rounded-full hover:bg-cyan-300 transition duration-300 shadow-lg mt-4">
                    Register Account
                </button>
            </form>

            <div class="mt-8">
                <p class="text-sm text-gray-300">Already registered? <a href="login.php" class="text-cyan-300 underline">Login here</a></p>
            </div>
        </div>
    </main>

    <footer class="py-6 text-center text-xs text-gray-400 border-t border-white/5">
        <p>&copy; <?php echo date("Y"); ?> Inventory Management System. All rights reserved.</p>
    </footer>

    <script src="validation.js"></script>
</body>
</html>