<?php
session_start();

// Check if admin is already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

// Predefined user credentials
$users = [
    'Admin' => 'admin123',
    'HR' => 'hr123',
    'Accounting Officer' => 'account123'
];

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate username and password
    if (isset($users[$username]) && $users[$username] === $password) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_role'] = $username; // Store role for access control
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid  Password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right,rgb(117, 245, 95),rgb(183, 245, 212));
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            width: 100%;
            max-width: 500px;
            padding: 40px;
            background-color: rgb(234, 234, 197);
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .login-container h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #333;
        }

        .login-container img {
            width: 120px;
            margin-bottom: 20px;
        }

        .login-container form label {
            display: block;
            font-size: 16px;
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
            text-align: left;
        }

        .login-container form select, 
        .login-container form input {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 18px;
            box-sizing: border-box;
        }

        .login-container form button {
            width: 100%;
            padding: 15px;
            background-color: rgb(0, 115, 255);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-container form button:hover {
            background-color: rgb(0, 179, 131);
        }

        .error {
            color: #d9534f;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .login-container {
                padding: 30px;
            }
            .login-container h1 {
                font-size: 28px;
            }
            .login-container img {
                width: 100px;
            }
            .login-container form select,
            .login-container form input {
                padding: 14px;
                font-size: 16px;
            }
            .login-container form button {
                padding: 14px;
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 25px;
            }
            .login-container h1 {
                font-size: 24px;
            }
            .login-container img {
                width: 90px;
            }
            .login-container form select,
            .login-container form input {
                padding: 12px;
                font-size: 14px;
            }
            .login-container form button {
                padding: 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="images/Logo.jpg" alt="Nina Trading Logo">
        <h1>Welcome! </h1>
        <h3>Please Select Your Role & Log In</h3>
        
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <label for="username">Select Role:</label>
            <select id="username" name="username" required>
                <option value="Admin">Admin</option>
                <option value="HR">HR</option>
                <option value="Accounting Officer">Accounting Officer</option>
            </select>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Log In</button>
        </form>
    </div>
</body>
</html>

