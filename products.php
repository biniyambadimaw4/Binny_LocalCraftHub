<?php
$page_title = "Products";
include 'includes/header.php';

// Handle adding to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    
    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Add product to cart or increase quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        // Get product details from database
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        
        if ($product) {
            $_SESSION['cart'][$product_id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1
            ];
        }
    }
    
    echo '<div class="alert alert-success">Product added to cart!</div>';
}

// Handle search and filter
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

// Build query
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($category) && $category != 'all') {
    $sql .= " AND category = ?";
    $params[] = $category;
}

$sql .= " ORDER BY created_at DESC";

// Get products
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Get unique categories for filter
$category_stmt = $pdo->query("SELECT DISTINCT category FROM products");
$categories = $category_stmt->fetchAll();
?>

<div class="row">
    <div class="col-md-3">
        <!-- Search and Filter Sidebar -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Search & Filter</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="">
                    <div class="mb-3">
                        <label for="search" class="form-label">Search Products</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?php echo htmlspecialchars($search); ?>" 
                               placeholder="Search products...">
                    </div>
                    
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="all">All Categories</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?php echo $cat['category']; ?>" 
                                    <?php echo ($category == $cat['category']) ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($cat['category']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    <a href="products.php" class="btn btn-outline-secondary w-100 mt-2">Clear Filters</a>
                </form>
            </div>
        </div>
        
        <!-- Cart Summary -->
        <div class="card">
            <div class="card-header">
                <h5>Cart Summary</h5>
            </div>
            <div class="card-body">
                <?php 
                $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                $cart_total = 0;
                
                if (isset($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) {
                        $cart_total += $item['price'] * $item['quantity'];
                    }
                }
                ?>
                <p>Items in cart: <strong><?php echo $cart_count; ?></strong></p>
                <p>Total: <strong>$<?php echo number_format($cart_total, 2); ?></strong></p>
                <a href="cart.php" class="btn btn-success w-100">View Cart</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <!-- Products Grid -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Our Handmade Crafts</h2>
            <div>
                <span class="text-muted">
                    Showing <?php echo count($products); ?> product(s)
                    <?php if (!empty($search)) echo "for '$search'"; ?>
                </span>
            </div>
        </div>
        
        <?php if (count($products) > 0): ?>
            <div class="row">
                <?php foreach($products as $product): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 product-card">
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
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo $product['name']; ?></h5>
                            <p class="card-text flex-grow-1">
                                <?php echo substr($product['description'], 0, 100); ?>...
                            </p>
                            <div class="mt-auto">
                                <p class="card-text">
                                    <strong class="text-primary">$<?php echo $product['price']; ?></strong>
                                </p>
                                <p class="card-text">
                                    <small class="text-muted">Category: <?php echo ucfirst($product['category']); ?></small>
                                </p>
                                
                                <form method="POST" action="">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" name="add_to_cart" class="btn btn-primary w-100">
                                        Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <h4>No products found</h4>
                <p class="text-muted">Try adjusting your search or filter criteria.</p>
                <a href="products.php" class="btn btn-primary">View All Products</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>