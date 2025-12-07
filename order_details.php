<?php
$page_title = "Order Details";
include 'includes/header.php';  // This already includes config.php
// REMOVE this line: include 'includes/config.php';

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
    SELECT o.*, os.* 
    FROM orders o 
    LEFT JOIN order_shipping os ON o.id = os.order_id 
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
    SELECT oi.*, p.name, p.image, p.description 
    FROM order_items oi 
    LEFT JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll();
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Order Details #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></h2>
            <a href="orders.php" class="btn btn-outline-primary">← Back to My Orders</a>
			<!-- Add this in order_details.php after the back button -->
<a href="receipt.php?order_id=<?php echo $order['id']; ?>" class="btn btn-success">
    🧾 View Printable Receipt
</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Order Items -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Order Items</h5>
            </div>
            <div class="card-body">
                <?php if ($order_items): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $subtotal = 0;
                                foreach($order_items as $item): 
                                    $item_total = $item['price'] * $item['quantity'];
                                    $subtotal += $item_total;
                                    
                                    // Get correct image path
                                    $image_path = 'images/products/' . ($item['image'] ?? 'placeholder.jpg');
                                    if (($item['image'] ?? 'placeholder.jpg') == 'placeholder.jpg' || !file_exists($image_path)) {
                                        $image_path = 'images/placeholder.jpg';
                                    }
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo $image_path; ?>" 
                                                 alt="<?php echo $item['name']; ?>" 
                                                 style="width: 60px; height: 60px; object-fit: cover;" 
                                                 class="me-3"
                                                 onerror="this.src='images/placeholder.jpg'">
                                            <div>
                                                <h6 class="mb-0"><?php echo $item['name']; ?></h6>
                                                <small class="text-muted"><?php echo substr($item['description'], 0, 50); ?>...</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>$<?php echo number_format($item_total, 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-active">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                                    <td><strong>$<?php echo number_format($subtotal, 2); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted">No items found for this order.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Order Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Order Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Order ID:</strong> #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></p>
                <p><strong>Order Date:</strong> <?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?></p>
                <p><strong>Status:</strong> 
                    <span class="badge bg-<?php 
                        echo $order['status'] == 'completed' ? 'success' : 
                             ($order['status'] == 'cancelled' ? 'danger' : 'warning'); 
                    ?>">
                        <?php echo ucfirst($order['status']); ?>
                    </span>
                </p>
                <p><strong>Payment Method:</strong> 
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
        
        <!-- Shipping Information -->
        <div class="card">
            <div class="card-header">
                <h5>Shipping Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> <?php echo $order['full_name']; ?></p>
                <p><strong>Email:</strong> <?php echo $order['email']; ?></p>
                <p><strong>Phone:</strong> <?php echo $order['phone']; ?></p>
                <p><strong>Address:</strong> <?php echo $order['address']; ?></p>
                <p><strong>City:</strong> <?php echo $order['city']; ?></p>
                <?php if (!empty($order['zip_code'])): ?>
                    <p><strong>ZIP Code:</strong> <?php echo $order['zip_code']; ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>