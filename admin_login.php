<?php
session_start();
require_once 'includes/db.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $user['username'];
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - ACCHM</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background: var(--color-light); display: flex; align-items: center; justify-content: center; min-height: 100vh;">

    <div class="glass-card" style="width: 100%; max-width: 450px; text-align: center; padding: 3rem;">
        <img src="assets/images/downloads/ACHM-Logo-1.png" alt="ACCHM Logo" style="height: 50px; margin: 0 auto 2rem;">
        
        <h2 style="margin-bottom: 2rem; font-size: 1.8rem; background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Admin Portal</h2>
        
        <?php if($error): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #f5c6cb;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form action="admin_login.php" method="POST">
            <div style="margin-bottom: 1.5rem; text-align: left;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--color-text-light);">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Admin Username" required>
            </div>
            
            <div style="margin-bottom: 2rem; text-align: left;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--color-text-light);">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Secure Login <i class="fas fa-lock" style="margin-left: 10px;"></i></button>
        </form>
        
        <div style="margin-top: 2rem;">
            <a href="index.php" style="color: var(--color-text-light); font-size: 0.9rem; text-decoration: underline;"><i class="fas fa-arrow-left"></i> Back to Website</a>
        </div>
    </div>

</body>
</html>
