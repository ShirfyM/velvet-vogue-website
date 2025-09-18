<?php
session_start();
require_once 'config.php';
require_once 'csrf_helper.php';

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'signup' => $_SESSION['signup_error'] ?? ''
];

$activeForm = $_SESSION['active_form'] ?? 'login';

unset($_SESSION['login_error'], $_SESSION['signup_error'], $_SESSION['active_form']);

function showError($error) {
    return !empty($error) ? "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4'>{$error}</div>" : '';
}

function isActive($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}

function getProductsFromDatabase() {
    global $conn;
    
    $products = [];
    
    $result = $conn->query("SELECT * FROM products ORDER BY RAND()");
    
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = [
                'id' => $row['id'],
                'image' => $row['image'],
                'name' => $row['name'],
                'price' => 'Rs ' . number_format($row['price'], 2),
                'unit_price' => (float)$row['price'],
                'description' => $row['description'],
                'category' => $row['category'],
                'gender' => $row['gender'] ?? 'Unisex'
            ];
        }
    }
    
    return $products;
}

$products = getProductsFromDatabase();

$randomProducts = array_values(array_filter($products, function($product) {
    $category = isset($product['category']) ? strtolower(trim($product['category'])) : '';
    return $category === 'shirts' || $category === 'shirt';
}));

