<?php
session_start();

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Handle mark-as-read AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    require_once '../includes/db.php';
    header('Content-Type: application/json');

    if ($_POST['action'] === 'mark_read_general') {
        $stmt = $pdo->prepare("UPDATE contact_inquiries SET is_read = 1 WHERE id = ?");
        $stmt->execute([(int)$_POST['id']]);
        echo json_encode(['success' => true]);
        exit;
    }
    if ($_POST['action'] === 'mark_read_admission') {
        $stmt = $pdo->prepare("UPDATE admissions SET is_read = 1 WHERE id = ?");
        $stmt->execute([(int)$_POST['id']]);
        echo json_encode(['success' => true]);
        exit;
    }
    if ($_POST['action'] === 'delete_general') {
        $stmt = $pdo->prepare("DELETE FROM contact_inquiries WHERE id = ?");
        $stmt->execute([(int)$_POST['id']]);
        echo json_encode(['success' => true]);
        exit;
    }
    if ($_POST['action'] === 'delete_admission') {
        $stmt = $pdo->prepare("DELETE FROM admissions WHERE id = ?");
        $stmt->execute([(int)$_POST['id']]);
        echo json_encode(['success' => true]);
        exit;
    }
    echo json_encode(['success' => false]);
    exit;
}

$active_page = 'dashboard';
$admin_page_title = 'Dashboard';
require_once '_layout.php';

