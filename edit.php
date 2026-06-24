<?php
require_once 'auth.php';
requireAdmin();
require_once 'config.php';

$id = (int)($_GET['id'] ?? 0);
$result = $conn->query("SELECT * FROM products WHERE id = $id");
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

$message = '';

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

        $sql = "UPDATE products SET
                name='$name', description='$description', price=$price_val,
                stock=$stock_val, category_id=$cat_val, supplier_id=$sup_val
                WHERE id=$id";

        if ($conn->query($sql)) {
            header('Location: index.php');
            exit;
        } else {
            $message = '<p style="color:red;">Error: ' . $conn->error . '</p>';
        }
    }
} else {
    // Pre-fill from existing product
    $name        = $product['name'];
    $description = $product['description'];
    $price       = $product['price'];
    $stock       = $product['stock'];
    $category_id = $product['category_id'];
    $supplier_id = $product['supplier_id'];
}

$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
$suppliers  = $conn->query("SELECT id, name FROM suppliers ORDER BY name");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container form-page">
        <h1>Edit Product #<?= $product['id'] ?></h1>

        <?= $message ?>

        <form method="POST" action="edit.php?id=<?= $id ?>">

            <label>Product Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required>

            <label>Description</label>
            <textarea name="description" rows="3"><?= htmlspecialchars($description) ?></textarea>

            <label>Price (₱)</label>
            <input type="number" name="price" value="<?= htmlspecialchars($price) ?>" step="0.01" min="0" required>

            <label>Stock</label>
            <input type="number" name="stock" value="<?= htmlspecialchars($stock) ?>" min="0" required>

            <label>Category</label>
            <select name="category_id" required>
                <option value="">-- Select Category --</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>"
                        <?= ($cat['id'] == $category_id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Supplier</label>
            <select name="supplier_id" required>
                <option value="">-- Select Supplier --</option>
                <?php while ($sup = $suppliers->fetch_assoc()): ?>
                    <option value="<?= $sup['id'] ?>"
                        <?= ($sup['id'] == $supplier_id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sup['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Update Product</button>
            <a href="index.php" class="cancel">Cancel</a>

        </form>
    </div>
</body>
</html>