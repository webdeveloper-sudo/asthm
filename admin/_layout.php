<?php
// Usage: set $admin_page_title and $active_page before including this file.
// e.g., $admin_page_title = "Dashboard"; $active_page = "dashboard";
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Fetch counts for sidebar badges
require_once '../includes/db.php';
$count_general  = $pdo->query("SELECT COUNT(*) FROM contact_inquiries")->fetchColumn();
$count_admissions = $pdo->query("SELECT COUNT(*) FROM admissions")->fetchColumn();
$count_unread_general    = $pdo->query("SELECT COUNT(*) FROM contact_inquiries WHERE is_read = 0")->fetchColumn();
$count_unread_admissions = $pdo->query("SELECT COUNT(*) FROM admissions WHERE is_read = 0")->fetchColumn();
$count_blogs    = $pdo->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($admin_page_title) ? htmlspecialchars($admin_page_title) . ' — ACCHM Admin' : 'ACCHM Admin'; ?></title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/images/acchm-logo/ACCHM_LOGO-02.png" type="image/x-icon">
    <link rel="icon" href="../assets/images/acchm-logo/ACCHM_LOGO-02.png" type="image/png">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w: 270px;
            --bg-sidebar: #0f1117;
            --bg-sidebar-hover: rgba(255,255,255,0.06);
            --bg-main: #f4f6fb;
            --bg-card: #ffffff;
            --accent: #e8294c;
            --accent2: #ff7b54;
            --gradient: linear-gradient(135deg, #e8294c 0%, #ff7b54 100%);
            --text-primary: #1a1d2e;
            --text-secondary: #6b7280;
            --text-muted: #9ca3af;
            --border: #e5e7eb;
            --radius: 14px;
            --shadow: 0 4px 24px rgba(0,0,0,0.08);
            --shadow-lg: 0 12px 40px rgba(0,0,0,0.12);
            --transition: all 0.22s cubic-bezier(0.4,0,0.2,1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-main);
            color: var(--text-primary);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Sidebar ── */
        .admin-sidebar {
            width: var(--sidebar-w);
            min-width: var(--sidebar-w);
            background: var(--bg-sidebar);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-brand {
            padding: 1.75rem 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .sidebar-brand-icon {
            width: 42px; height: 42px;
            background: var(--gradient);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: white;
            flex-shrink: 0;
        }
        .sidebar-brand-text { line-height: 1.2; }
        .sidebar-brand-text strong {
            display: block; color: #fff; font-size: 1rem; font-weight: 700;
        }
        .sidebar-brand-text span {
            font-size: 0.72rem; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px;
        }

        .sidebar-section-label {
            font-size: 0.65rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1.5px; color: rgba(255,255,255,0.28);
            padding: 1.5rem 1.5rem 0.5rem;
        }

        .sidebar-nav { padding: 0.5rem 0.75rem; flex: 1; }
        .nav-item {
            display: flex; align-items: center; gap: 0.85rem;
            padding: 0.75rem 0.85rem;
            border-radius: 10px;
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            font-size: 0.88rem; font-weight: 500;
            transition: var(--transition);
            margin-bottom: 2px;
            position: relative;
        }
        .nav-item:hover {
            background: var(--bg-sidebar-hover);
            color: rgba(255,255,255,0.9);
        }
        .nav-item.active {
            background: rgba(232,41,76,0.18);
            color: #fff;
        }
        .nav-item.active .nav-icon { color: var(--accent); }
        .nav-icon { width: 20px; text-align: center; font-size: 0.95rem; flex-shrink: 0; }
        .nav-badge {
            margin-left: auto;
            background: var(--accent);
            color: white;
            font-size: 0.65rem; font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
            min-width: 20px; text-align: center;
        }
        .nav-badge.muted {
            background: rgba(255,255,255,0.12);
            color: rgba(255,255,255,0.5);
        }

        .sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid rgba(255,255,255,0.07);
        }
        .sidebar-user {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.6rem 0.85rem;
            border-radius: 10px;
        }
        .user-avatar {
            width: 34px; height: 34px;
            background: var(--gradient);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; font-weight: 700; color: white;
            flex-shrink: 0;
        }
        .user-info strong { display: block; color: #fff; font-size: 0.82rem; }
        .user-info span { font-size: 0.72rem; color: rgba(255,255,255,0.35); }

        .logout-btn {
            display: flex; align-items: center; gap: 0.85rem;
            padding: 0.65rem 0.85rem;
            border-radius: 10px;
            color: rgba(255,100,100,0.75);
            text-decoration: none;
            font-size: 0.85rem; font-weight: 500;
            transition: var(--transition);
            margin-top: 4px;
        }
        .logout-btn:hover {
            background: rgba(255,80,80,0.1);
            color: #ff6b6b;
        }

        /* ── Main content area ── */
        .admin-main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
        }

        .admin-topbar {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-size: 1.15rem; font-weight: 700; color: var(--text-primary); }
        .topbar-subtitle { font-size: 0.78rem; color: var(--text-muted); margin-top: 1px; }
        .topbar-right { display: flex; align-items: center; gap: 1rem; }
        .topbar-time { font-size: 0.8rem; color: var(--text-muted); }

        .admin-content { padding: 2rem; flex: 1; min-width: 0; }

        /* ── Reusable UI Atoms ── */
        .card {
            background: var(--bg-card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }
        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem;
        }
        .card-title { font-size: 1rem; font-weight: 700; color: var(--text-primary); }
        .card-body { padding: 1.5rem; }

        .btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.55rem 1.2rem;
            border-radius: 9px;
            font-size: 0.84rem; font-weight: 600;
            cursor: pointer; border: none;
            transition: var(--transition);
            text-decoration: none;
            white-space: nowrap;
        }
        .btn-primary {
            background: var(--gradient);
            color: white;
            box-shadow: 0 4px 14px rgba(232,41,76,0.3);
        }
        .btn-primary:hover { box-shadow: 0 6px 20px rgba(232,41,76,0.45); transform: translateY(-1px); }
        .btn-ghost {
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }
        .btn-ghost:hover { background: var(--bg-main); color: var(--text-primary); }
        .btn-danger { background: #fff0f0; color: #e8294c; border: 1px solid #ffd0d0; }
        .btn-danger:hover { background: #e8294c; color: white; }
        .btn-success { background: #f0fff4; color: #22c55e; border: 1px solid #bbf7d0; }
        .btn-success:hover { background: #22c55e; color: white; }
        .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.78rem; border-radius: 7px; }

        .badge {
            display: inline-block;
            padding: 0.22rem 0.65rem;
            border-radius: 20px;
            font-size: 0.72rem; font-weight: 700;
            letter-spacing: 0.3px;
        }
        .badge-read   { background: #f0fdf4; color: #16a34a; }
        .badge-unread { background: #fff7ed; color: #ea580c; }
        .badge-default { background: var(--bg-main); color: var(--text-secondary); }

        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block;
            font-size: 0.82rem; font-weight: 600;
            color: var(--text-primary); margin-bottom: 0.4rem;
        }
        .form-control {
            width: 100%;
            padding: 0.65rem 0.9rem;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            font-family: 'Inter', sans-serif;
            font-size: 0.88rem;
            color: var(--text-primary);
            background: var(--bg-main);
            transition: var(--transition);
            outline: none;
        }
        .form-control:focus { border-color: var(--accent); background: #fff; box-shadow: 0 0 0 3px rgba(232,41,76,0.1); }
        textarea.form-control { resize: vertical; min-height: 110px; }

        .alert {
            padding: 0.9rem 1.2rem;
            border-radius: 10px;
            font-size: 0.87rem; font-weight: 500;
            margin-bottom: 1.5rem;
            display: flex; align-items: center; gap: 0.6rem;
        }
        .alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-error   { background: #fff0f0; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-info    { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }

        /* ── Data Table ── */
        .data-table-wrap { overflow-x: auto; border-radius: var(--radius); }
        .data-table {
            width: 100%; border-collapse: collapse;
            font-size: 0.855rem;
        }
        .data-table thead th {
            background: #f9fafb;
            color: var(--text-secondary);
            font-size: 0.75rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.7px;
            padding: 0.85rem 1.1rem;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        .data-table tbody td {
            padding: 0.9rem 1.1rem;
            border-bottom: 1px solid #f3f4f6;
            color: var(--text-primary);
            vertical-align: middle;
        }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background: #fafbff; }
        .data-table .td-muted { color: var(--text-muted); font-size: 0.8rem; }

        /* ── Stats Cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: var(--bg-card);
            border-radius: var(--radius);
            padding: 1.4rem 1.5rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            display: flex; align-items: center; gap: 1rem;
            transition: var(--transition);
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }
        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; flex-shrink: 0;
        }
        .stat-icon.red   { background: rgba(232,41,76,0.1); color: var(--accent); }
        .stat-icon.blue  { background: rgba(59,130,246,0.1); color: #3b82f6; }
        .stat-icon.green { background: rgba(34,197,94,0.1); color: #22c55e; }
        .stat-icon.purple{ background: rgba(168,85,247,0.1); color: #a855f7; }
        .stat-icon.orange{ background: rgba(249,115,22,0.1); color: #f97316; }
        .stat-value { font-size: 1.85rem; font-weight: 800; color: var(--text-primary); line-height: 1; }
        .stat-label { font-size: 0.78rem; color: var(--text-muted); margin-top: 3px; font-weight: 500; }
        .stat-sub { font-size: 0.73rem; margin-top: 4px; font-weight: 600; }
        .stat-sub.warn { color: #f97316; }
        .stat-sub.ok   { color: #22c55e; }

        /* ── Tabs ── */
        .tabs { display: flex; gap: 0.25rem; border-bottom: 2px solid var(--border); margin-bottom: 1.5rem; }
        .tab-btn {
            padding: 0.75rem 1.25rem;
            border: none; background: none;
            font-family: 'Inter', sans-serif;
            font-size: 0.87rem; font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: var(--transition);
            display: flex; align-items: center; gap: 0.5rem;
        }
        .tab-btn:hover { color: var(--text-primary); }
        .tab-btn.active { color: var(--accent); border-bottom-color: var(--accent); }
        .tab-count {
            background: var(--bg-main);
            color: var(--text-secondary);
            font-size: 0.72rem; font-weight: 700;
            padding: 1px 7px; border-radius: 20px;
        }
        .tab-btn.active .tab-count { background: rgba(232,41,76,0.12); color: var(--accent); }
        .tab-pane { display: none; }
        .tab-pane.active { display: block; }

        /* ── Search bar ── */
        .search-bar-wrap { position: relative; }
        .search-bar-wrap .fa-search {
            position: absolute; left: 0.9rem; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted); font-size: 0.8rem;
        }
        .search-input {
            padding-left: 2.3rem !important;
        }

        /* ── Modal ── */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 500;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none;
            transition: opacity 0.2s;
        }
        .modal-overlay.open { opacity: 1; pointer-events: all; }
        .modal-box {
            background: var(--bg-card);
            border-radius: 18px;
            width: 90%; max-width: 640px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 2rem;
            box-shadow: var(--shadow-lg);
            transform: translateY(20px);
            transition: transform 0.25s;
        }
        .modal-overlay.open .modal-box { transform: translateY(0); }
        .modal-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .modal-title { font-size: 1.1rem; font-weight: 700; }
        .modal-close {
            background: var(--bg-main); border: none; cursor: pointer;
            width: 32px; height: 32px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted); transition: var(--transition);
            font-size: 0.9rem;
        }
        .modal-close:hover { background: #ffd0d0; color: #e8294c; }

        /* Image preview box */
        .img-preview {
            width: 100%; height: 160px;
            border-radius: 10px;
            border: 2px dashed var(--border);
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            background: var(--bg-main);
            margin-bottom: 1rem;
            transition: var(--transition);
        }
        .img-preview img { width: 100%; height: 100%; object-fit: cover; display: none; border-radius: 8px; }
        .img-preview .preview-placeholder { color: var(--text-muted); font-size: 0.82rem; text-align: center; }

        /* Responsive overrides & Mobile menu sidebar toggle */
        .admin-sidebar-toggle {
            display: none;
            background: transparent;
            border: none;
            color: var(--text-primary);
            font-size: 1.2rem;
            cursor: pointer;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            margin-right: 0.5rem;
            flex-shrink: 0;
            transition: var(--transition);
        }
        .admin-sidebar-toggle:hover {
            background: var(--bg-main);
        }

        .admin-sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 95;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        .admin-sidebar-overlay.active {
            opacity: 1;
            pointer-events: all;
        }

        @media (max-width: 900px) {
            .admin-sidebar { width: 220px; min-width: 220px; }
            .admin-main { margin-left: 220px; }
        }
        @media (max-width: 680px) {
            .admin-sidebar { position: fixed; left: -270px; transition: left 0.3s; }
            .admin-sidebar.open { left: 0; }
            .admin-main { margin-left: 0; }
            .admin-sidebar-toggle { display: inline-flex; }
            .admin-topbar { padding: 1rem 1.25rem; justify-content: flex-start; }
            .topbar-right { margin-left: auto; }
            .admin-content { padding: 1.25rem 1rem; }
            .stats-grid { grid-template-columns: 1fr; }
            .modal-box { padding: 1.25rem; width: 95%; }
            .modal-box div[style*="grid-template-columns:1fr 1fr"],
            .modal-box div[style*="grid-template-columns: 1fr 1fr"] { grid-template-columns: 1fr !important; }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const topbar = document.querySelector('.admin-topbar');
            if (topbar) {
                const toggleBtn = document.createElement('button');
                toggleBtn.className = 'admin-sidebar-toggle';
                toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
                toggleBtn.setAttribute('aria-label', 'Toggle sidebar');
                topbar.insertBefore(toggleBtn, topbar.firstChild);
                
                toggleBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    document.getElementById('adminSidebar').classList.toggle('open');
                    document.getElementById('adminSidebarOverlay').classList.toggle('active');
                });
            }
            
            const overlay = document.createElement('div');
            overlay.className = 'admin-sidebar-overlay';
            overlay.id = 'adminSidebarOverlay';
            document.body.appendChild(overlay);
            
            overlay.addEventListener('click', () => {
                document.getElementById('adminSidebar').classList.remove('open');
                overlay.classList.remove('active');
            });
        });
    </script>
</head>
<body>

<!-- Sidebar -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon"><i class="fas fa-hotel"></i></div>
        <div class="sidebar-brand-text">
            <strong>ACCHM</strong>
            <span>Admin Panel</span>
        </div>
    </div>

    <div class="sidebar-section-label">Main</div>
    <nav class="sidebar-nav">
        <a href="index.php" class="nav-item <?php echo ($active_page ?? '') === 'dashboard' ? 'active' : ''; ?>">
            <span class="nav-icon"><i class="fas fa-chart-pie"></i></span>
            Dashboard
            <?php if (($count_unread_general + $count_unread_admissions) > 0): ?>
                <span class="nav-badge"><?php echo $count_unread_general + $count_unread_admissions; ?></span>
            <?php endif; ?>
        </a>

        <div class="sidebar-section-label" style="padding-top:1rem;">Enquiries</div>
        <a href="index.php?tab=general" class="nav-item <?php echo ($active_page ?? '') === 'general' ? 'active' : ''; ?>">
            <span class="nav-icon"><i class="fas fa-envelope-open-text"></i></span>
            General Enquiries
            <?php if ($count_unread_general > 0): ?>
                <span class="nav-badge"><?php echo $count_unread_general; ?></span>
            <?php else: ?>
                <span class="nav-badge muted"><?php echo $count_general; ?></span>
            <?php endif; ?>
        </a>
        <a href="index.php?tab=admissions" class="nav-item <?php echo ($active_page ?? '') === 'admissions' ? 'active' : ''; ?>">
            <span class="nav-icon"><i class="fas fa-user-graduate"></i></span>
            Admissions
            <?php if ($count_unread_admissions > 0): ?>
                <span class="nav-badge"><?php echo $count_unread_admissions; ?></span>
            <?php else: ?>
                <span class="nav-badge muted"><?php echo $count_admissions; ?></span>
            <?php endif; ?>
        </a>

        <div class="sidebar-section-label" style="padding-top:1rem;">Content</div>
        <a href="manage_blogs.php" class="nav-item <?php echo ($active_page ?? '') === 'blogs' ? 'active' : ''; ?>">
            <span class="nav-icon"><i class="fas fa-newspaper"></i></span>
            Blog Manager
            <span class="nav-badge muted"><?php echo $count_blogs; ?></span>
        </a>
        <a href="manage_courses.php" class="nav-item <?php echo ($active_page ?? '') === 'courses' ? 'active' : ''; ?>">
            <span class="nav-icon"><i class="fas fa-graduation-cap"></i></span>
            Courses
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)); ?></div>
            <div class="user-info">
                <strong><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></strong>
                <span>Administrator</span>
            </div>
        </div>
        <a href="index.php?logout=1" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</aside>

<!-- Main content shell opened here; each page provides its own topbar + content, then closes -->
<div class="admin-main">
