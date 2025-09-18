<?php
session_start();

if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = isset($_POST['product_id']) && $_POST['product_id'] !== '' ? (int)$_POST['product_id'] : null;
    $name = trim($_POST['name'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $image = trim($_POST['image'] ?? '');

    // Check if item already exists
    $itemExists = false;
    $itemIndex = -1;
    
    foreach ($_SESSION['wishlist'] as $index => $item) {
        if (($productId !== null && (int)($item['product_id'] ?? 0) === $productId) || ($productId === null && ($item['name'] ?? '') === $name)) {
            $itemExists = true;
            $itemIndex = $index;
            break;
        }
    }

    if ($itemExists) {
        // Remove from wishlist
        array_splice($_SESSION['wishlist'], $itemIndex, 1);
        $action = 'removed';
    } else {
        // Add to wishlist
        $_SESSION['wishlist'][] = [
            'product_id' => $productId,
            'name' => $name,
            'price' => $price,
            'image' => $image,
        ];
        $action = 'added';
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'action' => $action, 'message' => 'Wishlist updated']);
    exit;
}

// If not POST request, return error
header('Content-Type: application/json');
echo json_encode(['success' => false, 'message' => 'Invalid request']);
exit;