// Fetch data
$general_inquiries = $pdo->query("SELECT * FROM contact_inquiries ORDER BY created_at DESC")->fetchAll();
$admissions = $pdo->query("
    SELECT a.*, c.title as course_title
    FROM admissions a
    LEFT JOIN courses c ON a.course_id = c.id
    ORDER BY a.created_at DESC
")->fetchAll();

$total_general   = count($general_inquiries);
$total_admissions = count($admissions);
$unread_general  = $pdo->query("SELECT COUNT(*) FROM contact_inquiries WHERE is_read = 0")->fetchColumn();
$unread_admissions = $pdo->query("SELECT COUNT(*) FROM admissions WHERE is_read = 0")->fetchColumn();
$total_blogs     = $pdo->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn();
$total_courses   = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();

$default_tab = $_GET['tab'] ?? 'general';
?>

<div class="admin-topbar">
    <div>
        <div class="topbar-title">Dashboard</div>
        <div class="topbar-subtitle">Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?> 👋</div>
    </div>
    <div class="topbar-right">
        <span class="topbar-time" id="liveTime"></span>
    </div>
</div>

<div class="admin-content">

    <!-- Stats Row -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-envelope"></i></div>
            <div>
                <div class="stat-value"><?php echo $total_general; ?></div>
                <div class="stat-label">General Enquiries</div>
                <?php if ($unread_general > 0): ?>
                    <div class="stat-sub warn"><i class="fas fa-circle" style="font-size:0.6rem;"></i> <?php echo $unread_general; ?> unread</div>
                <?php else: ?>
                    <div class="stat-sub ok"><i class="fas fa-check-circle" style="font-size:0.7rem;"></i> All read</div>
                <?php endif; ?>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-user-graduate"></i></div>
            <div>
                <div class="stat-value"><?php echo $total_admissions; ?></div>
                <div class="stat-label">Admission Applications</div>
                <?php if ($unread_admissions > 0): ?>
                    <div class="stat-sub warn"><i class="fas fa-circle" style="font-size:0.6rem;"></i> <?php echo $unread_admissions; ?> unread</div>
                <?php else: ?>
                    <div class="stat-sub ok"><i class="fas fa-check-circle" style="font-size:0.7rem;"></i> All read</div>
                <?php endif; ?>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-newspaper"></i></div>
            <div>
                <div class="stat-value"><?php echo $total_blogs; ?></div>
                <div class="stat-label">Blog Posts</div>
                <div class="stat-sub ok"><a href="manage_blogs.php" style="color:inherit;text-decoration:none;"><i class="fas fa-arrow-right" style="font-size:0.65rem;"></i> Manage</a></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-graduation-cap"></i></div>
            <div>
                <div class="stat-value"><?php echo $total_courses; ?></div>
                <div class="stat-label">Courses Listed</div>
                <div class="stat-sub ok"><a href="manage_courses.php" style="color:inherit;text-decoration:none;"><i class="fas fa-arrow-right" style="font-size:0.65rem;"></i> Manage</a></div>
            </div>
        </div>
    </div>

    <!-- Enquiries Card -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-inbox" style="color:var(--accent);margin-right:0.5rem;"></i> Enquiries Monitor</div>
        </div>
        <div class="card-body">
            <!-- Tabs -->
            <div class="tabs">
                <button class="tab-btn <?php echo $default_tab === 'general' ? 'active' : ''; ?>"
                        onclick="switchTab('general', this)">
                    <i class="fas fa-envelope-open-text"></i>
                    General Enquiries
                    <span class="tab-count"><?php echo $total_general; ?></span>
                </button>
                <button class="tab-btn <?php echo $default_tab === 'admissions' ? 'active' : ''; ?>"
                        onclick="switchTab('admissions', this)">
                    <i class="fas fa-user-graduate"></i>
                    Admission Applications
                    <span class="tab-count"><?php echo $total_admissions; ?></span>
                </button>
            </div>

            <!-- General Enquiries Pane -->
            <div id="pane-general" class="tab-pane <?php echo $default_tab === 'general' ? 'active' : ''; ?>">
                <div style="display:flex;gap:1rem;align-items:center;margin-bottom:1rem;flex-wrap:wrap;">
                    <div class="search-bar-wrap" style="flex:1;min-width:200px;">
                        <i class="fas fa-search"></i>
                        <input type="text" class="form-control search-input" id="searchGeneral" placeholder="Search by name, email or phone…" oninput="filterTable('tableGeneral', this.value)">
                    </div>
                    <select class="form-control" style="max-width:160px;" onchange="filterStatus('tableGeneral', this.value)">
                        <option value="">All Status</option>
                        <option value="unread">Unread</option>
                        <option value="read">Read</option>
                    </select>
                </div>

                <?php if (empty($general_inquiries)): ?>
                    <div class="alert alert-info"><i class="fas fa-info-circle"></i> No general enquiries yet.</div>
                <?php else: ?>
                <div class="data-table-wrap">
                    <table class="data-table" id="tableGeneral">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($general_inquiries as $row): ?>
                            <tr id="general-row-<?php echo $row['id']; ?>" data-status="<?php echo $row['is_read'] ? 'read' : 'unread'; ?>">
                                <td>
                                    <span class="badge <?php echo $row['is_read'] ? 'badge-read' : 'badge-unread'; ?>" id="badge-g-<?php echo $row['id']; ?>">
                                        <?php echo $row['is_read'] ? 'Read' : 'Unread'; ?>
                                    </span>
                                </td>
                                <td style="font-weight:600;"><?php echo htmlspecialchars($row['name'] ?? '—'); ?></td>
                                <td class="td-muted"><?php echo htmlspecialchars($row['email'] ?? '—'); ?></td>
                                <td class="td-muted"><?php echo htmlspecialchars($row['phone'] ?? '—'); ?></td>
                                <td style="max-width:220px;">
                                    <span title="<?php echo htmlspecialchars($row['message'] ?? ''); ?>">
                                        <?php echo htmlspecialchars(mb_strimwidth($row['message'] ?? '—', 0, 55, '…')); ?>
                                    </span>
                                </td>
                                <td class="td-muted" style="white-space:nowrap;"><?php echo date('d M Y, g:ia', strtotime($row['created_at'])); ?></td>
                                <td style="white-space:nowrap;display:flex;gap:0.4rem;">
                                    <?php if (!$row['is_read']): ?>
                                    <button class="btn btn-success btn-sm" onclick="markRead('general', <?php echo $row['id']; ?>)" id="readbtn-g-<?php echo $row['id']; ?>" title="Mark as read">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <?php endif; ?>
                                    <button class="btn btn-danger btn-sm" onclick="deleteRow('general', <?php echo $row['id']; ?>)" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>

            <!-- Admissions Pane -->
            <div id="pane-admissions" class="tab-pane <?php echo $default_tab === 'admissions' ? 'active' : ''; ?>">
                <div style="display:flex;gap:1rem;align-items:center;margin-bottom:1rem;flex-wrap:wrap;">
                    <div class="search-bar-wrap" style="flex:1;min-width:200px;">
                        <i class="fas fa-search"></i>
                        <input type="text" class="form-control search-input" id="searchAdmissions" placeholder="Search by name, mobile, state or course…" oninput="filterTable('tableAdmissions', this.value)">
                    </div>
                    <select class="form-control" style="max-width:160px;" onchange="filterStatus('tableAdmissions', this.value)">
                        <option value="">All Status</option>
                        <option value="unread">Unread</option>
                        <option value="read">Read</option>
                    </select>
                </div>

                <?php if (empty($admissions)): ?>
                    <div class="alert alert-info"><i class="fas fa-info-circle"></i> No admission applications yet.</div>
                <?php else: ?>
                <div class="data-table-wrap">
                    <table class="data-table" id="tableAdmissions">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Student</th>
                                <th>Parent</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Location</th>
                                <th>Board &amp; Marks</th>
                                <th>Course Interest</th>
                                <th>Callback</th>
                                <th>Campus Visit</th>
                                <th>Message</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($admissions as $row): ?>
                            <?php
                                // Support both old (first_name+last_name) and new (first_name = full student name) entries
                                $student_display = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));
                                // New form fields (may be empty for old entries)
                                $parent_name     = $row['parent_name']     ?? '';
                                $state           = $row['state']           ?? '';
                                $district        = $row['district']        ?? '';
                                $board           = $row['board']           ?? '';
                                $total_mark      = $row['total_mark']      ?? '';
                                $course_interest = $row['course_interest'] ?? ($row['course_title'] ?? '');
                                $callback        = $row['callback']        ?? '';
                                $callback_from   = $row['callback_from']   ?? '';
                                $callback_till   = $row['callback_till']   ?? '';
                                $campus_date     = $row['campus_date']     ?? '';
                                $message         = $row['message']         ?? '';
                            ?>
                            <tr id="admission-row-<?php echo $row['id']; ?>" data-status="<?php echo $row['is_read'] ? 'read' : 'unread'; ?>">
                                <td>
                                    <span class="badge <?php echo $row['is_read'] ? 'badge-read' : 'badge-unread'; ?>" id="badge-a-<?php echo $row['id']; ?>">
                                        <?php echo $row['is_read'] ? 'Read' : 'Unread'; ?>
                                    </span>
                                </td>
                                <td style="font-weight:600;white-space:nowrap;"><?php echo htmlspecialchars($student_display ?: '—'); ?></td>
                                <td class="td-muted"><?php echo htmlspecialchars($parent_name ?: '—'); ?></td>
                                <td class="td-muted" style="white-space:nowrap;"><?php echo htmlspecialchars($row['phone'] ?? '—'); ?></td>
                                <td class="td-muted"><?php echo htmlspecialchars($row['email'] ?? '—'); ?></td>
                                <td class="td-muted" style="white-space:nowrap;">
                                    <?php if ($district || $state): ?>
                                        <?php echo htmlspecialchars(implode(', ', array_filter([$district, $state]))); ?>
                                    <?php else: ?>—<?php endif; ?>
                                </td>
                                <td class="td-muted">
                                    <?php if ($board || $total_mark): ?>
                                        <span style="display:block;font-size:0.78rem;"><?php echo htmlspecialchars(mb_strimwidth($board, 0, 30, '…')); ?></span>
                                        <?php if ($total_mark): ?>
                                            <span style="background:#f0fdf4;color:#16a34a;font-size:0.72rem;font-weight:700;padding:1px 7px;border-radius:20px;"><?php echo htmlspecialchars($total_mark); ?> marks</span>
                                        <?php endif; ?>
                                    <?php else: ?>—<?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($course_interest): ?>
                                    <span style="background:rgba(102,78,136,0.1);color:#664e88;padding:3px 10px;border-radius:20px;font-size:0.74rem;font-weight:700;white-space:nowrap;display:inline-block;max-width:160px;overflow:hidden;text-overflow:ellipsis;" title="<?php echo htmlspecialchars($course_interest); ?>">
                                        <?php echo htmlspecialchars(mb_strimwidth($course_interest, 0, 28, '…')); ?>
                                    </span>
                                    <?php else: ?>—<?php endif; ?>
                                </td>
                                <td class="td-muted">
                                    <?php if ($callback === 'Yes'): ?>
                                        <span style="background:#fff7ed;color:#ea580c;font-size:0.72rem;font-weight:700;padding:2px 8px;border-radius:20px;">Yes</span>
                                        <?php if ($callback_from): ?>
                                            <span style="display:block;font-size:0.75rem;margin-top:3px;"><?php echo htmlspecialchars($callback_from); ?></span>
                                        <?php endif; ?>
                                        <?php if ($callback_till): ?>
                                            <span style="display:block;font-size:0.75rem;color:#94a3b8;">to <?php echo htmlspecialchars($callback_till); ?></span>
                                        <?php endif; ?>
                                    <?php elseif ($callback === 'No'): ?>
                                        <span style="background:#f1f5f9;color:#94a3b8;font-size:0.72rem;font-weight:700;padding:2px 8px;border-radius:20px;">No</span>
                                    <?php else: ?>—<?php endif; ?>
                                </td>
                                <td class="td-muted" style="white-space:nowrap;"><?php echo $campus_date ? htmlspecialchars(date('d M Y', strtotime($campus_date))) : '—'; ?></td>
                                <td style="max-width:180px;">
                                    <?php if ($message): ?>
                                    <span title="<?php echo htmlspecialchars($message); ?>" style="font-size:0.81rem;color:var(--text-secondary);">
                                        <?php echo htmlspecialchars(mb_strimwidth($message, 0, 45, '…')); ?>
                                    </span>
                                    <?php else: ?>—<?php endif; ?>
                                </td>
                                <td class="td-muted" style="white-space:nowrap;"><?php echo date('d M Y, g:ia', strtotime($row['created_at'])); ?></td>
                                <td style="white-space:nowrap;display:flex;gap:0.4rem;">
                                    <?php if (!$row['is_read']): ?>
                                    <button class="btn btn-success btn-sm" onclick="markRead('admission', <?php echo $row['id']; ?>)" id="readbtn-a-<?php echo $row['id']; ?>" title="Mark as read">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <?php endif; ?>
                                    <button class="btn btn-danger btn-sm" onclick="deleteRow('admission', <?php echo $row['id']; ?>)" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div><!-- /.admin-content -->
