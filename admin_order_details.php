<?php
require_once 'config.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { echo '<h3>Order not found</h3>'; exit; }

$order = $conn->query("SELECT * FROM orders WHERE id = $id");
if (!$order || !$order->num_rows) { echo '<h3>Order not found</h3>'; exit; }
$order = $order->fetch_assoc();

$items = [];
$res = $conn->query("SELECT * FROM order_items WHERE order_id = $id");
if ($res) { while ($row = $res->fetch_assoc()) { $items[] = $row; } }
?>
<h3 class="text-xl font-bold mb-2">Order #<?php echo $order['id']; ?></h3>
<p class="text-sm text-gray-600 mb-4">Placed on <?php echo date('d M Y, H:i', strtotime($order['created_at'])); ?> • Status: <?php echo htmlspecialchars($order['status']); ?></p>

<table class="min-w-full">
  <thead class="bg-gray-50">
    <tr>
      <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
      <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
      <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
      <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
      <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
    </tr>
  </thead>
  <tbody class="divide-y divide-gray-200">
  <?php foreach ($items as $it): ?>
    <tr>
      <td class="px-4 py-2"><?php echo htmlspecialchars($it['product_name']); ?></td>
      <td class="px-4 py-2"><?php echo htmlspecialchars($it['size'] ?? '-'); ?></td>
      <td class="px-4 py-2"><?php echo (int)$it['quantity']; ?></td>
      <td class="px-4 py-2">Rs <?php echo number_format($it['unit_price'], 2); ?></td>
      <td class="px-4 py-2">Rs <?php echo number_format($it['unit_price'] * $it['quantity'], 2); ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<div class="mt-4 text-right font-semibold">
  Subtotal: Rs <?php echo number_format($order['subtotal'], 2); ?><br>
  Shipping: Rs <?php echo number_format($order['shipping_fee'], 2); ?><br>
  Total: Rs <?php echo number_format($order['total_amount'], 2); ?>
  </div>


