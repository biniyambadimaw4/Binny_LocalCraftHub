<?php
$page_title = "Shopping Cart";
include 'includes/header.php';

// Handle quantity updates
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    if ($quantity <= 0) {
        unset($_SESSION['cart'][$product_id]);
    } else {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Handle item removal
if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
}

// Handle clear cart
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
}

$cart_items = $_SESSION['cart'] ?? [];
$total = 0;
?>

<div class="row">
    <div class="col-12">
        <h2>Shopping Cart</h2>
        
        <?php if (empty($cart_items)): ?>
            <div class="alert alert-info text-center">
                <h4>Your cart is empty</h4>
                <p>Start shopping to add some beautiful crafts to your cart!</p>
                <a href="products.php" class="btn btn-primary">Browse Products</a>
            </div>
        <?php else: ?>
            <!-- Cart Actions -->
            <div class="d-flex justify-content-between mb-3">
                <span class="h5"><?php echo count($cart_items); ?> item(s) in cart</span>
                <form method="POST">
                    <button type="submit" name="clear_cart" class="btn btn-outline-danger btn-sm" 
                            onclick="return confirm('Are you sure you want to clear your cart?')">
                        Clear Cart
                    </button>
                </form>
            </div>
            
            <!-- Cart Items -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cart_items as $product_id => $item): 
                            $item_total = $item['price'] * $item['quantity'];
                            $total += $item_total;
                            
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
                                    </div>
                                </div>
                            </td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td style="width: 150px;">
                                <form method="POST" class="d-flex">
                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                    <input type="number" name="quantity" 
                                           value="<?php echo $item['quantity']; ?>" 
                                           min="1" max="10" class="form-control form-control-sm">
                                    <button type="submit" name="update_quantity" 
                                            class="btn btn-sm btn-outline-primary ms-1">
                                        Update
                                    </button>
                                </form>
                            </td>
                            <td>$<?php echo number_format($item_total, 2); ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                    <button type="submit" name="remove_item" 
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Remove this item from cart?')">
                                        Remove
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-active">
                        <tr>
                            <td colspan="3" class="text-end"><strong>Grand Total:</strong></td>
                            <td colspan="2"><strong>$<?php echo number_format($total, 2); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Checkout Section -->
            <div class="row mt-4">
                <div class="col-md-8">
                    <a href="products.php" class="btn btn-outline-primary">Continue Shopping</a>
                </div>
                <div class="col-md-4 text-end">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="checkout.php" class="btn btn-success btn-lg">Proceed to Checkout</a>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <p class="mb-2">Please login to checkout</p>
                            <a href="login.php" class="btn btn-primary btn-sm">Login</a>
                            <a href="register.php" class="btn btn-outline-primary btn-sm">Register</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>