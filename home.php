<!DOCTYPE html>
<html>
<head>
    <title>Home</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 40px auto;
            background-color: #f4f7fc;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .container {
            background: #fff;
            padding: 20px;
            border: 1px solid #dbe3f0;
            border-radius: 12px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        p {
            color: #444;
            margin: 15px 0;
        }

        a {
            text-decoration: none;
        }

        .btn {
            display: block;
            margin-top: 15px;
            padding: 10px 15px;
            width: 100%;
            border: none;
            border-radius: 6px;
            background-color: #4a90e2;
            color: white;
            cursor: pointer;
            font-size: 16px;
            box-sizing: border-box;
        }

        .btn:hover {
            background-color: #357abd;
        }

        .logout {
            background-color: #357abd;
        }

        .logout:hover {
            background-color: #357abd;
        }
    </style>

</head>
<body>

<div class="container">

    <h1>Home Page</h1>

    <p>
        Welcome,
        <strong><?= htmlspecialchars(getUsername()) ?></strong>!
    </p>

    <p>
        You are successfully logged in.
    </p>

    <a href="report.php" class="btn">
        Go to Report Page
    </a>

    <a href="logout.php" class="btn logout">
        Logout
    </a>

</div>

</body>
</html>