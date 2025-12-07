<?php
$page_title = "Checkout";
include 'includes/header.php';  // This already includes config.php
// REMOVE this line: include 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $zip_code = trim($_POST['zip_code']);
    $payment_method = $_POST['payment_method'];
    
    $errors = [];
    
    // Validation
    if (empty($name)) $errors[] = "Full name is required";
    if (empty($email)) $errors[] = "Email is required";
    if (empty($phone)) $errors[] = "Phone number is required";
    if (empty($address)) $errors[] = "Address is required";
    if (empty($city)) $errors[] = "City is required";
    if (empty($payment_method)) $errors[] = "Payment method is required";
    
    if (empty($errors)) {
        try {
            $pdo->beginTransaction();
            
            // Calculate total
            $total_amount = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total_amount += $item['price'] * $item['quantity'];
            }
            
            // Create order
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')");
            $stmt->execute([$_SESSION['user_id'], $total_amount]);
            $order_id = $pdo->lastInsertId();
            
            // Add order items
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            
            foreach ($_SESSION['cart'] as $product_id => $item) {
                $stmt->execute([$order_id, $product_id, $item['quantity'], $item['price']]);
            }
            
            // Add shipping address
            $stmt = $pdo->prepare("INSERT INTO order_shipping (order_id, full_name, email, phone, address, city, zip_code, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$order_id, $name, $email, $phone, $address, $city, $zip_code, $payment_method]);
            
            $pdo->commit();
            
            // Clear cart and show success
            unset($_SESSION['cart']);
            
            // Show success page
            header("Location: order_success.php?order_id=" . $order_id);
            exit;
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = "Order failed: " . $e->getMessage();
        }
    }
    
    // Display errors
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
    }
}

// Calculate cart total
$cart_total = 0;
$cart_items = $_SESSION['cart'];
foreach ($cart_items as $item) {
    $cart_total += $item['price'] * $item['quantity'];
}
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Shipping Information</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo $_SESSION['user_name'] ?? ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo $_SESSION['user_email'] ?? ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number *</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Shipping Address *</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City *</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="zip_code" class="form-label">ZIP Code</label>
                            <input type="text" class="form-control" id="zip_code" name="zip_code">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Payment Method *</label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="cod" value="cod" checked>
                                <label class="form-check-label" for="cod">
                                    💵 Cash on Delivery
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="card" value="card">
                                <label class="form-check-label" for="card">
                                    💳 Credit/Debit Card (Simulation)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="bank" value="bank">
                                <label class="form-check-label" for="bank">
                                    🏦 Bank Transfer
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">Place Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Order Summary -->
        <div class="card">
            <div class="card-header">
                <h5>Order Summary</h5>
            </div>
            <div class="card-body">
                <?php foreach($cart_items as $product_id => $item): 
                    $item_total = $item['price'] * $item['quantity'];
                ?>
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        <strong><?php echo $item['name']; ?></strong>
                        <br>
                        <small class="text-muted">Qty: <?php echo $item['quantity']; ?> × $<?php echo $item['price']; ?></small>
                    </div>
                    <div>$<?php echo number_format($item_total, 2); ?></div>
                </div>
                <?php endforeach; ?>
                
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total:</strong>
                    <strong>$<?php echo number_format($cart_total, 2); ?></strong>
                </div>
                
                <div class="mt-3">
                    <a href="cart.php" class="btn btn-outline-primary w-100">Edit Cart</a>
                </div>
            </div>
        </div>
        
        <!-- Security Notice -->
        <div class="card mt-3">
            <div class="card-body">
                <h6>🔒 Secure Checkout</h6>
                <small class="text-muted">
                    This is a simulation for educational purposes. No real payments are processed.
                </small>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>