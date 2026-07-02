<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

$id = $_GET['id'] ?? null;
$post = [
    'title' => '',
    'slug' => '',
    'excerpt' => '',
    'content' => '',
    'image_url' => ''
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->execute([$id]);
    $existing_post = $stmt->fetch();
    if ($existing_post) {
        $post = $existing_post;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? '';
    $slug = $_POST['slug'] ?? '';
    $excerpt = $_POST['excerpt'] ?? '';
    $content = $_POST['content'] ?? '';
    $image_url = $_POST['image_url'] ?? '';
    
    // Auto-generate slug if empty
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
    }

    if ($id) {
        $stmt = $pdo->prepare("UPDATE blog_posts SET title = ?, slug = ?, excerpt = ?, content = ?, image_url = ? WHERE id = ?");
        $stmt->execute([$title, $slug, $excerpt, $content, $image_url, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO blog_posts (title, slug, excerpt, content, image_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $excerpt, $content, $image_url]);
    }
    
    header("Location: admin_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Edit' : 'Add'; ?> Blog Post - Admin Portal</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .dashboard-header { background: var(--color-white); padding: 1.5rem 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; }
        .form-label { display: block; font-weight: 600; color: var(--color-dark); margin-bottom: 0.5rem; }
        .form-control { width: 100%; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 8px; font-family: inherit; margin-bottom: 1.5rem; background: var(--color-light); }
        .form-control:focus { outline: none; border-color: var(--color-accent-red); background: #fff; box-shadow: 0 0 0 3px rgba(205, 53, 57, 0.1); }
        @media (max-width: 680px) {
            .dashboard-header { flex-direction: column; gap: 0.8rem; padding: 1rem; text-align: center; }
            .dashboard-header div { width: auto !important; }
            main { padding: 1.5rem 1rem !important; }
            .glass-card { padding: 1.5rem !important; }
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
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-18282686971"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'AW-18282686971');
    </script>
</head>
<body style="background: #f1f5f9;">

    <header class="dashboard-header">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <a href="admin_dashboard.php" style="color: var(--color-text-light); text-decoration: none;"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>
        <div>
            <h1 style="font-size: 1.5rem; color: var(--color-dark);"><?php echo $id ? 'Edit' : 'Add New'; ?> Blog Post</h1>
        </div>
        <div style="width: 150px;"></div> <!-- Spacer -->
    </header>

    <main style="padding: 3rem 2rem; max-width: 900px; margin: 0 auto;">
        <div class="glass-card" style="padding: 3rem;">
            <form action="admin_blog_form.php<?php echo $id ? '?id='.$id : ''; ?>" method="POST">
                
                <label class="form-label">Post Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                
                <label class="form-label">URL Slug (leave blank to auto-generate)</label>
                <input type="text" name="slug" class="form-control" value="<?php echo htmlspecialchars($post['slug']); ?>">
                
                <label class="form-label">Featured Image URL</label>
                <input type="text" name="image_url" class="form-control" value="<?php echo htmlspecialchars($post['image_url']); ?>" placeholder="https://example.com/image.jpg">
                
                <label class="form-label">Excerpt (Short summary for the blog grid)</label>
                <textarea name="excerpt" rows="3" class="form-control" required><?php echo htmlspecialchars($post['excerpt']); ?></textarea>
                
                <label class="form-label">Full Content (HTML allowed)</label>
                <textarea name="content" rows="10" class="form-control" style="font-family: monospace; font-size: 0.9rem;" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                
                <div style="text-align: right; margin-top: 2rem;">
                    <a href="admin_dashboard.php" class="btn" style="color: var(--color-text-light); margin-right: 1rem;">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Post</button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
