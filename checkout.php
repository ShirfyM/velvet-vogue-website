<?php
session_start();
require_once 'config.php';
require_once 'csrf_helper.php';

// Cart session helper
$sessionCart = $_SESSION['cart'] ?? [];

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login_register.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Velvet Vogue</title>
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
                <button class="text-gray-600 hover:text-black transition-colors" onclick="window.location.href='account.php'">
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
        <h1 class="text-4xl font-bold text-center mb-2">PAYMENT DETAILS</h1>
        <p class="text-center text-gray-600 mb-8">Fill in you payment details and complete the order</p>
        <div class="flex flex-col md:flex-row space-y-8 md:space-y-0 md:space-x-8">
            <div class="md:w-2/3 bg-white p-8 rounded-xl shadow-md">
                <form action="place_order.php" method="post">
                    <?= getCSRFTokenField(); ?>
                    <div class="mb-4">
                        <label for="email_address" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" id="email_address" name="email_address" required class="mt-1 p-3 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div class="mb-4">
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required class="mt-1 p-3 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input type="text" id="address" name="address" required class="mt-1 p-3 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div class="mb-4">
                        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                        <input type="text" id="city" name="city" required class="mt-1 p-3 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div class="mb-6">
                        <label for="zip_code" class="block text-sm font-medium text-gray-700">Zip Code</label>
                        <input type="text" id="zip_code" name="zip_code" required class="mt-1 p-3 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div class="mb-4">
                        <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                        <input type="tel" id="contact_number" name="contact_number" required class="mt-1 p-3 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black" placeholder="07X XXX XXXX">
                    </div>
                    <div class="mb-4">
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <select id="payment_method" name="payment_method" required class="mt-1 p-3 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                            <option value="" disabled selected>Select a method</option>
                            <option value="COD">Cash on Delivery</option>
                            <option value="Card">Card</option>
                            <option value="BankTransfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label for="shipping_method" class="block text-sm font-medium text-gray-700">Shipping Method</label>
                        <select id="shipping_method" name="shipping_method" required class="mt-1 p-3 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                            <option value="" disabled selected>Select shipping</option>
                            <option value="Standard">Standard (3-5 days)</option>
                            <option value="Express">Express (1-2 days)</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full btn-primary">Place Order</button>
                </form>
            </div>
            <div class="md:w-1/3 bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-3xl font-bold mb-6">CART TOTALS</h2>
                <div class="space-y-4 text-lg">
                    <div class="flex justify-between">
                        <span>Price</span>
                        <span>
                            <?php 
                            $subtotal_pay = 0.0; 
                            foreach ($sessionCart as $ci) { 
                                $subtotal_pay += ((float)($ci['price'] ?? 0)) * ((int)($ci['qty'] ?? 1)); 
                            }
                            echo number_format($subtotal_pay, 2); 
                            ?>
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping fees</span>
                        <?php 
                            $shipping_pay = 400.00; 
                            // If Express selected, JS cannot read; fee finalized in place_order.php. Show standard here.
                            echo '<span>' . number_format($shipping_pay, 2) . '</span>'; 
                        ?>
                    </div>
                    <div class="flex justify-between font-bold text-xl border-t border-gray-200 pt-4 mt-4">
                        <span>Grand Total</span>
                        <span>
                            <?php echo number_format($subtotal_pay + $shipping_pay, 2); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
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
            
            // For checkout page, we'll redirect to collection for product search
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
