<?php
// check_files.php - Check what files exist
$files = [
    'index.php',
    'products.php', 
    'cart.php',
    'checkout.php',
    'login.php',
    'register.php',
    'orders.php',
    'order_success.php',
    'logout.php',
    'order_details.php',
    'contact.php'
];

echo "<h3>Checking files in: " . __DIR__ . "</h3>";
echo "<table border='1'>";
echo "<tr><th>File</th><th>Exists</th></tr>";

foreach ($files as $file) {
    $exists = file_exists($file) ? '✅ YES' : '❌ NO';
    echo "<tr><td>$file</td><td>$exists</td></tr>";
}

echo "</table>";
?>