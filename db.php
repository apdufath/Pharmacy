<?php
$host = 'localhost';
$user = 'root';
$pass = '';  
$dbname = 'pharmacy_db';     
 
try { 
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch(PDOException $e) {
    if ($e->getCode() == 1049) {
        die("<div style='font-family:sans-serif; text-align:center; margin-top:50px; background:#111; color:#fff; padding:20px; border-radius:10px;'>
             <h2>Database not found!</h2>
             <p>Please run the <a href='init_db.php' style='color:#00e5ff;'>Database Initialization Script</a> first.</p>
             </div>");
    } else {
        die("Connection failed: " . $e->getMessage());
    }
}
?> 
