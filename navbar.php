<div class="navbar">
    <a href="index.php">Products</a>
    <a href="add.php">Add Product</a>
    <a href="report.php">Reports</a>
    <span class="nav-user">Hi, <?= htmlspecialchars($_SESSION['username'] ?? 'Guest') ?></span>
    <span class="nav-role">Role: <?= htmlspecialchars(ucfirst($_SESSION['role'] ?? 'staff')) ?></span>
    <a href="logout.php" class="logout">Logout</a>
</div>