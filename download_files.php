<?php
// download_files.php - Create ZIP of all project files
$page_title = "Download Project Files";
include 'includes/header.php';

if (isset($_POST['download'])) {
    // Create ZIP file
    $zip = new ZipArchive();
    $zip_filename = 'local_craft_hub_website.zip';
    
    if ($zip->open($zip_filename, ZipArchive::CREATE) === TRUE) {
        // Add all PHP files
        $php_files = glob('*.php');
        foreach ($php_files as $file) {
            $zip->addFile($file, $file);
        }
        
        // Add folders recursively
        function addFolderToZip($folder, $zip, $base_path = '') {
            $files = glob($folder . '/*');
            foreach ($files as $file) {
                if (is_dir($file)) {
                    addFolderToZip($file, $zip, $base_path . basename($file) . '/');
                } else {
                    $zip->addFile($file, $base_path . basename($file));
                }
            }
        }
        
        $folders = ['admin', 'includes', 'css', 'js', 'images'];
        foreach ($folders as $folder) {
            if (is_dir($folder)) {
                addFolderToZip($folder, $zip, $folder . '/');
            }
        }
        
        $zip->close();
        
        // Download the ZIP file
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zip_filename . '"');
        header('Content-Length: ' . filesize($zip_filename));
        readfile($zip_filename);
        
        // Delete the temporary ZIP file
        unlink($zip_filename);
        exit;
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4>📥 Download Complete Website</h4>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <h5>Ready for Deployment</h5>
                    <p>Click the button below to download all your website files as a ZIP package.</p>
                </div>
                
                <form method="POST">
                    <button type="submit" name="download" class="btn btn-primary btn-lg">
                        📦 Download ZIP File
                    </button>
                </form>
                
                <div class="mt-4 alert alert-info">
                    <h6>📋 Files Included in Download:</h6>
                    <ul class="text-start">
                        <li>All PHP pages (15+ files)</li>
                        <li>Complete admin panel</li>
                        <li>Database configuration</li>
                        <li>CSS and JavaScript files</li>
                        <li>Image assets and placeholders</li>
                        <li>Documentation pages</li>
                    </ul>
                </div>
                
                <div class="mt-3">
                    <a href="deployment_guide.php" class="btn btn-outline-primary">
                        📚 View Deployment Guide
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>