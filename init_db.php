<?php
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
   
    // Create databasee
    $sql = "CREATE DATABASE IF NOT EXISTS pharmacy_db";
    $pdo->exec($sql);
    echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px; background:#1e1e2f; color:#fff; padding:40px; border-radius:15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); max-width: 600px; margin-left: auto; margin-right: auto;'>";
    echo "<h2 style='color: #00e5ff;'>Database Initialization</h2>";
    echo "<p style='color: #a0a0b0;'>Database created successfully ✓</p>";
 
    $pdo->exec("USE pharmacy_db");
 
    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(20) DEFAULT 'admin'  
    )");
    
    // Insert default admin (password: admin123)
    $checkUser = $pdo->query("SELECT count(*) FROM users")->fetchColumn();
    if ($checkUser == 0) {
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO users (username, password) VALUES ('admin', '$hash')");
    }
    echo "<p style='color: #a0a0b0;'>Table 'users' ready ✓</p>";

    // Create medicines table
    $sql_medicines = "CREATE TABLE IF NOT EXISTS medicines (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        category VARCHAR(50),
        price DECIMAL(10,2) NOT NULL,
        stock INT(11) NOT NULL,
        expiry_date DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql_medicines);
    echo "<p style='color: #a0a0b0;'>Table 'medicines' ready ✓</p>";

    // Create sales table
    $sql_sales = "CREATE TABLE IF NOT EXISTS sales (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        medicine_id INT(11),
        quantity INT(11) NOT NULL,
        total_price DECIMAL(10,2) NOT NULL,
        sale_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (medicine_id) REFERENCES medicines(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql_sales);
    echo "<p style='color: #a0a0b0;'>Table 'sales' ready ✓</p>";

    // Create warehouse table
    $pdo->exec("CREATE TABLE IF NOT EXISTS warehouse (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        item_name VARCHAR(100) NOT NULL,
        supplier VARCHAR(100),
        quantity_received INT(11) NOT NULL,
        arrival_date DATE
    )");
    echo "<p style='color: #a0a0b0;'>Table 'warehouse' ready ✓</p>";

    // Create messages table (Chat)
    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        sender VARCHAR(50) NOT NULL,
        message TEXT NOT NULL,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "<p style='color: #a0a0b0;'>Table 'messages' ready ✓</p>";

    // Create videos table
    $pdo->exec("CREATE TABLE IF NOT EXISTS videos (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        url VARCHAR(255) NOT NULL,
        added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "<p style='color: #a0a0b0;'>Table 'videos' ready ✓</p>";

    // Insert dummy data (10 Examples)
    $check = $pdo->query("SELECT count(*) FROM medicines")->fetchColumn();
    if ($check == 0) {
        $pdo->exec("INSERT INTO medicines (name, category, price, stock, expiry_date) VALUES 
            ('Paracetamol 500mg', 'Painkiller', 5.00, 100, '2025-12-31'),
            ('Amoxicillin 250mg', 'Antibiotic', 15.50, 50, '2026-06-30'),
            ('Ibuprofen 400mg', 'Painkiller', 8.00, 75, '2025-10-15'),
            ('Vitamin C 1000mg', 'Supplement', 12.00, 200, '2026-01-01'),
            ('Cetirizine 10mg', 'Antihistamine', 6.50, 120, '2025-08-20'),
            ('Omeprazole 20mg', 'Antacid', 18.00, 40, '2026-11-10'),
            ('Metformin 500mg', 'Diabetes', 10.00, 90, '2025-05-05'),
            ('Aspirin 81mg', 'Painkiller', 4.50, 150, '2026-09-30'),
            ('Loratadine 10mg', 'Antihistamine', 7.00, 85, '2026-04-12'),
            ('Azithromycin 250mg', 'Antibiotic', 22.00, 30, '2025-07-25')
        ");
        echo "<p style='color: #a0a0b0;'>10 Dummy medicines inserted ✓</p>";
    }

    echo "<h3 style='color: #00ff88; margin-top:30px;'>Initialization Complete!</h3>";
    echo "<p style='color: #ff00cc;'>Default Login: admin / admin123</p>";
    echo "<a href='login.php' style='display:inline-block; padding: 10px 20px; background: linear-gradient(45deg, #ff00cc, #333399); color: white; text-decoration: none; border-radius: 25px; margin-top: 15px; font-weight: bold;'>Go to Login</a>";
    echo "</div>";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
