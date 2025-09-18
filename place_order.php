<?php
session_start();
require_once 'config.php';

function respondWithError($message) {
    http_response_code(400);
    echo "<html><body style=\"font-family: Inter, sans-serif; padding:24px;\"><h2>Order Error</h2><p>" . htmlspecialchars($message) . "</p><p><a href='index.php#payment'>Go back</a></p></body></html>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respondWithError('Invalid request method.');
}

$email = trim($_POST['email_address'] ?? '');
$fullName = trim($_POST['full_name'] ?? '');
$address = trim($_POST['address'] ?? '');
$city = trim($_POST['city'] ?? '');
$zip = trim($_POST['zip_code'] ?? '');
$contact = trim($_POST['contact_number'] ?? '');
$paymentMethod = trim($_POST['payment_method'] ?? '');
$shippingMethod = trim($_POST['shipping_method'] ?? '');

if ($email === '' || $fullName === '' || $address === '' || $city === '' || $zip === '' || $contact === '' || $paymentMethod === '' || $shippingMethod === '') {
    respondWithError('Please fill all required fields.');
}

$cartItems = $_SESSION['cart'] ?? [];
if (!is_array($cartItems)) $cartItems = [];

$subtotal = 0.0;
foreach ($cartItems as $item) {
    $qty = isset($item['qty']) ? (int)$item['qty'] : 1;
    $price = isset($item['price']) ? (float)$item['price'] : 0.0;
    $subtotal += ($price * $qty);
}
$shippingFee = ($shippingMethod === 'Express') ? 800.00 : 400.00;
$grandTotal = $subtotal + $shippingFee;

$conn->begin_transaction();
try {
    $stmt = $conn->prepare("INSERT INTO orders (user_id, email, full_name, address, city, zip_code, contact_number, payment_method, shipping_method, subtotal, shipping_fee, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    $stmt->bind_param('isssssssssdd', $userId, $email, $fullName, $address, $city, $zip, $contact, $paymentMethod, $shippingMethod, $subtotal, $shippingFee, $grandTotal);
    $stmt->execute();
    $orderId = $stmt->insert_id;
    $stmt->close();

    $orderItemsForSummary = [];
    if (!empty($cartItems)) {
        $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, unit_price, quantity, size) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($cartItems as $item) {
            $productId = isset($item['product_id']) ? (int)$item['product_id'] : null;
            $productName = $item['name'] ?? '';
            $unitPrice = isset($item['price']) ? (float)$item['price'] : 0.0;
            $quantity = isset($item['qty']) ? (int)$item['qty'] : 1;
            $size = $item['size'] ?? null;
            $stmtItem->bind_param('iisdis', $orderId, $productId, $productName, $unitPrice, $quantity, $size);
            $stmtItem->execute();
            $orderItemsForSummary[] = [
                'name' => $productName,
                'unit_price' => $unitPrice,
                'qty' => $quantity,
                'size' => $item['size'] ?? null,
                'image' => $item['image'] ?? null
            ];
        }
        $stmtItem->close();
    }

    $conn->commit();

    $_SESSION['cart'] = [];

    echo "<html><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'>";
    echo "<link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap' rel='stylesheet'>";
    echo "<link rel='stylesheet' href='assets/css/style.css'></head><body class='order-success-body'>";
    echo "<div class='order-container'>";
    echo "<div class='order-card'><h1>Order placed successfully</h1><div class='order-muted'>Thank you! Your order has been received.</div></div>";
    echo "<div class='order-grid'>";
    echo "<div class='order-card'>";
    echo "<h2 style='margin:0 0 12px;font-size:18px'>Order Summary</h2>";
    echo "<div class='order-muted' style='margin-bottom:8px'>Order ID: " . htmlspecialchars((string)$orderId) . "</div>";
    echo "<table class='order-table'><thead><tr><th>Item</th><th>Size</th><th class='order-right'>Qty</th><th class='order-right'>Unit</th><th class='order-right'>Total</th></tr></thead><tbody>";
    foreach ($orderItemsForSummary as $it) {
        $line = $it['unit_price'] * $it['qty'];
        echo "<tr>";
        echo "<td>" . htmlspecialchars($it['name']) . "</td>";
        echo "<td>" . htmlspecialchars($it['size'] ?? '-') . "</td>";
        echo "<td class='order-right'>" . (int)$it['qty'] . "</td>";
        echo "<td class='order-right'>Rs " . number_format($it['unit_price'], 2) . "</td>";
        echo "<td class='order-right'>Rs " . number_format($line, 2) . "</td>";
        echo "</tr>";
    }
    echo "</tbody><tfoot>";
    echo "<tr><td colspan='4' class='order-right' style='font-weight:600'>Subtotal</td><td class='order-right'>Rs " . number_format($subtotal, 2) . "</td></tr>";
    echo "<tr><td colspan='4' class='order-right' style='font-weight:600'>Shipping (" . htmlspecialchars($shippingMethod) . ")</td><td class='order-right'>Rs " . number_format($shippingFee, 2) . "</td></tr>";
    echo "<tr><td colspan='4' class='order-right' style='font-weight:700'>Grand Total</td><td class='order-right' style='font-weight:700'>Rs " . number_format($grandTotal, 2) . "</td></tr>";
    echo "</tfoot></table>";
    echo "</div>";

    echo "<div class='order-card'>";
    echo "<h2 style='margin:0 0 12px;font-size:18px'>Customer</h2>";
    echo "<p style='margin:0 0 4px'><strong>Name:</strong> " . htmlspecialchars($fullName) . "</p>";
    echo "<p style='margin:0 0 4px'><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
    echo "<p style='margin:0 0 4px'><strong>Contact:</strong> " . htmlspecialchars($contact) . "</p>";
    echo "<p style='margin:8px 0 4px'><strong>Shipping address</strong></p>";
    echo "<p style='margin:0 0 4px'>" . htmlspecialchars($address) . ", " . htmlspecialchars($city) . " " . htmlspecialchars($zip) . "</p>";
    echo "<p style='margin:8px 0 4px'><strong>Payment method:</strong> " . htmlspecialchars($paymentMethod) . "</p>";
    echo "<p style='margin:0 0 0'><strong>Shipping method:</strong> " . htmlspecialchars($shippingMethod) . "</p>";
    echo "</div>";
    echo "</div>";

    echo "<div class='order-card order-actions'>";
    echo "<a class='order-btn' href='my_orders.php'>View my orders</a>";
    echo "<a class='order-btn' style='background:#111827' href='collection.php'>Continue shopping</a>";
    echo "</div>";

    echo "</div></body></html>";
    exit;
} catch (Throwable $e) {
    $conn->rollback();
    respondWithError('Failed to place order. ' . $e->getMessage());
}


