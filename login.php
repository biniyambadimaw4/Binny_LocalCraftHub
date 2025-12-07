<?php
$page_title = "Login";
include 'includes/header.php';  // This already includes config.php
// REMOVE this line: include 'includes/config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // No need to include config.php here - it's already included in header.php
    
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    $errors = [];
    
    // Validation
    if (empty($email) || empty($password)) {
        $errors[] = "Both email and password are required";
    }
    
    if (empty($errors)) {
        // Check user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            
            echo '<div class="alert alert-success">Login successful! Redirecting...</div>';
            
            // Redirect based on user role
            if ($user['role'] == 'artisan' || $user['role'] == 'admin') {
                header("Refresh: 2; URL=admin/index.php");
            } else {
                header("Refresh: 2; URL=index.php");
            }
            exit;
        } else {
            $errors[] = "Invalid email or password";
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

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center">Login to Your Account</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo $_POST['email'] ?? ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                
                <div class="text-center mt-3">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>