</div><!-- /.admin-main -->

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box" style="max-width:420px;">
        <div class="modal-header">
            <div class="modal-title" style="color:#e8294c;"><i class="fas fa-exclamation-triangle"></i> Confirm Delete</div>
            <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        </div>
        <p style="color:var(--text-secondary);font-size:0.9rem;margin-bottom:1.5rem;">
            Are you sure you want to permanently delete this record? This action cannot be undone.
        </p>
        <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
            <button class="btn btn-ghost" onclick="closeModal()">Cancel</button>
            <button class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
        </div>
    </div>
</div>

<script>
// ── Live clock ──
function updateClock() {
    const now = new Date();
    document.getElementById('liveTime').textContent = now.toLocaleTimeString('en-IN', {hour:'2-digit',minute:'2-digit',second:'2-digit'});
}
setInterval(updateClock, 1000); updateClock();

// ── Tabs ──
function switchTab(tabId, btn) {
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('pane-' + tabId).classList.add('active');
    btn.classList.add('active');
}

// ── Table filter ──
function filterTable(tableId, query) {
    const rows = document.querySelectorAll('#' + tableId + ' tbody tr');
    query = query.toLowerCase();
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
    });
}
function filterStatus(tableId, status) {
    const rows = document.querySelectorAll('#' + tableId + ' tbody tr');
    rows.forEach(row => {
        if (!status || row.dataset.status === status) row.style.display = '';
        else row.style.display = 'none';
    });
}

