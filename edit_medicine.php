<?php include 'header.php'; ?>
<?php
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: medicines.php");
    exit(); 
} 

// Handle form submission 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_medicine'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $expiry = $_POST['expiry_date'];

    $stmt = $conn->prepare("UPDATE medicines SET name=?, category=?, price=?, stock=?, expiry_date=? WHERE id=?");
    $stmt->execute([$name, $category, $price, $stock, $expiry, $id]);

    header("Location: medicines.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM medicines WHERE id=?");
$stmt->execute([$id]);
$medicine = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$medicine) {
    header("Location: medicines.php");
    exit();
}
?>

<div class="page-header">
    <h1>Edit Medicine</h1>
    <a href="medicines.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="glass" style="padding: 2rem; max-width: 600px; margin: 0 auto;">
    <h3 style="margin-bottom: 1.5rem; color: var(--primary-color);">Update Details</h3>
    <form method="POST">
        <div class="form-group">
            <label>Medicine Name</label>
            <input type="text" name="name" class="form-control"
                value="<?php echo htmlspecialchars($medicine['name']); ?>" required>
        </div>
        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" class="form-control"
                value="<?php echo htmlspecialchars($medicine['category']); ?>">
        </div>
        <div class="form-group">
            <label>Price ($)</label>
            <input type="number" step="0.01" name="price" class="form-control"
                value="<?php echo htmlspecialchars($medicine['price']); ?>" required>
        </div>
        <div class="form-group">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control"
                value="<?php echo htmlspecialchars($medicine['stock']); ?>" required>
        </div>
        <div class="form-group">
            <label>Expiry Date</label>
            <input type="date" name="expiry_date" class="form-control"
                value="<?php echo htmlspecialchars($medicine['expiry_date']); ?>" required>
        </div>
        <button type="submit" name="edit_medicine" class="btn" style="width: 100%;"><i class="fas fa-save"></i> Save
            Changes</button>
    </form>
</div>

<?php include 'footer.php'; ?>
