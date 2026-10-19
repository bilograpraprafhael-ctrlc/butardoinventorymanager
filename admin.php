<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

$msg = "";
$error = "";

if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $p_name = trim($_POST['product_name']);
    $category = trim($_POST['category']);
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    if (preg_match('/[0-9]/', $category)) {
        $error = "Category name must not contain numbers.";
    } elseif (!is_numeric($quantity) || !is_numeric($price)) {
        $error = "Quantity and Price must contain numbers only.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO products (product_name, category, quantity, price) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssid", $p_name, $category, $quantity, $price);
        if (mysqli_stmt_execute($stmt)) {
            $msg = "Product added successfully.";
        }
        mysqli_stmt_close($stmt);
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = $_POST['product_id'];
    $p_name = trim($_POST['product_name']);
    $category = trim($_POST['category']);
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    if (!is_numeric($quantity) || !is_numeric($price)) {
        $error = "Quantity and Price must contain numbers only.";
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE products SET product_name = ?, category = ?, quantity = ?, price = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssidi", $p_name, $category, $quantity, $price, $id);
        if (mysqli_stmt_execute($stmt)) {
            $msg = "Product updated successfully.";
        }
        mysqli_stmt_close($stmt);
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['product_id'];
    $stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        $msg = "Product deleted successfully.";
    }
    mysqli_stmt_close($stmt);
}
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
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col justify-between font-sans">

    <header class="bg-slate-800 border-b border-slate-700 p-4 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-4">
            <div class="flex items-center space-x-3">
                <i class="text-2xl text-cyan-400"></i>
                <span class="font-bold text-xl text-white">InventoryManager Admin Dashboard</span>
            </div>
            
            <div class="flex items-center space-x-6">
                <span class="text-sm text-gray-300">
                    Admin: <strong class="text-cyan-400"><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong>
                </span>
                <a href="logout.php" class="bg-red-600 hover:bg-red-500 text-white text-xs font-semibold px-4 py-2 rounded-lg transition">
                    <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto w-full p-4 sm:p-6 flex-grow space-y-6">

        <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-slate-800 p-4 rounded-xl border border-slate-700">
            <form action="admin.php" method="GET" class="flex items-center w-full md:w-1/2 space-x-2">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search inventory..." 
                    class="w-full bg-slate-900 border border-slate-600 text-sm rounded-lg px-4 py-2 text-white">
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg text-sm">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <?php if(!empty($search)): ?>
                    <a href="admin.php" class="bg-gray-700 text-gray-300 px-3 py-2 rounded-lg text-sm">Clear</a>
                <?php endif; ?>
            </form>

            <button onclick="openModal('addModal')" class="w-full md:w-auto bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold px-4 py-2 rounded-lg text-sm flex items-center justify-center space-x-2 transition">
                <i class="fa-solid fa-plus"></i>
                <span>Add Product</span>
            </button>
        </div>

        <?php if (!empty($msg)): ?>
            <div class="bg-green-600/30 border border-green-500 text-green-200 p-3 rounded-lg text-sm">
                <?php echo htmlspecialchars($msg); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="bg-red-600/30 border border-red-500 text-red-200 p-3 rounded-lg text-sm">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

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
                            <th class="p-4 text-center">Actions</th>
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
                                    <td class="p-4 text-center space-x-2">
                                        <button onclick="editProduct(<?php echo htmlspecialchars(json_encode($row)); ?>)" 
                                            class="bg-amber-600 hover:bg-amber-500 text-white text-xs px-3 py-1.5 rounded transition">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </button>
                                        <form action="admin.php" method="POST" class="inline" onsubmit="return confirm('Delete item?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="bg-red-600 hover:bg-red-500 text-white text-xs px-3 py-1.5 rounded transition">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-500">No products found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="addModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 hidden z-50">
        <div class="bg-slate-800 border border-slate-700 w-full max-w-md rounded-xl p-6 space-y-4">
            <h3 class="text-lg font-bold text-white">Add Product</h3>
            <form action="admin.php" method="POST" class="space-y-3">
                <input type="hidden" name="action" value="add">
                <div>
                    <label class="text-xs text-gray-400">Product Name</label>
                    <input type="text" name="product_name" required class="w-full bg-slate-900 border border-slate-600 rounded p-2 text-sm text-white">
                </div>
                <div>
                    <label class="text-xs text-gray-400">Category (No Numbers)</label>
                    <input type="text" name="category" required oninput="this.value = this.value.replace(/[0-9]/g, '')" class="w-full bg-slate-900 border border-slate-600 rounded p-2 text-sm text-white">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-gray-400">Quantity</label>
                        <input type="number" name="quantity" required min="0" class="w-full bg-slate-900 border border-slate-600 rounded p-2 text-sm text-white">
                    </div>
                    <div>
                        <label class="text-xs text-gray-400">Price (₱)</label>
                        <input type="number" step="0.01" name="price" required min="0" class="w-full bg-slate-900 border border-slate-600 rounded p-2 text-sm text-white">
                    </div>
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" onclick="closeModal('addModal')" class="bg-gray-600 text-white px-4 py-2 rounded text-sm">Cancel</button>
                    <button type="submit" class="bg-cyan-500 text-slate-950 font-bold px-4 py-2 rounded text-sm">Save Item</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 hidden z-50">
        <div class="bg-slate-800 border border-slate-700 w-full max-w-md rounded-xl p-6 space-y-4">
            <h3 class="text-lg font-bold text-white">Edit Product</h3>
            <form action="admin.php" method="POST" class="space-y-3">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" id="edit_id" name="product_id">
                <div>
                    <label class="text-xs text-gray-400">Product Name</label>
                    <input type="text" id="edit_name" name="product_name" required class="w-full bg-slate-900 border border-slate-600 rounded p-2 text-sm text-white">
                </div>
                <div>
                    <label class="text-xs text-gray-400">Category</label>
                    <input type="text" id="edit_category" name="category" required oninput="this.value = this.value.replace(/[0-9]/g, '')" class="w-full bg-slate-900 border border-slate-600 rounded p-2 text-sm text-white">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-gray-400">Quantity</label>
                        <input type="number" id="edit_quantity" name="quantity" required min="0" class="w-full bg-slate-900 border border-slate-600 rounded p-2 text-sm text-white">
                    </div>
                    <div>
                        <label class="text-xs text-gray-400">Price (₱)</label>
                        <input type="number" step="0.01" id="edit_price" name="price" required min="0" class="w-full bg-slate-900 border border-slate-600 rounded p-2 text-sm text-white">
                    </div>
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" onclick="closeModal('editModal')" class="bg-gray-600 text-white px-4 py-2 rounded text-sm">Cancel</button>
                    <button type="submit" class="bg-amber-500 text-slate-950 font-bold px-4 py-2 rounded text-sm">Update Item</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="bg-slate-800 border-t border-slate-700 py-4 text-center text-xs text-gray-400">
        <p>&copy; <?php echo date("Y"); ?> Inventory Manager. Admin Dashboard.</p>
    </footer>

    <script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
    function editProduct(product) {
        document.getElementById('edit_id').value = product.id;
        document.getElementById('edit_name').value = product.product_name;
        document.getElementById('edit_category').value = product.category;
        document.getElementById('edit_quantity').value = product.quantity;
        document.getElementById('edit_price').value = product.price;
        openModal('editModal');
    }
    </script>
</body>
</html>