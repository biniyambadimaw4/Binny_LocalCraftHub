<?php
$page_title = "My Orders";
include 'includes/header.php';  // This already includes config.php
// REMOVE this line: include 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get user's orders
$stmt = $pdo->prepare("
    SELECT o.*, os.full_name, os.payment_method 
    FROM orders o 
    LEFT JOIN order_shipping os ON o.id = os.order_id 
    WHERE o.user_id = ? 
    ORDER BY o.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();
?>

<div class="row">
    <div class="col-12">
        <h2>My Orders</h2>
        
        <?php if (empty($orders)): ?>
            <div class="alert alert-info text-center">
                <h4>No orders yet</h4>
                <p>You haven't placed any orders yet.</p>
                <a href="products.php" class="btn btn-primary">Start Shopping</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Shipping To</th>
                            <th>Total</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($orders as $order): ?>
                        <tr>
                            <td>#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                            <td><?php echo $order['full_name']; ?></td>
                            <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td>
                                <?php 
                                $payment_methods = [
                                    'cod' => 'Cash on Delivery',
                                    'card' => 'Credit Card',
                                    'bank' => 'Bank Transfer'
                                ];
                                echo $payment_methods[$order['payment_method']] ?? $order['payment_method'];
                                ?>
                            </td>
                            <td>
                                <span class="badge 
                                    <?php 
                                    switch($order['status']) {
                                        case 'completed': echo 'bg-success'; break;
                                        case 'cancelled': echo 'bg-danger'; break;
                                        default: echo 'bg-warning';
                                    }
                                    ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </td>
                            <td>
    <div class="btn-group">
        <a href="order_details.php?order_id=<?php echo $order['id']; ?>" 
           class="btn btn-sm btn-outline-primary">View Details</a>
        <a href="receipt.php?order_id=<?php echo $order['id']; ?>" 
           class="btn btn-sm btn-outline-success">Print Receipt</a>
    </div>
</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>