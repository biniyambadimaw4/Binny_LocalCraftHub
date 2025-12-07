<?php
$page_title = "Order Receipt";
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

// Get order details (only if it belongs to the logged-in user)
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
    echo '<div class="alert alert-danger">Order not found or you do not have permission to view this receipt.</div>';
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

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <!-- Receipt Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Order Receipt</h2>
            <div>
                <a href="orders.php" class="btn btn-outline-primary me-2">← Back to Orders</a>
                <button onclick="window.print()" class="btn btn-success">🖨️ Print Receipt</button>
            </div>
        </div>

        <!-- Printable Receipt -->
        <div class="card receipt-printable" id="receiptContent">
            <div class="card-body">
                <!-- Receipt Header -->
                <div class="text-center mb-4 border-bottom pb-3">
                    <h3 class="text-primary mb-1">CraftHub Store</h3>
                    <p class="text-muted mb-1">Local Crafts & Artisanal Products</p>
                    <p class="text-muted mb-0">Order Receipt</p>
                </div>

                <!-- Order & Date Info -->
                <div class="row mb-4">
                    <div class="col-6">
                        <strong>Receipt No:</strong><br>
                        #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?>
                    </div>
                    <div class="col-6 text-end">
                        <strong>Date Issued:</strong><br>
                        <?php echo date('M j, Y g:i A'); ?>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">Customer Information</h6>
                        <p class="mb-1"><strong>Name:</strong> <?php echo $order['user_name']; ?></p>
                        <p class="mb-1"><strong>Email:</strong> <?php echo $order['user_email']; ?></p>
                        <p class="mb-0"><strong>Phone:</strong> <?php echo $order['phone'] ?? 'N/A'; ?></p>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">Shipping Address</h6>
                        <p class="mb-0"><?php echo $order['full_name']; ?></p>
                        <p class="mb-0"><?php echo $order['address']; ?></p>
                        <p class="mb-0"><?php echo $order['city']; ?>, <?php echo $order['zip_code'] ?? ''; ?></p>
                    </div>
                </div>

                <!-- Order Items -->
                <h6 class="border-bottom pb-2">Order Items</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($order_items as $item): 
                                $item_total = $item['price'] * $item['quantity'];
                            ?>
                            <tr>
                                <td>
                                    <strong><?php echo $item['name']; ?></strong><br>
                                    <small class="text-muted"><?php echo substr($item['description'], 0, 60); ?>...</small>
                                </td>
                                <td class="text-center"><?php echo $item['quantity']; ?></td>
                                <td class="text-end">$<?php echo number_format($item['price'], 2); ?></td>
                                <td class="text-end">$<?php echo number_format($item_total, 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Order Summary -->
                <div class="row justify-content-end">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td><strong>Subtotal:</strong></td>
                                <td class="text-end">$<?php echo number_format($subtotal, 2); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Shipping:</strong></td>
                                <td class="text-end">$0.00</td>
                            </tr>
                            <tr class="border-top">
                                <td><strong>Total Amount:</strong></td>
                                <td class="text-end"><strong>$<?php echo number_format($subtotal, 2); ?></strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Payment & Status -->
                <div class="row mt-4">
                    <div class="col-6">
                        <strong>Payment Method:</strong><br>
                        <?php 
                        $payment_methods = [
                            'cod' => 'Cash on Delivery',
                            'card' => 'Credit Card',
                            'bank' => 'Bank Transfer'
                        ];
                        echo $payment_methods[$order['payment_method']] ?? $order['payment_method'];
                        ?>
                    </div>
                    <div class="col-6 text-end">
                        <strong>Order Status:</strong><br>
                        <span class="badge bg-<?php 
                            echo $order['status'] == 'completed' ? 'success' : 
                                 ($order['status'] == 'cancelled' ? 'danger' : 'warning'); 
                        ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-4 pt-3 border-top">
                    <p class="text-muted mb-1">Thank you for shopping with CraftHub!</p>
                    <p class="text-muted small mb-0">For questions about this receipt, contact: support@crafthub.com</p>
                    <p class="text-muted small">This is an computer-generated receipt. No signature required.</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="text-center mt-4 d-print-none">
            <button onclick="window.print()" class="btn btn-success btn-lg me-2">
                🖨️ Print Receipt
            </button>
            <a href="order_details.php?order_id=<?php echo $order_id; ?>" class="btn btn-outline-primary">
                ← Back to Order Details
            </a>
        </div>
    </div>
</div>

<style>
/* Print-specific styles */
@media print {
    body * {
        visibility: hidden;
    }
    .receipt-printable, .receipt-printable * {
        visibility: visible;
    }
    .receipt-printable {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none;
        border: 1px solid #ddd;
    }
    .btn, .d-print-none {
        display: none !important;
    }
    
    /* Improve print quality */
    .card {
        border: none !important;
    }
    .table {
        font-size: 12px;
    }
}

/* Screen styles */
.receipt-printable {
    border: 2px solid #e9ecef;
}
.table th {
    border-top: none;
    font-weight: 600;
}
</style>

<?php include 'includes/footer.php'; ?>