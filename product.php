<?php
session_start();
require_once 'config.php';
require_once 'csrf_helper.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id <= 0) {
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$product = $result->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare("SELECT * FROM products WHERE category = ? AND id != ? ORDER BY RAND() LIMIT 4");
$stmt->bind_param("si", $product['category'], $product_id);
$stmt->execute();
$related_products = $stmt->get_result();
$stmt->close();

$sessionCart = $_SESSION['cart'] ?? [];
$sessionWishlist = $_SESSION['wishlist'] ?? [];

$inWishlist = false;
foreach ($sessionWishlist as $item) {
    if ($item['product_id'] == $product_id) {
        $inWishlist = true;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Velvet Vogue</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-40">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-8">
                    <a href="index.php" class="text-2xl font-bold">Velvet Vogue</a>
                    <nav class="hidden md:flex space-x-6">
                        <a href="index.php" class="hover:text-black transition-colors">Home</a>
                        <a href="index.php#collection" class="hover:text-black transition-colors">Collection</a>
                        <a href="index.php#contact" class="hover:text-black transition-colors">Contact</a>
                    </nav>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <form id="nav-search-form">
                            <input id="nav-search" type="text" placeholder="Search" class="p-2 border rounded-lg text-sm w-48" />
                        </form>
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
        </div>
    </header>

    <!-- Breadcrumb -->
    <div class="container mx-auto px-4 py-4">
        <nav class="text-sm text-gray-600">
            <a href="index.php" class="hover:text-black">Home</a> / 
            <a href="index.php#collection" class="hover:text-black">Collection</a> / 
            <span class="text-black"><?php echo htmlspecialchars($product['name']); ?></span>
        </nav>
    </div>

    <!-- Product Detail Section -->
    <section class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Image -->
            <div class="space-y-4">
                <div class="aspect-square overflow-hidden rounded-xl bg-gray-100">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                         class="w-full h-full object-cover object-center">
                </div>
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <div>
                    <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($product['name']); ?></h1>
                    <p class="text-2xl font-semibold text-gray-900">Rs <?php echo number_format($product['price'], 2); ?></p>
                    <div class="flex items-center space-x-4 mt-2">
                        <span class="text-sm text-gray-600">Category: <?php echo ucfirst($product['category']); ?></span>
                        <span class="text-sm text-gray-600">Gender: <?php echo ucfirst($product['gender']); ?></span>
                    </div>
                </div>

                <!-- Size Selection (for Shirts, T-Shirts, Jeans) -->
                <?php if (in_array(strtolower($product['category']), ['shirts', 't-shirts', 'jeans'])): ?>
                <div>
                    <h3 class="text-lg font-semibold mb-3">Size</h3>
                    <div class="flex space-x-2" id="size-selection">
                        <button class="size-btn" data-size="S">S</button>
                        <button class="size-btn" data-size="M">M</button>
                        <button class="size-btn" data-size="L">L</button>
                        <button class="size-btn" data-size="XL">XL</button>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">Please select a size</p>
                </div>
                <?php endif; ?>

                <!-- Description -->
                <div>
                    <h3 class="text-lg font-semibold mb-3">Description</h3>
                    <p class="text-gray-700 leading-relaxed">
                        <?php echo htmlspecialchars($product['description'] ?: 'A premium quality product from Velvet Vogue, designed for the modern individual who values both style and comfort.'); ?>
                    </p>
                </div>

                <!-- Quantity Selector -->
                <div>
                    <h3 class="text-lg font-semibold mb-3">Quantity</h3>
                    <div class="flex items-center space-x-4">
                        <button class="quantity-btn" onclick="updateQuantity(-1)">-</button>
                        <span id="quantity" class="text-lg font-medium min-w-[2rem] text-center">1</span>
                        <button class="quantity-btn" onclick="updateQuantity(1)">+</button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-4">
                    <button id="add-to-cart-btn" class="btn-primary flex-1" 
                            data-id="<?php echo $product['id']; ?>"
                            data-name="<?php echo htmlspecialchars($product['name']); ?>"
                            data-price="<?php echo $product['price']; ?>"
                            data-image="<?php echo htmlspecialchars($product['image']); ?>">
                        Add to Cart
                    </button>
                    <button id="buy-now-btn" class="btn-secondary flex-1"
                            data-id="<?php echo $product['id']; ?>"
                            data-name="<?php echo htmlspecialchars($product['name']); ?>"
                            data-price="<?php echo $product['price']; ?>"
                            data-image="<?php echo htmlspecialchars($product['image']); ?>">
                        Buy Now
                    </button>
                </div>

                <!-- Wishlist Button -->
                <div class="mt-4">
                    <button id="wishlist-btn" class="btn-wishlist w-full flex items-center justify-center" 
                            data-id="<?php echo $product['id']; ?>"
                            data-name="<?php echo htmlspecialchars($product['name']); ?>"
                            data-price="<?php echo $product['price']; ?>"
                            data-image="<?php echo htmlspecialchars($product['image']); ?>">
                        <i class="fas fa-heart mr-2"></i>
                        <span id="wishlist-text" class="font-medium"><?php echo $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist'; ?></span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <?php if ($related_products->num_rows > 0): ?>
    <section class="container mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold mb-8">Related Products</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php while ($related = $related_products->fetch_assoc()): ?>
            <div class="bg-white rounded-xl overflow-hidden product-card p-4 cursor-pointer"
                 onclick="window.location.href='product.php?id=<?php echo $related['id']; ?>'">
                <div class="w-full h-[200px] overflow-hidden rounded-lg mb-4">
                    <img src="<?php echo htmlspecialchars($related['image']); ?>" 
                         alt="<?php echo htmlspecialchars($related['name']); ?>" 
                         class="w-full h-full object-cover object-center">
                </div>
                <h4 class="font-semibold text-lg"><?php echo htmlspecialchars($related['name']); ?></h4>
                <p class="text-gray-600">Rs <?php echo number_format($related['price'], 2); ?></p>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="bg-black text-white p-8 mt-12">
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4">Velvet Vogue</h3>
                <p class="text-sm leading-relaxed">
                    Velvet Vogue is your ultimate destination for high-quality, expressive fashion. We believe in style that tells a story, offering curated collections for the modern individual who values both trend and timelessness.
                </p>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="index.php" class="hover:underline">Home</a></li>
                    <li><a href="index.php#collection" class="hover:underline">Collection</a></li>
                    <li><a href="index.php#contact" class="hover:underline">Contact</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Contact Info</h4>
                <p class="text-sm">Email: info@velvetvogue.com</p>
                <p class="text-sm">Phone: +1 (555) 123-4567</p>
            </div>
        </div>
    </footer>

    <script>
        let selectedSize = null;

        function updateQuantity(change) {
            const quantityElement = document.getElementById('quantity');
            let quantity = parseInt(quantityElement.textContent);
            quantity += change;
            
            if (quantity < 1) quantity = 1;
            if (quantity > 10) quantity = 10; 
            
            quantityElement.textContent = quantity;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const sizeButtons = document.querySelectorAll('.size-btn');
            
            if (sizeButtons.length > 0) {
                selectedSize = 'M';
                const defaultBtn = Array.from(sizeButtons).find(btn => btn.dataset.size === 'M');
                if (defaultBtn) {
                    defaultBtn.classList.add('selected');
                }
            }
            
            sizeButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    sizeButtons.forEach(b => b.classList.remove('selected'));
                    this.classList.add('selected');
                    selectedSize = this.dataset.size;
                });
            });

            document.getElementById('add-to-cart-btn').addEventListener('click', function() {
                const productId = this.dataset.id;
                const name = this.dataset.name;
                const price = this.dataset.price;
                const image = this.dataset.image;
                const qty = 1;

                const sizeSelection = document.getElementById('size-selection');
                if (sizeSelection && !selectedSize) {
                    alert('Please select a size');
                    return;
                }

                addToCart(productId, name, price, image, qty, selectedSize || 'M');
            });

            document.getElementById('buy-now-btn').addEventListener('click', function() {
                const productId = this.dataset.id;
                const name = this.dataset.name;
                const price = this.dataset.price;
                const image = this.dataset.image;
                const qty = 1;

                const sizeSelection = document.getElementById('size-selection');
                if (sizeSelection && !selectedSize) {
                    alert('Please select a size');
                    return;
                }

                addToCartForBuyNow(productId, name, price, image, qty, selectedSize || 'M');
            });

            document.getElementById('wishlist-btn').addEventListener('click', function() {
                const productId = this.dataset.id;
                const name = this.dataset.name;
                const price = this.dataset.price;
                const image = this.dataset.image;

                toggleWishlist(productId, name, price, image);
            });
        });

        function addToCart(productId, name, price, image, qty, size) {
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('name', name);
            formData.append('price', price);
            formData.append('image', image);
            formData.append('qty', qty);
            if (size) formData.append('size', size);
            formData.append('csrf_token', '<?php echo generateCSRFToken(); ?>');

            fetch('add_to_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Product added to cart!');
                } else {
                    alert('Error adding product to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding product to cart');
            });
        }

        function addToCartForBuyNow(productId, name, price, image, qty, size) {
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('name', name);
            formData.append('price', price);
            formData.append('image', image);
            formData.append('qty', qty);
            if (size) formData.append('size', size);
            formData.append('csrf_token', '<?php echo generateCSRFToken(); ?>');

            fetch('add_to_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'checkout.php';
                } else {
                    alert('Error adding product to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding product to cart');
            });
        }

        function toggleWishlist(productId, name, price, image) {
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('name', name);
            formData.append('price', price);
            formData.append('image', image);
            formData.append('csrf_token', '<?php echo generateCSRFToken(); ?>');

            fetch('add_to_wishlist.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const btn = document.getElementById('wishlist-btn');
                    const text = document.getElementById('wishlist-text');
                    if (data.action === 'added') {
                        text.textContent = 'Remove from Wishlist';
                        btn.classList.add('bg-red-500', 'text-white');
                        btn.classList.remove('bg-gray-200', 'text-gray-800');
                    } else {
                        text.textContent = 'Add to Wishlist';
                        btn.classList.remove('bg-red-500', 'text-white');
                        btn.classList.add('bg-gray-200', 'text-gray-800');
                    }
                } else {
                    alert('Error updating wishlist');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating wishlist');
            });
        }
    </script>
</body>
</html>
