<?php
// export_database.php - Export your database for deployment
include 'includes/config.php';

header('Content-Type: application/sql');
header('Content-Disposition: attachment; filename="local_craft_hub.sql"');

// Get database structure and data
$tables = ['users', 'products', 'orders', 'order_items', 'order_shipping'];

foreach ($tables as $table) {
    // Get table structure
    $stmt = $pdo->query("SHOW CREATE TABLE $table");
    $create_table = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo $create_table['Create Table'] . ";\n\n";
    
    // Get table data
    $stmt = $pdo->query("SELECT * FROM $table");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($rows) > 0) {
        $columns = array_keys($rows[0]);
        $columns_str = '`' . implode('`, `', $columns) . '`';
        
        foreach ($rows as $row) {
            $values = [];
            foreach ($row as $value) {
                $values[] = is_null($value) ? 'NULL' : $pdo->quote($value);
            }
            $values_str = implode(', ', $values);
            echo "INSERT INTO `$table` ($columns_str) VALUES ($values_str);\n";
        }
        echo "\n";
    }
}

// Add some sample data for demonstration
echo "-- Sample data for demonstration\n";
echo "INSERT INTO users (name, email, password, role) VALUES \n";
echo "('Demo Admin', 'admin@demo.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artisan'),\n";
echo "('Demo Customer', 'customer@demo.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer');\n\n";

echo "INSERT INTO products (name, description, price, category, image, stock) VALUES \n";
echo "('Handmade Pottery Vase', 'Beautiful traditional clay vase', 45.99, 'pottery', 'placeholder.jpg', 5),\n";
echo "('Woven Basket', 'Handwoven natural materials', 29.99, 'textiles', 'placeholder.jpg', 8),\n";
echo "('Wooden Carving', 'Traditional wooden sculpture', 65.50, 'woodwork', 'placeholder.jpg', 3);\n";
?>