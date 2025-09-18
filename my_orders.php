<?php
session_start();
require_once 'config.php';
require_once 'csrf_helper.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login_register.php');
    exit;
}

$userId = (int)$_SESSION['user_id'];

// Get user info
$userStmt = $conn->prepare("SELECT id, name, email FROM users WHERE id = ?");
$userStmt->bind_param('i', $userId);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();
$userStmt->close();

// Get user's orders with order items
$ordersStmt = $conn->prepare("
    SELECT o.id, o.total_amount, o.status, o.created_at, o.full_name, o.email, o.address, o.city, o.zip_code, o.contact_number, o.payment_method, o.shipping_method
    FROM orders o 
    WHERE o.user_id = ? 
    ORDER BY o.created_at DESC
");
$ordersStmt->bind_param('i', $userId);
$ordersStmt->execute();
$ordersResult = $ordersStmt->get_result();
$orders = $ordersResult->fetch_all(MYSQLI_ASSOC);
$ordersStmt->close();

// Get order items for each order
$orderItems = [];
foreach ($orders as $order) {
    $itemsStmt = $conn->prepare("
        SELECT oi.*, p.name as product_name, p.image as product_image, p.price as product_price
        FROM order_items oi 
        LEFT JOIN products p ON oi.product_id = p.id 
        WHERE oi.order_id = ?
    ");
    $itemsStmt->bind_param('i', $order['id']);
    $itemsStmt->execute();
    $itemsResult = $itemsStmt->get_result();
    $orderItems[$order['id']] = $itemsResult->fetch_all(MYSQLI_ASSOC);
    $itemsStmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Velvet Vogue</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="min-h-screen flex flex-col bg-gray-50">

    <!-- Header -->
    <header class="bg-white p-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold">Velvet Vogue</a>
            <nav class="hidden md:flex space-x-6 text-gray-600 font-medium items-center">
                <a href="index.php" class="hover:text-black transition-colors">Home</a>
                <a href="collection.php" class="hover:text-black transition-colors">Collection</a>
                <a href="about.php" class="hover:text-black transition-colors">About</a>
                <a href="contact.php" class="hover:text-black transition-colors">Contact</a>
            </nav>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <form id="nav-search-form">
                        <input id="nav-search" type="text" placeholder="Search" class="p-2 border rounded-lg text-sm w-48" />
                    </form>
                    <!-- Search Results Dropdown -->
                    <div id="search-results" class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 max-h-96 overflow-y-auto z-50 hidden">
                        <div id="search-results-content" class="p-2">
                            <!-- Search results will be populated here -->
                        </div>
                    </div>
                </div>
                <button class="text-gray-600 hover:text-black transition-colors text-black font-semibold" onclick="window.location.href='account.php'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </button>
                <button class="text-gray-600 hover:text-black transition-colors" onclick="window.location.href='wishlist.php'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </button>
                <button class="text-gray-600 hover:text-black transition-colors" onclick="window.location.href='cart.php'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.182 1.764.707 1.764H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <section class="flex-grow container mx-auto p-6">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="index.php" class="hover:text-black">Home</a></li>
                <li>/</li>
                <li><a href="account.php" class="hover:text-black">My Account</a></li>
                <li>/</li>
                <li class="text-black font-medium">My Orders</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
            <p class="text-gray-600 mt-2">Welcome back, <?php echo htmlspecialchars($user['name']); ?>! Here are all your orders.</p>
        </div>

        <?php if (empty($orders)): ?>
            <!-- No Orders State -->
            <div class="bg-white rounded-xl shadow-md p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">No Orders Yet</h2>
                <p class="text-gray-600 mb-8">You haven't placed any orders yet. Start shopping to see your orders here!</p>
                <a href="collection.php" class="btn-primary">Start Shopping</a>
            </div>
        <?php else: ?>
            <!-- Orders List -->
            <div class="space-y-6">
                <?php foreach ($orders as $order): ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <!-- Order Header -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Order #<?php echo $order['id']; ?></h3>
                                <p class="text-sm text-gray-500">Placed on <?php echo date('F j, Y \a\t g:i A', strtotime($order['created_at'])); ?></p>
                            </div>
                            <div class="mt-2 sm:mt-0 flex items-center space-x-4">
                                <span class="text-2xl font-bold text-gray-900">Rs <?php echo number_format($order['total_amount'], 2); ?></span>
                                <span class="px-3 py-1 text-sm font-medium rounded-full <?php 
                                    echo $order['status'] === 'Delivered' ? 'bg-green-100 text-green-800' : 
                                         ($order['status'] === 'Shipped' ? 'bg-blue-100 text-blue-800' : 
                                         ($order['status'] === 'Processing' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')); 
                                ?>">
                                    <?php echo htmlspecialchars($order['status']); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="p-6">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Order Items</h4>
                        <div class="space-y-4">
                            <?php foreach ($orderItems[$order['id']] as $item): ?>
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                    <?php if ($item['product_image']): ?>
                                        <img src="<?php echo htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-grow">
                                    <h5 class="font-medium text-gray-900"><?php echo htmlspecialchars($item['product_name'] ?? 'Unknown Product'); ?></h5>
                                    <p class="text-sm text-gray-500">Size: <?php echo htmlspecialchars($item['size'] ?? 'N/A'); ?> | Qty: <?php echo $item['quantity'] ?? 1; ?></p>
                                </div>
                                <div class="text-right">
                                    <?php 
                                    $itemPrice = $item['product_price'] ?? $item['price'] ?? 0;
                                    $itemQuantity = $item['quantity'] ?? 1;
                                    $totalPrice = $itemPrice * $itemQuantity;
                                    ?>
                                    <p class="font-semibold text-gray-900">Rs <?php echo number_format($totalPrice, 2); ?></p>
                                    <p class="text-sm text-gray-500">Rs <?php echo number_format($itemPrice, 2); ?> each</p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <h5 class="font-semibold text-gray-900 mb-2">Shipping Address</h5>
                                <p class="text-gray-600">
                                    <?php echo htmlspecialchars($order['full_name'] ?? 'N/A'); ?><br>
                                    <?php echo htmlspecialchars($order['address'] ?? 'N/A'); ?><br>
                                    <?php echo htmlspecialchars($order['city'] ?? 'N/A'); ?>, <?php echo htmlspecialchars($order['zip_code'] ?? 'N/A'); ?><br>
                                    Contact: <?php echo htmlspecialchars($order['contact_number'] ?? 'N/A'); ?>
                                </p>
                            </div>
                            <div>
                                <h5 class="font-semibold text-gray-900 mb-2">Payment & Shipping</h5>
                                <p class="text-gray-600">
                                    Payment: <?php echo htmlspecialchars($order['payment_method'] ?? 'N/A'); ?><br>
                                    Shipping: <?php echo htmlspecialchars($order['shipping_method'] ?? 'N/A'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
    
    <!-- Footer -->
    <footer class="footer p-8 mt-auto">
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4 text-white">Velvet Vogue</h3>
                <p class="text-sm leading-relaxed">
                    Velvet Vogue is your ultimate destination for high-quality, expressive fashion. We believe in style that tells a story, offering curated collections for the modern individual who values both trend and timelessness. Our mission is to empower you to look and feel your best, every day.
                </p>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4 text-white">Company</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="about.php" class="hover:underline">About Us</a></li>
                    <li><a href="#" class="hover:underline">Careers</a></li>
                    <li><a href="#" class="hover:underline">Store Locator</a></li>
                    <li><a href="#" class="hover:underline">Our Blog</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4 text-white">Other</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:underline">Returns & Exchanges</a></li>
                    <li><a href="#" class="hover:underline">Shipping & Delivery</a></li>
                    <li><a href="#" class="hover:underline">FAQ</a></li>
                    <li><a href="#" class="hover:underline">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="text-center mt-8 text-sm text-gray-500">
            &copy; 2025 Velvet Vogue. All rights reserved.
        </div>
    </footer>

<script src="assets/js/script.js"></script>
<script>
// Nav search functionality
document.addEventListener('DOMContentLoaded', function() {
    const navSearch = document.getElementById('nav-search');
    const navForm = document.getElementById('nav-search-form');
    const searchResults = document.getElementById('search-results');
    const searchResultsContent = document.getElementById('search-results-content');
    
    if (navForm) navForm.addEventListener('submit', e => e.preventDefault());
    
    if (navSearch) {
        navSearch.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            
            if (query.length < 2) {
                searchResults.classList.add('hidden');
                return;
            }
            
            // Redirect to collection for product search
            if (query.length >= 2) {
                window.location.href = `collection.php?search=${encodeURIComponent(query)}`;
            }
        });
        
        // Hide search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!navSearch.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });
    }
});
</script>

</body>
</html>
