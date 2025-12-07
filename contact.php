<?php
$page_title = "Contact Us";
include 'includes/header.php';

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    $errors = [];
    
    // Simple validation
    if (empty($name)) $errors[] = "Name is required";
    if (empty($email)) $errors[] = "Email is required";
    if (empty($subject)) $errors[] = "Subject is required";
    if (empty($message)) $errors[] = "Message is required";
    
    if (empty($errors)) {
        // In a real application, you would send an email here
        // For this demo, we'll just show a success message
        echo '<div class="alert alert-success">Thank you for your message! We will get back to you within 24 hours.</div>';
        
        // Clear form
        $name = $email = $subject = $message = '';
    } else {
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
                <h4>Send us a Message</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Your Name *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo $_POST['name'] ?? ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Your Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo $_POST['email'] ?? ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject *</label>
                        <select class="form-select" id="subject" name="subject" required>
                            <option value="">Select a subject</option>
                            <option value="general">General Inquiry</option>
                            <option value="order">Order Support</option>
                            <option value="artisan">Become an Artisan</option>
                            <option value="wholesale">Wholesale Inquiry</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Your Message *</label>
                        <textarea class="form-control" id="message" name="message" 
                                  rows="5" required><?php echo $_POST['message'] ?? ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Contact Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>📞 Contact Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Email:</strong><br>support@localcrafthub.com</p>
                <p><strong>Phone:</strong><br>+251924518765</p>
                <p><strong>Address:</strong><br>DKU<br>IT Dept</p>
                <p><strong>Business Hours:</strong><br>Monday - Friday: 9AM - 6PM<br>Saturday: 10AM - 4PM</p>
            </div>
        </div>
        
        <!-- Quick Links -->
        <div class="card">
            <div class="card-header">
                <h5>🔗 Quick Links</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><a href="about.php">About Our Mission</a></li>
                    <li><a href="products.php">Browse Products</a></li>
                    <li><a href="orders.php">Order Support</a></li>
                    <li><a href="admin/index.php">Artisan Login</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>