<?php
$page_title = "Contact Us - ACCHM";
require_once 'includes/db.php';

$success_msg = "";
$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_contact'])) {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if ($name && $phone && $email) {
        if (preg_match('/[0-9]/', $name)) {
            $error_msg = "Name cannot contain numbers.";
        } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
            $error_msg = "Phone number must be exactly 10 digits.";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO contact_inquiries (name, phone, email, message) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $phone, $email, $message]);
                
                // Redirect to thank you page
                header("Location: thank_you.php");
                exit;
            } catch (Exception $e) {
                $error_msg = "Sorry, there was an error processing your request. Please try again.";
            }
        }
    } else {
        $error_msg = "Please fill in all required fields.";
    }
}

require_once 'includes/header.php';
?>

<style>
    /* ═══════════════════════════════════════════════
       CONTACT PAGE SPECIFIC STYLES
    ═══════════════════════════════════════════════ */
    :root {
        --contact-purple: #664e88;
        --contact-red: #cd3539;
        --contact-text: #334155;
        --contact-text-light: #64748b;
        --grad-contact-hero: linear-gradient(135deg, rgba(102, 78, 136, 0.95), rgba(50, 35, 73, 0.9));
    }

    .contact-container {
        padding: 5rem 0;
        background-color: var(--color-light);
    }

    /* Grid Layout */
    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 3.5rem;
        align-items: stretch;
    }

    @media (max-width: 992px) {
        .contact-grid {
            grid-template-columns: 1fr;
            gap: 2.5rem;
        }
        .contact-hero {
            margin-top: 70px;
            height: 300px;
        }
    }

    /* Alerts */
    .alert {
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        padding: 1.1rem 1.4rem;
        border-radius: var(--radius-lg);
        margin-bottom: 2rem;
        font-size: 0.95rem;
        font-weight: 500;
        border: 1px solid transparent;
        text-align: left;
    }

    .alert-success {
        background: #f0fdf4;
        color: #166534;
        border-color: #bbf7d0;
    }

    .alert-error {
        background: #fff1f2;
        color: #be123c;
        border-color: #fecdd3;
    }

    .alert i {
        font-size: 1.1rem;
        margin-top: 2px;
        flex-shrink: 0;
    }

    /* Contact Card Details */
    .contact-info-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .contact-title {
        font-size: 1.6rem;
        margin-bottom: 2.2rem;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 700;
        line-height: 1.3;
    }

    .contact-info-list {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        margin-bottom: 2.5rem;
    }

    .contact-info-item {
        display: flex;
        gap: 1.5rem;
        align-items: flex-start;
    }

    .contact-info-icon {
        background: rgba(102, 78, 136, 0.05);
        border: 1px solid rgba(102, 78, 136, 0.08);
        width: 54px;
        height: 54px;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--contact-purple);
        font-size: 1.35rem;
        transition: var(--transition-smooth);
    }

    .contact-info-item:hover .contact-info-icon {
        background: var(--contact-purple);
        color: var(--color-white);
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(102, 78, 136, 0.15);
    }

    .contact-info-text h4 {
        font-size: 1.1rem;
        margin-bottom: 0.35rem;
        font-weight: 600;
        color: var(--color-dark);
    }

    .contact-info-text p, 
    .contact-info-text a {
        font-size: 0.95rem;
        color: var(--contact-text-light);
        line-height: 1.6;
        transition: var(--transition-smooth);
    }

    .contact-info-text a:hover {
        color: var(--contact-red);
    }

    /* Business Hours */
    .business-hours {
        background: rgba(0, 0, 0, 0.015);
        border: 1px solid rgba(0, 0, 0, 0.04);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        margin-top: 1.5rem;
    }

    .business-hours h4 {
        font-size: 1.05rem;
        margin-bottom: 0.8rem;
        font-weight: 600;
        color: var(--color-dark);
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .business-hours h4 i {
        color: var(--contact-red);
    }

    .business-hours ul {
        padding: 0;
        margin: 0;
    }

    .business-hours li {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
        padding: 0.4rem 0;
        color: var(--contact-text-light);
        border-bottom: 1px dashed rgba(0, 0, 0, 0.05);
    }

    .business-hours li:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .business-hours li span:first-child {
        font-weight: 500;
        color: var(--contact-text);
    }

    /* Social Links */
    .contact-social-section {
        margin-top: 2rem;
        padding-top: 1.8rem;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
    }

    .contact-social-section h4 {
        font-size: 1rem;
        margin-bottom: 1rem;
        font-weight: 600;
        color: var(--color-dark);
    }

    .contact-social-links {
        display: flex;
        gap: 0.8rem;
    }

    .contact-social-links a {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-lg);
        background: rgba(102, 78, 136, 0.05);
        border: 1px solid rgba(102, 78, 136, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--contact-purple);
        font-size: 1rem;
        transition: var(--transition-smooth);
    }

    .contact-social-links a:hover {
        background: var(--primary-gradient);
        color: var(--color-white);
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(102, 78, 136, 0.2);
        border-color: transparent;
    }

    /* Form Fields Styling */
    .form-header {
        margin-bottom: 2rem;
    }

    .form-header h3 {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--color-dark);
    }

    .form-header p {
        font-size: 0.95rem;
        color: var(--contact-text-light);
    }

    .contact-form-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .form-field {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .form-field label {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--color-dark);
        letter-spacing: 0.2px;
        text-align: left;
    }

    .form-field label .req {
        color: var(--contact-red);
        margin-left: 2px;
    }

    .form-input,
    .form-textarea {
        width: 100%;
        padding: 0.8rem 1.1rem;
        background: var(--color-light);
        border: 1.5px solid rgba(0, 0, 0, 0.08);
        border-radius: var(--radius-lg);
        font-family: var(--font-body);
        font-size: 0.9rem;
        color: var(--color-dark);
        transition: var(--transition-smooth);
        outline: none;
    }

    .form-input::placeholder,
    .form-textarea::placeholder {
        color: rgba(0, 0, 0, 0.35);
    }

    .form-input:focus,
    .form-textarea:focus {
        border-color: var(--contact-red);
        background: var(--color-white);
        box-shadow: 0 0 0 4px rgba(205, 53, 57, 0.08);
    }

    .form-textarea {
        min-height: 140px;
        resize: vertical;
    }

    /* Submit Button styling */
    .submit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        width: 100%;
        padding: 1rem 2rem;
        background: var(--contact-red);
        color: var(--color-white);
        font-family: var(--font-heading);
        font-weight: 700;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-radius: var(--radius-lg);
        border: none;
        cursor: pointer;
        transition: var(--transition-smooth);
        box-shadow: 0 5px 15px rgba(205, 53, 57, 0.2);
        margin-top: 1rem;
    }

    .submit-btn:hover {
        background: #a8272b;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(205, 53, 57, 0.3);
    }

    .submit-btn .btn-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.15);
        width: 32px;
        height: 32px;
        border-radius: var(--radius-lg);
        transition: transform 0.3s;
    }

    .submit-btn:hover .btn-icon {
        transform: translateX(4px);
    }

    /* Map Section */
    .map-section {
        padding: 0 0 5rem 0;
        background-color: var(--color-light);
    }

    .map-title {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .map-title h3 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--color-dark);
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .map-title p {
        font-size: 0.95rem;
        color: var(--contact-text-light);
        margin-top: 0.5rem;
    }

    .map-container {
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.08);
        height: 480px;
    }

    .map-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
