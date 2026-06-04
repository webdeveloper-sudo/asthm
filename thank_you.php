<?php
$page_title = "Thank You - ACCHM";
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
                    url('assets/images/downloads/0W7A6176-2048x1365.jpg') center/cover no-repeat;
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
                <i class="fas fa-check-circle"></i>
                Submission Successful
            </div>

            <h1 style="
                font-size: clamp(2.2rem, 5vw, 3.8rem);
                font-weight: 900;
                color: #ffffff;
                text-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
                margin-bottom: 1rem;
                letter-spacing: -0.5px;
            ">
                Thank You!
            </h1>
            <p style="
                font-size: 1.05rem;
                color: rgba(255, 255, 255, 0.85);
                max-width: 580px;
                margin: 0 auto;
                font-weight: 300;
            ">
                Your submission has been received. Our team will get back to you shortly.
            </p>
        </div>
    </div>

    <!-- Done Button Container -->
    <section class="section" style="padding: 6rem 0; background: var(--color-white); text-align: center;">
        <div class="container" style="max-width: 600px;">
            <div class="glass-card" style="padding: 3rem; box-shadow: 0 15px 35px rgba(0,0,0,0.05); border-radius: var(--radius-xl); border: 1px solid rgba(0,0,0,0.06);">
                <div style="background: rgba(46, 204, 113, 0.1); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; color: #2ecc71; font-size: 2.2rem;">
                    <i class="fas fa-check"></i>
                </div>
                <h2 style="font-size: 2rem; color: var(--color-primary-dark); margin-bottom: 1rem;">Submission Completed</h2>
                <p style="color: var(--color-text-light); line-height: 1.7; margin-bottom: 2rem;">We have successfully saved your details. A representative from ACHARIYA College of Catering and Hotel Management will contact you via phone or email shortly.</p>
                <a href="index.php" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 1rem 2.5rem; font-weight: 700; text-transform: uppercase;">
                    Done <i class="fas fa-home"></i>
                </a>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
