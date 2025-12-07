<?php
$page_title = "Project Documentation";
include 'includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h2>Local Craft Hub - E-commerce Website Documentation</h2>
                <p class="mb-0">Debark University - Department of Information Technology</p>
            </div>
            <div class="card-body">
                
                <!-- Project Overview -->
                <div class="mb-5">
                    <h3 class="text-primary">1. Project Overview</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Business Information</h5>
                            <ul>
                                <li><strong>Business Name:</strong> Local Craft Hub</li>
                                <li><strong>Mission:</strong> Connect local artisans with customers to preserve traditional crafts</li>
                                <li><strong>Target Audience:</strong> Art lovers, tourists, gift shoppers</li>
                                <li><strong>Products:</strong> Handmade pottery, textiles, woodwork, jewelry</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Technical Information</h5>
                            <ul>
                                <li><strong>Project Type:</strong> E-commerce Website</li>
                                <li><strong>Development Period:</strong> sep 29/2018-nov 1/2018</li>
                                <li><strong>Student Name:</strong> Biniyam Badimaw</li>
                                <li><strong>Student ID:</strong> DKU14_01183</li>
                                <li><strong>Course:</strong> E-commerce</li>
                                <li><strong>Year:</strong> 4th Year IT</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- System Architecture -->
                <div class="mb-5">
                    <h3 class="text-primary">2. System Architecture</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Technology Stack</h5>
                            <div class="card">
                                <div class="card-body">
                                    <h6>Frontend Technologies:</h6>
                                    <ul>
                                        <li>HTML5</li>
                                        <li>CSS3 with Bootstrap 5</li>
                                        <li>JavaScript (Vanilla)</li>
                                        <li>Responsive Design</li>
                                    </ul>
                                    
                                    <h6>Backend Technologies:</h6>
                                    <ul>
                                        <li>PHP 8.2</li>
                                        <li>MySQL Database</li>
                                        <li>Apache Web Server</li>
                                    </ul>
                                    
                                    <h6>Development Tools:</h6>
                                    <ul>
                                        <li>XAMPP Local Server</li>
                                        <li>phpMyAdmin</li>
                                        <li>Notepad++ Code Editor</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Database Schema</h5>
                            <div class="card">
                                <div class="card-body">
                                    <h6>Main Tables:</h6>
                                    <ul>
                                        <li><strong>users</strong> - User accounts & authentication</li>
                                        <li><strong>products</strong> - Product catalog</li>
                                        <li><strong>orders</strong> - Order management</li>
                                        <li><strong>order_items</strong> - Order line items</li>
                                        <li><strong>order_shipping</strong> - Shipping information</li>
                                    </ul>
                                    
                                    <h6>Key Features:</h6>
                                    <ul>
                                        <li>User registration & login</li>
                                        <li>Product search & filtering</li>
                                        <li>Shopping cart functionality</li>
                                        <li>Order processing system</li>
                                        <li>Admin panel for management</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Implemented Features -->
                <div class="mb-5">
                    <h3 class="text-primary">3. Implemented E-commerce Features</h3>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h5>🛒 Product Catalog</h5>
                                    <ul class="text-start">
                                        <li>Product listings with images</li>
                                        <li>Search functionality</li>
                                        <li>Category filtering</li>
                                        <li>Product details</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h5>👥 User System</h5>
                                    <ul class="text-start">
                                        <li>User registration</li>
                                        <li>Login/Logout system</li>
                                        <li>Session management</li>
                                        <li>Password hashing</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h5>💰 Shopping Cart</h5>
                                    <ul class="text-start">
                                        <li>Add/remove items</li>
                                        <li>Quantity updates</li>
                                        <li>Cart persistence</li>
                                        <li>Total calculation</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h5>📦 Checkout Process</h5>
                                    <ul class="text-start">
                                        <li>Shipping information</li>
                                        <li>Payment simulation</li>
                                        <li>Order confirmation</li>
                                        <li>Order history</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h5>🛠️ Admin Panel</h5>
                                    <ul class="text-start">
                                        <li>Product management</li>
                                        <li>Order management</li>
                                        <li>Image upload</li>
                                        <li>Sales statistics</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h5>🔒 Security</h5>
                                    <ul class="text-start">
                                        <li>Form validation</li>
                                        <li>SQL injection prevention</li>
                                        <li>XSS protection</li>
                                        <li>Session security</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- E-commerce Process Flow -->
                <div class="mb-5">
                    <h3 class="text-primary">4. E-commerce Process Flow</h3>
                    <div class="card">
                        <div class="card-body">
                            <ol>
                                <li><strong>Customer Registration/Login</strong> - User creates account or logs in</li>
                                <li><strong>Product Browsing</strong> - User searches and filters products</li>
                                <li><strong>Add to Cart</strong> - User selects products and adds to shopping cart</li>
                                <li><strong>Checkout Process</strong> - User provides shipping information</li>
                                <li><strong>Payment Simulation</strong> - User selects payment method (simulated)</li>
                                <li><strong>Order Confirmation</strong> - System generates order confirmation</li>
                                <li><strong>Order Management</strong> - Admin processes and tracks orders</li>
                                <li><strong>Order History</strong> - Customer views past orders</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Challenges & Solutions -->
                <div class="mb-5">
                    <h3 class="text-primary">5. Challenges & Solutions</h3>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Challenges Faced:</h5>
                                    <ul>
                                        <li>Session management and cart persistence</li>
                                        <li>Image upload and file handling</li>
                                        <li>Database relationship design</li>
                                        <li>User authentication security</li>
                                        <li>Responsive design implementation</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5>Solutions Implemented:</h5>
                                    <ul>
                                        <li>Used PHP sessions for cart data</li>
                                        <li>Implemented file validation and upload handling</li>
                                        <li>Designed normalized database schema</li>
                                        <li>Used password hashing and prepared statements</li>
                                        <li>Utilized Bootstrap for responsive layout</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Screenshots Section -->
                <div class="mb-5">
                   <h3 class="text-primary">6. 📸 Website Screenshots</h3>
                    <div class="alert alert-info">                        
                                
                                   <h3> Homepage</h3>
									<img src="home.jpg"/><br></br>
                                      <h3>Login page </h3>
										<img src="login.jpg"/>
                            
                           
                                
                                  <h3>  Checkout Process page</h3>
									<img src="checkout.jpg"/>
                                 
                                  <h3>  Admin Dashboard page</h3>
										<img src="admin board.jpg"/>
										
										 <h3>  products page</h3>
										<img src="products.jpg"/>
                            
                        </div>
                </div>

                <!-- Conclusion -->
                <div class="mb-5">
                    <h3 class="text-primary">7. Conclusion</h3>
                    <div class="card">
                        <div class="card-body">
                            <p>The Local Craft Hub e-commerce website successfully demonstrates the implementation of core e-commerce concepts including product catalog management, user authentication, shopping cart functionality, order processing, and administrative controls.</p>
                            
                            <p><strong>Learning Outcomes:</strong></p>
                            <ul>
                                <li>Full-stack web development with PHP and MySQL</li>
                                <li>E-commerce business process implementation</li>
                                <li>Database design and management</li>
                                <li>User interface design principles</li>
                                <li>Security best practices in web development</li>
                            </ul>
                            
                            <p>The project meets all assignment requirements and provides a functional e-commerce platform that could be extended with additional features in the future.</p>
                        </div>
                    </div>
                </div>

                <!-- Download Report -->
                <div class="text-center mt-4">
                    <div class="alert alert-success">
                        <h5>Assignment Report Ready</h5>
                        <p>This documentation serves as the required 1-2 page report for your assignment submission.</p>
                        <a href="javascript:window.print()" class="btn btn-primary me-2">Print Documentation</a>
                        <button onclick="downloadReport()" class="btn btn-success">Save as PDF</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
function downloadReport() {
    alert('To save as PDF: Use browser print function and select "Save as PDF" as destination');
    window.print();
}
</script>

<?php include 'includes/footer.php'; ?>