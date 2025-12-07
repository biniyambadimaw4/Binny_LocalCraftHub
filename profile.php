<?php
$page_title = "My Profile";
include 'includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    
    $errors = [];
    
    // Validation
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($errors)) {
        // Update users table
        $stmt = $pdo->prepare("UPDATE users SET name = ? WHERE id = ?");
        if ($stmt->execute([$name, $user_id])) {
            // Update session
            $_SESSION['user_name'] = $name;
            echo '<div class="alert alert-success">Profile updated successfully!</div>';
            
            // Refresh user data
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
        } else {
            $errors[] = "Failed to update profile. Please try again.";
        }
    }
    
    // Display errors
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
    }
}
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>👤 My Profile</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                            <small class="text-muted">Email cannot be changed</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" 
                                  rows="3"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city" 
                               value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                    <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Account Info -->
        <div class="card">
            <div class="card-header">
                <h6>📊 Account Information</h6>
            </div>
            <div class="card-body">
                <?php
                // Get user statistics
                $order_stmt = $pdo->prepare("SELECT COUNT(*) as order_count FROM orders WHERE user_id = ?");
                $order_stmt->execute([$user_id]);
                $order_count = $order_stmt->fetch()['order_count'];
                ?>
                <p><strong>Member Since:</strong><br>
                <?php echo date('F Y', strtotime($user['created_at'])); ?></p>
                <p><strong>Total Orders:</strong><br>
                <?php echo $order_count; ?> orders</p>
                <p><strong>Account Type:</strong><br>
                <span class="badge bg-info"><?php echo ucfirst($user['role']); ?></span></p>
            </div>
        </div>
        
        <!-- Quick Links -->
        <div class="card mt-3">
            <div class="card-header">
                <h6>🔗 Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="orders.php" class="btn btn-outline-primary btn-sm">📦 My Orders</a>
                    <a href="products.php" class="btn btn-outline-success btn-sm">🛍️ Continue Shopping</a>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">🚪 Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>