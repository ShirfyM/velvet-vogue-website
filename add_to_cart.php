<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config.php';
    $productId = isset($_POST['product_id']) && $_POST['product_id'] !== '' ? (int)$_POST['product_id'] : null;
    $qty = max(1, (int)($_POST['qty'] ?? 1));
    $size = trim($_POST['size'] ?? 'M');

    // Lookup server-side to prevent tampering
    $name = '';
    $price = 0.0;
    $image = '';
    if ($productId !== null) {
        $stmt = $conn->prepare('SELECT name, price, image FROM products WHERE id = ?');
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $name = $row['name'];
            $price = (float)$row['price'];
            $image = $row['image'];
        }
        $stmt->close();
    }

    // Merge with existing if same product_id and same size
    $merged = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($productId !== null && (int)($item['product_id'] ?? 0) === $productId && (($item['size'] ?? '') === $size)) {
            $item['qty'] = (int)($item['qty'] ?? 1) + $qty;
            $merged = true;
            break;
        }
    }
    unset($item);

    if (!$merged) {
        $_SESSION['cart'][] = [
            'product_id' => $productId,
            'name' => $name,
            'price' => $price,
            'image' => $image,
            'qty' => $qty,
            'size' => $size,
        ];
    }
    
    // Return JSON response for AJAX requests
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Product added to cart']);
    exit;
}

// If not POST request, return error
header('Content-Type: application/json');
echo json_encode(['success' => false, 'message' => 'Invalid request']);
exit;


