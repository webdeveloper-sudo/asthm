<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require_once '../includes/db.php';

$message = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $title = trim($_POST['title'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $img = trim($_POST['image_url'] ?? '');
        
        if ($title && $desc && $img) {
            $stmt = $pdo->prepare("INSERT INTO courses (title, description, image_url) VALUES (?, ?, ?)");
            $stmt->execute([$title, $desc, $img]);
            $message = "Course added successfully.";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Course deleted successfully.";
    }
}

$courses = $pdo->query("SELECT * FROM courses")->fetchAll();

$active_page = 'courses';
$admin_page_title = 'Manage Courses';
require_once '_layout.php';
?>

<div class="admin-topbar">
    <div>
        <div class="topbar-title">Manage Courses</div>
        <div class="topbar-subtitle">Create, view, and delete ACCHM courses</div>
    </div>
</div>

<div class="admin-content">
    
    <?php if($message): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span><?php echo htmlspecialchars($message); ?></span>
        </div>
    <?php endif; ?>

    <!-- Add Course Form -->
    <div class="card" style="margin-bottom: 2rem;">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-plus-circle" style="color:var(--accent);margin-right:0.5rem;"></i> Add New Course</div>
        </div>
        <div class="card-body">
            <form action="manage_courses.php" method="POST">
                <input type="hidden" name="action" value="add">
                
                <div class="form-group">
                    <label class="form-label" for="title">Course Title</label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="e.g. Diploma in Catering & Hotel Administration" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="image_url">Image URL</label>
                    <input type="url" id="image_url" name="image_url" class="form-control" placeholder="https://images.unsplash.com/..." required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <textarea id="description" name="description" rows="4" class="form-control" placeholder="Write course duration, eligibility details, and a brief syllabus summary..." required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Add Course
                </button>
            </form>
        </div>
    </div>

    <!-- Courses List Table -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-graduation-cap" style="color:var(--accent);margin-right:0.5rem;"></i> Existing Courses</div>
        </div>
        <div class="card-body" style="padding: 0;">
            <?php if (empty($courses)): ?>
                <div class="alert alert-info" style="margin: 1.5rem;"><i class="fas fa-info-circle"></i> No courses found. Add a course above.</div>
            <?php else: ?>
                <div class="data-table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Course Title</th>
                                <th style="width: 150px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($courses as $c): ?>
                            <tr>
                                <td><strong>#<?php echo $c['id']; ?></strong></td>
                                <td style="font-weight: 600; color: var(--text-primary);"><?php echo htmlspecialchars($c['title']); ?></td>
                                <td style="text-align: center;">
                                    <form action="manage_courses.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div><!-- /.admin-content -->
</div><!-- /.admin-main -->
</body>
</html>