function getBestSellingProducts($conn) {
    $stmt = $conn->prepare("
        SELECT p.*, COUNT(oi.product_id) as order_count 
        FROM products p 
        LEFT JOIN order_items oi ON p.id = oi.product_id 
        GROUP BY p.id 
        ORDER BY order_count DESC, p.id ASC 
        LIMIT 5
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    $bestSellers = [];
    while ($row = $result->fetch_assoc()) {
        $bestSellers[] = $row;
    }
    $stmt->close();
    return $bestSellers;
}

$bestSellingProducts = getBestSellingProducts($conn);
$sessionCart = $_SESSION['cart'] ?? [];
$sessionWishlist = $_SESSION['wishlist'] ?? [];

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
    <title>Velvet Vogue</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="min-h-screen flex flex-col">
    <!-- Skip link for keyboard navigation -->
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <header class="bg-white p-4 shadow-md sticky top-0 z-50" role="banner">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold" aria-label="Velvet Vogue - Home">Velvet Vogue</a>
            <nav class="hidden md:flex space-x-6 text-gray-600 font-medium items-center" role="navigation" aria-label="Main navigation">
                <a href="index.php" class="hover:text-black transition-colors text-black font-semibold" aria-current="page">Home</a>
                <a href="collection.php" class="hover:text-black transition-colors">Collection</a>
                <a href="about.php" class="hover:text-black transition-colors">About</a>
                <a href="contact.php" class="hover:text-black transition-colors">Contact</a>
            </nav>
            <div class="flex items-center space-x-4" role="toolbar" aria-label="User actions">
                <div class="relative">
                    <form id="nav-search-form" role="search" aria-label="Search products">
                        <label for="nav-search" class="sr-only">Search products</label>
                        <input id="nav-search" type="text" placeholder="Search" class="p-2 border rounded-lg text-sm w-48" aria-describedby="search-results" />
                    </form>
                    <div id="search-results" class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 max-h-96 overflow-y-auto z-50 hidden" role="listbox" aria-label="Search results">
                        <div id="search-results-content" class="p-2">
                        </div>
                    </div>
                </div>
                <button class="text-gray-600 hover:text-black transition-colors" onclick="window.location.href='<?php echo isset($_SESSION['user_id']) ? "account.php" : "login_register.php"; ?>'" aria-label="<?php echo isset($_SESSION['user_id']) ? 'View account' : 'Login or register'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </button>
                <button class="text-gray-600 hover:text-black transition-colors" onclick="window.location.href='wishlist.php'" aria-label="View wishlist">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </button>
                <button class="text-gray-600 hover:text-black transition-colors" onclick="window.location.href='cart.php'" aria-label="View shopping cart">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.182 1.764.707 1.764H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <section class="flex-grow container mx-auto p-8">

        <main id="main-content" class="page-content active" role="main">
            <section class="mb-12" aria-labelledby="hero-heading">
                <div class="flex flex-col md:flex-row items-center justify-between bg-gray-200 rounded-xl p-8">
                    <div class="w-full md:w-1/2 text-center md:text-left mb-8 md:mb-0">
                        <h1 id="hero-heading" class="text-4xl md:text-5xl font-bold mb-4">Latest Arrivals</h1>
                        <p class="text-xl md:text-2xl text-gray-700 mb-6">Stay stylish with our trendiest arrivals</p>
                        <a href="collection.php" class="btn-primary inline-block" aria-label="Shop our latest arrivals">Shop Now</a>
                    </div>
                    <div class="w-full md:w-1/2">
                        <div class="w-full h-[300px] md:h-[400px] overflow-hidden rounded-lg">
                            <img src="assets/images/DSC02097.webp" alt="Fashion model showcasing latest clothing arrivals" 
                                class="w-full h-full object-cover object-center transform hover:scale-105 transition-transform duration-500">
                        </div>
                    </div>
                </div>
            </section>

            <section class="mb-12" aria-labelledby="collections-heading">
                <h2 id="collections-heading" class="text-3xl font-bold text-center mb-8">Latest Collections</h2>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-6" role="list" aria-label="Latest product collections">
                    <?php foreach ($randomProducts as $product): ?>
                    <article class="bg-white rounded-xl overflow-hidden product-card p-4" role="listitem"
                         data-id="<?php echo $product['id'] ?? ''; ?>"
                         data-name="<?php echo htmlspecialchars($product['name']); ?>"
                         data-price="<?php echo $product['price']; ?>"
                         data-price-raw="<?php echo htmlspecialchars($product['unit_price'] ?? 0, ENT_QUOTES); ?>"
                         data-image="<?php echo htmlspecialchars($product['image']); ?>"
                         data-description="<?php echo htmlspecialchars($product['description'] ?? ''); ?>"
                         data-category="<?php echo htmlspecialchars($product['category'] ?? ''); ?>">
                        <div class="w-full h-[300px] overflow-hidden rounded-lg mb-4">
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-full object-cover object-center">
                        </div>
                        <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="text-gray-600" aria-label="Price"><?php echo $product['price']; ?></p>
                    </article>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="mb-12" aria-labelledby="bestsellers-heading">
                <h2 id="bestsellers-heading" class="text-3xl font-bold text-center mb-8">Best Sellers</h2>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-6" role="list" aria-label="Best selling products">
                    <?php if (!empty($bestSellingProducts)): ?>
                        <?php foreach ($bestSellingProducts as $product): ?>
            <div class="bg-white rounded-xl overflow-hidden product-card p-4" 
                             data-id="<?php echo $product['id'] ?? ''; ?>"
                 data-name="<?php echo htmlspecialchars($product['name']); ?>"
                 data-price="<?php echo $product['price']; ?>"
                             data-price-raw="<?php echo htmlspecialchars($product['unit_price'] ?? 0, ENT_QUOTES); ?>"
                             data-image="<?php echo htmlspecialchars($product['image']); ?>"
                             data-description="<?php echo htmlspecialchars($product['description'] ?? ''); ?>"
                             data-category="<?php echo htmlspecialchars($product['category'] ?? ''); ?>">
                            <div class="w-full h-[300px] overflow-hidden rounded-lg mb-4 relative">
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="w-full h-full object-cover object-center">
                                <div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                                    BEST SELLER
                                </div>
                                <div class="absolute top-2 right-2 bg-black bg-opacity-70 text-white px-2 py-1 rounded-full text-xs">
                                    <?php echo $product['order_count']; ?> sold
                                </div>
                </div>
                <h4 class="font-semibold text-lg"><?php echo $product['name']; ?></h4>
                            <p class="text-gray-600">Rs. <?php echo number_format($product['price'], 2); ?></p>
            </div>
            <?php endforeach; ?>
                    <?php else: ?>
                        <?php 
                        $fallbackProducts = array_slice($products, 0, 5);
                        foreach ($fallbackProducts as $product): 
                        ?>
                        <div class="bg-white rounded-xl overflow-hidden product-card p-4"
                             data-id="<?php echo $product['id'] ?? ''; ?>"
                             data-name="<?php echo htmlspecialchars($product['name']); ?>"
                             data-price="<?php echo $product['price']; ?>"
                             data-price-raw="<?php echo htmlspecialchars($product['unit_price'] ?? 0, ENT_QUOTES); ?>"
                             data-image="<?php echo htmlspecialchars($product['image']); ?>"
                             data-description="<?php echo htmlspecialchars($product['description'] ?? ''); ?>"
                             data-category="<?php echo htmlspecialchars($product['category'] ?? ''); ?>">
                        <div class="w-full h-[300px] overflow-hidden rounded-lg mb-4">
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="w-full h-full object-cover object-center">
                        </div>
                        <h4 class="font-semibold text-lg"><?php echo $product['name']; ?></h4>
                            <p class="text-gray-600">Rs. <?php echo number_format($product['price'], 2); ?></p>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
            </div>
            </section>

            <section class="flex flex-col items-center justify-center p-8 bg-white rounded-xl shadow-md">
                <h3 class="text-2xl md:text-3xl font-bold mb-4">Subscribe now & get 20% off</h3>
                <form class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 w-full md:w-1/2">
                    <input type="email" placeholder="Enter your email" class="flex-grow p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-black">
                    <button type="submit" class="btn-primary">Subscribe</button>
                </form>
            </section>
        </div>

        <!-- Home Page Only - Other pages are now separate files -->
        
    </section>
    
    <!-- Footer -->
    <footer class="footer p-8 mt-auto" role="contentinfo">
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4 text-white">Velvet Vogue</h3>
                <p class="text-sm leading-relaxed">
                    Velvet Vogue is your ultimate destination for high-quality, expressive fashion. We believe in style that tells a story, offering curated collections for the modern individual who values both trend and timelessness. Our mission is to empower you to look and feel your best, every day.
                </p>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4 text-white">Company</h4>
                <nav aria-label="Company links">
                    <ul class="space-y-2 text-sm">
                        <li><a href="about.php" class="hover:underline">About Us</a></li>
                        <li><a href="#" class="hover:underline">Careers</a></li>
                        <li><a href="#" class="hover:underline">Store Locator</a></li>
                        <li><a href="#" class="hover:underline">Our Blog</a></li>
                    </ul>
                </nav>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4 text-white">Other</h4>
                <nav aria-label="Support and policy links">
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:underline">Returns & Exchanges</a></li>
                        <li><a href="#" class="hover:underline">Shipping & Delivery</a></li>
                        <li><a href="#" class="hover:underline">FAQ</a></li>
                        <li><a href="#" class="hover:underline">Privacy Policy</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="text-center mt-8 text-sm text-gray-500">
            &copy; 2025 Velvet Vogue. All rights reserved.
        </div>
    </footer>

<script src="assets/js/script.js"></script>
<script>
// Make all product cards clickable - redirect to dedicated product page
document.addEventListener('DOMContentLoaded', function() {
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.style.cursor = 'pointer';
        card.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (id) {
                window.location.href = `product.php?id=${id}`;
            }
    });
});

    // Nav search functionality
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
            
            // Get all products from anywhere on the page
            const allProducts = Array.from(document.querySelectorAll('.product-card'));
            
            const matchingProducts = allProducts.filter(card => {
                const name = (card.getAttribute('data-name') || '').toLowerCase();
                const category = (card.getAttribute('data-category') || '').toLowerCase();
                return name.includes(query) || category.includes(query);
            }).slice(0, 5); // Limit to 5 results
            
            if (matchingProducts.length > 0) {
                // Group products by category
                const categories = {};
                matchingProducts.forEach(card => {
                    const category = card.getAttribute('data-category') || 'Other';
                    if (!categories[category]) {
                        categories[category] = [];
                    }
                    categories[category].push(card);
                });
                
                let html = '';
                
                // Add Categories section
                const categoryNames = Object.keys(categories);
                if (categoryNames.length > 0) {
                    html += '<div class="border-b pb-2 mb-2">';
                    html += '<h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">CATEGORIES</h4>';
                    categoryNames.forEach(category => {
                        html += `
                            <div class="flex items-center space-x-2 p-2 hover:bg-gray-50 cursor-pointer rounded" onclick="filterByCategory('${category}')">
                                <div class="w-6 h-6 bg-gray-200 rounded flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">${category}</p>
                                    <p class="text-xs text-gray-500">in ${category}</p>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                }
                
                // Add Products section
                html += '<div>';
                html += '<h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">PRODUCTS</h4>';
                matchingProducts.slice(0, 4).forEach(card => {
                    const name = card.getAttribute('data-name') || '';
                    const price = card.getAttribute('data-price') || '';
                    const image = card.getAttribute('data-image') || '';
                    const productId = card.getAttribute('data-id') || '';
                    const category = card.getAttribute('data-category') || '';
                    
                    html += `
                        <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 cursor-pointer rounded" onclick="selectSearchResult('${productId}', '${name.replace(/'/g, "\\'")}')">
                            <img src="${image}" alt="${name}" class="w-8 h-8 object-cover rounded">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">${name}</p>
                                <p class="text-xs text-gray-500">${price}</p>
                            </div>
                        </div>
                    `;
                });
                
                // Add "See all products" link if there are more results
                if (matchingProducts.length > 4) {
                    html += `
                        <div class="border-t pt-2 mt-2">
                            <button class="text-xs text-blue-600 hover:text-blue-800 font-medium" onclick="showAllSearchResults('${query}')">
                                SEE ALL PRODUCTS... (${matchingProducts.length})
                            </button>
                        </div>
                    `;
                }
                
                html += '</div>';
                searchResultsContent.innerHTML = html;
                searchResults.classList.remove('hidden');
            } else {
                searchResultsContent.innerHTML = '<div class="p-2 text-sm text-gray-500">No products found</div>';
                searchResults.classList.remove('hidden');
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

// Function to handle search result selection
function selectSearchResult(productId, productName) {
    // Clear search and hide results
    const navSearch = document.getElementById('nav-search');
    const searchResults = document.getElementById('search-results');
    if (navSearch) navSearch.value = '';
    if (searchResults) searchResults.classList.add('hidden');
    
    // Redirect to dedicated product page
    window.location.href = `product.php?id=${productId}`;
}

// Function to filter by category from search results
function filterByCategory(category) {
    // Clear search and hide results
    const navSearch = document.getElementById('nav-search');
    const searchResults = document.getElementById('search-results');
    if (navSearch) navSearch.value = '';
    if (searchResults) searchResults.classList.add('hidden');
    
    // Go to collection page and filter by category
    window.location.href = `collection.php?category=${encodeURIComponent(category)}`;
}

// Function to show all search results
function showAllSearchResults(query) {
    // Clear search and hide results
    const navSearch = document.getElementById('nav-search');
    const searchResults = document.getElementById('search-results');
    if (navSearch) navSearch.value = '';
    if (searchResults) searchResults.classList.add('hidden');
    
    // Go to collection page and set search
    window.location.href = `collection.php?search=${encodeURIComponent(query)}`;
}
</script>

</body>
</html>
</html>