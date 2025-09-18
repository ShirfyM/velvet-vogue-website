function showProductDetail(product) {
    // Set product details
    document.getElementById('detail-image').src = product.image;
    document.getElementById('detail-name').textContent = product.name;
    document.getElementById('detail-price').textContent = product.price;
    
    const descriptions = {
        'Printed Half Sleeve Shirt': 'This stylish printed half sleeve shirt features a unique pattern that stands out. Made from high-quality cotton for maximum comfort and durability.',
        'Slim Fit Jeans': 'These slim fit jeans are designed to provide a modern silhouette while maintaining comfort. Perfect for casual outings or dressed-up occasions.',
        'Classic Leather Jacket': 'Crafted from genuine leather, this classic jacket offers timeless style and durability. Perfect for adding an edge to any outfit.',
        'Casual Hoodie': 'Our premium casual hoodie offers exceptional comfort with a soft inner lining and adjustable drawstring hood. Perfect for everyday wear.',
        'High-Top Sneakers': 'These high-top sneakers combine style and comfort with premium materials and excellent arch support for all-day wear.',
        'Knit Sweater': 'A cozy knit sweater made from soft, warm yarns. Perfect for layering in cooler weather while maintaining a stylish appearance.',
        'Chino Trousers': 'Versatile chino trousers that bridge the gap between casual and formal wear. Perfect for both office and weekend outfits.',
        'Polo Shirt': 'A classic polo shirt made from breathable pique cotton. The perfect balance between casual comfort and polished style.',
        'Denim Jacket': 'A timeless denim jacket that never goes out of style. Perfect for layering over various outfits in all seasons.',
        'Printed T-Shirt': 'A comfortable printed t-shirt featuring unique designs. Made from soft cotton for everyday comfort and style.',
        'Oversized Crewneck Sweatshirt': 'An oversized crewneck sweatshirt for a comfortable, relaxed fit. Perfect for lounging or casual outings.',
        'Corduroy Pants': 'Fashionable corduroy pants with a soft texture and comfortable fit. Ideal for creating stylish autumn and winter outfits.',
        'Wool Blend Coat': 'A warm wool blend coat that provides excellent insulation against cold weather while maintaining a sophisticated appearance.',
        'Basic White Tee': 'The essential basic white tee made from high-quality cotton. A versatile staple for any wardrobe.',
        'Cargo Shorts': 'Functional cargo shorts with multiple pockets. Perfect for outdoor activities and casual summer wear.',
        'Striped Polo': 'A classic striped polo shirt that adds a touch of nautical style to your wardrobe. Made from breathable fabric.',
        'Cap': 'A stylish cap to complete your look and protect from the sun. Adjustable for a perfect fit.',
        'Jeans': 'Classic jeans with a modern fit. Made from durable denim that will last for seasons while maintaining comfort.'
    };
    
    const description = descriptions[product.name] || 'A premium quality product designed for style and comfort. Made with attention to detail and perfect for everyday wear.';
    document.getElementById('detail-description').textContent = description;
    
    setupColorOptions(product);
    
    setupSizeOptions(product);
    
    document.getElementById('selected-color').textContent = '- Select';
    document.getElementById('selected-size').textContent = '- Select';
    
    document.getElementById('quantity').textContent = '1';
    
    showPage('product-detail');
}

function setupColorOptions(product) {
    const colorContainer = document.getElementById('color-options');
    colorContainer.innerHTML = '';
    
    let availableColors = [];
    
    if (product.name.includes('Shirt') || product.name.includes('Tee') || product.name.includes('Polo')) {
        availableColors = [
            { name: 'White', value: 'white', hex: '#ffffff' },
            { name: 'Black', value: 'black', hex: '#000000' },
            { name: 'Navy', value: 'navy', hex: '#0a192f' },
            { name: 'Light Blue', value: 'lightblue', hex: '#add8e6' }
        ];
    } else if (product.name.includes('Jeans') || product.name.includes('Pants')) {
        availableColors = [
            { name: 'Blue', value: 'blue', hex: '#1e40af' },
            { name: 'Dark Blue', value: 'darkblue', hex: '#1e3a8a' },
            { name: 'Black', value: 'black', hex: '#000000' },
            { name: 'Grey', value: 'grey', hex: '#6b7280' }
        ];
    } else if (product.name.includes('Jacket') || product.name.includes('Coat')) {
        availableColors = [
            { name: 'Black', value: 'black', hex: '#000000' },
            { name: 'Brown', value: 'brown', hex: '#78350f' },
            { name: 'Navy', value: 'navy', hex: '#0a192f' },
            { name: 'Green', value: 'green', hex: '#065f46' }
        ];
    } else {
        availableColors = [
            { name: 'Black', value: 'black', hex: '#000000' },
            { name: 'White', value: 'white', hex: '#ffffff' },
            { name: 'Grey', value: 'grey', hex: '#6b7280' },
            { name: 'Blue', value: 'blue', hex: '#1e40af' }
        ];
    }
    
    availableColors.forEach(color => {
        const colorElement = document.createElement('div');
        colorElement.className = 'color-option w-8 h-8 rounded-full border-2 border-transparent cursor-pointer hover:scale-110 transition-transform';
        colorElement.style.backgroundColor = color.hex;
        colorElement.setAttribute('data-color', color.value);
        colorElement.setAttribute('data-name', color.name);
        colorElement.addEventListener('click', () => selectColor(color.value, color.name));
        
        colorContainer.appendChild(colorElement);
    });
}