// ── Mark as read ──
function markRead(type, id) {
    const action = type === 'general' ? 'mark_read_general' : 'mark_read_admission';
    const prefix = type === 'general' ? 'g' : 'a';
    const rowId  = type === 'general' ? 'general-row-' + id : 'admission-row-' + id;

    fetch('index.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=' + action + '&id=' + id
    }).then(r => r.json()).then(data => {
        if (data.success) {
            const badge = document.getElementById('badge-' + prefix + '-' + id);
            badge.textContent = 'Read';
            badge.className = 'badge badge-read';
            const row = document.getElementById(rowId);
            if (row) row.dataset.status = 'read';
            const btn = document.getElementById('readbtn-' + prefix + '-' + id);
            if (btn) btn.remove();
        }
    });
}

// ── Delete with modal ──
let _deleteAction = null;
function deleteRow(type, id) {
    _deleteAction = { type, id };
    document.getElementById('deleteModal').classList.add('open');
}
function closeModal() {
    document.getElementById('deleteModal').classList.remove('open');
    _deleteAction = null;
}
document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!_deleteAction) return;
    const { type, id } = _deleteAction;
    const action = type === 'general' ? 'delete_general' : 'delete_admission';
    const rowId  = type === 'general' ? 'general-row-' + id : 'admission-row-' + id;

    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting…';
    fetch('index.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=' + action + '&id=' + id
    }).then(r => r.json()).then(data => {
        if (data.success) {
            const row = document.getElementById(rowId);
            if (row) {
                row.style.transition = 'opacity 0.3s, transform 0.3s';
                row.style.opacity = '0';
                row.style.transform = 'translateX(20px)';
                setTimeout(() => row.remove(), 300);
            }
        }
        closeModal();
        this.innerHTML = 'Delete';
    });
});
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
