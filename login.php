<?php
require 'config.php';
require 'auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare(
        "SELECT * FROM users WHERE username = ?"
    );

    $stmt->execute([$username]);

    $user = $stmt->fetch();

    if (
        $user &&
        password_verify(
            $password,
            $user['password_hash']
        )
    ) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        $_SESSION['role'] =
            $user['role'] ?? 'user';

        header('Location: index.php');
        exit;
    }

    $error = "Invalid username or password.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: #eef2ff;
        }

        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            padding: 36px 32px;
            border-radius: 18px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
            border: 1px solid rgba(15, 23, 42, 0.06);
        }

        .auth-card h2 {
            margin-bottom: 22px;
            font-size: 28px;
            color: #111827;
            text-align: center;
        }

        .auth-form label {
            display: block;
            margin-bottom: 10px;
            color: #475569;
            font-weight: 600;
            font-size: 14px;
        }

        .auth-form input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            background: #f8fafc;
            color: #0f172a;
            font-size: 15px;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            margin-bottom: 16px;
        }

        .auth-form input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .auth-form button {
            width: 100%;
            margin-top: 16px;
            padding: 14px 16px;
            border: none;
            border-radius: 12px;
            background: #2563eb;
            color: white;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .auth-form button:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }

        .auth-help {
            margin-top: 18px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }

        .auth-help a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .auth-help a:hover {
            text-decoration: underline;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #f87171;
            padding: 14px 16px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="auth-page">
    <div class="auth-card">
        <?php if ($error): ?>
            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            <h2>Login</h2>

            <label>Username</label>
            <input
                type="text"
                name="username"
                required
            >

            <label>Password</label>
            <input
                type="password"
                name="password"
                required
            >

            <button type="submit">
                Login
            </button>

            <p class="auth-help">
                Don't have an account?
                <a href="register.php">Register</a>
            </p>
        </form>
    </div>
</div>

</body>
</html>