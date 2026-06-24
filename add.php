<?php
require_once 'auth.php';
requireAdmin();
require_once 'config.php';

$message = '';
$name = $description = $price = $stock = $category_id = $supplier_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = $conn->real_escape_string(trim($_POST['name'] ?? ''));
    $description = $conn->real_escape_string(trim($_POST['description'] ?? ''));
    $price       = $_POST['price'] ?? '';
    $stock       = $_POST['stock'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $supplier_id = $_POST['supplier_id'] ?? '';

    if (empty($name) || empty($category_id) || empty($supplier_id)) {
        $message = '<p style="color:red;">Name, category, and supplier are required.</p>';
    } elseif (!is_numeric($price) || (float)$price < 0) {
        $message = '<p style="color:red;">Please enter a valid price.</p>';
    } elseif (!is_numeric($stock) || (int)$stock < 0) {
        $message = '<p style="color:red;">Please enter a valid stock quantity.</p>';
    } else {
        $price_val = (float)$price;
        $stock_val = (int)$stock;
        $cat_val   = (int)$category_id;
        $sup_val   = (int)$supplier_id;

        $sql = "INSERT INTO products (name, description, price, stock, category_id, supplier_id)
                VALUES ('$name', '$description', $price_val, $stock_val, $cat_val, $sup_val)";

        if ($conn->query($sql)) {
            echo '<p style="color:green; font-size:1.2em;">Product added! Redirecting...</p>';
            header('Refresh: 2; URL=index.php');
            exit;
        } else {
            $message = '<p style="color:red;">Error: ' . $conn->error . '</p>';
        }
    }
}

$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
$suppliers  = $conn->query("SELECT id, name FROM suppliers ORDER BY name");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    
</head>
<style>
    body {
    font-family: Arial, Helvetica, sans-serif;
    margin: 0;
    background: #f4f6f9; /* WHITE-LIGHT CLEAN BACKGROUND */
    color: #2c3e50;
}

/* =========================
   NAVBAR
========================= */
.navbar {
    background: #2c3e50;
    padding: 12px;
    text-align: center;
}

.navbar a {
    color: white;
    text-decoration: none;
    margin: 0 15px;
    font-weight: bold;
}

.navbar a:hover {
    color: #1abc9c;
}

/* =========================
   INDEX CONTAINER
========================= */
.container {
    width: 95%;
    max-width: 1200px;
    margin: 20px auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
}

/* TITLE */
h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #34495e;
}

/* =========================
   FILTERS
========================= */
.filters {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 15px;
    align-items: center;
}

.filters input,
.filters select {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    flex: 1;
    min-width: 180px;
}

.filters button {
    padding: 10px 15px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    background: #3498db;
    color: white;
    font-weight: bold;
}

.filters button:hover {
    background: #2980b9;
}

/* =========================
   BUTTON LINKS
========================= */
.btn,
.btn-add,
.btn-report {
    padding: 10px 15px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    color: white;
    display: inline-block;
    text-align: center;
}

/* RESET */
.btn {
    background: #7f8c8d;
}

.btn:hover {
    background: #636e72;
}

/* ADD */
.btn-add {
    background: #27ae60;
}

.btn-add:hover {
    background: #1e8449;
}

/* REPORT */
.btn-report {
    background: #8e44ad;
}

.btn-report:hover {
    background: #6c3483;
}

/* =========================
   SUMMARY BAR
========================= */
.summary-bar {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    background: #ecf0f1;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 15px;
}

.summary-bar span {
    font-weight: bold;
    padding: 5px;
}

.summary-bar .low {
    color: #e74c3c;
}

/* =========================
   TABLE
========================= */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

table th {
    background: #2c3e50;
    color: white;
    padding: 12px;
    font-size: 14px;
}

table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    text-align: center;
    font-size: 14px;
}

tr:hover {
    background: #f2f2f2;
}

/* LOW STOCK */
.low-stock {
    background: #ffe6e6;
    color: #c0392b;
    font-weight: bold;
}

/* =========================
   ACTION BUTTONS
========================= */
.actions a {
    padding: 6px 10px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 13px;
    margin: 2px;
}

/* EDIT */
.btn-edit {
    background: #f39c12;
    color: white;
}

.btn-edit:hover {
    background: #d68910;
}

/* DELETE */
.btn-delete {
    background: #e74c3c;
    color: white;
}

.btn-delete:hover {
    background: #c0392b;
}

/* =========================
   ADD PRODUCT PAGE (WHITE BACKGROUND)
========================= */
.container.form-page {
    max-width: 500px;
    margin: 60px auto;
    background: white;
    padding: 25px 30px;
    border-radius: 15px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    animation: popIn 0.4s ease-in-out;
}

/* FORM TITLE */
.container.form-page h1 {
    text-align: center;
    margin-bottom: 20px;
}

/* LABELS */
.container.form-page label {
    font-weight: bold;
    font-size: 13px;
    color: #555;
    display: block;
    margin: 10px 0 5px;
}

/* INPUTS */
.container.form-page input,
.container.form-page textarea,
.container.form-page select {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ddd;
    outline: none;
    transition: 0.3s;
    font-size: 14px;
}

/* FOCUS */
.container.form-page input:focus,
.container.form-page textarea:focus,
.container.form-page select:focus {
    border-color: #6c63ff;
    box-shadow: 0 0 6px rgba(108, 99, 255, 0.3);
}

/* BUTTON */
.container.form-page button {
    width: 100%;
    margin-top: 15px;
    padding: 12px;
    border: none;
    border-radius: 8px;
    background: grey;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

.container.form-page button:hover {
    transform: scale(1.03);
}

/* CANCEL LINK */
.container.form-page .cancel {
    display: block;
    text-align: center;
    margin-top: 12px;
    text-decoration: none;
    color: #555;
    font-size: 13px;
}

.container.form-page .cancel:hover {
    color: #e74c3c;
}


</style>
<body>
    <div class="container form-page">
        <h1>Add Product</h1>

        <?= $message ?>

        <form method="POST" action="add.php">

            <label>Product Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required placeholder="e.g. Wireless Mouse">

            <label>Description</label>
            <textarea name="description" rows="3" placeholder="e.g. 2.4GHz cordless mouse"><?= htmlspecialchars($description) ?></textarea>

            <label>Price (₱)</label>
            <input type="number" name="price" value="<?= htmlspecialchars($price) ?>" step="0.01" min="0" required placeholder="e.g. 499.00">

            <label>Stock</label>
            <input type="number" name="stock" value="<?= htmlspecialchars($stock) ?>" min="0" required placeholder="e.g. 50">

            <label>Category</label>
            <select name="category_id" required>
                <option value="">-- Select Category --</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>"
                        <?= ($category_id == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Supplier</label>
            <select name="supplier_id" required>
                <option value="">-- Select Supplier --</option>
                <?php while ($sup = $suppliers->fetch_assoc()): ?>
                    <option value="<?= $sup['id'] ?>"
                        <?= ($supplier_id == $sup['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sup['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Add Product</button>
            <a href="index.php" class="cancel">Cancel</a>

        </form>
    </div>
</body>
</html>