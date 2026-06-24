<?php
require 'config.php';

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    } elseif (strlen($username) > 50) {
        $errors[] = "Username cannot exceed 50 characters.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 3) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    if (empty($confirm_password)) {
        $errors[] = "Please confirm your password.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare(
            "SELECT id FROM users WHERE username = ?"
        );
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $errors[] = "Username already exists.";
        }
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare(
            "SELECT id FROM users WHERE email = ?"
        );
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $errors[] = "Email is already registered.";
        }
    }

    if (empty($errors)) {

        $password_hash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $stmt = $pdo->prepare(
            "INSERT INTO users (username, email, password_hash)
             VALUES (?, ?, ?)"
        );

        $stmt->execute([
            $username,
            $email,
            $password_hash
        ]);

        $success = "Registration successful!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    
    <style>
        body {
    font-family: Arial, sans-serif;
    max-width: 500px;
    margin: 40px auto;
    background-color: #f4f7fc;
    padding: 20px;
}

h2 {
    text-align: center;
    color: #333;
}

form {
    background: #fff;
    padding: 20px;
    border: 1px solid #dbe3f0;
    border-radius: 12px;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
}

.error {
    color: #b00020;
    background: #ffe5e5;
    border-left: 4px solid #b00020;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
}

.success {
    color: #155724;
    background: #d4edda;
    border-left: 4px solid #155724;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
}

label {
    display: block;
    margin-top: 12px;
    margin-bottom: 5px;
    font-weight: bold;
    color: #444;
}

input {
    width: 100%;
    padding: 10px;
    border: 1px solid #cfd8e3;
    border-radius: 6px;
    box-sizing: border-box;
}

input:focus {
    outline: none;
    border-color: #4a90e2;
}

button {
    margin-top: 15px;
    padding: 10px 15px;
    width: 100%;
    border: none;
    border-radius: 6px;
    background-color: #4a90e2;
    color: white;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background-color: #357abd;
}
        </style>
</head>

<body>
<?php if (!empty($errors)): ?>
    <div class="error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="success">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<form method="POST">
    <h2>Register</h2>

    <label>Username</label>
    <input
        type="text"
        name="username"
        value="<?= htmlspecialchars($username ?? '') ?>"
    >

    <label>Email</label>
    <input
        type="email"
        name="email"
        value="<?= htmlspecialchars($email ?? '') ?>"
    >

    <label>Password</label>
    <input
        type="password"
        name="password"
    >

    <label>Confirm Password</label>
    <input
        type="password"
        name="confirm_password"
    >

    <button type="submit">
        Register
    </button>

</form>

</body>
</html>