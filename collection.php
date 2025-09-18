<?php
session_start();
require_once 'config.php';
require_once 'csrf_helper.php';

function getProductsFromDatabase() {
    global $conn;
    
    $products = [];
    
    // Fetch products from database
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

// Get products from database
$products = getProductsFromDatabase();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection - Velvet Vogue</title>
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
                <a href="collection.php" class="hover:text-black transition-colors text-black font-semibold">Collection</a>
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
                <button class="text-gray-600 hover:text-black transition-colors" onclick="window.location.href='<?php echo isset($_SESSION['user_id']) ? "account.php" : "login_register.php"; ?>'">
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
        <h1 class="text-4xl font-bold mb-8 text-center">ALL COLLECTIONS</h1>
        <div class="flex flex-col md:flex-row space-y-8 md:space-y-0 md:space-x-8">
            <!-- Filters Section -->
            <div class="md:w-1/4 bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-2xl font-bold mb-4">Filters</h2>
                <div class="space-y-4">
                    <div class="border-b pb-4">
                        <h3 class="font-semibold mb-2">Sort By</h3>
                        <select class="w-full p-2 border rounded-lg" onchange="sortProducts(this.value)">
                            <option value="popularity">Popularity</option>
                            <option value="price-low">Price: Low to High</option>
                            <option value="price-high">Price: High to Low</option>
                            <option value="random">Random</option>
                        </select>
                    </div>
                    <div class="border-b pb-4">
                        <h3 class="font-semibold mb-2">Search</h3>
                        <input type="text" id="search-input" placeholder="Search products" class="w-full p-2 border rounded-lg" />
                    </div>
                    <div class="border-b pb-4">
                        <h3 class="font-semibold mb-2">Gender</h3>
                        <div class="space-y-2 text-sm">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="gender-filter" value="Men" class="rounded"> <span>Men</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="gender-filter" value="Women" class="rounded"> <span>Women</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="gender-filter" value="Unisex" class="rounded"> <span>Unisex</span>
                            </label>
                            <button type="button" class="mt-2 text-xs underline" onclick="clearGenderFilters()">Clear</button>
                        </div>
                    </div>
                    <div class="border-b pb-4">
                        <h3 class="font-semibold mb-2">Categories</h3>
                        <div class="space-y-2 text-sm">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="category-filter" value="Shirts" class="rounded"> <span>Shirts</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="category-filter" value="T-Shirts" class="rounded"> <span>T-Shirts</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="category-filter" value="Jeans" class="rounded"> <span>Jeans</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="category-filter" value="Accessories" class="rounded"> <span>Accessories</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="category-filter" value="Shoes" class="rounded"> <span>Shoes</span>
                            </label>
                            <button type="button" class="mt-2 text-xs underline" onclick="clearCategoryFilters()">Clear</button>
                        </div>
                    </div>
                    <div class="border-b pb-2">
                        <h3 class="font-semibold mb-2">Price Range (Rs)</h3>
                        <div class="flex space-x-2">
                            <input type="number" id="price-min" placeholder="Min" class="w-1/2 p-2 border rounded-lg" />
                            <input type="number" id="price-max" placeholder="Max" class="w-1/2 p-2 border rounded-lg" />
                        </div>
                        <button type="button" class="mt-2 text-xs underline" onclick="clearPriceFilters()">Clear</button>
                    </div>
                </div>
            </div>

            <div class="md:w-3/4 grid grid-cols-2 md:grid-cols-4 gap-6" id="product-grid">
                <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-xl overflow-hidden product-card p-4" 
                     data-id="<?php echo $product['id']; ?>"
                     data-name="<?php echo htmlspecialchars($product['name']); ?>"
                     data-price="<?php echo $product['price']; ?>"
                     data-price-raw="<?php echo htmlspecialchars($product['unit_price'] ?? 0, ENT_QUOTES); ?>"
                     data-image="<?php echo $product['image']; ?>"
                     data-description="<?php echo htmlspecialchars($product['description'] ?? ''); ?>"
                     data-category="<?php echo htmlspecialchars($product['category'] ?? ''); ?>"
                     data-gender="<?php echo htmlspecialchars($product['gender'] ?? 'Unisex'); ?>">
                    <div class="w-full h-[300px] overflow-hidden rounded-lg mb-4">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="w-full h-full object-cover object-center">
                    </div>
                    <h4 class="font-semibold text-lg"><?php echo $product['name']; ?></h4>
                    <p class="text-gray-600"><?php echo $product['price']; ?></p>
                </div>
                <?php endforeach; ?>
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

    // Wire category filters
    const categoryInputs = document.querySelectorAll('input[name="category-filter"]');
    categoryInputs.forEach(input => {
        input.addEventListener('change', applyAllFilters);
    });
    
    // Wire gender filters
    const genderInputs = document.querySelectorAll('input[name="gender-filter"]');
    genderInputs.forEach(input => {
        input.addEventListener('change', applyAllFilters);
    });
    
    // Apply on load in case of persisted state
    applyAllFilters();
    
    // Search and price filters
    const searchInput = document.getElementById('search-input');
    if (searchInput) searchInput.addEventListener('input', applyAllFilters);
    const priceMin = document.getElementById('price-min');
    const priceMax = document.getElementById('price-max');
    if (priceMin) priceMin.addEventListener('input', applyAllFilters);
    if (priceMax) priceMax.addEventListener('input', applyAllFilters);

    // Nav search with live dropdown results
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

    // Apply initial sort based on the selected option (defaults to Popularity)
    const sortSelect = document.querySelector('select[onchange^="sortProducts"]');
    if (sortSelect) {
        sortProducts(sortSelect.value);
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
    
    // Check the category checkbox
    const categoryCheckbox = document.querySelector(`input[name="category-filter"][value="${category}"]`);
    if (categoryCheckbox) {
        categoryCheckbox.checked = true;
        applyAllFilters();
    }
}

// Function to show all search results
function showAllSearchResults(query) {
    // Clear search and hide results
    const navSearch = document.getElementById('nav-search');
    const searchResults = document.getElementById('search-results');
    if (navSearch) navSearch.value = '';
    if (searchResults) searchResults.classList.add('hidden');
    
    // Set search
    const collectionSearch = document.getElementById('search-input');
    if (collectionSearch) {
        collectionSearch.value = query;
        applyAllFilters();
    }
}

// Category filtering
function getSelectedCategories() {
    return Array.from(document.querySelectorAll('input[name="category-filter"]:checked'))
        .map(cb => cb.value);
}

function applyCategoryFilters() {
    const selected = getSelectedCategories();
    const grid = document.querySelector('#product-grid');
    if (!grid) return;
    const cards = grid.querySelectorAll('.product-card');
    cards.forEach(card => {
        const category = (card.getAttribute('data-category') || '');
        const show = selected.length === 0 || selected.some(sel => categoriesMatch(sel, category));
        card.style.display = show ? '' : 'none';
    });
}

function clearCategoryFilters() {
    document.querySelectorAll('input[name="category-filter"]:checked').forEach(cb => { cb.checked = false; });
    applyAllFilters();
}

// Gender filtering
function getSelectedGenders() {
    return Array.from(document.querySelectorAll('input[name="gender-filter"]:checked'))
        .map(cb => cb.value);
}

function applyGenderFilters() {
    const selected = getSelectedGenders();
    const grid = document.querySelector('#product-grid');
    if (!grid) return;
    const cards = grid.querySelectorAll('.product-card');
    cards.forEach(card => {
        const gender = (card.getAttribute('data-gender') || 'Unisex');
        const show = selected.length === 0 || selected.includes(gender);
        card.style.display = show ? '' : 'none';
    });
}

function clearGenderFilters() {
    document.querySelectorAll('input[name="gender-filter"]:checked').forEach(cb => { cb.checked = false; });
    applyAllFilters();
}

// Helpers for tolerant category matching (case/spacing/punctuation/plural-insensitive)
function normalizeCategory(raw) {
    const simple = (raw || '')
        .toLowerCase()
        .trim()
        .replace(/&/g, 'and')
        .replace(/[^a-z]/g, '');
    // Strip trailing plural 's' to match singular/plural
    return simple.endsWith('s') ? simple.slice(0, -1) : simple;
}

function categoriesMatch(a, b) {
    const na = normalizeCategory(a);
    const nb = normalizeCategory(b);
    return na !== '' && nb !== '' && na === nb;
}

function applyAllFilters() {
    const grid = document.querySelector('#product-grid');
    if (!grid) return;
    const cards = Array.from(grid.querySelectorAll('.product-card'));

    const selectedCategories = getSelectedCategories();
    const selectedGenders = getSelectedGenders();
    const q = (document.getElementById('search-input')?.value || '').trim().toLowerCase();
    const minVal = parseFloat(document.getElementById('price-min')?.value || '') || -Infinity;
    const maxVal = parseFloat(document.getElementById('price-max')?.value || '') || Infinity;

    cards.forEach(card => {
        const name = (card.getAttribute('data-name') || '').toLowerCase();
        const category = (card.getAttribute('data-category') || '');
        const gender = (card.getAttribute('data-gender') || 'Unisex');
        const priceRaw = parseFloat(card.getAttribute('data-price-raw') || '0') || 0;

        const catOk = selectedCategories.length === 0 || selectedCategories.some(c => categoriesMatch(c, category));
        const genderOk = selectedGenders.length === 0 || selectedGenders.includes(gender);
        const textOk = q === '' || name.includes(q);
        const priceOk = priceRaw >= minVal && priceRaw <= maxVal;

        card.style.display = (catOk && genderOk && textOk && priceOk) ? '' : 'none';
    });
}

function clearPriceFilters() {
    const min = document.getElementById('price-min');
    const max = document.getElementById('price-max');
    if (min) min.value = '';
    if (max) max.value = '';
    applyAllFilters();
}

// Sorting
function sortProducts(mode) {
    const grid = document.querySelector('#product-grid');
    if (!grid) return;
    const cards = Array.from(grid.querySelectorAll('.product-card'));
    if (cards.length === 0) return;

    const popularityOrder = {
        shirts: 0,
        tshirt: 1, // normalized for T-Shirts
        jean: 2,
        accessorie: 3,
        shoe: 4
    };

    const getCategoryRank = (card) => {
        const catRaw = card.getAttribute('data-category') || '';
        const norm = normalizeCategory(catRaw);
        return popularityOrder.hasOwnProperty(norm) ? popularityOrder[norm] : 999;
    };

    const getPriceValue = (card) => {
        const priceRaw = card.getAttribute('data-price') || '';
        const numeric = parseFloat(priceRaw.replace(/[^0-9.,]/g, '').replace(/,/g, ''));
        return isNaN(numeric) ? Number.MAX_SAFE_INTEGER : numeric;
    };

    let sorted;
    switch (mode) {
        case 'price-low':
            sorted = cards.sort((a, b) => getPriceValue(a) - getPriceValue(b));
            break;
        case 'price-high':
            sorted = cards.sort((a, b) => getPriceValue(b) - getPriceValue(a));
            break;
        case 'random':
            sorted = cards.sort(() => Math.random() - 0.5);
            break;
        case 'popularity':
        default:
            // Shirts first, then others by name for stability
            sorted = cards.sort((a, b) => {
                const ra = getCategoryRank(a);
                const rb = getCategoryRank(b);
                if (ra !== rb) return ra - rb;
                const na = (a.getAttribute('data-name') || '').toLowerCase();
                const nb = (b.getAttribute('data-name') || '').toLowerCase();
                return na.localeCompare(nb);
            });
            break;
    }

    // Re-append in new order
    sorted.forEach(card => grid.appendChild(card));

    // Maintain current category filters
    applyCategoryFilters();
}
</script>

</body>
</html>
