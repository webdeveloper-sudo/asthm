<?php
require_once 'includes/db.php';

$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header("Location: blog.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE slug = ?");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    header("Location: blog.php");
    exit;
}

// Dynamic SEO Setup
$page_title = $post['title'] . " - ACCHM Blog";
$meta_description = htmlspecialchars($post['excerpt']);
$meta_keywords = "ACCHM blog, hotel management, " . str_replace('-', ' ', $post['slug']);

require_once 'includes/header.php';
?>

<main>
    <!-- ══════════════════════════════════════════
     HERO BANNER
    ══════════════════════════════════════════ -->
    <div style="
        position: relative;
        height: 600px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.85)),
                    url('<?php echo htmlspecialchars($post['image_url'] ?: 'assets/images/downloads/0W7A5988-2048x1365 - Copy.jpg'); ?>') center/cover no-repeat;
    ">
        <div style="
            position: relative;
            z-index: 2;
            color: #ffffff;
            padding: 0 2rem;
            margin-top: 72px;
        ">
            <div
                style="
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    font-family: 'Poppins', sans-serif;
                    font-size: 0.78rem;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: 3px;
                    color: rgba(255, 255, 255, 0.75);
                    background: rgba(255, 255, 255, 0.1);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    padding: 0.45rem 1.2rem;
                    border-radius: 8px;
                    margin-bottom: 1.5rem;
                    backdrop-filter: blur(8px);
                "
            >
                <i class="far fa-calendar-alt"></i>
                Published <?php echo date('F j, Y', strtotime($post['published_at'])); ?>
            </div>

            <h1 style="
                font-size: clamp(2rem, 4.5vw, 3.5rem);
                font-weight: 900;
                color: #ffffff;
                text-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
                margin-bottom: 1rem;
                letter-spacing: -0.5px;
                max-width: 900px;
                margin-left: auto;
                margin-right: auto;
                line-height: 1.25;
            ">
                <?php echo htmlspecialchars($post['title']); ?>
            </h1>
            <p style="
                font-size: 1.05rem;
                color: rgba(255, 255, 255, 0.85);
                max-width: 680px;
                margin: 0 auto;
                font-weight: 300;
                line-height: 1.6;
            ">
                <?php echo htmlspecialchars($post['excerpt']); ?>
            </p>
        </div>
    </div>

    <!-- Post Content -->
    <section style="padding-top: 4rem; padding-bottom: 6rem;">
        <div class="container" style="max-width: 800px;">
            <div style="margin-bottom: 2rem;">
                <a href="blog.php" style="color: var(--color-accent-red); text-decoration: none; font-weight: 600; font-size: 1.05rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-arrow-left"></i> Back to Blog List
                </a>
            </div>
            <div class="glass-card" style="padding: 2.5rem; font-size: 1.15rem; line-height: 1.8; color: var(--color-text);">
                <?php 
                    // Post content is expected to have basic HTML from the admin
                    echo $post['content']; 
                ?>
            </div>
            
            <div style="margin-top: 4rem; margin-bottom:30px; text-align: center;">
                <h3 style="margin-bottom: 1.5rem;">Interested in joining ACCHM?</h3>
                <a href="admissions.php" class="btn btn-primary">Apply Now <i class="fas fa-arrow-right" style="margin-left: 10px;"></i></a>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
