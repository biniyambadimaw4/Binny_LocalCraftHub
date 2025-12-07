<?php
$page_title = "Order Receipt";
include 'includes/header.php';

// Check if order_id is provided
if (!isset($_GET['order_id'])) {
    header("Location: orders.php");
    exit;
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

// Get order details (only if it belongs to the logged-in user)
$stmt = $pdo->prepare("
    SELECT o.*, os.*, u.name as customer_name 
    FROM orders o 
    LEFT JOIN order_shipping os ON o.id = os.order_id 
    LEFT JOIN users u ON o.user_id = u.id 
    WHERE o.id = ? AND o.user_id = ?
");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();

if (!$order) {
    echo '<div class="alert alert-danger">Order not found or you do not have permission to view this order.</div>';
    include 'includes/footer.php';
    exit;
}

// Get order items
$stmt = $pdo->prepare("
    SELECT oi.*, p.name, p.description 
    FROM order_items oi 
    LEFT JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll();
?>

<!-- Print Button -->
<div class="row mb-3">
    <div class="col-12 text-end">
        <button onclick="window.print()" class="btn btn-primary">
            🖨️ Print Receipt
        </button>
        <a href="orders.php" class="btn btn-outline-secondary">← Back to Orders</a>
    </div>
</div>

<!-- Printable Receipt -->
<div class="card printable-receipt">
    <div class="card-body">
        <!-- Receipt Header -->
        <div class="text-center mb-4 border-bottom pb-3">
            <h1 class="text-primary">🏺 Local Craft Hub</h1>
            <p class="mb-1">Handmade Crafts Marketplace</p>
            <p class="text-muted small">Order Receipt</p>
        </div>

        <div class="row">
            <!-- Store Information -->
            <div class="col-md-6">
                <h6>From:</h6>
                <p class="mb-1"><strong>Local Craft Hub</strong></p>
                <p class="mb-1">123 Artisan Street</p>
                <p class="mb-1">Creative City, CC 10101</p>
                <p class="mb-1">Phone: (555) 123-CRAFT</p>
                <p class="mb-0">Email: support@localcrafthub.com</p>
            </div>

            <!-- Order Information -->
            <div class="col-md-6 text-end">
                <h6>Order Details:</h6>
                <p class="mb-1"><strong>Receipt #:</strong> <?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></p>
                <p class="mb-1"><strong>Date:</strong> <?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?></p>
                <p class="mb-1"><strong>Status:</strong> <span class="badge bg-success"><?php echo ucfirst($order['status']); ?></span></p>
                <p class="mb-0"><strong>Payment Method:</strong> 
                    <?php 
                    $payment_methods = [
                        'cod' => 'Cash on Delivery',
                        'card' => 'Credit Card',
                        'bank' => 'Bank Transfer'
                    ];
                    echo $payment_methods[$order['payment_method']] ?? $order['payment_method'];
                    ?>
                </p>
            </div>
        </div>

        <hr>

        <!-- Customer Information -->
        <div class="row mb-4">
            <div class="col-12">
                <h6>Bill To:</h6>
                <p class="mb-1"><strong><?php echo $order['full_name']; ?></strong></p>
                <p class="mb-1"><?php echo $order['address']; ?></p>
                <p class="mb-1"><?php echo $order['city']; ?></p>
                <p class="mb-1">Phone: <?php echo $order['phone']; ?></p>
                <p class="mb-0">Email: <?php echo $order['email']; ?></p>
            </div>
        </div>

        <!-- Order Items Table -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Item Description</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Unit Price</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $subtotal = 0;
                    $item_count = 1;
                    foreach($order_items as $item): 
                        $item_total = $item['price'] * $item['quantity'];
                        $subtotal += $item_total;
                    ?>
                    <tr>
                        <td><?php echo $item_count++; ?></td>
                        <td>
                            <strong><?php echo $item['name']; ?></strong>
                            <?php if (!empty($item['description'])): ?>
                                <br><small class="text-muted"><?php echo substr($item['description'], 0, 80); ?>...</small>
                            <?php endif; ?>
                        </td>
                        <td class="text-center"><?php echo $item['quantity']; ?></td>
                        <td class="text-end">$<?php echo number_format($item['price'], 2); ?></td>
                        <td class="text-end">$<?php echo number_format($item_total, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                        <td class="text-end"><strong>$<?php echo number_format($subtotal, 2); ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Shipping:</strong></td>
                        <td class="text-end"><strong>$0.00</strong></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Tax:</strong></td>
                        <td class="text-end"><strong>$0.00</strong></td>
                    </tr>
                    <tr class="table-active">
                        <td colspan="4" class="text-end"><strong>TOTAL:</strong></td>
                        <td class="text-end"><strong>$<?php echo number_format($subtotal, 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Thank You Message -->
        <div class="text-center mt-4 p-3 bg-light rounded">
            <h5 class="text-success">Thank You for Your Order!</h5>
            <p class="mb-2">We appreciate your business and hope you enjoy your handmade crafts.</p>
            <p class="mb-0 small text-muted">For any questions about your order, please contact our support team.</p>
        </div>

        <!-- Footer -->
        <div class="text-center mt-4 pt-3 border-top">
            <p class="small text-muted mb-1">
                Local Craft Hub - Connecting Artisans with Craft Lovers
            </p>
            <p class="small text-muted mb-0">
                This is an official receipt. Please keep it for your records.
            </p>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    .navbar, .btn, .breadcrumb, .no-print {
        display: none !important;
    }
    
    .printable-receipt {
        border: none !important;
        box-shadow: none !important;
    }
    
    body {
        background: white !important;
        font-size: 12pt;
    }
    
    .container {
        max-width: 100% !important;
        padding: 0 !important;
    }
    
    .card {
        border: none !important;
    }
    
    .table {
        font-size: 10pt;
    }
    
    .text-primary {
        color: #000 !important;
    }
}

@media screen {
    .printable-receipt {
        border: 1px solid #dee2e6;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
}
</style>

<?php include 'includes/footer.php'; ?>