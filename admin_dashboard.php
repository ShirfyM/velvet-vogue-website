<?php
session_start();
require_once 'config.php';
require_once 'csrf_helper.php';

requireCSRFToken();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

function secureFileUpload($file, $uploadDir = 'uploads/products/') {
    $allowedTypes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg', 
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp'
    ];
    
    $maxSize = 2 * 1024 * 1024;
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'File upload error'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'error' => 'File too large. Maximum size is 2MB'];
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!array_key_exists($extension, $allowedTypes)) {
        return ['success' => false, 'error' => 'Invalid file type. Only JPG, PNG, GIF, and WebP are allowed'];
    }
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if ($mimeType !== $allowedTypes[$extension]) {
        return ['success' => false, 'error' => 'File type mismatch. Please upload a valid image'];
    }
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $imageName = uniqid('img_', true) . '.' . $extension;
    $imagePath = $uploadDir . $imageName;
    
    if (move_uploaded_file($file['tmp_name'], $imagePath)) {
        return ['success' => true, 'path' => $imagePath];
    } else {
        return ['success' => false, 'error' => 'Failed to save file'];
    }
}

if ($_POST && isset($_POST['action']) && $_POST['action'] === 'add_product') {
    $name = trim($_POST['name'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $gender = trim($_POST['gender'] ?? 'Unisex');
    
    if (empty($name) || $price <= 0 || empty($category) || empty($gender)) {
        $error_message = "Please provide valid product details";
    } else {
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $uploadResult = secureFileUpload($_FILES['image']);
            if ($uploadResult['success']) {
                $image = $uploadResult['path'];
            } else {
                $error_message = $uploadResult['error'];
            }
        }
        
        if (!isset($error_message)) {
            $stmt = $conn->prepare("INSERT INTO products (name, price, description, category, gender, image) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sdssss", $name, $price, $description, $category, $gender, $image);
    
    if ($stmt->execute()) {
        $success_message = "Product added successfully!";
    } else {
                $error_message = "Error adding product: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

if ($_POST && isset($_POST['action']) && $_POST['action'] === 'delete_product') {
    $product_id = (int)($_POST['product_id'] ?? 0);
    
    if ($product_id > 0) {
        $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
    if ($result && $row = $result->fetch_assoc()) {
        if (file_exists($row['image'])) {
            unlink($row['image']);
        }
    }
        $stmt->close();
    
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        if ($stmt->execute()) {
        $success_message = "Product deleted successfully!";
        } else {
            $error_message = "Error deleting product: " . $stmt->error;
        }
        $stmt->close();
    }
}

if ($_POST && isset($_POST['action']) && $_POST['action'] === 'update_product') {
    $product_id = (int)($_POST['product_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $gender = trim($_POST['gender'] ?? 'Unisex');

    if ($product_id > 0 && $name !== '' && $price > 0 && $category !== '' && $gender !== '') {
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $uploadResult = secureFileUpload($_FILES['image']);
            if ($uploadResult['success']) {
                $imagePath = $uploadResult['path'];
            } else {
                $error_message = $uploadResult['error'];
            }
        }

        if ($imagePath) {
            $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ?, category = ?, gender = ?, image = ? WHERE id = ?");
            $stmt->bind_param("sdssssi", $name, $price, $description, $category, $gender, $imagePath, $product_id);
        } else {
            $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ?, category = ?, gender = ? WHERE id = ?");
            $stmt->bind_param("sdsssi", $name, $price, $description, $category, $gender, $product_id);
        }
        if ($stmt->execute()) {
            $success_message = "Product updated successfully!";
        } else {
            $error_message = "Error updating product: " . $conn->error;
        }
        $stmt->close();
    } else {
        $error_message = "Please provide valid product details.";
    }
}

if ($_POST && isset($_POST['action']) && $_POST['action'] === 'update_user') {
    $user_id = (int)($_POST['user_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? 'customer');

    if ($user_id > 0 && $name !== '' && $email !== '') {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $role, $user_id);
        if ($stmt->execute()) {
            $success_message = "User updated successfully!";
        } else {
            $error_message = "Error updating user: " . $conn->error;
        }
        $stmt->close();
    } else {
        $error_message = "Please provide valid user details.";
    }
}

if ($_POST && isset($_POST['action']) && $_POST['action'] === 'delete_user') {
    $user_id = (int)($_POST['user_id'] ?? 0);
    if ($user_id > 0) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $success_message = "User deleted successfully!";
        } else {
            $error_message = "Error deleting user: " . $stmt->error;
        }
        $stmt->close();
    }
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

try {
    $stats = [];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM products");
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['products'] = $result->fetch_assoc()['count'] ?? 0;
    $stmt->close();
    
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders");
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['orders'] = $result->fetch_assoc()['count'] ?? 0;
    $stmt->close();
    
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM message");
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['messages'] = $result->fetch_assoc()['count'] ?? 0;
    $stmt->close();
    
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE status = ?");
    $status = 'Pending';
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['pending_orders'] = $result->fetch_assoc()['count'] ?? 0;
    $stmt->close();
    
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users");
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['users'] = $result->fetch_assoc()['count'] ?? 0;
    $stmt->close();
    
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE status = ?");
    $status = 'Delivered';
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['completed_orders'] = $result->fetch_assoc()['count'] ?? 0;
    $stmt->close();
    
} catch (Exception $e) {
    error_log("Error fetching statistics: " . $e->getMessage());
    $stats = [
        'products' => 0,
        'orders' => 0,
        'messages' => 0,
        'pending_orders' => 0,
        'users' => 0,
        'completed_orders' => 0
    ];
}

if ($page === 'orders') {
    $stmt = $conn->prepare("SELECT * FROM orders ORDER BY created_at DESC");
    $stmt->execute();
    $orders = $stmt->get_result();
    $stmt->close();
} elseif ($page === 'products') {
    $stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC");
    $stmt->execute();
    $products = $stmt->get_result();
    $stmt->close();
} elseif ($page === 'messages') {
    $stmt = $conn->prepare("SELECT * FROM message ORDER BY id DESC");
    $stmt->execute();
    $messages = $stmt->get_result();
    $stmt->close();
} elseif ($page === 'users') {
    $stmt = $conn->prepare("SELECT * FROM users ORDER BY id DESC");
    $stmt->execute();
    $users = $stmt->get_result();
    $stmt->close();
}

// Handle order status update
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'update_order_status') {
    $order_id = (int)($_POST['order_id'] ?? 0);
    $status = trim($_POST['status'] ?? 'Pending');
    if ($order_id > 0) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        if ($stmt->execute()) {
            $success_message = "Order status updated!";
        } else {
            $error_message = "Error updating order: " . $conn->error;
        }
        $stmt->close();
    }
}

// Handle order deletion
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'delete_order') {
    $order_id = (int)($_POST['order_id'] ?? 0);
    if ($order_id > 0) {
        // Delete items first due to FK
        $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();
        
        // Delete the order
        $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        if ($stmt->execute()) {
            $success_message = "Order deleted successfully!";
        } else {
            $error_message = "Error deleting order: " . $stmt->error;
        }
        $stmt->close();
    }
}

if ($_POST && isset($_POST['action']) && $_POST['action'] === 'delete_message') {
    $message_id = (int)($_POST['message_id'] ?? 0);
    if ($message_id > 0) {
        $stmt = $conn->prepare("DELETE FROM message WHERE id = ?");
        $stmt->bind_param("i", $message_id);
        if ($stmt->execute()) {
            $success_message = "Message deleted successfully!";
        } else {
            $error_message = "Error deleting message: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Velvet Vogue</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-100">
    <div class="md:hidden fixed top-4 left-4 z-50">
        <button id="menuToggle" class="p-2 bg-white rounded shadow-md">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div id="addProductModal" class="modal">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Add New Product</h3>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <?= getCSRFTokenField(); ?>
                <input type="hidden" name="action" value="add_product">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (Rs)</label>
                    <input type="number" step="0.01" name="price" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Category</option>
                        <option value="shirts">Shirts</option>
                        <option value="t-shirts">T-Shirts</option>
                        <option value="jeans">Jeans</option>
                        <option value="accessories">Accessories</option>
                        <option value="shoes">Shoes</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                    <select name="gender" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Gender</option>
                        <option value="Men">Men</option>
                        <option value="Women">Women</option>
                        <option value="Unisex">Unisex</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                    <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="cancelBtn" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-black text-white rounded-md hover:bg-gray-800">Add Product</button>
                </div>
            </form>
        </div>
    </div>

    <div class="flex min-h-screen">
        <aside id="sidebar" class="sidebar fixed h-full bg-white shadow-md">
            <div class="p-4 border-b">
                <h1 class="text-xl font-bold flex items-center">
                    <i class="fas fa-crown mr-2 text-purple-600"></i>
                    Velvet Vogue Admin
                </h1>
                <p class="text-sm text-gray-500">Welcome, <?php echo $_SESSION['name'] ?? 'Admin'; ?></p>
            </div>
            <nav class="mt-4">
                <a href="?page=dashboard" class="nav-item block p-4 hover:bg-gray-100 flex items-center <?php echo $page === 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                </a>
                <a href="?page=products" class="nav-item block p-4 hover:bg-gray-100 flex items-center <?php echo $page === 'products' ? 'active' : ''; ?>">
                    <i class="fas fa-tshirt mr-3"></i> Products
                </a>
                <a href="?page=orders" class="nav-item block p-4 hover:bg-gray-100 flex items-center <?php echo $page === 'orders' ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-bag mr-3"></i> Orders
                </a>
                <a href="?page=users" class="nav-item block p-4 hover:bg-gray-100 flex items-center <?php echo $page === 'users' ? 'active' : ''; ?>">
                    <i class="fas fa-users mr-3"></i> Users
                </a>
                <a href="?page=messages" class="nav-item block p-4 hover:bg-gray-100 flex items-center <?php echo $page === 'messages' ? 'active' : ''; ?>">
                    <i class="fas fa-envelope mr-3"></i> Messages
                </a>
                <a href="logout.php" class="block p-4 hover:bg-red-100 text-red-600 flex items-center mt-4">
                    <i class="fas fa-sign-out-alt mr-3"></i> Logout
                </a>
            </nav>
        </aside>

        <main class="main-content flex-1 p-4 md:p-8">
            <?php if (isset($success_message)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div id="dashboard" class="page-content <?php echo $page === 'dashboard' ? 'active' : ''; ?>">
                <h2 class="text-2xl font-bold mb-6">Dashboard Overview</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm">Total Products</h3>
                                <p class="text-2xl font-bold"><?php echo $stats['products']; ?></p>
                            </div>
                            <i class="fas fa-tshirt text-blue-500 text-2xl"></i>
                        </div>
                    </div>
                    <div class="stat-card bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm">Total Orders</h3>
                                <p class="text-2xl font-bold"><?php echo $stats['orders']; ?></p>
                            </div>
                            <i class="fas fa-shopping-bag text-green-500 text-2xl"></i>
                        </div>
                    </div>
                    <div class="stat-card bg-white p-6 rounded-lg shadow-md border-l-4 border-yellow-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm">Pending Orders</h3>
                                <p class="text-2xl font-bold"><?php echo $stats['pending_orders']; ?></p>
                            </div>
                            <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                        </div>
                    </div>
                    <div class="stat-card bg-white p-6 rounded-lg shadow-md border-l-4 border-purple-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm">Messages</h3>
                                <p class="text-2xl font-bold"><?php echo $stats['messages']; ?></p>
                            </div>
                            <i class="fas fa-envelope text-purple-500 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Recent Orders</h2>
                            <a href="?page=orders" class="text-sm text-blue-500 hover:underline">View all</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php
                                    $stmt = $conn->prepare("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
                                    $stmt->execute();
                                    $recent_orders = $stmt->get_result();
                                    while($order = $recent_orders->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td class="px-4 py-3">#<?php echo $order['id']; ?></td>
                                        <td class="px-4 py-3"><?php echo htmlspecialchars($order['full_name']); ?></td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs rounded-full <?php echo ($order['status'] === 'Delivered') ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                                <?php echo htmlspecialchars($order['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Recent Messages</h2>
                            <a href="?page=messages" class="text-sm text-blue-500 hover:underline">View all</a>
                        </div>
                        <div class="space-y-4">
                            <?php
                            $stmt = $conn->prepare("SELECT * FROM message ORDER BY id DESC LIMIT 3");
                            $stmt->execute();
                            $recent_messages = $stmt->get_result();
                            while($message = $recent_messages->fetch_assoc()):
                            ?>
                            <div class="border-l-4 border-blue-500 pl-4 py-2">
                                <h3 class="font-semibold"><?php echo htmlspecialchars($message['name']); ?></h3>
                                <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($message['email']); ?></p>
                                <p class="mt-1 text-sm truncate"><?php echo htmlspecialchars($message['message']); ?></p>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div id="products" class="page-content <?php echo $page === 'products' ? 'active' : ''; ?>">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Product Management</h2>
                    <button id="addProductBtn" class="bg-black text-white px-4 py-2 rounded-lg flex items-center hover:bg-gray-800">
                        <i class="fas fa-plus mr-2"></i> Add Product
                    </button>
                </div>
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if(isset($products) && $products->num_rows > 0): ?>
                                <?php while($product = $products->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4">
                                        <?php if ($product['image']): ?>
                                            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" class="w-12 h-12 object-cover rounded">
                                        <?php else: ?>
                                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4"><?php echo $product['id']; ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td class="px-6 py-4">Rs <?php echo number_format($product['price']); ?></td>
                                    <td class="px-6 py-4"><?php echo ucfirst($product['category']); ?></td>
                                    <td class="px-6 py-4">
                                        <a href="#" class="text-blue-500 hover:text-blue-700 mr-2 js-edit-product"
                                           data-id="<?php echo $product['id']; ?>"
                                           data-name="<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>"
                                           data-price="<?php echo (float)$product['price']; ?>"
                                           data-category="<?php echo htmlspecialchars($product['category'], ENT_QUOTES); ?>"
                                           data-gender="<?php echo htmlspecialchars($product['gender'] ?? 'Unisex', ENT_QUOTES); ?>"
                                           data-description="<?php echo htmlspecialchars($product['description'] ?? '', ENT_QUOTES); ?>">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            <?= getCSRFTokenField(); ?>
                                            <input type="hidden" name="action" value="delete_product">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center">No products found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="orders" class="page-content <?php echo $page === 'orders' ? 'active' : ''; ?>">
                <h2 class="text-2xl font-bold mb-6">Order Management</h2>
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if(isset($orders) && $orders->num_rows > 0): ?>
                                <?php while($order = $orders->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4">#<?php echo $order['id']; ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($order['full_name']); ?></td>
                                    <td class="px-6 py-4">Rs <?php echo number_format($order['total_amount']); ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded-full <?php echo ($order['status'] === 'Delivered') ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                            <?php echo htmlspecialchars($order['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4"><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                                    <td class="px-6 py-4">
                                        <button class="text-blue-500 hover:text-blue-700 mr-2" onclick="viewOrderDetails(<?php echo $order['id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-green-500 hover:text-green-700 mr-2" onclick="openOrderEditModal(<?php echo $order['id']; ?>, '<?php echo htmlspecialchars($order['status'], ENT_QUOTES); ?>')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" class="inline" onsubmit="return confirm('Delete this order?');">
                                            <?= getCSRFTokenField(); ?>
                                            <input type="hidden" name="action" value="delete_order">
                                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                            <button type="submit" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center">No orders found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="messages" class="page-content <?php echo $page === 'messages' ? 'active' : ''; ?>">
                <h2 class="text-2xl font-bold mb-6">Customer Messages</h2>
                
                <div class="space-y-4">
                    <?php if(isset($messages) && $messages->num_rows > 0): ?>
                        <?php while($message = $messages->fetch_assoc()): ?>
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($message['name']); ?></h3>
                                    <p class="text-gray-600"><?php echo htmlspecialchars($message['email']); ?></p>
                                    <p class="text-sm text-gray-500"><?php echo date('d M Y, H:i', strtotime($message['created_at'])); ?></p>
                                </div>
                                <form method="POST" onsubmit="return confirm('Delete this message?');">
                                    <?= getCSRFTokenField(); ?>
                                    <input type="hidden" name="action" value="delete_message">
                                    <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                                </form>
                            </div>
                            <p class="text-gray-700"><?php echo htmlspecialchars($message['message']); ?></p>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="bg-white rounded-lg shadow-md p-6 text-center">
                            <p class="text-gray-500">No messages found</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div id="users" class="page-content <?php echo $page === 'users' ? 'active' : ''; ?>">
                <h2 class="text-2xl font-bold mb-6">User Management</h2>
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if(isset($users) && $users->num_rows > 0): ?>
                                <?php while($user = $users->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4"><?php echo $user['id']; ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="px-6 py-4">
                                        <?php $role = strtolower($user['role'] ?? 'customer'); ?>
                                        <span class="px-2 py-1 text-xs rounded-full <?php echo $role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'; ?>">
                                            <?php echo ucfirst($role); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button class="text-blue-500 hover:text-blue-700 mr-2" onclick="openUserEditModal(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['name'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($user['role'] ?? 'customer', ENT_QUOTES); ?>')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" class="inline" onsubmit="return confirm('Delete this user?');">
                                            <?= getCSRFTokenField(); ?>
                                            <input type="hidden" name="action" value="delete_user">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center">No users found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        function openProductEditModal(id, name, price, category, gender, description) {
            const modal = document.createElement('div');
            modal.className = 'modal show';
            modal.innerHTML = `
            <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Edit Product</h3>
                    <button id="productEditClose" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
                </div>
                <form method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">
                    <input type="hidden" name="action" value="update_product">
                    <input type="hidden" name="product_id" value="${id}">
                    <div>
                        <label class="block text-sm font-medium mb-1">Name</label>
                        <input type="text" name="name" required class="w-full px-3 py-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Price</label>
                        <input type="number" step="0.01" name="price" required class="w-full px-3 py-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Category</label>
                        <input type="text" name="category" required class="w-full px-3 py-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Gender</label>
                        <select name="gender" required class="w-full px-3 py-2 border rounded">
                            <option value="Men">Men</option>
                            <option value="Women">Women</option>
                            <option value="Unisex">Unisex</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Replace Image</label>
                        <input type="file" name="image" accept="image/*" class="w-full">
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" id="productEditCancel" class="px-4 py-2 text-gray-600 border rounded hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">Save</button>
                    </div>
                </form>
            </div>`;
            document.body.appendChild(modal);
            function close(){ modal.classList.remove('show'); modal.remove(); }
            modal.addEventListener('click', e=>{ if(e.target===modal) close(); });
            modal.querySelector('#productEditClose').addEventListener('click', close);
            modal.querySelector('#productEditCancel').addEventListener('click', close);
            modal.querySelector('input[name="name"]').value = name;
            modal.querySelector('input[name="price"]').value = price;
            modal.querySelector('input[name="category"]').value = category;
            modal.querySelector('select[name="gender"]').value = gender || 'Unisex';
            modal.querySelector('textarea[name="description"]').value = description || '';
        }

        document.addEventListener('click', function(e){
            const link = e.target.closest('.js-edit-product');
            if (!link) return;
            e.preventDefault();
            openProductEditModal(
                parseInt(link.getAttribute('data-id')),
                link.getAttribute('data-name') || '',
                link.getAttribute('data-price') || '',
                link.getAttribute('data-category') || '',
                link.getAttribute('data-gender') || 'Unisex',
                link.getAttribute('data-description') || ''
            );
        });

        function openOrderEditModal(id, status) {
            const modal = document.createElement('div');
            modal.className = 'modal show';
            modal.innerHTML = `
            <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Update Order Status</h3>
                    <button id="orderEditClose" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
                </div>
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">
                    <input type="hidden" name="action" value="update_order_status">
                    <input type="hidden" name="order_id" value="${id}">
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 border rounded">
                            ${['Pending','Processing','Shipped','Delivered','Cancelled'].map(s=>`<option value="${s}" ${s===status?'selected':''}>${s}</option>`).join('')}
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" id="orderEditCancel" class="px-4 py-2 text-gray-600 border rounded hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">Save</button>
                    </div>
                </form>
            </div>`;
            document.body.appendChild(modal);
            function close(){ modal.classList.remove('show'); modal.remove(); }
            modal.addEventListener('click', e=>{ if(e.target===modal) close(); });
            modal.querySelector('#orderEditClose').addEventListener('click', close);
            modal.querySelector('#orderEditCancel').addEventListener('click', close);
        }

        function viewOrderDetails(id) {
            fetch('admin_order_details.php?id=' + id)
              .then(r=>r.text())
              .then(html=>{
                const modal = document.createElement('div');
                modal.className = 'modal show';
                modal.innerHTML = `<div class="bg-white rounded-lg p-6 w-full max-w-2xl mx-4">${html}<div class=\"mt-4\"><button id=\"orderViewClose\" class=\"px-4 py-2 bg-black text-white rounded\">Close</button></div></div>`;
                document.body.appendChild(modal);
                function close(){ modal.classList.remove('show'); modal.remove(); }
                modal.addEventListener('click', e=>{ if(e.target===modal) close(); });
                modal.querySelector('#orderViewClose').addEventListener('click', close);
              });
        }
        function openUserEditModal(id, name, email, role) {
            const modal = document.createElement('div');
            modal.className = 'modal show';
            modal.innerHTML = `
            <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Edit User</h3>
                    <button id="userEditClose" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
                </div>
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">
                    <input type="hidden" name="action" value="update_user">
                    <input type="hidden" name="user_id" value="${id}">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" value="${name}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="${email}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="customer" ${role==='customer'?'selected':''}>Customer</option>
                            <option value="admin" ${role==='admin'?'selected':''}>Admin</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" id="userEditCancel" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-black text-white rounded-md hover:bg-gray-800">Save</button>
                    </div>
                </form>
            </div>`;
            document.body.appendChild(modal);

            function close() { modal.classList.remove('show'); modal.remove(); }
            modal.addEventListener('click', (e)=>{ if(e.target===modal) close(); });
            modal.querySelector('#userEditClose').addEventListener('click', close);
            modal.querySelector('#userEditCancel').addEventListener('click', close);
        }
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });

        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.getElementById('menuToggle');
            
            if (window.innerWidth < 768 && 
                !sidebar.contains(event.target) && 
                !menuToggle.contains(event.target) &&
                sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
            }
        });

        const addProductBtn = document.getElementById('addProductBtn');
        const addProductModal = document.getElementById('addProductModal');
        const closeModal = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');

        addProductBtn.addEventListener('click', function() {
            addProductModal.classList.add('show');
        });

        closeModal.addEventListener('click', function() {
            addProductModal.classList.remove('show');
        });

        cancelBtn.addEventListener('click', function() {
            addProductModal.classList.remove('show');
        });

        addProductModal.addEventListener('click', function(e) {
            if (e.target === addProductModal) {
                addProductModal.classList.remove('show');
            }
        });

        document.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('click', function(e) {
                if(this.getAttribute('href').includes('logout.php') || this.getAttribute('href').includes('?')) return;
                
                e.preventDefault();
                document.querySelectorAll('nav a').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                const pageId = this.getAttribute('href').substring(1);
                document.querySelectorAll('.page-content').forEach(page => {
                    page.classList.remove('active');
                });
                document.getElementById(pageId).classList.add('active');
            });
        });
    </script>
</body>
</html>