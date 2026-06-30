<?php
// Simple helper to detect active page
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'ACCHM - Premium Hospitality Education'; ?></title>
    <meta name="description" content="<?php echo isset($meta_description) ? htmlspecialchars($meta_description) : 'Achariya College of Catering and Hotel Management offers premium education, world-class facilities, and 100% placement assistance in the hospitality sector.'; ?>">
    <meta name="keywords" content="<?php echo isset($meta_keywords) ? htmlspecialchars($meta_keywords) : 'hotel management college, catering institute, ACCHM, hospitality education, diploma in hotel management, B.Sc catering'; ?>">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/acchm-logo/ACCHM_LOGO-02.png" type="image/x-icon">
    <link rel="icon" href="assets/images/acchm-logo/ACCHM_LOGO-02.png" type="image/png">
    
    <!-- Open Graph / Facebook Meta Tags for Link Preview Sharing -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo isset($page_title) ? htmlspecialchars($page_title) : 'ACCHM - Premium Hospitality Education'; ?>">
    <meta property="og:description" content="Achariya College of Catering and Hotel Management (ACCHM) is a premier hospitality institute offering world-class training in culinary arts, catering, and hotel administration. We prepare students for international careers with hands-on practice, expert guidance, and a 100% placement guarantee. Start your journey with us and transform your passion into a premium profession. Connect with our admissions desk today! Email: admissions@asthm.edu.in | Mobile: +91 94422 77028">
    <meta property="og:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/images/acchm-logo/ACCHM_LOGO-01.png">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo isset($page_title) ? htmlspecialchars($page_title) : 'ACCHM - Premium Hospitality Education'; ?>">
    <meta name="twitter:description" content="Achariya College of Catering and Hotel Management (ACCHM) is a premier hospitality institute offering world-class training in culinary arts, catering, and hotel administration. We prepare students for international careers with hands-on practice, expert guidance, and a 100% placement guarantee. Start your journey with us and transform your passion into a premium profession. Connect with our admissions desk today! Email: admissions@asthm.edu.in | Mobile: +91 94422 77028">
    <meta name="twitter:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/images/acchm-logo/ACCHM_LOGO-01.png">
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="assets/css/styles.css">

    // <!-- Google Tag Manager -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || []; w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            }); var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : ''; j.async = true; j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl; f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-NQK9QHHK');</script>
    // <!-- End Google Tag Manager -->

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

     <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-P2BTKRLCX0"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-P2BTKRLCX0');
</script>
</head>
<body>

  <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NQK9QHHK" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->g

    <header class="site-header <?php if($current_page == 'index.php' || $current_page == '') echo 'home-header'; ?>">
        <div class="container">
            <div class="logo">
                <a href="index.php">
                    <img src="assets/images/acchm-logo/ACCHM_LOGO-01.png" width="180" alt="ACCHM Logo">
                </a>
            </div>
            <button class="mobile-menu-toggle" aria-label="Toggle navigation">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
            
            <nav>
                <ul class="nav-links">
                    <li><a href="index.php" <?php if($current_page == 'index.php') echo 'class="active"'; ?>>Home</a></li>
                    <li><a href="about.php" <?php if($current_page == 'about.php') echo 'class="active"'; ?>>About Us</a></li>
                    <li><a href="courses.php" <?php if($current_page == 'courses.php') echo 'class="active"'; ?>>Courses</a></li>
                    <li><a href="placement.php" <?php if($current_page == 'placement.php') echo 'class="active"'; ?>>Placements</a></li>
                    <li><a href="admissions.php" <?php if($current_page == 'admissions.php') echo 'class="active"'; ?>>Admissions</a></li>
                    <li><a href="blog.php" <?php if($current_page == 'blog.php' || $current_page == 'single_post.php') echo 'class="active"'; ?>>Blog</a></li>
                    <li><a href="contact.php" <?php if($current_page == 'contact.php') echo 'class="active"'; ?>>Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>
