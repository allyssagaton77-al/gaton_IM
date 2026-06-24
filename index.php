<?php
require_once 'auth.php';
requireLogin();
require_once 'config.php';

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "SELECT p.id, p.name, p.description, p.price, p.stock, p.created_at,
               c.name AS category_name,
               s.name AS supplier_name,
               s.contact_person AS contact_name
        FROM products p
        JOIN categories c ON p.category_id = c.id
        JOIN suppliers s ON p.supplier_id = s.id
        WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (p.name LIKE '%" . $conn->real_escape_string($search) . "%'"
          . " OR p.description LIKE '%" . $conn->real_escape_string($search) . "%')";
}

if (!empty($category)) {
    $sql .= " AND c.name = '" . $conn->real_escape_string($category) . "'";
}

$sql .= " ORDER BY p.id ASC";

$result = $conn->query($sql);

$stats_sql = "SELECT COUNT(*) AS total, SUM(p.stock) AS total_stock,
                     SUM(p.price * p.stock) AS total_value,
                     SUM(CASE WHEN p.stock < 20 THEN 1 ELSE 0 END) AS low_stock
              FROM products p
              LEFT JOIN categories c ON p.category_id = c.id
              WHERE 1=1";

if (!empty($search)) {
    $stats_sql .= " AND (p.name LIKE '%" . $conn->real_escape_string($search) . "%'"
                . " OR p.description LIKE '%" . $conn->real_escape_string($search) . "%')";
}

if (!empty($category)) {
    $stats_sql .= " AND c.name = '" . $conn->real_escape_string($category) . "'";
}

$stats = $conn->query($stats_sql)->fetch_assoc();

$categories = $conn->query("SELECT DISTINCT name FROM categories ORDER BY name");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <h1>Product Inventory</h1>

    <form method="GET" class="filters">
        <input type="text" name="search" placeholder="Search by name or description..."
               value="<?= htmlspecialchars($search) ?>">

        <select name="category">
            <option value="">All Categories</option>
            <?php while ($c = $categories->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($c['name']) ?>"
                    <?= $category === $c['name'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Filter</button>

        <a href="index.php" class="btn">Reset</a>
        <?php if (isAdmin()): ?>
            <a href="add.php" class="btn-add">Add Product</a>
        <?php endif; ?>
        <a href="report.php" class="btn-report">View Report</a>
    </form>

    <div class="summary-bar">
        <span>Total Products: <strong><?= $stats['total'] ?? 0 ?></strong></span>
        <span>Total Stock: <strong><?= $stats['total_stock'] ?? 0 ?></strong></span>
        <span>Inventory Value: <strong>₱<?= number_format($stats['total_value'] ?? 0, 2) ?></strong></span>
        <span class="low">Low Stock: <strong><?= $stats['low_stock'] ?? 0 ?></strong></span>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Product Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Supplier</th>
            <th>Contact Person</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="<?= $row['stock'] < 20 ? 'low-stock' : '' ?>">
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['category_name']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td>₱<?= number_format($row['price'], 2) ?></td>
                <td><?= $row['stock'] ?></td>
                <td><?= htmlspecialchars($row['supplier_name']) ?></td>
                <td><?= htmlspecialchars($row['contact_name']) ?></td>
                <td><?= $row['created_at'] ?></td>
                <td class="actions">
                    <?php if (isAdmin()): ?>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                        <a href="delete.php?id=<?= $row['id'] ?>"
                           onclick="return confirm('Delete <?= htmlspecialchars(addslashes($row['name'])) ?>?')"
                           class="btn-delete">Delete</a>
                    <?php else: ?>
                        <span class="text-muted">N/A</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <p class="count">Showing <?= $result->num_rows ?> product(s)</p>
</div>

</body>
</html>

