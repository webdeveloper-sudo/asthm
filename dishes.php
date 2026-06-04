<?php
$page_title = "Student Culinary Gallery - ACCHM";
$meta_description = "Browse all the creative cooking and baking dishes prepared by students of Achariya College of Catering and Hotel Management.";
$meta_keywords = "ACCHM, student gallery, culinary crafts, catering college, baking creations, hotel management, puducherry";
require_once 'includes/header.php';
?>

<main style="padding-top: 100px;">
    <!-- Hero Section -->
    <section class="section" style="padding-top: 4rem; padding-bottom: 2rem;">
        <div class="container">
            <div class="section-title text-center" style="margin-bottom: 0;">
                <h4 style="color: var(--color-accent-red); text-transform: uppercase; letter-spacing: 2px;">Student Crafts</h4>
                <h2 style="font-size: 2.8rem; margin-bottom: 0.5rem;">Culinary Masterpieces</h2>
                <!-- <div style="height: 4px; background: var(--primary-gradient); width: 80px; margin: 2rem auto 0; border-radius: 2px;"></div> -->
                <p style="margin-top: 1rem; color: var(--color-text-light); max-width: 900px; margin-left: auto; margin-right: auto; font-size: 1.1rem;">A complete showcase of premium gourmet dishes and pastry creations crafted by our students in practical training.</p>
            </div>
        </div>
    </section>

    <!-- Dishes Grid Section -->
    <section class="dishes-section" style="padding-top: 2rem; padding-bottom: 6rem;">
        <div class="container">
            <?php
            // Retrieve all dish images from the dishes folder using a robust method
            $dishes_dir = "assets/images/dishes";
            $all_dishes = [];
            if (is_dir($dishes_dir)) {
                $files = scandir($dishes_dir);
                if ($files !== false) {
                    foreach ($files as $file) {
                        if ($file !== '.' && $file !== '..') {
                            $path = $dishes_dir . '/' . $file;
                            if (is_file($path)) {
                                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'jfif', 'avif'])) {
                                    $all_dishes[] = $path;
                                }
                            }
                        }
                    }
                }
            }
            if (!empty($all_dishes)) {
                natsort($all_dishes);
                $all_dishes = array_values($all_dishes);
            }

            // Construct JSON data for JS lightbox
            $dishes_js_data = [];
            foreach ($all_dishes as $dish) {
                $filename = basename($dish);
                $filename_without_ext = pathinfo($filename, PATHINFO_FILENAME);
                $title = ucwords(str_replace(['-', '_'], ' ', $filename_without_ext));
                if (stripos($title, 'WhatsApp Image') !== false) {
                    $title = 'Culinary Artistry';
                }
                $dishes_js_data[] = [
                    'src' => $dish,
                    'title' => $title
                ];
            }
            ?>

            <?php if (empty($all_dishes)): ?>
                <div class="glass-card" style="text-align: center; padding: 4rem;">
                    <i class="fas fa-utensils" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--color-text-light);">No dishes found in the directory. Check back later!</h3>
                </div>
            <?php else: ?>
                <div class="dishes-grid">
                    <?php foreach ($all_dishes as $idx => $dish): 
                        $title = $dishes_js_data[$idx]['title'];
                    ?>
                        <div class="dish-card" onclick="openDishesLightbox(<?php echo $idx; ?>)">
                            <div class="dish-image-wrapper">
                                <img src="<?php echo htmlspecialchars($dish); ?>" alt="<?php echo htmlspecialchars($title); ?>" loading="lazy">
                                <div class="dish-hover-overlay">
                                    <i class="fas fa-search-plus"></i>
                                    <span><?php echo htmlspecialchars($title); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Back to Home Button -->
            <div style="text-align: center; margin-top: 2rem; display: flex; justify-content: center;">
                <a href="index.php" class="btn btn-outline" style="padding: 1rem 2.5rem; font-size: 1.1rem; letter-spacing: 1px; color: var(--color-primary); border: 2px solid var(--color-primary);">Back To Home</a>
            </div>
        </div>
    </section>

    <!-- Fullscreen Lightbox Modal -->
    <div id="dishesLightbox" class="dishes-lightbox">
        <span class="lightbox-close" onclick="closeDishesLightbox()">&times;</span>
        <button class="lightbox-nav lightbox-prev" onclick="changeDishesLightboxImage(-1)">&lt;</button>
        <div class="lightbox-content-wrapper">
            <img id="lightboxImage" class="lightbox-image" src="" alt="Full Screen Dish">
            <div id="lightboxCaption" class="lightbox-caption"></div>
        </div>
        <button class="lightbox-nav lightbox-next" onclick="changeDishesLightboxImage(1)">&gt;</button>
    </div>
</main>

<!-- Lightbox Script -->
<script>
    const dishesData = <?php echo json_encode($dishes_js_data); ?>;
    let currentLightboxIndex = -1;

    function openDishesLightbox(index) {
        currentLightboxIndex = index;
        updateDishesLightbox();
        const lb = document.getElementById('dishesLightbox');
        lb.classList.add('active');
        document.body.style.overflow = 'hidden'; // Disable page scroll
    }

    function closeDishesLightbox() {
        const lb = document.getElementById('dishesLightbox');
        lb.classList.remove('active');
        document.body.style.overflow = ''; // Enable page scroll
    }

    function changeDishesLightboxImage(direction) {
        if (dishesData.length === 0) return;
        currentLightboxIndex = (currentLightboxIndex + direction + dishesData.length) % dishesData.length;
        updateDishesLightbox();
    }

    function updateDishesLightbox() {
        const imgElement = document.getElementById('lightboxImage');
        const captionElement = document.getElementById('lightboxCaption');
        if (dishesData[currentLightboxIndex]) {
            imgElement.src = dishesData[currentLightboxIndex].src;
            captionElement.textContent = dishesData[currentLightboxIndex].title;
        }
    }

    // Close lightbox on click outside the image
    document.getElementById('dishesLightbox').addEventListener('click', function(e) {
        if (e.target === this || e.target.classList.contains('lightbox-content-wrapper')) {
            closeDishesLightbox();
        }
    });

    // Keyboard controls
    document.addEventListener('keydown', function(e) {
        const lb = document.getElementById('dishesLightbox');
        if (!lb || !lb.classList.contains('active')) return;
        
        if (e.key === 'Escape') {
            closeDishesLightbox();
        } else if (e.key === 'ArrowRight') {
            changeDishesLightboxImage(1);
        } else if (e.key === 'ArrowLeft') {
            changeDishesLightboxImage(-1);
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>
