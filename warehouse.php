<?php include 'header.php'; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_warehouse'])) {
    $item_name = $_POST['item_name'];
    $supplier = $_POST['supplier'];
    $qty = $_POST['quantity'];
    $date = $_POST['arrival_date'];

    $stmt = $conn->prepare("INSERT INTO warehouse (item_name, supplier, quantity_received, arrival_date) VALUES (?, ?, ?, ?)");
    $stmt->execute([$item_name, $supplier, $qty, $date]);
    
    header("Location: warehouse.php");
    exit(); 
}
?>
 
<div class="page-header">
    <h1>Warehouse & Suppliers</h1>
</div>
 
<div class="flex-row"> 
    <div class="flex-col glass" style="padding: 2rem;"> 
        <h3 style="margin-bottom: 1.5rem; color: var(--primary-color);">Receive New Stock</h3>
        <form method="POST">
            <div class="form-group">
                <label>Item Name</label>
                <input type="text" name="item_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Supplier</label>
                <input type="text" name="supplier" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Quantity Received</label>
                <input type="number" name="quantity" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Arrival Date</label>
                <input type="date" name="arrival_date" class="form-control" required>
            </div>
            <button type="submit" name="add_warehouse" class="btn"><i class="fas fa-box"></i> Add to Warehouse</button>
        </form>
    </div>

    <div class="flex-col table-container glass" style="flex: 2; margin-top: 0;">
        <h3 style="padding: 15px; border-bottom: 1px solid var(--glass-border); color: var(--primary-color);">Warehouse Log</h3>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Supplier</th>
                    <th>Qty Received</th>
                    <th>Arrival Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT * FROM warehouse ORDER BY id DESC");
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                        <td><strong>{$row['item_name']}</strong></td>
                        <td>{$row['supplier']}</td>
                        <td style='color: #00ff88;'>{$row['quantity_received']}</td>
                        <td>{$row['arrival_date']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
