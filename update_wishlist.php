<?php
session_start();
require_once 'config.php';
require_once 'csrf_helper.php';

// Require CSRF token for all POST requests
requireCSRFToken();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: wishlist.php");
    exit();
}

$productId = (int)($_POST['product_id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$remove = isset($_POST['remove']);

if ($productId <= 0) {
    header("Location: wishlist.php");
    exit();
}

// Initialize wishlist if not exists
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

$wishlist = $_SESSION['wishlist'];

if ($remove) {
    // Remove item from wishlist
    foreach ($wishlist as $key => $item) {
        if (isset($item['product_id']) && (int)$item['product_id'] === $productId) {
            unset($wishlist[$key]);
            break;
        }
    }
} else {
    // Add item to wishlist (if not already present)
    $exists = false;
    foreach ($wishlist as $item) {
        if (isset($item['product_id']) && (int)$item['product_id'] === $productId) {
            $exists = true;
            break;
        }
    }
    
    if (!$exists) {
        // Get product details from database
        $stmt = $conn->prepare("SELECT name, price, image FROM products WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $wishlist[] = [
                'product_id' => $productId,
                'name' => $product['name'],
                'price' => (float)$product['price'],
                'image' => $product['image']
            ];
        }
    }
}

// Update session wishlist
$_SESSION['wishlist'] = array_values($wishlist); // Re-index array

// Redirect back to wishlist
header("Location: wishlist.php");
exit();
?>