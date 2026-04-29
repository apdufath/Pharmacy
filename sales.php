<?php include 'header.php'; ?>
<?php
// Handle form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_sale'])) {
    $medicine_id = $_POST['medicine_id'];
    $quantity = $_POST['quantity'];

    // Get medicine details
    $stmt = $conn->prepare("SELECT price, stock FROM medicines WHERE id = ?");
    $stmt->execute([$medicine_id]);
    $medicine = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($medicine) {
        if ($medicine['stock'] >= $quantity) {
            $total_price = $medicine['price'] * $quantity;

            // Start transaction
            $conn->beginTransaction();
            try {
                // Insert sale
                $stmt = $conn->prepare("INSERT INTO sales (medicine_id, quantity, total_price) VALUES (?, ?, ?)");
                $stmt->execute([$medicine_id, $quantity, $total_price]);

                // Update stock
                $stmt = $conn->prepare("UPDATE medicines SET stock = stock - ? WHERE id = ?");
                $stmt->execute([$quantity, $medicine_id]);

                $conn->commit();
                header("Location: sales.php");
                exit();
            } catch (Exception $e) {
                $conn->rollBack();
                $error = "Transaction failed: " . $e->getMessage();
            }
        } else {
            $error = "Insufficient stock! Available: " . $medicine['stock'];
        }
    }
}
?>

<div class="page-header">
    <h1>Sales Record</h1>
</div>

<?php if ($error): ?>
    <div style="background: rgba(255,0,0,0.2); border: 1px solid red; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
    </div>
<?php endif; ?>

<div class="flex-row">
    <!-- Add Sale Form -->
    <div class="flex-col glass" style="padding: 2rem;">
        <h3 style="margin-bottom: 1.5rem; color: var(--primary-color);">Record New Sale</h3>
        <form method="POST">
            <div class="form-group">
                <label>Select Medicine</label>
                <select name="medicine_id" class="form-control" required style="appearance: auto; background: rgba(0,0,0,0.5);">
                    <option value="">-- Choose Medicine --</option>
                    <?php
                    $stmt = $conn->query("SELECT id, name, price, stock FROM medicines WHERE stock > 0 ORDER BY name ASC");
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['id']}'>{$row['name']} ($" . number_format($row['price'], 2) . ") - Stock: {$row['stock']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Quantity</label>
                <input type="number" name="quantity" class="form-control" min="1" required>
            </div>
            <button type="submit" name="add_sale" class="btn"><i class="fas fa-cart-plus"></i> Complete Sale</button>
        </form>
    </div>

    <!-- Sales Table -->
    <div class="flex-col table-container glass" style="flex: 2; margin-top: 0;">
        <h3 style="padding: 15px; border-bottom: 1px solid var(--glass-border); color: var(--primary-color);">Sales History</h3>
        <table>
            <thead>
                <tr>
                    <th>Sale ID</th>
                    <th>Medicine</th>
                    <th>Qty</th>
                    <th>Total Price</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("
                    SELECT s.id, m.name as medicine_name, s.quantity, s.total_price, s.sale_date 
                    FROM sales s 
                    JOIN medicines m ON s.medicine_id = m.id 
                    ORDER BY s.sale_date DESC
                ");
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                        <td>#{$row['id']}</td>
                        <td><strong>{$row['medicine_name']}</strong></td>
                        <td>{$row['quantity']}</td>
                        <td style='color: #00ff88; font-weight: bold;'>$" . number_format($row['total_price'], 2) . "</td>
                        <td>{$row['sale_date']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
