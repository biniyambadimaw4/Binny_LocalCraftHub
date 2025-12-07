<?php
$page_title = "Welcome to Binny Local_Craft_Hub!";
include 'includes/header.php';

// Check if user is newly registered (first visit)
$is_new_user = !isset($_SESSION['welcome_shown']);
$_SESSION['welcome_shown'] = true;

// Get user stats
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT COUNT(*) as product_count FROM products");
$stmt->execute();
$stats = $stmt->fetch();
?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card border-success">
            <div class="card-header bg-success text-white text-center">
                <h2 class="mb-0">🎉 Welcome to Binny Local_Craft_Hub!</h2>
                <p class="mb-0">We're thrilled to have you, <?php echo $_SESSION['user_name']; ?>!</p>
            </div>
            <div class="card-body">
                
                <?php if ($is_new_user): ?>
                <div class="alert alert-info text-center">
                    <h5>✨ Your Account is Ready!</h5>
                    <p class="mb-0">We've sent a welcome email to <?php echo $_SESSION['user_email']; ?></p>
                </div>
                <?php endif; ?>

                <!-- Quick Start Guide -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h4 class="text-primary">🚀 Get Started in 3 Easy Steps</h4>
                    </div>
                </div>

                <div class="row text-center">
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-primary">
                            <div class="card-body">
                                <div class="display-4 mb-3">1</div>
                                <h5>Complete Your Profile</h5>
                                <p class="text-muted">Add your details for faster checkout</p>
                                <a href="profile.php" class="btn btn-outline-primary">Setup Profile</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-warning">
                            <div class="card-body">
                                <div class="display-4 mb-3">2</div>
                                <h5>Explore Products</h5>
                                <p class="text-muted">Discover <?php echo $stats['product_count']; ?>+ handmade items</p>
                                <a href="products.php" class="btn btn-outline-warning">Browse Crafts</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-success">
                            <div class="card-body">
                                <div class="display-4 mb-3">3</div>
                                <h5>Start Shopping</h5>
                                <p class="text-muted">Add items to cart and checkout securely</p>
                                <a href="products.php" class="btn btn-outline-success">Start Shopping</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features Grid -->
                <div class="row mt-5">
                    <div class="col-12">
                        <h4 class="text-primary">💫 What You Can Do Now</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <span class="fs-4">🛍️</span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6>Shop Handmade Products</h6>
                                <p class="text-muted small">Browse unique crafts from local artisans</p>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <span class="fs-4">❤️</span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6>Save Favorites</h6>
                                <p class="text-muted small">Create your wishlist of favorite items</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <span class="fs-4">📦</span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6>Track Orders</h6>
                                <p class="text-muted small">Monitor your purchases and delivery</p>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <span class="fs-4">⭐</span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6>Leave Reviews</h6>
                                <p class="text-muted small">Share your experience with artisans</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5>Ready to Explore?</h5>
                                <p class="text-muted">Start your journey with Binny Local_Craft_Hub</p>
                                <div class="d-flex justify-content-center gap-3 flex-wrap">
                                    <a href="products.php" class="btn btn-primary">Browse All Products</a>
                                    <a href="profile.php" class="btn btn-outline-primary">Complete Profile</a>
                                    <a href="about.php" class="btn btn-outline-secondary">Learn About Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>