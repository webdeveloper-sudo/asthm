<?php
$page_title = "Courses - ACCHM";
require_once 'includes/header.php';
require_once 'includes/db.php';

$stmt = $pdo->query("SELECT * FROM courses");
$courses = $stmt->fetchAll();

// Group courses for visual alternating layout
$pg_courses = [];
$ug_courses = [];
$diploma_courses = [];
$cert_courses = [];

foreach ($courses as $c) {
    if (strpos(strtolower($c['title']), 'mba') !== false || strpos(strtolower($c['title']), 'post graduate') !== false) {
        $pg_courses[] = $c;
    } elseif (strpos(strtolower($c['title']), 'b.sc') !== false || strpos(strtolower($c['title']), 'under graduate') !== false) {
        $ug_courses[] = $c;
    } elseif (strpos(strtolower($c['title']), 'diploma') !== false) {
        $diploma_courses[] = $c;
    } else {
        $cert_courses[] = $c;
    }
}

// Helper to extract duration, eligibility, and intake from description string
function extractMeta($desc) {
    $duration = 'N/A';
    $eligibility = 'N/A';
    $intake = 'N/A';
    
    if (preg_match('/Duration:\s*([^.]+)\./i', $desc, $matches)) {
        $duration = trim($matches[1]);
    }
    if (preg_match('/Eligibility:\s*([^.]+)\./i', $desc, $matches)) {
        $eligibility = trim($matches[1]);
    }
    if (preg_match('/Intake:\s*([^.]+)\./i', $desc, $matches)) {
        $intake = trim($matches[1]);
    }
    
    // Remove duration, eligibility, and intake from main desc
    $clean_desc = preg_replace('/Duration:\s*[^.]+\.\s*/i', '', $desc);
    $clean_desc = preg_replace('/Eligibility:\s*[^.]+\.\s*/i', '', $clean_desc);
    $clean_desc = preg_replace('/Intake:\s*[^.]+\.\s*/i', '', $clean_desc);
    
    return ['duration' => $duration, 'eligibility' => $eligibility, 'intake' => $intake, 'desc' => trim($clean_desc)];
}
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
                    url('assets/images/downloads/0I5A4419-2048x1365.jpg') center/cover no-repeat;
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
                <i class="fas fa-graduation-cap"></i>
                Transforming Passion Into Profession
            </div>

            <h1 style="
                font-size: clamp(2.2rem, 5vw, 3.8rem);
                font-weight: 900;
                color: #ffffff;
                text-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
                margin-bottom: 1rem;
                letter-spacing: -0.5px;
            ">
                Our Programs
            </h1>
            <p style="
                font-size: 1.05rem;
                color: rgba(255, 255, 255, 0.85);
                max-width: 580px;
                margin: 0 auto;
                font-weight: 300;
            ">
                Explore our specialized undergraduate, post-graduate, diploma, and certificate courses in catering and hotel administration.
            </p>
        </div>
    </div>

    <section class="section" style="padding-bottom: 2rem;">
        <div class="container">
            <div class="section-title text-center" style="margin-bottom: 2rem;">
                <h4 style="color: var(--color-accent-red); text-transform: uppercase; letter-spacing: 2px;">Hotel Management</h4>
                <h2 style="font-size: 2.8rem; margin-bottom: 0.5rem;">Post Graduate Program</h2>
            </div>
            
            <?php foreach ($pg_courses as $index => $course): 
                $meta = extractMeta($course['description']);
            ?>
                <div class="course-premium-row">
                    <div class="course-image" style="background-image: url('<?php echo htmlspecialchars($course['image_url']); ?>');"></div>
                    <div class="course-details">
                        <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                        <div class="course-meta">
                            <span><i class="fas fa-clock"></i> <?php echo htmlspecialchars($meta['duration']); ?></span>
                            <span><i class="fas fa-user-check"></i> <?php echo htmlspecialchars($meta['eligibility']); ?></span>
                        </div>
                        <p style="color: var(--color-text-light); font-size: 1.1rem; line-height: 1.8;"><?php echo htmlspecialchars($meta['desc']); ?></p>
                        <div style="margin-top: 2rem;">
                            <a href="admissions.php?course=<?php echo urlencode($course['title']); ?>" class="btn btn-primary">Register Now <i class="fas fa-arrow-right" style="margin-left: 10px;"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section style="background: var(--color-light);">
        <div class="container">
            <div class="section-title text-center" style="margin-bottom: 2rem;">
                <h4 style="color: var(--color-accent-red); text-transform: uppercase; letter-spacing: 2px;">Hotel Management</h4>
                <h2 style="font-size: 2.8rem; margin-bottom: 0.5rem;">Under Graduate Programs</h2>
            </div>
            
            <?php foreach ($ug_courses as $index => $course): 
                $meta = extractMeta($course['description']);
            ?>
                <div class="course-premium-row">
                    <div class="course-image" style="background-image: url('<?php echo htmlspecialchars($course['image_url']); ?>');"></div>
                    <div class="course-details">
                        <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                        <div class="course-meta">
                            <span><i class="fas fa-clock"></i> <?php echo htmlspecialchars($meta['duration']); ?></span>
                            <span><i class="fas fa-user-check"></i> <?php echo htmlspecialchars($meta['eligibility']); ?></span>
                        </div>
                        <p style="color: var(--color-text-light); font-size: 1.1rem; line-height: 1.8;"><?php echo htmlspecialchars($meta['desc']); ?></p>
                        <div style="margin-top: 2rem;">
                            <a href="admissions.php?course=<?php echo urlencode($course['title']); ?>" class="btn btn-primary">Register Now <i class="fas fa-arrow-right" style="margin-left: 10px;"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section >
        <div class="container">
            <div class="section-title text-center" style="margin-bottom: 4rem;">
                <h4 style="color: var(--color-accent-red); text-transform: uppercase; letter-spacing: 2px;">Hotel Management</h4>
                <h2 style="font-size: 2.8rem; margin-bottom: 0.5rem;">Diploma Programs</h2>
            </div>
            
            <?php foreach ($diploma_courses as $index => $course): 
                $meta = extractMeta($course['description']);
            ?>
                <div class="course-premium-row">
                    <div class="course-image" style="background-image: url('<?php echo htmlspecialchars($course['image_url']); ?>');"></div>
                    <div class="course-details">
                        <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                        <div class="course-meta">
                            <span><i class="fas fa-clock"></i> <?php echo htmlspecialchars($meta['duration']); ?></span>
                            <span><i class="fas fa-user-check"></i> <?php echo htmlspecialchars($meta['eligibility']); ?></span>
                        </div>
                        <p style="color: var(--color-text-light); font-size: 1.1rem; line-height: 1.8;"><?php echo htmlspecialchars($meta['desc']); ?></p>
                        <div style="margin-top: 2rem;">
                            <a href="admissions.php?course=<?php echo urlencode($course['title']); ?>" class="btn btn-primary">Register Now <i class="fas fa-arrow-right" style="margin-left: 10px;"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section style="background: var(--color-light);">
        <div class="container">
            <div class="section-title text-center" style="margin-bottom: 4rem;">
                <h4 style="color: var(--color-accent-red); text-transform: uppercase; letter-spacing: 2px;">Hotel Management</h4>
                <h2 style="font-size: 2.8rem; margin-bottom: 0.5rem;">Craft & Certificate Courses</h2>
            </div>
            
            <?php foreach ($cert_courses as $index => $course): 
                $meta = extractMeta($course['description']);
            ?>
                <div class="course-premium-row">
                    <div class="course-image" style="background-image: url('<?php echo htmlspecialchars($course['image_url']); ?>');"></div>
                    <div class="course-details">
                        <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                        <div class="course-meta">
                            <span><i class="fas fa-clock"></i> <?php echo htmlspecialchars($meta['duration']); ?></span>
                            <span><i class="fas fa-user-check"></i> <?php echo htmlspecialchars($meta['eligibility']); ?></span>
                        </div>
                        <p style="color: var(--color-text-light); font-size: 1.1rem; line-height: 1.8;"><?php echo htmlspecialchars($meta['desc']); ?></p>
                        <div style="margin-top: 2rem;">
                            <a href="admissions.php?course=<?php echo urlencode($course['title']); ?>" class="btn btn-primary">Register Now <i class="fas fa-arrow-right" style="margin-left: 10px;"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Fee Structure -->
    <section  style="background: var(--color-light); border-top: 1px solid rgba(0,0,0,0.05);">
        <div class="container">
            <div class="section-title text-center" style="margin-bottom: 4rem;">
                <h4 style="color: var(--color-accent-red); text-transform: uppercase; letter-spacing: 2px;">Investment</h4>
                <h2 style="font-size: 2.8rem; margin-bottom: 0.5rem;">Fee Structure & Programs</h2>
            </div>
            
            <div style="overflow-x: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-radius: var(--radius-lg); background: white;">
                <table class="data-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="padding: 1.25rem 1.5rem; text-align: left; background: #f8fafc; font-weight: 600; color: var(--color-dark); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; border-bottom: 1px solid rgba(0,0,0,0.05);">S. No.</th>
                            <th style="padding: 1.25rem 1.5rem; text-align: left; background: #f8fafc; font-weight: 600; color: var(--color-dark); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; border-bottom: 1px solid rgba(0,0,0,0.05);">Course</th>
                            <th style="padding: 1.25rem 1.5rem; text-align: left; background: #f8fafc; font-weight: 600; color: var(--color-dark); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; border-bottom: 1px solid rgba(0,0,0,0.05);">Eligibility</th>
                            <th style="padding: 1.25rem 1.5rem; text-align: left; background: #f8fafc; font-weight: 600; color: var(--color-dark); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; border-bottom: 1px solid rgba(0,0,0,0.05);">Seats</th>
                            <th style="padding: 1.25rem 1.5rem; text-align: left; background: #f8fafc; font-weight: 600; color: var(--color-dark); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; border-bottom: 1px solid rgba(0,0,0,0.05);">Ist Year</th>
                            <th style="padding: 1.25rem 1.5rem; text-align: left; background: #f8fafc; font-weight: 600; color: var(--color-dark); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; border-bottom: 1px solid rgba(0,0,0,0.05);">IInd Year</th>
                            <th style="padding: 1.25rem 1.5rem; text-align: left; background: #f8fafc; font-weight: 600; color: var(--color-dark); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; border-bottom: 1px solid rgba(0,0,0,0.05);">IIIrd Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);"><strong>1</strong></td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">MBA Hospitality Management</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">Any UG Program</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">20</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">Rs. 65,000</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">Rs. 70,000</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">–</td>
                        </tr>
                        <tr>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);"><strong>2</strong></td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">B.Sc. (Catering & Hotel Administration)</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">12th</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">40</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">Rs. 60,000</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">Rs. 60,000</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">Rs. 60,000</td>
                        </tr>
                        <tr>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);"><strong>3</strong></td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">Diploma (Catering & Hotel Administration)</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">10th</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">40</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">Rs. 58,000</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">Rs. 53,000</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">Rs. 53,000</td>
                        </tr>
                        <tr>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);"><strong>4</strong></td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">Craft Certificate Course in Food Production</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">10th</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">30</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">Rs. 41,000</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">-</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">-</td>
                        </tr>
                        <tr>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);"><strong>5</strong></td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">Craft Certificate Course in Food & Beverage Service</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">10th</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">30</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">Rs. 35, 000</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">-</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">–</td>
                        </tr>
                        <tr>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);"><strong>6</strong></td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">Craft Certificate Course in Front Office</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">10th</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">30</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">Rs. 35, 000</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">–</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">–</td>
                        </tr>
                        <tr>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);"><strong>7</strong></td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">Craft Certificate Course in Housekeeping</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">10th</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">30</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">Rs. 35, 000</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">–</td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">–</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 2rem; background: white; padding: 2rem; border-radius: var(--radius-lg); border-left: 4px solid var(--color-accent-red); box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                <p style="color: var(--color-text); font-size: 1rem; margin-bottom: 0.5rem;"><strong>Registration Fees: Rs. 500 for all programs.</strong></p>
                <p style="color: var(--color-text-light); font-size: 0.95rem; line-height: 1.6;">Note: The above mentioned fee structure is for one year and is exclusive of uniform fee and university semester examination fee. We send Students to Singapore for Industrial Training after completion of the Degree program at free of cost (except Flight tickets) for 100 days.</p>
            </div>
        </div>
    </section>

</main>

<?php require_once 'includes/footer.php'; ?>
