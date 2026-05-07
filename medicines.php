<?php include 'header.php'; ?>
<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_medicine'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $expiry = $_POST['expiry_date'];
 
    $stmt = $conn->prepare("INSERT INTO medicines (name, category, price, stock, expiry_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $category, $price, $stock, $expiry]);
     
    // Redirect to prevent form resubmissionn
    header("Location: medicines.php");
    exit();
} 
// Handle deletion 
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM medicines WHERE id = ?"); 
    $stmt->execute([$id]);
    header("Location: medicines.php");
    exit();
}
?> 

<div class="page-header">
    <h1>Medicines Inventory</h1>
</div>

<div class="flex-row">
    <!-- Add Medicine Form -->
    <div class="flex-col glass" style="padding: 2rem;">
        <h3 style="margin-bottom: 1.5rem; color: var(--primary-color);">Add New Medicine</h3>
        <form method="POST">
            <div class="form-group">
                <label>Medicine Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category" class="form-control">
            </div>
            <div class="form-group">
                <label>Price ($)</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Initial Stock</label>
                <input type="number" name="stock" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Expiry Date</label>
                <input type="date" name="expiry_date" class="form-control" required>
            </div>
            <button type="submit" name="add_medicine" class="btn"><i class="fas fa-plus"></i> Add Medicine</button>
        </form>
    </div>

    <!-- Medicines Table -->
    <div class="flex-col table-container glass" style="flex: 2; margin-top: 0;">
        <h3 style="padding: 15px; border-bottom: 1px solid var(--glass-border); color: var(--primary-color);">Inventory List</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Expiry Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT * FROM medicines ORDER BY id DESC");
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $stockColor = $row['stock'] < 20 ? '#ff00cc' : 'inherit';
                    echo "<tr>
                        <td><strong>{$row['name']}</strong></td>
                        <td>{$row['category']}</td>
                        <td style='color: #00ff88;'>$" . number_format($row['price'], 2) . "</td>
                        <td style='color: {$stockColor}; font-weight: bold;'>{$row['stock']}</td>
                        <td>{$row['expiry_date']}</td>
                        <td>
                            <a href='edit_medicine.php?id={$row['id']}' class='btn' style='padding: 5px 15px; font-size: 0.8rem; margin-right:5px;'><i class='fas fa-edit'></i></a>
                            <a href='?delete={$row['id']}' class='btn btn-secondary' style='padding: 5px 15px; font-size: 0.8rem;' onclick='return confirm(\"Are you sure you want to delete this medicine?\");'><i class='fas fa-trash'></i></a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