function setupSizeOptions(product) {
    const sizeContainer = document.getElementById('size-options');
    sizeContainer.innerHTML = '';
    
    let availableSizes = [];
    
    if (product.name.includes('Shirt') || product.name.includes('Tee') || product.name.includes('Polo')) {
        availableSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
    } else if (product.name.includes('Jeans') || product.name.includes('Pants')) {
        availableSizes = ['28', '30', '32', '34', '36', '38'];
    } else if (product.name.includes('Shoes') || product.name.includes('Sneakers')) {
        availableSizes = ['US 7', 'US 8', 'US 9', 'US 10', 'US 11', 'US 12'];
    } else {
        availableSizes = ['XS', 'S', 'M', 'L', 'XL'];
    }
    
    availableSizes.forEach(size => {
        const sizeElement = document.createElement('div');
        sizeElement.className = 'size-option w-12 h-10 flex items-center justify-center border rounded-lg cursor-pointer hover:border-gray-500 transition-colors';
        sizeElement.textContent = size;
        sizeElement.setAttribute('data-size', size);
        sizeElement.addEventListener('click', () => selectSize(size));
        
        sizeContainer.appendChild(sizeElement);
    });
}

function selectColor(colorValue, colorName) {
    document.querySelectorAll('.color-option').forEach(option => {
        option.classList.remove('border-black', 'scale-110');
        option.classList.add('border-transparent');
    });
    
    const selectedOption = document.querySelector(`.color-option[data-color="${colorValue}"]`);
    selectedOption.classList.remove('border-transparent');
    selectedOption.classList.add('border-black', 'scale-110');
    
    document.getElementById('selected-color').textContent = colorName;
    
    document.getElementById('color-error').classList.add('hidden');
}

function selectSize(size) {
    document.querySelectorAll('.size-option').forEach(option => {
        option.classList.remove('bg-black', 'text-white', 'border-black');
        option.classList.add('bg-white', 'text-gray-800', 'border-gray-300');
    });
    
    const selectedOption = document.querySelector(`.size-option[data-size="${size}"]`);
    selectedOption.classList.remove('bg-white', 'text-gray-800', 'border-gray-300');
    selectedOption.classList.add('bg-black', 'text-white', 'border-black');
    
    // Update selected size text
    document.getElementById('selected-size').textContent = size;
    
    document.getElementById('size-error').classList.add('hidden');
}

// Function to update quantity
function updateQuantity(change) {
    const quantityElement = document.getElementById('quantity');
    let quantity = parseInt(quantityElement.textContent);
    quantity += change;
    
    if (quantity < 1) quantity = 1;
    if (quantity > 10) quantity = 10;
    
    quantityElement.textContent = quantity;
}

function addToCart() {
    const selectedColor = document.querySelector('.color-option.border-black');
    if (!selectedColor) {
        document.getElementById('color-error').classList.remove('hidden');
        return;
    }
    
    const selectedSize = document.querySelector('.size-option.bg-black');
    if (!selectedSize) {
        document.getElementById('size-error').classList.remove('hidden');
        return;
    }
    
    const productName = document.getElementById('detail-name').textContent;
    const productPrice = document.getElementById('detail-price').textContent;
    const quantity = document.getElementById('quantity').textContent;
    const color = selectedColor.getAttribute('data-name');
    const size = selectedSize.getAttribute('data-size');
    
    alert(`Added to cart: ${productName}\nColor: ${color}, Size: ${size}, Quantity: ${quantity}\nTotal: ${productPrice}`);
    
    showPage('cart');
}

