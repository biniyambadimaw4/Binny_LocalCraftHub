<?php
$page_title = "Home";
include 'includes/header.php';

// Show logout success message
if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    echo '<div class="alert alert-success text-center">You have been successfully logged out.</div>';
}
?>

<div class="hero-section text-center py-5 bg-light rounded">
    <h1 class="display-4">Welcome to Binny Local Craft Hub</h1>
    
    <?php if(isset($_SESSION['user_id'])): ?>
        <div class="alert alert-success">
            <h5>Welcome back, <?php echo $_SESSION['user_name']; ?>! 👋</h5>
            <p class="mb-0">Ready to explore our beautiful crafts?</p>
        </div>
    <?php endif; ?>
    
    <a href="products.php" class="btn btn-primary btn-lg">Start Shopping</a>
</div>

<div class="row mt-5">
    <div class="col-md-4 mb-4">
        <div class="card h-100 text-center">
            <div class="card-body">
                <h3>🏺 Pottery</h3>
                <p>Beautiful handmade clay products with traditional designs</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100 text-center">
            <div class="card-body">
                <h3>🧵 Textiles</h3>
                <p>Traditional woven fabrics and clothing items</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100 text-center">
            <div class="card-body">
                <h3>🪵 Woodwork</h3>
                <p>Exquisite wooden carvings and furniture</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <h2 class="text-center mb-4">Featured Products</h2>
        <?php
        // Display featured products
        $stmt = $pdo->query("SELECT * FROM products LIMIT 3");
        $products = $stmt->fetchAll();
        
        if($products):
        ?>
        <div class="row">
            <?php foreach($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card product-card">
                    <?php
                    // Simple image path handling
                    $image_path = 'images/products/' . ($product['image'] ?? 'placeholder.jpg');
                    if (($product['image'] ?? 'placeholder.jpg') == 'placeholder.jpg' || !file_exists($image_path)) {
                        $image_path = 'images/placeholder.jpg';
                    }
                    ?>
                    <img src="<?php echo $image_path; ?>" 
                         class="card-img-top" 
                         alt="<?php echo $product['name']; ?>"
                         style="height: 200px; object-fit: cover;"
                         onerror="this.src='images/placeholder.jpg'">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['name']; ?></h5>
                        <p class="card-text"><?php echo substr($product['description'], 0, 100); ?>...</p>
                        <p class="card-text"><strong>$<?php echo $product['price']; ?></strong></p>
                        <form action="cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p class="text-center">No products available yet.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>