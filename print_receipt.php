<?php
$page_title = "Print Receipt";
include 'includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if order_id is provided
if (!isset($_GET['order_id'])) {
    header("Location: orders.php");
    exit;
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

// Get order details (same query as receipt.php)
$stmt = $pdo->prepare("
    SELECT o.*, os.*, u.name as user_name, u.email as user_email
    FROM orders o 
    LEFT JOIN order_shipping os ON o.id = os.order_id 
    LEFT JOIN users u ON o.user_id = u.id
    WHERE o.id = ? AND o.user_id = ?
");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();

if (!$order) {
    echo '<div class="alert alert-danger">Order not found.</div>';
    include 'includes/footer.php';
    exit;
}

// Get order items
$stmt = $pdo->prepare("
    SELECT oi.*, p.name, p.image, p.description 
    FROM order_items oi 
    LEFT JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll();

// Calculate totals
$subtotal = 0;
foreach($order_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Receipt - Order #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            margin: 0;
            padding: 10px;
            background: white;
        }
        .receipt {
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .underline { border-bottom: 1px dashed #000; }
        .mt-1 { margin-top: 5px; }
        .mt-2 { margin-top: 10px; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .pt-1 { padding-top: 5px; }
        .border-top { border-top: 1px dashed #000; }
        .border-bottom { border-bottom: 1px dashed #000; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 2px 0; }
        .item-name { width: 60%; }
        .item-qty { width: 15%; text-align: center; }
        .item-price { width: 25%; text-align: right; }
        
        @media print {
            body { margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .receipt { width: 100%; }
        }
    </style>
</head>
<body onload="window.print();">
    <div class="receipt">
        <!-- Store Header -->
        <div class="text-center mb-2">
            <div class="bold" style="font-size: 14px;">CRAFT HUB STORE</div>
            <div>Local Crafts & Artisanal Products</div>
            <div>--------------------------------</div>
        </div>

        <!-- Order Info -->
        <div class="mb-2">
            <table>
                <tr>
                    <td class="text-left"><strong>RECEIPT #:</strong></td>
                    <td class="text-right"><?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></td>
                </tr>
                <tr>
                    <td class="text-left"><strong>DATE:</strong></td>
                    <td class="text-right"><?php echo date('M j, Y g:i A'); ?></td>
                </tr>
            </table>
        </div>

        <!-- Customer Info -->
        <div class="mb-2">
            <div class="bold border-bottom">CUSTOMER</div>
            <div><?php echo $order['user_name']; ?></div>
            <div><?php echo $order['user_email']; ?></div>
            <?php if ($order['phone']): ?>
            <div>Tel: <?php echo $order['phone']; ?></div>
            <?php endif; ?>
        </div>

        <!-- Shipping Info -->
        <div class="mb-2">
            <div class="bold border-bottom">SHIP TO</div>
            <div><?php echo $order['full_name']; ?></div>
            <div><?php echo $order['address']; ?></div>
            <div><?php echo $order['city']; ?>, <?php echo $order['zip_code'] ?? ''; ?></div>
        </div>

        <!-- Items -->
        <div class="mb-2">
            <div class="bold border-bottom">ITEMS</div>
            <table>
                <thead>
                    <tr>
                        <th class="item-name text-left">ITEM</th>
                        <th class="item-qty">QTY</th>
                        <th class="item-price">AMOUNT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($order_items as $item): 
                        $item_total = $item['price'] * $item['quantity'];
                    ?>
                    <tr>
                        <td class="text-left"><?php echo substr($item['name'], 0, 20); ?></td>
                        <td class="text-center"><?php echo $item['quantity']; ?></td>
                        <td class="text-right">$<?php echo number_format($item_total, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="border-top pt-1 mb-2">
            <table>
                <tr>
                    <td class="text-left">SUBTOTAL:</td>
                    <td class="text-right">$<?php echo number_format($subtotal, 2); ?></td>
                </tr>
                <tr>
                    <td class="text-left">SHIPPING:</td>
                    <td class="text-right">$0.00</td>
                </tr>
                <tr class="bold">
                    <td class="text-left">TOTAL:</td>
                    <td class="text-right">$<?php echo number_format($subtotal, 2); ?></td>
                </tr>
            </table>
        </div>

        <!-- Payment Info -->
        <div class="mb-2">
            <table>
                <tr>
                    <td class="text-left"><strong>PAYMENT:</strong></td>
                    <td class="text-right">
                        <?php 
                        $payment_methods = [
                            'cod' => 'CASH',
                            'card' => 'CARD',
                            'bank' => 'BANK'
                        ];
                        echo $payment_methods[$order['payment_method']] ?? strtoupper($order['payment_method']);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-left"><strong>STATUS:</strong></td>
                    <td class="text-right"><?php echo strtoupper($order['status']); ?></td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="text-center mt-2">
            <div>Thank you for your purchase!</div>
            <div>** CRAFT HUB STORE **</div>
            <div>Support: support@crafthub.com</div>
            <div class="mt-1">Computer-generated receipt</div>
        </div>
    </div>

    <!-- Print Button (hidden when printing) -->
    <div class="no-print text-center mt-3">
        <button onclick="window.print()" class="btn btn-success">Print Receipt</button>
        <button onclick="window.close()" class="btn btn-outline-secondary">Close</button>
    </div>

    <script>
        // Auto-close after printing (optional)
        window.onafterprint = function() {
            setTimeout(function() {
                window.close();
            }, 500);
        };
    </script>
</body>
</html>