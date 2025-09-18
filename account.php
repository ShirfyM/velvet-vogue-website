<?php
session_start();
require_once 'config.php';
require_once 'csrf_helper.php';

// Load current user's info and orders if logged in
$currentUser = null;
$myOrders = [];
if (isset($_SESSION['user_id'])) {
    $uid = (int)$_SESSION['user_id'];
    $resUser = $conn->query("SELECT id, name, email FROM users WHERE id = $uid LIMIT 1");
    if ($resUser && $resUser->num_rows) {
        $currentUser = $resUser->fetch_assoc();
    }
    $resOrders = $conn->query("SELECT id, total_amount, status, created_at FROM orders WHERE user_id = $uid ORDER BY created_at DESC LIMIT 10");
    if ($resOrders) {
        while ($row = $resOrders->fetch_assoc()) { $myOrders[] = $row; }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Velvet Vogue</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="min-h-screen flex flex-col">

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
                <button class="text-gray-600 hover:text-black transition-colors text-black font-semibold" onclick="window.location.href='<?php echo isset($_SESSION['user_id']) ? "account.php" : "login_register.php"; ?>'">
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
    <section class="flex-grow container mx-auto p-8">
        <?php if ($currentUser): ?>
        <h1 class="text-4xl font-bold mb-6">My Account</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-1 bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-2xl font-bold mb-4">Profile</h2>
                <p class="mb-2"><span class="font-semibold">Name:</span> <?php echo htmlspecialchars($currentUser['name']); ?></p>
                <p class="mb-2"><span class="font-semibold">Email:</span> <?php echo htmlspecialchars($currentUser['email']); ?></p>
                <div class="mt-8 space-y-2">
                    <a href="logout.php" class="block w-full btn-primary text-center rounded-full">Logout</a>
                </div>
            </div>
            <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-2xl font-bold mb-4">Recent Orders</h2>
                <?php if (!empty($myOrders)): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($myOrders as $o): ?>
                            <tr>
                                <td class="px-4 py-3">#<?php echo $o['id']; ?></td>
                                <td class="px-4 py-3">Rs <?php echo number_format($o['total_amount'], 2); ?></td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full <?php echo ($o['status'] === 'Delivered') ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                        <?php echo htmlspecialchars($o['status']); ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3"><?php echo date('d M Y', strtotime($o['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <p>You have no orders yet.</p>
                    <a href="collection.php" class="btn-primary inline-block mt-4">Shop Now</a>
                <?php endif; ?>
                <?php if (!empty($myOrders)): ?>
                    <div class="mt-4">
                        <a href="my_orders.php" class="btn-secondary inline-block">View All Orders</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php else: ?>
            <div class="bg-white p-8 rounded-xl shadow-md text-center">
                <h2 class="text-2xl font-bold mb-2">You're not signed in</h2>
                <p class="text-gray-600 mb-6">Please sign in to view your account.</p>
                <a href="login_register.php" class="btn-primary">Sign In</a>
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
            
            // For account page, we'll redirect to collection for product search
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