</style>

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
                <i class="fas fa-envelope"></i>
                Connect With Us
            </div>

            <h1 style="
                font-size: clamp(2.2rem, 5vw, 3.8rem);
                font-weight: 900;
                color: #ffffff;
                text-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
                margin-bottom: 1rem;
                letter-spacing: -0.5px;
            ">
                Contact Us
            </h1>
            <p style="
                font-size: 1.05rem;
                color: rgba(255, 255, 255, 0.85);
                max-width: 580px;
                margin: 0 auto;
                font-weight: 300;
            ">
                Get in touch with our admissions office or visit our campus in Puducherry.
            </p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <section class="contact-container">
        <div class="container">
            
            <!-- Success/Error Messages -->
            <?php if ($success_msg): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>Success!</strong> <?php echo htmlspecialchars($success_msg); ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($error_msg): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Error!</strong> <?php echo htmlspecialchars($error_msg); ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="contact-grid">
                
                <!-- Left: Contact Details Card -->
                <div class="glass-card contact-info-card">
                    <div>
                        <h3 class="contact-title">ACHARIYA COLLEGE OF CATERING AND HOTEL MANAGEMENT</h3>
                        
                        <div class="contact-info-list">
                            <div class="contact-info-item">
                                <div class="contact-info-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="contact-info-text">
                                    <h4>Address</h4>
                                    <p>Vazhudavoor Rd, Ousteri, Pathukannu, Puducherry 605110</p>
                                </div>
                            </div>
                            
                            <div class="contact-info-item">
                                <div class="contact-info-icon">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div class="contact-info-text">
                                    <h4>Phone</h4>
                                    <p><a href="tel:+919442277028">+91 94422 77028</a></p>
                                </div>
                            </div>
                            
                            <div class="contact-info-item">
                                <div class="contact-info-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="contact-info-text">
                                    <h4>Email</h4>
                                    <p><a href="mailto:admissions@asthm.edu.in">admissions@asthm.edu.in</a></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="business-hours">
                            <h4><i class="far fa-clock"></i> Office Hours</h4>
                            <ul>
                                <li><span>Monday - Saturday:</span> <span>9:00 AM - 5:30 PM</span></li>
                                <li><span>Sunday:</span> <span>Closed</span></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="contact-social-section">
                        <h4>Connect with us:</h4>
                        <div class="contact-social-links">
                            <a href="https://www.facebook.com/profile.php?id=100092878623015&sfnsn=wiwspmo&mibextid=RUbZ1f" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f" class="font-size:30px;"></i></a>
                            <a href="https://www.instagram.com/achariya_college_of_catering?igsh=Mm0wc3Q3cGV4bXZ5" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="https://youtube.com/@achariyahotelmanagement?si=YNCmyOLYXrku_Mav" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Right: Inquiry Form Card -->
                <div class="glass-card">
                    <div class="form-header">
                        <h3>Send Us a Message</h3>
                        <p>Have questions about courses, admissions, or placements? Drop us a line and we'll get back to you shortly.</p>
                    </div>
                    
                    <form action="contact.php" method="POST">
                        <div class="contact-form-grid">
                            <div class="form-field">
                                <label for="name">Full Name <span class="req">*</span></label>
                                <input type="text" id="name" name="name" class="form-input" placeholder="e.g. John Doe" required>
                            </div>
                            
                            <div class="form-field">
                                <label for="phone">Phone Number <span class="req">*</span></label>
                                <input type="tel" id="phone" name="phone" class="form-input" placeholder="e.g. 9876543210" required>
                            </div>
                            
                            <div class="form-field">
                                <label for="email">Email Address <span class="req">*</span></label>
                                <input type="email" id="email" name="email" class="form-input" placeholder="e.g. john@example.com" required>
                            </div>
                            
                            <div class="form-field">
                                <label for="message">Your Message</label>
                                <textarea id="message" name="message" class="form-textarea" placeholder="How can we help you?"></textarea>
                            </div>
                            
                            <button type="submit" name="submit_contact" class="submit-btn">
                                Send Message
                                <span class="btn-icon"><i class="fas fa-paper-plane"></i></span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>

    <!-- Map Section (Full Width) -->
    <section class="map-section">
        <div class="container">
            <div class="map-title">
                <h3>Our Location</h3>
                <p>Find us on Google Maps and get directions to our campus</p>
            </div>
            
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31229.908461705058!2d79.71925531247486!3d11.923274091347963!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a536174fb6e1793%3A0x245e30c44d9f4a99!2sACHARIYA%20COLLEGE%20OF%20CATERING%20AND%20HOTEL%20MANAGEMENT!5e0!3m2!1sen!2sin!4v1780480379539!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const nameInput = document.getElementById('name');
        const phoneInput = document.getElementById('phone');
        const form = nameInput ? nameInput.closest('form') : null;

        if (nameInput) {
            nameInput.addEventListener('input', function() {
                // Remove all numbers
                this.value = this.value.replace(/[0-9]/g, '');
            });
        }

        if (phoneInput) {
            phoneInput.setAttribute('maxlength', '10');
            phoneInput.addEventListener('input', function() {
                // Remove all non-digits
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }

        if (form) {
            form.addEventListener('submit', function (e) {
                const name = nameInput.value.trim();
                const phone = phoneInput.value.trim();

                if (/[0-9]/.test(name)) {
                    e.preventDefault();
                    alert('Full Name cannot contain numbers.');
                    nameInput.focus();
                    return;
                }

                if (!/^[0-9]{10}$/.test(phone)) {
                    e.preventDefault();
                    alert('Phone number must be exactly 10 digits.');
                    phoneInput.focus();
                    return;
                }
            });
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>
