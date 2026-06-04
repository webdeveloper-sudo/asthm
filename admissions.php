<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

// Google Apps Script Web App URL for Google Sheets integration
define('GOOGLE_SCRIPT_URL', 'https://script.google.com/macros/s/AKfycbz96pVRwX08EPijObDWn2GOTeRYK3boBPrHVtd5EFebtbdhHSVYJ4SjZqDTF-1GKUJM/exec');


// ── Indian States & Districts ──────────────────────────────────────────────
$states_districts = [
    "Andhra Pradesh" => ["Visakhapatnam", "Vijayawada", "Guntur", "Nellore", "Kurnool", "Tirupati", "Rajahmundry", "Kakinada", "Kadapa", "Anantapur"],
    "Arunachal Pradesh" => ["Itanagar", "Naharlagun", "Pasighat", "Tawang", "Ziro"],
    "Assam" => ["Guwahati", "Silchar", "Dibrugarh", "Jorhat", "Nagaon", "Tinsukia"],
    "Bihar" => ["Patna", "Gaya", "Bhagalpur", "Muzaffarpur", "Darbhanga", "Purnia"],
    "Chhattisgarh" => ["Raipur", "Bhilai", "Bilaspur", "Korba", "Durg", "Rajnandgaon"],
    "Goa" => ["Panaji", "Margao", "Vasco da Gama", "Mapusa", "Ponda"],
    "Gujarat" => ["Ahmedabad", "Surat", "Vadodara", "Rajkot", "Bhavnagar", "Jamnagar"],
    "Haryana" => ["Faridabad", "Gurgaon", "Panipat", "Ambala", "Hisar", "Rohtak"],
    "Himachal Pradesh" => ["Shimla", "Dharamsala", "Solan", "Mandi", "Kullu", "Hamirpur"],
    "Jharkhand" => ["Ranchi", "Jamshedpur", "Dhanbad", "Bokaro", "Deoghar", "Hazaribagh"],
    "Karnataka" => ["Bangalore", "Mysore", "Hubli", "Dharwad", "Mangalore", "Belgaum", "Bellary"],
    "Kerala" => ["Thiruvananthapuram", "Kochi", "Kozhikode", "Thrissur", "Kollam", "Palakkad", "Alappuzha", "Kannur"],
    "Madhya Pradesh" => ["Bhopal", "Indore", "Jabalpur", "Gwalior", "Ujjain", "Sagar", "Dewas"],
    "Maharashtra" => ["Mumbai", "Pune", "Nagpur", "Nashik", "Aurangabad", "Solapur", "Thane", "Kolhapur"],
    "Manipur" => ["Imphal", "Thoubal", "Bishnupur", "Churachandpur"],
    "Meghalaya" => ["Shillong", "Tura", "Jowai", "Nongstoin"],
    "Mizoram" => ["Aizawl", "Lunglei", "Champhai"],
    "Nagaland" => ["Kohima", "Dimapur", "Mokokchung"],
    "Odisha" => ["Bhubaneswar", "Cuttack", "Rourkela", "Berhampur", "Sambalpur", "Puri"],
    "Punjab" => ["Ludhiana", "Amritsar", "Jalandhar", "Patiala", "Bathinda", "Mohali"],
    "Rajasthan" => ["Jaipur", "Jodhpur", "Udaipur", "Kota", "Ajmer", "Bikaner", "Alwar"],
    "Sikkim" => ["Gangtok", "Namchi", "Gyalshing", "Mangan"],
    "Tamil Nadu" => ["Chennai", "Coimbatore", "Madurai", "Tiruchirappalli", "Salem", "Tirunelveli", "Vellore", "Erode", "Thanjavur", "Pudukottai", "Cuddalore", "Villupuram"],
    "Telangana" => ["Hyderabad", "Warangal", "Nizamabad", "Karimnagar", "Ramagundam", "Khammam"],
    "Tripura" => ["Agartala", "Udaipur", "Dharmanagar", "Kailasahar"],
    "Uttar Pradesh" => ["Lucknow", "Kanpur", "Agra", "Varanasi", "Prayagraj", "Meerut", "Ghaziabad", "Noida", "Bareilly", "Aligarh"],
    "Uttarakhand" => ["Dehradun", "Haridwar", "Roorkee", "Haldwani", "Nainital", "Rishikesh"],
    "West Bengal" => ["Kolkata", "Howrah", "Asansol", "Siliguri", "Durgapur", "Bardhaman"],
    "Andaman and Nicobar" => ["Port Blair", "Mayabunder", "Campbell Bay"],
    "Chandigarh" => ["Chandigarh"],
    "Dadra and Nagar Haveli" => ["Silvassa"],
    "Daman and Diu" => ["Daman", "Diu"],
    "Delhi" => ["New Delhi", "North Delhi", "South Delhi", "East Delhi", "West Delhi", "Central Delhi"],
    "Jammu and Kashmir" => ["Srinagar", "Jammu", "Anantnag", "Baramulla", "Sopore", "Udhampur"],
    "Ladakh" => ["Leh", "Kargil"],
    "Lakshadweep" => ["Kavaratti", "Agatti", "Amini"],
    "Puducherry" => ["Puducherry", "Karaikal", "Mahe", "Yanam"],
];

