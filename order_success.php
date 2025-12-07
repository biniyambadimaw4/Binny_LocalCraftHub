<?php
$page_title = "Order Confirmation";
include 'includes/header.php';  // This already includes config.php
// REMOVE this line: include 'includes/config.php';

// Check if order_id is provided
if (!isset($_GET['order_id'])) {
    header("Location: index.php");
    exit;
}

$order_id = $_GET['order_id'];

// Get order details
$stmt = $pdo->prepare("
    SELECT o.*, os.* 
    FROM orders o 
    LEFT JOIN order_shipping os ON o.id = os.order_id 
    WHERE o.id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    echo '<div class="alert alert-danger">Order not found.</div>';
    include 'includes/footer.php';
    exit;
}

// Get order items
$stmt = $pdo->prepare("
    SELECT oi.*, p.name, p.image 
    FROM order_items oi 
    LEFT JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <div class="text-success" style="font-size: 4rem;">✅</div>
                    <h1 class="text-success">Order Confirmed!</h1>
                    <p class="lead">Thank you for your purchase from Local Craft Hub</p>
                </div>
                
                <div class="row text-start mb-4">
                    <div class="col-md-6">
                        <h5>Order Details</h5>
                        <p><strong>Order ID:</strong> #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></p>
                        <p><strong>Order Date:</strong> <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                        <p><strong>Total Amount:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
                        <p><strong>Status:</strong> <span class="badge bg-warning"><?php echo ucfirst($order['status']); ?></span></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Shipping Info</h5>
                        <p><strong><?php echo $order['full_name']; ?></strong></p>
                        <p><?php echo $order['address']; ?></p>
                        <p><?php echo $order['city']; ?></p>
                        <p>Phone: <?php echo $order['phone']; ?></p>
                        <p>Email: <?php echo $order['email']; ?></p>
                    </div>
                </div>
                
                <!-- Order Items -->
                <div class="text-start">
                    <h5>Items Ordered</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($order_items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php
                                            // Get correct image path
                                            $image_path = 'images/products/' . ($item['image'] ?? 'placeholder.jpg');
                                            if (($item['image'] ?? 'placeholder.jpg') == 'placeholder.jpg' || !file_exists($image_path)) {
                                                $image_path = 'images/placeholder.jpg';
                                            }
                                            ?>
                                            <img src="<?php echo $image_path; ?>" 
                                                 alt="<?php echo $item['name']; ?>" 
                                                 style="width: 50px; height: 50px; object-fit: cover;" 
                                                 class="me-3"
                                                 onerror="this.src='images/placeholder.jpg'">
                                            <?php echo $item['name']; ?>
                                        </div>
                                    </td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="mt-4">
                    <p class="text-muted">
                        You will receive an email confirmation shortly. 
                        For any questions, please contact our support team.
                    </p>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="products.php" class="btn btn-primary me-md-2">Continue Shopping</a>
                        <a href="orders.php" class="btn btn-outline-primary">View My Orders</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>