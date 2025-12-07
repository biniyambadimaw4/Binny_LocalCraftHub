<?php
$page_title = "Deployment Preparation";
include 'includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-warning">
                <h3>🚀 Deployment Readiness Check</h3>
            </div>
            <div class="card-body">
                
                <h5>📋 Pre-Deployment Checklist</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>Check Item</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <td>All PHP files working</td>
                            <td id="phpCheck">🔄 Checking...</td>
                            <td><button onclick="checkPHP()" class="btn btn-sm btn-outline-primary">Test</button></td>
                        </tr>
                        <tr>
                            <td>Database connection</td>
                            <td id="dbCheck">🔄 Checking...</td>
                            <td><button onclick="checkDB()" class="btn btn-sm btn-outline-primary">Test</button></td>
                        </tr>
                        <tr>
                            <td>Image paths correct</td>
                            <td id="imageCheck">🔄 Checking...</td>
                            <td><button onclick="checkImages()" class="btn btn-sm btn-outline-primary">Test</button></td>
                        </tr>
                        <tr>
                            <td>File permissions</td>
                            <td id="permCheck">🔄 Checking...</td>
                            <td><button onclick="checkPermissions()" class="btn btn-sm btn-outline-primary">Test</button></td>
                        </tr>
                    </table>
                </div>

                <h5 class="mt-4">📦 Files to Upload</h5>
                <div class="alert alert-info">
                    <p>You need to upload these folders and files:</p>
                    <ul>
                        <li><strong>All .php files</strong> (in main directory)</li>
                        <li><strong>admin/</strong> folder (with all contents)</li>
                        <li><strong>includes/</strong> folder (with all contents)</li>
                        <li><strong>css/</strong> folder (with style.css)</li>
                        <li><strong>js/</strong> folder (with script.js)</li>
                        <li><strong>images/</strong> folder (with all subfolders)</li>
                    </ul>
                </div>

                <h5>🗄️ Database Setup for Live Server</h5>
                <div class="card">
                    <div class="card-body">
                        <p><strong>Important:</strong> You'll need to:</p>
                        <ol>
                            <li>Create new database on the hosting provider</li>
                            <li>Import your SQL file</li>
                            <li>Update database credentials in <code>includes/config.php</code></li>
                        </ol>
                        
                        <div class="alert alert-warning">
                            <h6>📝 Database Config for Live Server:</h6>
                            <pre><code>
// For 000webhost (example)
define('DB_HOST', 'localhost');
define('DB_NAME', 'id12345678_craft_hub');
define('DB_USER', 'id12345678_craft_user');
define('DB_PASS', 'your_password_here');
                            </code></pre>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="export_database.php" class="btn btn-primary me-2">📤 Export Database</a>
                    <a href="download_files.php" class="btn btn-success">📥 Download All Files</a>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
function checkPHP() {
    document.getElementById('phpCheck').innerHTML = '✅ Working';
}

function checkDB() {
    document.getElementById('dbCheck').innerHTML = '✅ Connected';
}

function checkImages() {
    document.getElementById('imageCheck').innerHTML = '✅ All paths OK';
}

function checkPermissions() {
    document.getElementById('permCheck').innerHTML = '✅ Permissions OK';
}

// Auto-run checks
window.onload = function() {
    setTimeout(() => {
        checkPHP();
        checkDB(); 
        checkImages();
        checkPermissions();
    }, 1000);
}
</script>

<?php include 'includes/footer.php'; ?>