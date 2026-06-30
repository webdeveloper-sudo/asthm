<?php
session_start();
require_once '../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $user['username'];
        header("Location: index.php");
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
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/images/acchm-logo/ACCHM_LOGO-02.png" type="image/x-icon">
    <link rel="icon" href="../assets/images/acchm-logo/ACCHM_LOGO-02.png" type="image/png">
    
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; background-color: var(--color-light); font-family: var(--font-body); }
        .login-card { background: white; padding: 3rem; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); width: 100%; max-width: 400px; }
        .login-card h2 { font-family: var(--font-heading); color: var(--color-heading); }
        label { font-weight: 500; color: var(--color-dark); }
        @media (max-width: 480px) {
            .login-card { padding: 1.5rem; }
        }
    </style>
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1010949551430712');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=1010949551430712&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
</head>
<body>
    <div class="login-card">
        <div style="text-align: center; margin-bottom: 2rem;">
            <img src="../assets/images/downloads/cropped-image-300x300.png" alt="Logo" style="height: 60px; margin: 0 auto;">
            <h2 style="margin-top: 1rem;">Admin Login</h2>
        </div>
        
        <?php if($error): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div style="margin-bottom: 1rem;">
                <label for="username" style="display: block; margin-bottom: 0.5rem;">Username</label>
                <input type="text" id="username" name="username" style="width: 100%; padding: 0.75rem; border: 1px solid var(--color-dark); border-radius: 8px;" required>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label for="password" style="display: block; margin-bottom: 0.5rem;">Password</label>
                <input type="password" id="password" name="password" style="width: 100%; padding: 0.75rem; border: 1px solid var(--color-dark); border-radius: 8px;" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
        </form>
        <p style="text-align: center; margin-top: 1.5rem; font-size: 0.8rem; color: var(--color-text-light);">Default login: admin / admin123</p>
    </div>
</body>
</html>
