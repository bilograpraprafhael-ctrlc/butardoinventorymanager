<?php
session_start();
require_once 'db.php';

// Authorization Check: Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Redirect Admin to admin.php
if ($_SESSION['role'] === 'Admin') {
    header("Location: admin.php");
    exit();
}

// Search functionality
$search = "";
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $search_term = "%" . $search . "%";
    $stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE product_name LIKE ? OR category LIKE ? ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, "ss", $search_term, $search_term);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee View</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col justify-between font-sans">

    <header class="bg-slate-800 border-b border-slate-700 p-4 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-4">
            <div class="flex items-center space-x-3">
                <i class="text-2xl text-cyan-400"></i>
                <span class="font-bold text-xl text-white">Inventory Employee Dashboard</span>
            </div>
            
            <div class="flex items-center space-x-6">
                <span class="text-sm text-gray-300">
                    Employee: <strong class="text-cyan-400"><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong>
                </span>
                <a href="logout.php" class="bg-red-600 hover:bg-red-500 text-white text-xs font-semibold px-4 py-2 rounded-lg transition">
                    <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto w-full p-4 sm:p-6 flex-grow space-y-6">

        <!-- Search Bar -->
        <div class="bg-slate-800 p-4 rounded-xl border border-slate-700">
            <form action="dashboard.php" method="GET" class="flex items-center space-x-2">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search product or category..." 
                    class="w-full bg-slate-900 border border-slate-600 text-sm rounded-lg px-4 py-2 text-white">
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg text-sm">
                    <i class="fa-solid fa-magnifying-glass"></i> Search
                </button>
                <?php if(!empty($search)): ?>
                    <a href="dashboard.php" class="bg-gray-700 text-gray-300 px-3 py-2 rounded-lg text-sm">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead class="bg-slate-900 text-xs text-gray-400 uppercase tracking-wider border-b border-slate-700">
                        <tr>
                            <th class="p-4">ID</th>
                            <th class="p-4">Product Name</th>
                            <th class="p-4">Category</th>
                            <th class="p-4">Quantity</th>
                            <th class="p-4">Price (₱)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr class="hover:bg-slate-700/50 transition">
                                    <td class="p-4 font-mono text-gray-400">#<?php echo $row['id']; ?></td>
                                    <td class="p-4 font-medium text-white"><?php echo htmlspecialchars($row['product_name']); ?></td>
                                    <td class="p-4"><span class="bg-slate-900 px-2 py-1 rounded text-xs border border-slate-600"><?php echo htmlspecialchars($row['category']); ?></span></td>
                                    <td class="p-4"><?php echo $row['quantity']; ?></td>
                                    <td class="p-4 font-mono text-cyan-300">₱<?php echo number_format($row['price'], 2); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-500">No inventory products found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer class="bg-slate-800 border-t border-slate-700 py-4 text-center text-xs text-gray-400">
        <p>&copy; <?php echo date("Y"); ?> Butardo Inventory Manager. All Rights Reserved.</p>
    </footer>

</body>
</html>