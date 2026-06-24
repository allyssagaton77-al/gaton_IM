<?php
require_once 'auth.php';
requireAdmin();
require_once 'config.php';

$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($conn->query("DELETE FROM products WHERE id = $id")) {

        echo "
        <script>
            alert('Product deleted successfully.');
            window.location.href='index.php';
        </script>";
        exit;
    } else {
        die('Delete failed.');
    }
}

// Show confirmation (GET)
$result = $conn->query("
    SELECT p.*, c.name AS category
    FROM products p
    JOIN categories c ON p.category_id = c.id
    WHERE p.id = $id
");

$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}
?>

<!DOCTYPE html>

<html>
<head>
    <title>Delete Product</title>
    <style>
        body { font-family: Arial; margin: 20px; }

    .container {
        max-width: 450px;
        margin: 50px auto;
        background: white;
        padding: 30px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .warning {
        color: #34383a;
        font-size: 1.1em;
        margin: 20px 0;
    }

    .name {
        font-size: 1.3em;
        font-weight: bold;
        color: #333;
    }

    .details {
        color: #666;
        margin: 10px 0;
    }

    .btn-delete {
        padding: 12px 30px;
        background: #34383a;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1em;
    }

    .btn-delete:hover {
        background: #34383a;
    }

    .btn-cancel {
        display: inline-block;
        padding: 12px 30px;
        background: #34383a;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        margin-left: 10px;
    }
</style>

</head>
<body>

<div class="container">

<h1>Delete Product</h1>

<p>Are you sure you want to delete:</p>
<p class="name">
    <?= htmlspecialchars($product['name']) ?>
</p>
<p class="details">
    <?= htmlspecialchars($product['category']) ?>
     ₱<?= number_format($product['price'], 2) ?>
     Stock <?= $product['stock'] ?>
</p>
<p class="warning">
    This action cannot be undone.
</p>

<form method="POST" style="display: inline;">
    <button type="submit" class="btn-delete">
        Yes, Delete
    </button>
</form>

<a href="index.php" class="btn-cancel">
    Cancel
</a>

</div>

</body>
</html>
