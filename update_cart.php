<?php
session_start();
require_once 'config.php';
require_once 'csrf_helper.php';

// Require CSRF token for all POST requests
requireCSRFToken();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cart.php");
    exit();
}

$productId = (int)($_POST['product_id'] ?? 0);
$action = $_POST['action'] ?? '';
$remove = isset($_POST['remove']);

if ($productId <= 0) {
    header("Location: cart.php");
    exit();
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];

if ($remove) {
    // Remove item from cart
    foreach ($cart as $key => $item) {
        if (isset($item['product_id']) && (int)$item['product_id'] === $productId) {
            unset($cart[$key]);
            break;
        }
    }
} else {
    // Update quantity
    foreach ($cart as $key => $item) {
        if (isset($item['product_id']) && (int)$item['product_id'] === $productId) {
            $currentQty = (int)($item['qty'] ?? 1);
            
            if ($action === 'inc') {
                $cart[$key]['qty'] = $currentQty + 1;
            } elseif ($action === 'dec') {
                $cart[$key]['qty'] = max(1, $currentQty - 1);
            }
            break;
        }
    }
}

// Update session cart
$_SESSION['cart'] = array_values($cart); // Re-index array

// Redirect back to cart
header("Location: cart.php");
exit();
?>