function toggleWishlist() {
    const wishlistBtn = document.getElementById('wishlist-btn');
    const isInWishlist = wishlistBtn.classList.contains('text-red-500');
    
    if (isInWishlist) {
        wishlistBtn.classList.remove('text-red-500');
        wishlistBtn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        `;
        alert('Removed from wishlist');
    } else {
        wishlistBtn.classList.add('text-red-500');
        wishlistBtn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        `;
        alert('Added to wishlist');
    }
}

// Function to update cart count display
function updateCartCount() {
    // Get cart count from session storage or calculate from cart items
    let cartCount = 0;
    
    // Try to get from session storage first
    if (typeof(Storage) !== "undefined") {
        const cartData = sessionStorage.getItem('cart');
        if (cartData) {
            try {
                const cart = JSON.parse(cartData);
                cartCount = cart.length;
            } catch (e) {
                console.log('Error parsing cart data:', e);
            }
        }
    }
    
    // Update cart count in navigation
    const cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach(element => {
        element.textContent = cartCount;
        element.style.display = cartCount > 0 ? 'inline' : 'none';
    });
    
    // Update cart count in cart icon
    const cartIcon = document.querySelector('[onclick*="cart.php"]');
    if (cartIcon) {
        const existingCount = cartIcon.querySelector('.cart-count');
        if (existingCount) {
            existingCount.textContent = cartCount;
            existingCount.style.display = cartCount > 0 ? 'inline' : 'none';
        } else if (cartCount > 0) {
            // Add cart count badge if it doesn't exist
            const countBadge = document.createElement('span');
            countBadge.className = 'cart-count absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center';
            countBadge.textContent = cartCount;
            cartIcon.style.position = 'relative';
            cartIcon.appendChild(countBadge);
        }
    }
}

// Function to add item to cart with proper count update
function addItemToCart(productId, productName, productPrice, quantity = 1, color = '', size = '') {
    // Get existing cart or create new one
    let cart = [];
    if (typeof(Storage) !== "undefined") {
        const cartData = sessionStorage.getItem('cart');
        if (cartData) {
            try {
                cart = JSON.parse(cartData);
            } catch (e) {
                console.log('Error parsing cart data:', e);
                cart = [];
            }
        }
    }
    
    // Check if item already exists in cart
    const existingItemIndex = cart.findIndex(item => 
        item.id === productId && item.color === color && item.size === size
    );
    
    if (existingItemIndex > -1) {
        // Update quantity if item exists
        cart[existingItemIndex].quantity += quantity;
    } else {
        // Add new item to cart
        cart.push({
            id: productId,
            name: productName,
            price: productPrice,
            quantity: quantity,
            color: color,
            size: size
        });
    }
    
    // Save cart to session storage
    if (typeof(Storage) !== "undefined") {
        sessionStorage.setItem('cart', JSON.stringify(cart));
    }
    
    // Update cart count display
    updateCartCount();
    
    // Show success message
    alert(`Added to cart: ${productName}\nColor: ${color || 'N/A'}, Size: ${size || 'N/A'}, Quantity: ${quantity}`);
}

// Function to remove item from cart
function removeFromCart(productId, color = '', size = '') {
    let cart = [];
    if (typeof(Storage) !== "undefined") {
        const cartData = sessionStorage.getItem('cart');
        if (cartData) {
            try {
                cart = JSON.parse(cartData);
            } catch (e) {
                console.log('Error parsing cart data:', e);
                return;
            }
        }
    }
    
    // Remove item from cart
    cart = cart.filter(item => 
        !(item.id === productId && item.color === color && item.size === size)
    );
    
    // Save updated cart
    if (typeof(Storage) !== "undefined") {
        sessionStorage.setItem('cart', JSON.stringify(cart));
    }
    
    // Update cart count display
    updateCartCount();
}

// Function to update cart item quantity
function updateCartItemQuantity(productId, color, size, newQuantity) {
    let cart = [];
    if (typeof(Storage) !== "undefined") {
        const cartData = sessionStorage.getItem('cart');
        if (cartData) {
            try {
                cart = JSON.parse(cartData);
            } catch (e) {
                console.log('Error parsing cart data:', e);
                return;
            }
        }
    }
    
    // Find and update item
    const itemIndex = cart.findIndex(item => 
        item.id === productId && item.color === color && item.size === size
    );
    
    if (itemIndex > -1) {
        if (newQuantity <= 0) {
            cart.splice(itemIndex, 1);
        } else {
            cart[itemIndex].quantity = newQuantity;
        }
        
        // Save updated cart
        if (typeof(Storage) !== "undefined") {
            sessionStorage.setItem('cart', JSON.stringify(cart));
        }
        
        // Update cart count display
        updateCartCount();
    }
}

// Initialize cart count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
});
