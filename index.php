<?php include 'header.php'; ?>
 
<?php
// Fetch stats
try {
    $total_medicines = $conn->query("SELECT COUNT(*) FROM medicines")->fetchColumn();
    $low_stock = $conn->query("SELECT COUNT(*) FROM medicines WHERE stock < 20")->fetchColumn();
    $total_sales = $conn->query("SELECT SUM(total_price) FROM sales")->fetchColumn();
    $total_sales = $total_sales ? $total_sales : 0;
} catch (Exception $e) {
    // Graceful fallback
    $total_medicines = 0;
    $low_stock = 0;
    $total_sales = 0;
}
?>
 
<div class="page-header">
    <h1>Dashboard Overview</h1>
    <a href="sales.php" class="btn"><i class="fas fa-plus"></i> New Sale</a>
</div>

<div class="dashboard-grid">
    <div class="stat-card glass">
        <div class="stat-icon"><i class="fas fa-pills"></i></div>
        <div class="stat-value"><?php echo $total_medicines; ?></div>
        <div class="stat-label">Total Medicines</div>
    </div>
    <div class="stat-card glass" style="border-color: <?php echo $low_stock > 0 ? '#ff00cc' : 'var(--glass-border)'; ?>;">
        <div class="stat-icon"><i class="fas fa-exclamation-triangle" style="color: <?php echo $low_stock > 0 ? '#ff00cc' : 'inherit'; ?>;"></i></div>
        <div class="stat-value"><?php echo $low_stock; ?></div>
        <div class="stat-label">Low Stock Alerts</div>
    </div>
    <div class="stat-card glass">
        <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
        <div class="stat-value">$<?php echo number_format($total_sales, 2); ?></div>
        <div class="stat-label">Total Revenue</div>
    </div>
</div>

<div class="table-container glass" style="margin-top: 3rem;">
    <h3 style="padding: 15px; border-bottom: 1px solid var(--glass-border); color: var(--primary-color);">Recent Sales</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Medicine</th>
                <th>Qty</th>
                <th>Total ($)</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $stmt = $conn->query("
                    SELECT s.id, m.name as medicine_name, s.quantity, s.total_price, s.sale_date 
                    FROM sales s 
                    JOIN medicines m ON s.medicine_id = m.id 
                    ORDER BY s.sale_date DESC LIMIT 5
                ");
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                        <td>#{$row['id']}</td>
                        <td>{$row['medicine_name']}</td>
                        <td>{$row['quantity']}</td>
                        <td style='color: #00ff88; font-weight: bold;'>$" . number_format($row['total_price'], 2) . "</td>
                        <td>{$row['sale_date']}</td>
                    </tr>";
                }
            } catch (Exception $e) {}
            ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
