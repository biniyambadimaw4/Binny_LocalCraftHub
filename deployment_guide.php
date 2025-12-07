<?php
$page_title = "Deployment Guide";
include 'includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h2>Deployment Guide - Local Craft Hub</h2>
            </div>
            <div class="card-body">
                
                <div class="alert alert-warning">
                    <h5>📋 Before Submission Checklist</h5>
                    <ul class="mb-0">
                        <li>✅ All features tested and working</li>
                        <li>✅ Database properly configured</li>
                        <li>✅ Images and files uploaded</li>
                        <li>✅ Documentation completed</li>
                        <li>✅ Code commented and organized</li>
                    </ul>
                </div>

                <h3 class="text-primary">1. Local Deployment (XAMPP)</h3>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Steps for Local Setup:</h5>
                        <ol>
                            <li>Install XAMPP on your computer</li>
                            <li>Start Apache and MySQL services</li>
                            <li>Place project folder in <code>htdocs</code> directory</li>
                            <li>Access via: <code>http://localhost/craft/</code></li>
                            <li>Import database using phpMyAdmin</li>
                            <li>Update database credentials in <code>includes/config.php</code></li>
                        </ol>
                    </div>
                </div>

                <h3 class="text-primary">2. Database Setup</h3>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>MySQL Database Configuration:</h5>
                        <pre><code>
-- Create database
CREATE DATABASE craft_hub;

-- Import SQL structure (run the SQL we created earlier)
-- This will create all necessary tables
                        </code></pre>
                        
                        <h5>Database Configuration File:</h5>
                        <pre><code>
// includes/config.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'craft_hub');
define('DB_USER', 'root');
define('DB_PASS', '');
                        </code></pre>
                    </div>
                </div>

                <h3 class="text-primary">3. File Structure</h3>
                <div class="card mb-4">
                    <div class="card-body">
                        <pre><code>
local-craft-hub/
├── index.php
├── products.php
├── cart.php
├── checkout.php
├── login.php
├── register.php
├── orders.php
├── order_success.php
├── documentation.php
├── deployment_guide.php
├── includes/
│   ├── config.php
│   ├── header.php
│   └── footer.php
├── admin/
│   ├── index.php
│   ├── products.php
│   ├── orders.php
│   ├── order_details.php
│   ├── add_product.php
│   └── edit_product.php
├── css/
│   └── style.css
├── js/
│   └── script.js
└── images/
    ├── products/
    └── placeholder.jpg
                        </code></pre>
                    </div>
                </div>

                <h3 class="text-primary">4. Testing Checklist</h3>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Functional Testing:</h5>
                        <ul>
                            <li>✅ User registration and login</li>
                            <li>✅ Product browsing and search</li>
                            <li>✅ Shopping cart operations</li>
                            <li>✅ Checkout process</li>
                            <li>✅ Order management</li>
                            <li>✅ Admin panel functionality</li>
                            <li>✅ Image upload and display</li>
                        </ul>
                        
                        <h5>Security Testing:</h5>
                        <ul>
                            <li>✅ SQL injection prevention</li>
                            <li>✅ XSS protection</li>
                            <li>✅ Session management</li>
                            <li>✅ Form validation</li>
                        </ul>
                    </div>
                </div>

                <h3 class="text-primary">5. Submission Package</h3>
                <div class="card">
                    <div class="card-body">
                        <h5>Files to Submit:</h5>
                        <ul>
                            <li><strong>Source Code:</strong> Complete project folder</li>
                            <li><strong>Database:</strong> SQL export file</li>
                            <li><strong>Documentation:</strong> Printed report</li>
                            <li><strong>Screenshots:</strong> All main pages</li>
                            <li><strong>Demo Video:</strong> Website walkthrough (optional but recommended)</li>
                        </ul>
                        
                        <div class="alert alert-info">
                            <h6>📝 Assignment Submission Note:</h6>
                            <p class="mb-0">According to your assignment instructions, you need to:</p>
                            <ul class="mt-2">
                                <li>Publish your website online (using free hosting like 000webhost, InfinityFree, etc.)</li>
                                <li>Submit both the hosted link and documentation report</li>
                                <li>Ensure all features work on the live server</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>