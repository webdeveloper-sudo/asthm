<?php
$page_title = "Blog & News - ACCHM";
$meta_description = "Read the latest news, updates, and insights from Achariya College of Catering and Hotel Management. Stay informed about the hospitality industry.";
$meta_keywords = "ACCHM blog, hotel management news, hospitality industry updates, catering college news, puducherry";
require_once 'includes/db.php';
require_once 'includes/header.php';

// Fetch all posts
$stmt = $pdo->query("SELECT * FROM blog_posts ORDER BY published_at DESC");
$posts = $stmt->fetchAll();
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
                    url('assets/images/downloads/0W7A5988-2048x1365 - Copy.jpg') top/cover no-repeat;
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
                <i class="fas fa-newspaper"></i>
                Insights & Updates
            </div>

            <h1 style="
                font-size: clamp(2.2rem, 5vw, 3.8rem);
                font-weight: 900;
                color: #ffffff;
                text-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
                margin-bottom: 1rem;
                letter-spacing: -0.5px;
            ">
                ACCHM Blog
            </h1>
            <p style="
                font-size: 1.05rem;
                color: rgba(255, 255, 255, 0.85);
                max-width: 580px;
                margin: 0 auto;
                font-weight: 300;
            ">
                Discover industry trends, college news, and student success stories.
            </p>
        </div>
    </div>

    <!-- Blog Grid -->
    <section class="section" style="padding-top: 2rem;">
        <div class="container">
            <?php if (empty($posts)): ?>
                <div class="glass-card" style="text-align: center; padding: 4rem;">
                    <i class="fas fa-newspaper" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--color-text-light);">No posts available at the moment. Check back soon!</h3>
                </div>
            <?php else: ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem;">
                    <?php foreach ($posts as $post): ?>
                        <div class="glass-card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                            <div style="height: 200px; overflow: hidden;">
                                <?php if ($post['image_url']): ?>
                                    <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                <?php else: ?>
                                    <div style="width: 100%; height: 100%; background: var(--primary-gradient);"></div>
                                <?php endif; ?>
                            </div>
                            <div style="padding: 2rem; flex-grow: 1; display: flex; flex-direction: column;">
                                <div style="font-size: 0.85rem; color: var(--color-text-light); margin-bottom: 0.5rem;">
                                    <i class="far fa-calendar-alt"></i> <?php echo date('M d, Y', strtotime($post['published_at'])); ?>
                                </div>
                                <h3 style="font-size: 1.5rem; margin-bottom: 1rem;">
                                    <a href="single_post.php?slug=<?php echo urlencode($post['slug']); ?>" style="color: var(--color-dark); transition: color 0.3s;" onmouseover="this.style.color='var(--color-accent-red)'" onmouseout="this.style.color='var(--color-dark)'">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                </h3>
                                <p style="color: var(--color-text-light); margin-bottom: 1.5rem; flex-grow: 1;">
                                    <?php echo htmlspecialchars($post['excerpt']); ?>
                                </p>
                                <div>
                                    <a href="single_post.php?slug=<?php echo urlencode($post['slug']); ?>" style="color: var(--color-accent-red); font-weight: 600; font-size: 0.95rem;">Read More <i class="fas fa-arrow-right" style="margin-left: 5px;"></i></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