$boards = [
    "CBSE – Central Board of Secondary Education",
    "CISCE – Council for the Indian School Certificate Examinations",
    "State Board – Tamil Nadu",
    "State Board – Karnataka",
    "State Board – Maharashtra",
    "State Board – Andhra Pradesh",
    "State Board – Kerala",
    "State Board – Telangana",
    "State Board – Uttar Pradesh",
    "State Board – Rajasthan",
    "State Board – Gujarat",
    "State Board – West Bengal",
    "State Board – Bihar",
    "State Board – Other",
    "NIOS – National Institute of Open Schooling",
    "IB – International Baccalaureate",
    "Cambridge – IGCSE / A Level",
];

$programs = [
    "Diploma in Hotel & Catering Administration",
    "B.Sc Hotel & Catering Administration",
    "MBA Hospitality Management",
    "Certificate in Food & Beverage",
    "Certificate in Front Office Operations",
    "Certificate in Housekeeping",
];

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_name = trim($_POST['student_name'] ?? '');
    $parent_name = trim($_POST['parent_name'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $district = trim($_POST['district'] ?? '');
    $board = trim($_POST['board'] ?? '');
    $total_mark = trim($_POST['total_mark'] ?? '');
    $course_interest = trim($_POST['course_interest'] ?? '');
    $callback = trim($_POST['callback'] ?? '');
    $campus_date = trim($_POST['campus_date'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Assemble callback time strings from individual selects
    $cb_from_hh = $_POST['cb_from_hh'] ?? '12';
    $cb_from_mm = $_POST['cb_from_mm'] ?? '00';
    $cb_from_ampm = $_POST['cb_from_ampm'] ?? 'AM';
    $cb_till_hh = $_POST['cb_till_hh'] ?? '12';
    $cb_till_mm = $_POST['cb_till_mm'] ?? '00';
    $cb_till_ampm = $_POST['cb_till_ampm'] ?? 'AM';
    $cb_date = trim($_POST['cb_date'] ?? '');
    $callback_from = $callback === 'Yes' ? "$cb_from_hh:$cb_from_mm $cb_from_ampm" : '';
    $callback_till = $callback === 'Yes' ? "$cb_till_hh:$cb_till_mm $cb_till_ampm" : '';
    if ($callback === 'Yes' && $cb_date)
        $callback_from = $cb_date . ' ' . $callback_from;

    $required = $student_name && $parent_name && $mobile && $state && $district && $board && $total_mark && $course_interest && $callback;
    if (!$required) {
        $error_message = "Please fill in all required fields marked with *.";
    } elseif (!preg_match('/^[0-9]{10}$/', $mobile)) {
        $error_message = "Please enter a valid 10-digit mobile number.";
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO admissions
                    (first_name, last_name, email, phone, course_id, previous_education,
                     parent_name, state, district, board, total_mark, course_interest,
                     callback, callback_from, callback_till, campus_date, message, is_read)
                VALUES (?, '', ?, ?, NULL, '',
                        ?, ?, ?, ?, ?, ?,
                        ?, ?, ?, ?, ?, 0)
            ");
            $stmt->execute([
                $student_name,
                $email,
                $mobile,
                $parent_name,
                $state,
                $district,
                $board,
                $total_mark,
                $course_interest,
                $callback,
                $callback_from,
                $callback_till,
                $campus_date,
                $message
            ]);
            $success_message = "Your application has been submitted successfully! Our admissions team will contact you shortly.";

            // Save to Google Sheets via backend POST request
            if (defined('GOOGLE_SCRIPT_URL') && !empty(GOOGLE_SCRIPT_URL)) {
                $postdata = http_build_query([
                    'student_name' => $student_name,
                    'parent_name' => $parent_name,
                    'mobile' => $mobile,
                    'email' => $email,
                    'state' => $state,
                    'district' => $district,
                    'board' => $board,
                    'total_mark' => $total_mark,
                    'course_interest' => $course_interest,
                    'callback' => $callback,
                    'cb_from_hh' => $cb_from_hh,
                    'cb_from_mm' => $cb_from_mm,
                    'cb_from_ampm' => $cb_from_ampm,
                    'cb_till_hh' => $cb_till_hh,
                    'cb_till_mm' => $cb_till_mm,
                    'cb_till_ampm' => $cb_till_ampm,
                    'cb_date' => $cb_date,
                    'campus_date' => $campus_date,
                    'message' => $message
                ]);
                $opts = [
                    'http' => [
                        'method' => 'POST',
                        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                        'content' => $postdata,
                        'timeout' => 8
                    ]
                ];
                $context = stream_context_create($opts);
                @file_get_contents(GOOGLE_SCRIPT_URL, false, $context);
            }
            header("Location: thank_you.php");
            exit;
        } catch (Exception $e) {
            $error_message = "Error submitting application. Please try again.";
        }
    }
}

$states_json = json_encode(array_keys($states_districts));
$districts_json = json_encode($states_districts);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Admission – ACCHM | Achariya College of Catering &amp; Hotel Management</title>
    <meta name="description"
        content="Apply for admission to ACCHM. Fill out our online admission form for Diploma, B.Sc and MBA programmes in Hotel Management and Catering.">

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/acchm-logo/ACCHM_LOGO-02.png" type="image/x-icon">
    <link rel="icon" href="assets/images/acchm-logo/ACCHM_LOGO-02.png" type="image/png">

    <!-- Open Graph / Facebook Meta Tags for Link Preview Sharing -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Online Admission – ACCHM | Achariya College of Catering &amp; Hotel Management">
    <meta property="og:description"
        content="Achariya College of Catering and Hotel Management (ACCHM) is a premier hospitality institute offering world-class training in culinary arts, catering, and hotel administration. We prepare students for international careers with hands-on practice, expert guidance, and a 100% placement guarantee. Start your journey with us and transform your passion into a premium profession. Connect with our admissions desk today! Email: admissions@asthm.edu.in | Mobile: +91 94422 77028">
    <meta property="og:image"
        content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/images/acchm-logo/ACCHM_LOGO-01.png">
    <meta property="og:url"
        content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Online Admission – ACCHM | Achariya College of Catering &amp; Hotel Management">
    <meta name="twitter:description"
        content="Achariya College of Catering and Hotel Management (ACCHM) is a premier hospitality institute offering world-class training in culinary arts, catering, and hotel administration. We prepare students for international careers with hands-on practice, expert guidance, and a 100% placement guarantee. Start your journey with us and transform your passion into a premium profession. Connect with our admissions desk today! Email: admissions@asthm.edu.in | Mobile: +91 94422 77028">
    <meta name="twitter:image"
        content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/images/acchm-logo/ACCHM_LOGO-01.png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ═══════════════════════════════════════════════
       RESET & BASE
    ═══════════════════════════════════════════════ */
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --purple: #664e88;
            --purple-dark: #4d386b;
            --purple-deep: #322349;
            --red: #cd3539;
            --red-dark: #a8272b;
            --green: #8fbf57;
            --heading: #1a1a2e;
            --dark: #0f172a;
            --text: #334155;
            --text-light: #64748b;
            --light: #f8fafc;
            --white: #ffffff;
            --border: rgba(0, 0, 0, 0.08);
            --glass-bg: rgba(255, 255, 255, 0.72);
            --glass-border: rgba(255, 255, 255, 0.45);
            --grad-purple: linear-gradient(135deg, #664e88, #4d386b);
            --grad-red: linear-gradient(135deg, #cd3539, #a8272b);
            --grad-hero: linear-gradient(135deg, rgba(102, 78, 136, 0.92), rgba(50, 35, 73, 0.88));
            --radius: 5px;
            --radius-lg: 5px;
            --ease: cubic-bezier(0.16, 1, 0.3, 1);
            --shadow-card: 0 20px 50px rgba(0, 0, 0, 0.07);
            --shadow-focus: 0 0 0 4px rgba(205, 53, 57, 0.14);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text);
            background: var(--light);
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Poppins', sans-serif;
            color: var(--heading);
            line-height: 1.2;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
        }

        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        .container {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* ═══════════════════════════════════════════════
       HEADER — Glassmorphic Floating (exact replica)
    ═══════════════════════════════════════════════ */
        /* .site-header {
            position: fixed;
            top: 1rem;
            left: 50%;
            transform: translateX(-50%);
            width: 95%;
            max-width: 1280px;
            z-index: 1000;
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.07);
            padding: 1rem 2rem;
            transition: all 0.4s var(--ease);
        }

        .header-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo img {
            height: 45px;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            list-style: none;
        }

        .nav-links a {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text);
            position: relative;
            padding: 0.5rem 0;
            transition: color 0.3s;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--grad-red);
            transition: all 0.4s var(--ease);
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--dark);
        }

        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 100%;
        } */

        /* ═══════════════════════════════════════════════
       HERO BANNER
    ═══════════════════════════════════════════════ */
        .page-hero {
            position: relative;
            height: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow: hidden;
        }

        .page-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('assets/images/downloads/0W7A6176-2048x1365 - Copy.jpg') top/cover no-repeat;
            z-index: 0;
            transform: scale(1.05);
            transition: transform 8s ease;
        }

        .page-hero:hover::before {
            transform: scale(1.08);
        }

        .page-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.85));
            z-index: 1;
        }

        .page-hero-content {
            position: relative;
            z-index: 2;
            color: var(--white);
            padding: 0 2rem;
        }

        .hero-eyebrow {
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
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            backdrop-filter: blur(8px);
        }

        .page-hero h1 {
            font-size: clamp(2.2rem, 5vw, 3.8rem);
            font-weight: 900;
            color: var(--white);
            text-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }

        .page-hero p {
            font-size: 1.05rem;
            color: rgba(255, 255, 255, 0.85);
            max-width: 580px;
            margin: 0 auto;
            font-weight: 300;
        }

        /* ═══════════════════════════════════════════════
       MAIN FORM SECTION
    ═══════════════════════════════════════════════ */
        .form-section {
            padding: 4rem 0 6rem;
        }

        .form-wrapper {
            max-width: 900px;
            margin: 0 auto;
        }

        /* Alert messages */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            padding: 1.1rem 1.4rem;
            border-radius: var(--radius);
            margin-bottom: 2rem;
            font-size: 0.92rem;
            font-weight: 500;
            animation: slideDown 0.4s var(--ease);
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fff1f2;
            color: #be123c;
            border: 1px solid #fecdd3;
        }

        .alert i {
            font-size: 1.1rem;
            margin-top: 1px;
            flex-shrink: 0;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form Card */
        .form-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            border: 1px solid #b6b5b5b0;
            overflow: hidden;
        }

        /* Section headers inside form */
        .form-section-head {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.6rem 2.5rem 1.2rem;
            border-bottom: 1px solid #f1f5f9;
            background: linear-gradient(to right, #fdfcff, #f9f6ff);
        }

        .form-section-icon {
            width: 42px;
            height: 42px;
            border-radius: var(--radius);
            background: var(--grad-purple);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1rem;
            flex-shrink: 0;
        }

        .form-section-head h3 {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--heading);
            letter-spacing: 0.2px;
        }

        .form-section-head span {
            font-size: 0.78rem;
            color: var(--text-light);
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            margin-top: 1px;
            display: block;
        }

        /* Form body */
        .form-body {
            padding: 2rem 2.5rem 2.5rem;
        }

        /* Grid helpers */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.4rem 2rem;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1.4rem 2rem;
        }

        .col-full {
            grid-column: 1 / -1;
        }

        /* Field group */
        .field {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .field-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--heading);
            letter-spacing: 0.2px;
        }

        .field-label .req {
            color: var(--red);
            margin-left: 2px;
        }

        /* Inputs & Selects */
        .field-input,
        .field-select,
        .field-textarea {
            width: 100%;
            padding: 0.72rem 1rem;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: var(--radius);
            font-family: 'Poppins', sans-serif;
            font-size: 0.88rem;
            color: var(--heading);
            transition: all 0.3s var(--ease);
            outline: none;
            appearance: none;
            -webkit-appearance: none;
        }

        .field-input::placeholder,
        .field-textarea::placeholder {
            color: #a0aec0;
            font-weight: 400;
        }

        .field-input:focus,
        .field-select:focus,
        .field-textarea:focus {
            border-color: var(--red);
            background: var(--white);
            box-shadow: var(--shadow-focus);
        }

        .field-input:hover,
        .field-select:hover,
        .field-textarea:hover {
            border-color: #c4b5d8;
        }

        .field-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%2364748b' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
            cursor: pointer;
        }

        .field-select:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        .field-textarea {
            resize: vertical;
            min-height: 90px;
        }

        /* Hint text below field */
        .field-hint {
            font-size: 0.75rem;
            color: var(--text-light);
            margin-top: -2px;
        }

        /* ── Callback Section ── */
        .callback-box {
            /* background: linear-gradient(135deg, #faf7ff 0%, #f5f0ff 100%);
        border: 1.5px solid #e9e0f8;
        border-radius: var(--radius);
        padding: 1.5rem 1.75rem; */
            margin-top: 0.5rem;
        }

        .radio-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .radio-label {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--text);
            cursor: pointer;
            padding: 0.6rem 1.2rem;
            border-radius: var(--radius);
            border: 1.5px solid #e2e8f0;
            background: var(--white);
            transition: all 0.25s;
            user-select: none;
        }

        .radio-label input[type="radio"] {
            display: none;
        }

        .radio-label .radio-dot {
            width: 17px;
            height: 17px;
            border: 2px solid #d1d5db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .radio-label input[type="radio"]:checked~.radio-dot {
            border-color: var(--purple);
            background: var(--purple);
        }

        .radio-label input[type="radio"]:checked~.radio-dot::after {
            content: '';
            width: 6px;
            height: 6px;
            background: var(--white);
            border-radius: 50%;
            display: block;
        }

        .radio-label:has(input:checked) {
            border-color: var(--purple);
            background: #f5f0ff;
            color: var(--purple-dark);
        }

        /* Callback time picker fields */
        .callback-timing {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.2rem 1.8rem;
            margin-top: 1.4rem;
        }

        .time-row {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .time-row .field-select {
            flex: 1;
            min-width: 0;
            color: var(--color-dark);
            padding-left: 6px;
            padding-right: 16px;
            font-size: 0.8rem;
            background-position: right 4px center;
            background-size: 8px 5px;
        }

        .time-sep {
            font-size: 1.1rem;
            color: var(--text-light);
            font-weight: 600;
            flex-shrink: 0;
            padding: 0 2px;
        }

        /* ── Submit Button ── */
        .submit-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            width: 100%;
            padding: 1.05rem 2rem;
            font-family: 'Poppins', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--white);
            background: var(--grad-red);
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: all 0.35s var(--ease);
            box-shadow: 0 10px 28px rgba(205, 53, 57, 0.32);
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.12), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 36px rgba(205, 53, 57, 0.42);
        }

        .submit-btn:hover::before {
            opacity: 1;
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        .submit-btn .btn-icon {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.18);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            transition: transform 0.3s;
        }

        .submit-btn:hover .btn-icon {
            transform: translateX(4px);
        }

        /* Divider between form sections */
        .section-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0 20%, #e2e8f0 80%, transparent);
            margin: 0.5rem 0;
        }

        /* ── Side Info Panel ── */
        .page-layout {
            display: grid;
            grid-template-columns: 1fr 310px;
            gap: 2.5rem;
            align-items: flex-start;
        }

        .info-panel {
            position: sticky;
            top: 110px;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .info-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 1.6rem;
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.06);
            border: 1px solid #b6b5b5b0;
        }

        .info-card-title {
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--heading);
            margin-bottom: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .info-card-title i {
            width: 30px;
            height: 30px;
            background: var(--grad-purple);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .info-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            font-size: 0.82rem;
            color: var(--text);
            padding: 0.55rem 0;
            border-bottom: 1px solid #f1f5f9;
            line-height: 1.5;
        }

        .info-list li:last-child {
            border-bottom: none;
        }

        .info-list li i {
            color: var(--green);
            font-size: 0.75rem;
            margin-top: 3px;
            flex-shrink: 0;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.83rem;
            color: var(--text);
            padding: 0.5rem 0;
        }

        .contact-item i {
            width: 32px;
            height: 32px;
            background: rgba(205, 53, 57, 0.08);
            color: var(--red);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .progress-step {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.55rem 0;
            font-size: 0.82rem;
            color: var(--text-light);
        }

        .step-num {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--text-light);
            flex-shrink: 0;
        }

        .step-num.active {
            background: var(--grad-red);
            color: var(--white);
        }

        .progress-step.active {
            color: var(--heading);
            font-weight: 600;
        }

        /* ── Footer (Minimal version matching site style) ── */
        .site-footer {
            background: var(--dark);
            color: rgba(255, 255, 255, 0.6);
            position: relative;
            overflow: hidden;
        }

        .site-footer::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('assets/images/downloads/0W7A5988-2048x1365.jpg') center/cover;
            opacity: 0.08;
            z-index: 0;
        }

        .footer-inner {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            padding: 5rem 0 2.5rem;
        }

        .footer-brand h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            color: var(--white);
            margin-bottom: 1rem;
        }

        .footer-brand p {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.55);
            max-width: 380px;
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        .footer-contacts {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        .fc-item {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .fc-item i {
            color: #ff416c;
            font-size: 1.2rem;
            width: 20px;
            text-align: center;
        }

        .fc-item span {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .footer-bottom {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 1.5rem 0;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.35);
        }

        .footer-bottom a {
            color: rgba(255, 255, 255, 0.25);
        }

        .footer-bottom a:hover {
            color: rgba(255, 255, 255, 0.5);
        }

        /* ═══════════════════════════════════════════════
       RESPONSIVE
    ═══════════════════════════════════════════════ */
        @media (max-width: 1024px) {
            .page-layout {
                grid-template-columns: 1fr;
            }

            .info-panel {
                position: static;
                display: grid;
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .site-header {
                width: 100%;
                top: 0;
                border-radius: 0;
            }

            .nav-links {
                display: none;
            }

            .page-hero {
                height: 300px;
            }

            .page-hero h1 {
                font-size: 2rem;
            }

            .grid-2,
            .grid-3 {
                grid-template-columns: 1fr;
            }

            .form-body {
                padding: 1.5rem 1.25rem 2rem;
            }

            .form-section-head {
                padding: 1.2rem 1.25rem 1rem;
            }

            .callback-timing {
                grid-template-columns: 1fr;
            }

            .footer-inner {
                grid-template-columns: 1fr;
                gap: 2.5rem;
            }

            .info-panel {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .time-row {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>

    <!-- ══════════════════════════════════════════
     HEADER — Glassmorphic (matching site)
══════════════════════════════════════════ -->
    <!-- <header class="site-header">
        <div class="header-inner">
            <div class="logo">
                <a href="index.php">
                    <img src="assets/images/downloads/ACHM-Logo-1.png" alt="ACCHM Logo">
                </a>
            </div>
            <button class="mobile-menu-toggle" aria-label="Toggle navigation">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
            <nav>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="courses.php">Courses</a></li>
                    <li><a href="placement.php">Placements</a></li>
                    <li><a href="admissions.php" class="active">Admissions</a></li>
                    <li><a href="blog.php">Blog</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header> -->

    <!-- ══════════════════════════════════════════
     HERO BANNER
══════════════════════════════════════════ -->
    <div class="page-hero" >
        <div class="page-hero-content" style="margin-top:72px;">
            <div class="hero-eyebrow">
                <i class="fas fa-graduation-cap"></i>
                Admissions Open 2025–26
            </div>
            <h1>Apply for Admission</h1>
            <p>Take the first step towards a brilliant global career in hospitality.</p>
        </div>
    </div>

    <!-- ══════════════════════════════════════════
     FORM + SIDE PANEL
══════════════════════════════════════════ -->
    <section class="form-section">
        <div class="container">
            <div class="page-layout">

                <!-- ── Form Card ── -->
                <div class="form-wrapper">

                    <?php if ($success_message): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <strong>Application Submitted!</strong><br>
                                <?php echo htmlspecialchars($success_message); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($error_message): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <div><?php echo htmlspecialchars($error_message); ?></div>
                        </div>
                    <?php endif; ?>

                    <form id="admissionForm" action="admissions.php" method="POST" novalidate>


                        <!-- ════════ SECTION 1: Personal Details ════════ -->
                        <div class="form-card" style="margin-bottom:1.5rem;">
                            <div class="form-section-head">
                                <div class="form-section-icon"><i class="fas fa-user"></i></div>
                                <div>
                                    <h3>Please Submit the follwing student details !</h3>
                                    <span>Our team will get back to you within 48 hrs</span>
                                </div>
                            </div>
                            <div class="form-body">
                                <div class="grid-2">
                                    <!-- Student Name -->
                                    <div class="field">
                                        <label class="field-label" for="student_name">Student Name <span
                                                class="req">*</span></label>
                                        <input type="text" id="student_name" name="student_name" class="field-input"
                                            placeholder="Enter Student Name" required
                                            value="<?php echo htmlspecialchars($_POST['student_name'] ?? ''); ?>">
                                    </div>

                                    <!-- Parent Name -->
                                    <div class="field">
                                        <label class="field-label" for="parent_name">Parent Name <span
                                                class="req">*</span></label>
                                        <input type="text" id="parent_name" name="parent_name" class="field-input"
                                            placeholder="Enter Parent Name" required
                                            value="<?php echo htmlspecialchars($_POST['parent_name'] ?? ''); ?>">
                                    </div>

                                    <!-- Mobile -->
                                    <div class="field">
                                        <label class="field-label" for="mobile">Mobile No <span
                                                class="req">*</span></label>
                                        <input type="tel" id="mobile" name="mobile" class="field-input"
                                            placeholder="Enter 10-digit mobile number" maxlength="10"
                                            pattern="[0-9]{10}" required
                                            value="<?php echo htmlspecialchars($_POST['mobile'] ?? ''); ?>">
                                        <!-- <span class="field-hint">10 digits, no spaces or dashes</span> -->
                                    </div>

                                    <!-- Email -->
                                    <div class="field">
                                        <label class="field-label" for="email">Email Address</label>
                                        <input type="email" id="email" name="email" class="field-input"
                                            placeholder="example@mail.com"
                                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                                    </div>

                                    <!-- State -->
                                    <div class="field">
                                        <label class="field-label" for="state">State <span class="req">*</span></label>
                                        <select id="state" name="state" class="field-select" required
                                            onchange="loadDistricts(this.value)">
                                            <option value="">Select State</option>
                                            <?php foreach (array_keys($states_districts) as $s): ?>
                                                <option value="<?php echo htmlspecialchars($s); ?>" <?php echo (($_POST['state'] ?? '') === $s) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($s); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- District -->
                                    <div class="field">
                                        <label class="field-label" for="district">District <span
                                                class="req">*</span></label>
                                        <select id="district" name="district" class="field-select" required disabled>
                                            <option value="">Select District</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid-2" style="margin-top:1.5rem;">
                                    <!-- Board -->
                                    <div class="field">
                                        <label class="field-label" for="board">Board <span class="req">*</span></label>
                                        <select id="board" name="board" class="field-select" required>
                                            <option value="">Select Board</option>
                                            <?php foreach ($boards as $b): ?>
                                                <option value="<?php echo htmlspecialchars($b); ?>" <?php echo (($_POST['board'] ?? '') === $b) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($b); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Total Mark -->
                                    <div class="field">
                                        <label class="field-label" for="total_mark">Total Mark <span
                                                class="req">*</span></label>
                                        <input type="text" id="total_mark" name="total_mark" class="field-input"
                                            placeholder="Enter score (max 3 digits)" maxlength="3" inputmode="numeric" pattern="[0-9]{1,3}" required
                                            value="<?php echo htmlspecialchars($_POST['total_mark'] ?? ''); ?>">
                                    </div>

                                    <!-- Course Interest -->
                                    <div class="field col-full">
                                        <label class="field-label" for="course_interest">Course Interest <span
                                                class="req">*</span></label>
                                        <select id="course_interest" name="course_interest" class="field-select"
                                            required>
                                            <option value="">Select Preferred Program</option>
                                            <?php foreach ($programs as $p): ?>
                                                <option value="<?php echo htmlspecialchars($p); ?>" <?php echo (($_POST['course_interest'] ?? '') === $p) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($p); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- Callback Request -->


                                <div class="grid-2">
                                    <div class="field" style=" margin-top:1.5rem;">
                                        <label class="field-label">Do you need a Call Back Request? <span
                                                class="req">*</span></label>
                                        <div class="callback-box">
                                            <div class="radio-group" style="margin-bottom:0;" id="callbackRadioGroup">
                                                <label class="radio-label">
                                                    <input type="radio" name="callback" value="Yes" id="cb_yes" <?php echo (($_POST['callback'] ?? '') === 'Yes') ? 'checked' : ''; ?>
                                                        onchange="toggleCallbackTiming(true)">
                                                    <span class="radio-dot"></span>
                                                    Yes
                                                </label>
                                                <label class="radio-label">
                                                    <input type="radio" name="callback" value="No" id="cb_no" <?php echo (($_POST['callback'] ?? '') === 'No') ? 'checked' : ''; ?>
                                                        onchange="toggleCallbackTiming(false)">
                                                    <span class="radio-dot"></span>
                                                    No, not needed
                                                </label>
                                            </div>

                                            <!-- Callback timing shown only when Yes) -->
                                            <div id="callbackTimingBlock" class="callback-timing"
                                                style="display:<?php echo (($_POST['callback'] ?? '') === 'Yes') ? 'grid' : 'none'; ?>;">
                                                <!-- Date -->
                                                <div class="field" style="grid-column:1/-1;">
                                                    <label class="field-label" for="cb_date">Call preference
                                                        date</label>
                                                    <input type="date" id="cb_date" name="cb_date" class="field-input"
                                                        value="<?php echo htmlspecialchars($_POST['cb_date'] ?? ''); ?>">
                                                </div>

                                                <!-- From Time -->
                                                <div class="field">
                                                    <label class="field-label">From Time</label>
                                                    <div class="time-row">
                                                        <select name="cb_from_hh" class="field-select">
                                                            <?php
                                                            $sel_hh = $_POST['cb_from_hh'] ?? '';
                                                            for ($h = 1; $h <= 12; $h++):
                                                                $v = str_pad($h, 2, '0', STR_PAD_LEFT);
                                                                ?>
                                                                <option value="<?php echo $v; ?>" <?php echo ($sel_hh === $v) ? 'selected' : ''; ?>><?php echo $v; ?></option>
                                                            <?php endfor; ?>
                                                        </select>
                                                        <span class="time-sep">:</span>
                                                        <select name="cb_from_mm" class="field-select">
                                                            <?php
                                                            $sel_mm = $_POST['cb_from_mm'] ?? '';
                                                            foreach (['00', '15', '30', '45'] as $m):
                                                                ?>
                                                                <option value="<?php echo $m; ?>" <?php echo ($sel_mm === $m) ? 'selected' : ''; ?>><?php echo $m; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <select name="cb_from_ampm" class="field-select"
                                                            style="max-width:72px;">
                                                            <option value="AM" <?php echo (($_POST['cb_from_ampm'] ?? '') === 'AM') ? 'selected' : ''; ?>>AM</option>
                                                            <option value="PM" <?php echo (($_POST['cb_from_ampm'] ?? '') === 'PM') ? 'selected' : ''; ?>>PM</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Till Time -->
                                                <div class="field">
                                                    <label class="field-label">Till Time</label>
                                                    <div class="time-row">
                                                        <select name="cb_till_hh" class="field-select">
                                                            <?php
                                                            $sel_thh = $_POST['cb_till_hh'] ?? '';
                                                            for ($h = 1; $h <= 12; $h++):
                                                                $v = str_pad($h, 2, '0', STR_PAD_LEFT);
                                                                ?>
                                                                <option value="<?php echo $v; ?>" <?php echo ($sel_thh === $v) ? 'selected' : ''; ?>><?php echo $v; ?></option>
                                                            <?php endfor; ?>
                                                        </select>
                                                        <span class="time-sep">:</span>
                                                        <select name="cb_till_mm" class="field-select">
                                                            <?php
                                                            $sel_tmm = $_POST['cb_till_mm'] ?? '';
                                                            foreach (['00', '15', '30', '45'] as $m):
                                                                ?>
                                                                <option value="<?php echo $m; ?>" <?php echo ($sel_tmm === $m) ? 'selected' : ''; ?>><?php echo $m; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <select name="cb_till_ampm" class="field-select"
                                                            style="max-width:72px;">
                                                            <option value="AM" <?php echo (($_POST['cb_till_ampm'] ?? '') === 'AM') ? 'selected' : ''; ?>>AM</option>
                                                            <option value="PM" <?php echo (($_POST['cb_till_ampm'] ?? '') === 'PM') ? 'selected' : ''; ?>>PM</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Campus Visiting Date -->
                                    <div class="field" style="margin-top:1.5rem">
                                        <label class="field-label" for="campus_date">Campus Visiting Date <span
                                                style="font-weight:400;color:var(--text-light);font-size:0.76rem;">(Optional)</span></label>
                                        <input type="date" id="campus_date" name="campus_date" class="field-input"
                                            value="<?php echo htmlspecialchars($_POST['campus_date'] ?? ''); ?>">
                                    </div>


                                </div>

                                <!-- Message -->
                                <div class="field col-full" style="margin-top:1.5rem">
                                    <label class="field-label" for="message">Message <span
                                            style="font-weight:400;color:var(--text-light);font-size:0.76rem;">(Optional)</span></label>
                                    <textarea id="message" name="message" class="field-textarea"
                                        placeholder="Any questions or inquiries?"><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                                </div>

                                <button type="submit" class="submit-btn" id="submitBtn">
                                    <span>Submit Application</span>
                                    <span class="btn-icon"><i class="fas fa-paper-plane"></i></span>
                                </button>

                                <p style="text-align:center;font-size:0.78rem;color:var(--text-light);margin-top:1rem;">
                                    <i class="fas fa-shield-alt" style="margin-right:4px;color:var(--green);"></i>
                                    Your information is secure and will never be shared with third parties.
                                </p>
                            </div>
                        </div>

                    </form>
                </div><!-- /.form-wrapper -->

                <!-- ── Side Info Panel ── -->
                <aside class="info-panel">

                    <!-- Application Steps -->
                    <div class="info-card">
                        <div class="info-card-title">
                            <i class="fas fa-list-ol"></i>
                            Application Process
                        </div>
                        <div class="progress-step active">
                            <div class="step-num active">1</div>
                            Fill this online form
                        </div>
                        <div class="progress-step">
                            <div class="step-num">2</div>
                            Our team reviews & calls you
                        </div>
                        <div class="progress-step">
                            <div class="step-num">3</div>
                            Campus visit & counselling
                        </div>
                        <div class="progress-step">
                            <div class="step-num">4</div>
                            Enrolment & fee payment
                        </div>
                        <div class="progress-step">
                            <div class="step-num">5</div>
                            Begin your journey! 🎓
                        </div>
                    </div>

                    <!-- Why ACCHM -->
                    <div class="info-card">
                        <div class="info-card-title">
                            <i class="fas fa-star"></i>
                            Why Choose ACCHM
                        </div>
                        <ul class="info-list">
                            <li><i class="fas fa-check-circle"></i> 100% Placement Assistance</li>
                            <li><i class="fas fa-check-circle"></i> International Standard Curriculum</li>
                            <li><i class="fas fa-check-circle"></i> Expert Faculty & Industry Mentors</li>
                            <li><i class="fas fa-check-circle"></i> Global Career Opportunities</li>
                            <li><i class="fas fa-check-circle"></i> Earn While You Learn Program</li>
                            <li><i class="fas fa-check-circle"></i> 5-Star Hotel Tie-ups</li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div class="info-card">
                        <div class="info-card-title">
                            <i class="fas fa-headset"></i>
                            Need Help?
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone-alt"></i>
                            <span>+91 94422 77028</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>admissions@asthm.edu.in</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Vazhudavoor Rd, Ousteri, Puducherry 605110</span>
                        </div>
                    </div>

                </aside>

            </div>
        </div>
    </section>



    <script>
        // ── State → District loader ───────────────────────────────────────
        const districtsMap = <?php echo $districts_json; ?>;
        const savedDistrict = <?php echo json_encode($_POST['district'] ?? ''); ?>;
        const savedState = <?php echo json_encode($_POST['state'] ?? ''); ?>;

        function loadDistricts(state, preselectVal) {
            const sel = document.getElementById('district');
            sel.innerHTML = '<option value="">Select District</option>';
            if (state && districtsMap[state]) {
                sel.disabled = false;
                districtsMap[state].forEach(d => {
                    const opt = document.createElement('option');
                    opt.value = d; opt.textContent = d;
                    if (d === (preselectVal || '')) opt.selected = true;
                    sel.appendChild(opt);
                });
            } else {
                sel.disabled = true;
            }
        }

        // Restore state on page reload (after POST validation failure)
        if (savedState) loadDistricts(savedState, savedDistrict);

        // ── Callback timing toggle ────────────────────────────────────────
        function toggleCallbackTiming(show) {
            const block = document.getElementById('callbackTimingBlock');
            block.style.display = show ? 'grid' : 'none';
        }

        // ── Live input sanitization and length limits ─────────────────────────
        const studentNameInput = document.getElementById('student_name');
        const parentNameInput = document.getElementById('parent_name');
        const mobileInput = document.getElementById('mobile');
        const totalMarkInput = document.getElementById('total_mark');

        if (studentNameInput) {
            studentNameInput.addEventListener('input', function() {
                this.value = this.value.replace(/[0-9]/g, '');
            });
        }
        if (parentNameInput) {
            parentNameInput.addEventListener('input', function() {
                this.value = this.value.replace(/[0-9]/g, '');
            });
        }
        if (mobileInput) {
            mobileInput.setAttribute('maxlength', '10');
            mobileInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }
        if (totalMarkInput) {
            totalMarkInput.setAttribute('maxlength', '3');
            totalMarkInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }

        // ── Client-side validation ────────────────────────────────────────
        document.getElementById('admissionForm').addEventListener('submit', function (e) {
            const studentName = document.getElementById('student_name').value.trim();
            const parentName = document.getElementById('parent_name').value.trim();
            const mobile = document.getElementById('mobile').value.trim();
            const totalMark = document.getElementById('total_mark').value.trim();

            if (/[0-9]/.test(studentName)) {
                e.preventDefault();
                document.getElementById('student_name').focus();
                alert('Student Name cannot contain numbers.');
                return;
            }

            if (/[0-9]/.test(parentName)) {
                e.preventDefault();
                document.getElementById('parent_name').focus();
                alert('Parent Name cannot contain numbers.');
                return;
            }

            if (!/^[0-9]{10}$/.test(mobile)) {
                e.preventDefault();
                document.getElementById('mobile').focus();
                document.getElementById('mobile').style.borderColor = '#cd3539';
                alert('Please enter a valid 10-digit mobile number.');
                return;
            }

            if (!/^[0-9]{1,3}$/.test(totalMark)) {
                e.preventDefault();
                document.getElementById('total_mark').focus();
                document.getElementById('total_mark').style.borderColor = '#cd3539';
                alert('Total Mark must be a number with up to 3 digits.');
                return;
            }

            const cb = document.querySelector('input[name="callback"]:checked');
            if (!cb) {
                e.preventDefault();
                alert('Please indicate whether you need a callback.');
                return;
            }

            // Submit button loading state
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = '<span>Submitting…</span><span class="btn-icon"><i class="fas fa-spinner fa-spin"></i></span>';
            btn.style.opacity = '0.85';
        });

        // Remove red border on re-type
        document.getElementById('mobile').addEventListener('input', function () {
            this.style.borderColor = '';
        });
        if (totalMarkInput) {
            totalMarkInput.addEventListener('input', function () {
                this.style.borderColor = '';
            });
        }

        // ── Smooth date min (today) ───────────────────────────────────────
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('cb_date') && (document.getElementById('cb_date').min = today);
        document.getElementById('campus_date') && (document.getElementById('campus_date').min = today);
    </script>

    <?php require_once 'includes/footer.php'; ?>