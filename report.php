<?php
require_once 'auth.php';
requireLogin();
require_once 'config.php';

$summary = $conn->query("
    SELECT
        COUNT(id)            AS total_products,
        SUM(stock)           AS total_stock,
        SUM(price * stock)   AS total_value,
        AVG(price)           AS avg_price
    FROM products
")->fetch_assoc();

$by_category = $conn->query("
    SELECT
        c.name                               AS category,
        COUNT(p.id)                          AS product_count,
        COALESCE(SUM(p.stock), 0)            AS total_stock,
        COALESCE(SUM(p.price * p.stock), 0)  AS total_value,
        COALESCE(AVG(p.price), 0)            AS avg_price
    FROM categories c
    LEFT JOIN products p ON c.id = p.category_id
    GROUP BY c.id, c.name
    ORDER BY total_value DESC
");

$by_supplier = $conn->query("
    SELECT
        s.name                      AS supplier,
        COUNT(p.id)                 AS product_count,
        COALESCE(SUM(p.stock), 0)   AS total_stock
    FROM suppliers s
    LEFT JOIN products p ON s.id = p.supplier_id
    GROUP BY s.id, s.name
    ORDER BY product_count DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Report</title>
   
</head>
<style>
    /* =========================
   REPORT PAGE DESIGN
========================= */

body {
    background: #f4f6f9;
}

/* PAGE TITLE */
h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #2c3e50;
}

/* BACK BUTTON AREA */
.nav {
    margin-bottom: 15px;
}

.nav a button {
    padding: 10px 15px;
    border: none;
    border-radius: 6px;
    background: #2c3e50;
    color: white;
    cursor: pointer;
    font-weight: bold;
}

.nav a button:hover {
    background: #1a252f;
}

/* =========================
   REPORT CARDS (TOP STATS)
========================= */
.report-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
}

.report-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    text-align: center;
    transition: 0.3s;
}

.report-card:hover {
    transform: translateY(-3px);
}

.report-card-value {
    font-size: 22px;
    font-weight: bold;
    color: grey;
}

.report-card-label {
    margin-top: 5px;
    font-size: 13px;
    color: grey;
}


.report-section {
    margin-top: 30px;
    background: white;
    padding: 20px;
    border-radius: 12px;

}

.report-heading {
    margin-bottom: 15px;
    color: grey;
    border-left: 5px solid grey;
    padding-left: 10px;
}


table {
    width: 100%;
    border-collapse: collapse;
}

table th {
    background: grey;
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

/* HOVER */
tr:hover {
    background: #f2f2f2;
}

/* NUMBER ALIGNMENT */
.num {
    text-align: center;
    font-weight: 500;
}

/* MUTED TEXT */
.muted {
    color: #bdc3c7;
    font-style: italic;
}

.count {
    margin-top: 10px;
    text-align: right;
    font-weight: bold;
    color: #555;
}

    </style>
<body>
<div class="container">

    <div class="nav">
        <a href="index.php"><button>Back</button></a>
    </div>

    <h1>Inventory Report</h1>

    <div class="report-cards">
        <div class="report-card">
            <div class="report-card-value"><?= number_format($summary['total_products']) ?></div>
            <div class="report-card-label">Total Products</div>
        </div>
        <div class="report-card">
            <div class="report-card-value"><?= number_format($summary['total_stock']) ?></div>
            <div class="report-card-label">Total Stock</div>
        </div>
        <div class="report-card">
            <div class="report-card-value">₱<?= number_format($summary['total_value'], 2) ?></div>
            <div class="report-card-label">Inventory Value</div>
        </div>
        <div class="report-card">
            <div class="report-card-value">₱<?= number_format($summary['avg_price'], 2) ?></div>
            <div class="report-card-label">Average Price</div>
        </div>
    </div>

    <div class="report-section">
        <h2 class="report-heading">By Category</h2>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="num">Products</th>
                    <th class="num">Total Stock</th>
                    <th class="num">Total Value</th>
                    <th class="num">Avg Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $by_category->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td class="num <?= $row['product_count'] == 0 ? 'muted' : '' ?>">
                        <?= $row['product_count'] ?>
                    </td>
                    <td class="num"><?= number_format($row['total_stock']) ?></td>
                    <td class="num">₱<?= number_format($row['total_value'], 2) ?></td>
                    <td class="num">
                        <?= $row['product_count'] > 0
                            ? '₱' . number_format($row['avg_price'], 2)
                            : '<span class="muted">—</span>' ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p class="count"><?= $by_category->num_rows ?> categories</p>
    </div>

    <div class="report-section">
        <h2 class="report-heading">By Supplier</h2>
        <table>
            <thead>
                <tr>
                    <th>Supplier</th>
                    <th class="num">Products</th>
                    <th class="num">Total Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $by_supplier->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['supplier']) ?></td>
                    <td class="num <?= $row['product_count'] == 0 ? 'muted' : '' ?>">
                        <?= $row['product_count'] ?>
                    </td>
                    <td class="num"><?= number_format($row['total_stock']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p class="count"><?= $by_supplier->num_rows ?> suppliers</p>
    </div>

</div>
</body>
</html>