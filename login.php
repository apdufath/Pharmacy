<?php
session_start();
require_once 'db.php';
 
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
} 

$error = ''; 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username']; 
    $password = $_POST['password']; 

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);   
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) { 
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid username or password!";
        }
    } catch (Exception $e) {
        $error = "Login error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Neon Pharma</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-box {
            width: 100%;
            max-width: 400px;
            padding: 3rem;
            text-align: center;
        }
        .login-box h2 {
            margin-bottom: 2rem;
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="glass login-box">
            <img src="assets/img/logo.png" alt="Logo" style="width: 80px; margin-bottom: 20px; animation: pulseGlow 2s infinite alternate; border-radius:50%;">
            <h2>Welcome Back</h2>
            
            <?php if($error): ?>
                <div style="color: #ff00cc; margin-bottom: 15px;"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group" style="text-align: left;">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group" style="text-align: left;">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn" style="width: 100%; margin-top: 15px;">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
