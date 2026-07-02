<?php
session_start();
require_once 'includes/db.php';

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Fetch Admissions
$stmt = $pdo->query("SELECT a.*, c.title as course_title 
                     FROM admissions a 
                     LEFT JOIN courses c ON a.course_id = c.id 
                     ORDER BY a.created_at DESC");
$admissions = $stmt->fetchAll();

// Fetch Inquiries
$stmt2 = $pdo->query("SELECT * FROM contact_inquiries ORDER BY created_at DESC");
$inquiries = $stmt2->fetchAll();

// Fetch Blog Posts
$stmt3 = $pdo->query("SELECT * FROM blog_posts ORDER BY published_at DESC");
$blog_posts = $stmt3->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ACCHM</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .dashboard-header {
            background: var(--color-white);
            padding: 1.5rem 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .tab-btn {
            background: none;
            border: none;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--color-text-light);
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: var(--transition-smooth);
        }
        .tab-btn.active {
            color: var(--color-accent-red);
            border-bottom-color: var(--color-accent-red);
        }
        .tab-content {
            display: none;
            animation: fadeIn 0.4s;
        }
        .tab-content.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--color-white);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        }
        .data-table th, .data-table td {
            padding: 1.25rem 1.5rem;
            text-align: left;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .data-table th {
            background: #f8fafc;
            font-weight: 600;
            color: var(--color-dark);
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:hover td { background: #fdfdfd; }
        .badge {
            background: #e2e8f0;
            color: #475569;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
                align-items: center;
                text-align: center;
            }
            .dashboard-header h1 {
                font-size: 1.25rem !important;
                margin-left: 0.5rem !important;
                padding-left: 0.5rem !important;
            }
            main {
                padding: 1.5rem 1rem !important;
            }
            .tab-btn {
                padding: 0.75rem 1rem;
                font-size: 0.95rem;
                width: 100%;
                text-align: left;
            }
            div[style*="display: flex; justify-content: space-between; align-items: center;"] {
                flex-direction: column;
                align-items: stretch !important;
                gap: 1rem;
            }
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
<body style="background: var(--color-light);">

    <header class="dashboard-header">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <img src="assets/images/downloads/ACHM-Logo-1.png" alt="Logo" style="height: 40px;">
            <h1 style="font-size: 1.5rem; margin-left: 1rem; padding-left: 1rem; border-left: 2px solid #eee;">Admin Portal</h1>
        </div>
        <div style="display: flex; align-items: center; gap: 2rem;">
            <span style="font-weight: 500;">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            <a href="admin_logout.php" class="btn btn-primary" style="padding: 0.5rem 1.5rem; font-size: 0.9rem;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </header>

    <main style="padding: 3rem 2rem; max-width: 1400px; margin: 0 auto;">
        
        <div style="margin-bottom: 2rem; border-bottom: 1px solid rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <button class="tab-btn active" onclick="switchTab('admissions')"><i class="fas fa-user-graduate"></i> Admissions (<?php echo count($admissions); ?>)</button>
                <button class="tab-btn" onclick="switchTab('inquiries')"><i class="fas fa-envelope"></i> Contact Inquiries (<?php echo count($inquiries); ?>)</button>
                <button class="tab-btn" onclick="switchTab('blog')"><i class="fas fa-newspaper"></i> Blog Posts (<?php echo count($blog_posts); ?>)</button>
            </div>
            <div>
                <a href="admin_blog_form.php" class="btn btn-outline" style="color: var(--color-dark); border-color: var(--color-dark); padding: 0.5rem 1rem; font-size: 0.9rem;"><i class="fas fa-plus"></i> New Blog Post</a>
            </div>
        </div>

        <!-- Admissions Tab -->
        <div id="admissions" class="tab-content active">
            <?php if (empty($admissions)): ?>
                <div class="glass-card" style="text-align: center; padding: 4rem;">
                    <i class="fas fa-folder-open" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--color-text-light);">No admission applications yet.</h3>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Applicant Name</th>
                                <th>Contact Info</th>
                                <th>Program Selected</th>
                                <th>Previous Education</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($admissions as $row): ?>
                                <tr>
                                    <td style="white-space: nowrap; color: var(--color-text-light); font-size: 0.9rem;">
                                        <?php echo date('M d, Y', strtotime($row['created_at'])); ?><br>
                                        <small><?php echo date('h:i A', strtotime($row['created_at'])); ?></small>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></strong>
                                    </td>
                                    <td>
                                        <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" style="color: var(--color-accent-red); text-decoration: underline;"><?php echo htmlspecialchars($row['email']); ?></a><br>
                                        <a href="tel:<?php echo htmlspecialchars($row['phone']); ?>" style="color: var(--color-text);"><?php echo htmlspecialchars($row['phone']); ?></a>
                                    </td>
                                    <td><span class="badge"><?php echo htmlspecialchars($row['course_title'] ?? 'Unknown'); ?></span></td>
                                    <td style="max-width: 300px; font-size: 0.9rem;">
                                        <?php echo nl2br(htmlspecialchars($row['previous_education'] ?: 'Not provided')); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Inquiries Tab -->
        <div id="inquiries" class="tab-content">
            <?php if (empty($inquiries)): ?>
                <div class="glass-card" style="text-align: center; padding: 4rem;">
                    <i class="fas fa-inbox" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--color-text-light);">No contact inquiries yet.</h3>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Contact Info</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inquiries as $row): ?>
                                <tr>
                                    <td style="white-space: nowrap; color: var(--color-text-light); font-size: 0.9rem;">
                                        <?php echo date('M d, Y', strtotime($row['created_at'])); ?><br>
                                        <small><?php echo date('h:i A', strtotime($row['created_at'])); ?></small>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                                    <td>
                                        <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" style="color: var(--color-accent-red); text-decoration: underline;"><?php echo htmlspecialchars($row['email']); ?></a><br>
                                        <a href="tel:<?php echo htmlspecialchars($row['phone']); ?>" style="color: var(--color-text);"><?php echo htmlspecialchars($row['phone']); ?></a>
                                    </td>
                                    <td style="max-width: 400px; font-size: 0.95rem; line-height: 1.5;">
                                        <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Blog Tab -->
        <div id="blog" class="tab-content">
            <?php if (empty($blog_posts)): ?>
                <div class="glass-card" style="text-align: center; padding: 4rem;">
                    <i class="fas fa-newspaper" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--color-text-light);">No blog posts yet.</h3>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Published Date</th>
                                <th>Image</th>
                                <th>Title & Slug</th>
                                <th>Excerpt</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($blog_posts as $row): ?>
                                <tr>
                                    <td style="white-space: nowrap; color: var(--color-text-light); font-size: 0.9rem;">
                                        <?php echo date('M d, Y', strtotime($row['published_at'])); ?>
                                    </td>
                                    <td>
                                        <?php if ($row['image_url']): ?>
                                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Thumbnail" style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        <?php else: ?>
                                            <div style="width: 80px; height: 50px; background: #eee; border-radius: 4px;"></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
                                        <small style="color: var(--color-text-light);">/<?php echo htmlspecialchars($row['slug']); ?></small>
                                    </td>
                                    <td style="max-width: 300px; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <?php echo htmlspecialchars($row['excerpt']); ?>
                                    </td>
                                    <td>
                                        <a href="admin_blog_form.php?id=<?php echo $row['id']; ?>" style="color: #4b6cb7; margin-right: 1rem;"><i class="fas fa-edit"></i> Edit</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </main>

    <script>
        function switchTab(tabId) {
            // Update buttons
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            event.currentTarget.classList.add('active');
            
            // Update content
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
        }
    </script>
</body>
</html>
