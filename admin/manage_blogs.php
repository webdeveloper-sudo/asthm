<?php
session_start();

// ── AJAX / POST handler ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    require_once '../includes/db.php';
    header('Content-Type: application/json');
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $title   = trim($_POST['title']   ?? '');
        $author  = trim($_POST['author']  ?? 'Admin');
        $slug    = trim($_POST['slug']    ?? '');
        $excerpt = trim($_POST['excerpt'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $img     = trim($_POST['image_url'] ?? '');

        if (!$title || !$slug || !$content) {
            echo json_encode(['success' => false, 'error' => 'Title, slug and content are required.']);
            exit;
        }
        try {
            $stmt = $pdo->prepare("INSERT INTO blog_posts (title, author, slug, excerpt, content, image_url) VALUES (?,?,?,?,?,?)");
            $stmt->execute([$title, $author, $slug, $excerpt, $content, $img]);
            $newId = $pdo->lastInsertId();
            $post = $pdo->query("SELECT * FROM blog_posts WHERE id = $newId")->fetch();
            echo json_encode(['success' => true, 'post' => $post]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Slug already exists or DB error: ' . $e->getMessage()]);
        }
        exit;
    }

    if ($action === 'update') {
        $id      = (int)($_POST['id']      ?? 0);
        $title   = trim($_POST['title']   ?? '');
        $author  = trim($_POST['author']  ?? 'Admin');
        $slug    = trim($_POST['slug']    ?? '');
        $excerpt = trim($_POST['excerpt'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $img     = trim($_POST['image_url'] ?? '');

        if (!$id || !$title || !$slug || !$content) {
            echo json_encode(['success' => false, 'error' => 'Title, slug and content are required.']);
            exit;
        }
        try {
            $stmt = $pdo->prepare("UPDATE blog_posts SET title=?,author=?,slug=?,excerpt=?,content=?,image_url=? WHERE id=?");
            $stmt->execute([$title, $author, $slug, $excerpt, $content, $img, $id]);
            $post = $pdo->query("SELECT * FROM blog_posts WHERE id = $id")->fetch();
            echo json_encode(['success' => true, 'post' => $post]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Slug conflict or DB error: ' . $e->getMessage()]);
        }
        exit;
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $pdo->prepare("DELETE FROM blog_posts WHERE id = ?")->execute([$id]);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'get') {
        $id = (int)($_POST['id'] ?? 0);
        $post = $pdo->query("SELECT * FROM blog_posts WHERE id = $id")->fetch();
        echo json_encode(['success' => (bool)$post, 'post' => $post]);
        exit;
    }

    echo json_encode(['success' => false, 'error' => 'Unknown action']);
    exit;
}

$active_page = 'blogs';
$admin_page_title = 'Blog Manager';
require_once '_layout.php';

// Fetch all blogs for initial render
$blogs = $pdo->query("SELECT * FROM blog_posts ORDER BY published_at DESC")->fetchAll();
?>

<div class="admin-topbar">
    <div>
        <div class="topbar-title">Blog Manager</div>
        <div class="topbar-subtitle">Create, edit and manage all blog posts</div>
    </div>
    <div class="topbar-right">
        <button class="btn btn-primary" onclick="openModal('add')">
            <i class="fas fa-plus"></i> New Post
        </button>
    </div>
</div>

<div class="admin-content">

    <!-- Alert Banner -->
    <div id="alertBanner" style="display:none;"></div>

    <!-- Blog Cards Grid -->
    <div id="blogGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:1.5rem;">
        <?php if (empty($blogs)): ?>
        <div class="alert alert-info" style="grid-column:1/-1;">
            <i class="fas fa-info-circle"></i> No blog posts yet. Click <strong>New Post</strong> to create one.
        </div>
        <?php else: ?>
        <?php foreach ($blogs as $b): ?>
        <div class="blog-card card" id="blog-card-<?php echo $b['id']; ?>" style="overflow:hidden;display:flex;flex-direction:column;">
            <div class="blog-card-img" style="height:180px;overflow:hidden;background:#f4f6fb;position:relative;">
                <?php if ($b['image_url']): ?>
                <img src="<?php echo htmlspecialchars($b['image_url']); ?>"
                     alt="<?php echo htmlspecialchars($b['title']); ?>"
                     style="width:100%;height:100%;object-fit:cover;"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                <?php endif; ?>
                <div style="display:<?php echo $b['image_url'] ? 'none' : 'flex'; ?>;width:100%;height:100%;align-items:center;justify-content:center;background:linear-gradient(135deg,#f4f6fb,#e8ecf8);color:#c4c9d8;flex-direction:column;gap:0.5rem;">
                    <i class="fas fa-image" style="font-size:2.5rem;"></i>
                    <span style="font-size:0.75rem;font-weight:500;">No image set</span>
                </div>
            </div>
            <div style="padding:1.25rem 1.25rem 0.75rem;flex:1;display:flex;flex-direction:column;">
                <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.6rem;flex-wrap:wrap;">
                    <span style="background:rgba(232,41,76,0.1);color:var(--accent);font-size:0.7rem;font-weight:700;padding:2px 9px;border-radius:20px;">BLOG</span>
                    <span style="font-size:0.73rem;color:var(--text-muted);"><?php echo date('d M Y', strtotime($b['published_at'])); ?></span>
                </div>
                <h3 style="font-size:0.98rem;font-weight:700;color:var(--text-primary);margin-bottom:0.4rem;line-height:1.4;">
                    <?php echo htmlspecialchars($b['title']); ?>
                </h3>
                <div style="display:flex;align-items:center;gap:0.4rem;margin-bottom:0.6rem;">
                    <i class="fas fa-user-circle" style="color:var(--text-muted);font-size:0.8rem;"></i>
                    <span style="font-size:0.78rem;color:var(--text-muted);font-weight:500;"><?php echo htmlspecialchars($b['author'] ?? 'Admin'); ?></span>
                </div>
                <p style="font-size:0.81rem;color:var(--text-secondary);line-height:1.55;flex:1;margin-bottom:0;">
                    <?php echo htmlspecialchars(mb_strimwidth($b['excerpt'] ?? '', 0, 90, '…')); ?>
                </p>
            </div>
            <div style="padding:0.75rem 1.25rem 1.25rem;display:flex;gap:0.5rem;border-top:1px solid var(--border);margin-top:0.75rem;">
                <button class="btn btn-ghost btn-sm" style="flex:1;" onclick="editPost(<?php echo $b['id']; ?>)">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $b['id']; ?>, '<?php echo htmlspecialchars(addslashes($b['title'])); ?>')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div><!-- /.admin-content -->
</div><!-- /.admin-main -->

<!-- ══════════════ Blog Post Modal ══════════════ -->
<div class="modal-overlay" id="blogModal">
    <div class="modal-box" style="max-width:740px;">
        <div class="modal-header">
            <div class="modal-title" id="modalTitle"><i class="fas fa-pen-to-square" style="color:var(--accent);"></i> <span id="modalTitleText">New Blog Post</span></div>
            <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        </div>

        <div id="formError" style="display:none;" class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <span id="formErrorText"></span></div>

        <form id="blogForm" onsubmit="submitBlogForm(event)">
            <input type="hidden" id="blogId" name="id" value="">
            <input type="hidden" id="blogAction" name="action" value="add">
            <input type="hidden" name="ajax" value="1">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label" for="fTitle">Post Title <span style="color:var(--accent);">*</span></label>
                    <input type="text" id="fTitle" name="title" class="form-control" placeholder="e.g. The Future of Hospitality" required oninput="autoSlug()">
                </div>
                <div class="form-group">
                    <label class="form-label" for="fAuthor">Author</label>
                    <input type="text" id="fAuthor" name="author" class="form-control" placeholder="e.g. Admin" value="Admin">
                </div>
                <div class="form-group">
                    <label class="form-label" for="fSlug">
                        Slug (URL) <span style="color:var(--accent);">*</span>
                        <span style="font-weight:400;color:var(--text-muted);font-size:0.75rem;">(auto-generated)</span>
                    </label>
                    <input type="text" id="fSlug" name="slug" class="form-control" placeholder="e.g. future-of-hospitality" required
                           pattern="^[a-z0-9-]+$" title="Lowercase letters, numbers and hyphens only">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="fImage">Image URL <span style="font-weight:400;color:var(--text-muted);font-size:0.75rem;">(paste any image link)</span></label>
                <!-- Image Preview -->
                <div class="img-preview" id="imgPreview">
                    <img id="imgPreviewEl" src="" alt="Preview">
                    <div class="preview-placeholder" id="previewPlaceholder">
                        <i class="fas fa-image" style="font-size:1.8rem;color:var(--text-muted);display:block;margin-bottom:0.35rem;"></i>
                        Paste an image URL below to preview
                    </div>
                </div>
                <input type="url" id="fImage" name="image_url" class="form-control" placeholder="https://example.com/image.jpg" oninput="previewImage()">
            </div>

            <div class="form-group">
                <label class="form-label" for="fExcerpt">Excerpt <span style="font-weight:400;color:var(--text-muted);font-size:0.75rem;">(short summary shown in blog list)</span></label>
                <textarea id="fExcerpt" name="excerpt" class="form-control" rows="2" placeholder="Write a short compelling summary of this post…" style="min-height:70px;"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="fContent">Content <span style="color:var(--accent);">*</span> <span style="font-weight:400;color:var(--text-muted);font-size:0.75rem;">(HTML supported)</span></label>
                <textarea id="fContent" name="content" class="form-control" rows="8" placeholder="Write the full blog post content here. You can use basic HTML tags like &lt;p&gt;, &lt;h3&gt;, &lt;ul&gt;, &lt;strong&gt;, etc." required></textarea>
            </div>

            <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:0.5rem;">
                <button type="button" class="btn btn-ghost" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-paper-plane"></i> <span id="submitBtnText">Publish Post</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box" style="max-width:420px;">
        <div class="modal-header">
            <div class="modal-title" style="color:#e8294c;"><i class="fas fa-exclamation-triangle"></i> Delete Post?</div>
            <button class="modal-close" onclick="closeDeleteModal()"><i class="fas fa-times"></i></button>
        </div>
        <p style="color:var(--text-secondary);font-size:0.88rem;margin-bottom:0.5rem;">You are about to permanently delete:</p>
        <p style="font-weight:700;color:var(--text-primary);margin-bottom:1.5rem;font-size:0.95rem;" id="deletePostTitle"></p>
        <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
            <button class="btn btn-ghost" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn btn-danger" id="confirmDeleteBtn"><i class="fas fa-trash"></i> Delete Permanently</button>
        </div>
    </div>
</div>

<script>
const BLOGS_PHP = 'manage_blogs.php';
let _deleteId = null;

// ── Slug auto-gen ──
function autoSlug() {
    const titleEl = document.getElementById('fTitle');
    const slugEl  = document.getElementById('fSlug');
    // Only auto-update if slug hasn't been manually changed (or is empty)
    if (!slugEl.dataset.manual) {
        slugEl.value = titleEl.value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .substring(0, 80);
    }
}
document.getElementById('fSlug').addEventListener('input', function() {
    this.dataset.manual = 'yes';
});

// ── Image preview ──
function previewImage() {
    const url = document.getElementById('fImage').value.trim();
    const img = document.getElementById('imgPreviewEl');
    const ph  = document.getElementById('previewPlaceholder');
    if (url) {
        img.src = url;
        img.style.display = 'block';
        ph.style.display  = 'none';
    } else {
        img.style.display = 'none';
        ph.style.display  = 'block';
    }
}

// ── Modal helpers ──
function openModal(mode, id) {
    document.getElementById('formError').style.display = 'none';
    document.getElementById('blogForm').reset();
    delete document.getElementById('fSlug').dataset.manual;
    document.getElementById('imgPreviewEl').style.display = 'none';
    document.getElementById('previewPlaceholder').style.display = 'block';
    document.getElementById('imgPreviewEl').src = '';

    if (mode === 'add') {
        document.getElementById('blogId').value = '';
        document.getElementById('blogAction').value = 'add';
        document.getElementById('modalTitleText').textContent = 'New Blog Post';
        document.getElementById('submitBtnText').textContent = 'Publish Post';
        document.getElementById('fAuthor').value = 'Admin';
    }
    document.getElementById('blogModal').classList.add('open');
}

function closeModal() {
    document.getElementById('blogModal').classList.remove('open');
}

document.getElementById('blogModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// ── Edit: fetch post data and pre-fill form ──
function editPost(id) {
    openModal('edit', id);
    document.getElementById('modalTitleText').textContent = 'Loading…';
    document.getElementById('submitBtnText').textContent = 'Update Post';

    const fd = new FormData();
    fd.append('ajax', '1');
    fd.append('action', 'get');
    fd.append('id', id);

    fetch(BLOGS_PHP, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.post) {
                const p = data.post;
                document.getElementById('blogId').value    = p.id;
                document.getElementById('blogAction').value = 'update';
                document.getElementById('fTitle').value   = p.title;
                document.getElementById('fAuthor').value  = p.author || 'Admin';
                document.getElementById('fSlug').value    = p.slug;
                document.getElementById('fSlug').dataset.manual = 'yes';
                document.getElementById('fExcerpt').value = p.excerpt || '';
                document.getElementById('fContent').value = p.content;
                document.getElementById('fImage').value   = p.image_url || '';
                previewImage();
                document.getElementById('modalTitleText').textContent = 'Edit Post';
            }
        });
}

// ── Form submit ──
function submitBlogForm(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    const origText = document.getElementById('submitBtnText').textContent;
    btn.disabled = true;
    document.getElementById('submitBtnText').textContent = 'Saving…';
    document.getElementById('formError').style.display = 'none';

    const fd = new FormData(document.getElementById('blogForm'));

    fetch(BLOGS_PHP, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            document.getElementById('submitBtnText').textContent = origText;
            if (data.success) {
                closeModal();
                showAlert('success', 'Blog post saved successfully! 🎉');
                refreshBlogCard(data.post);
            } else {
                document.getElementById('formErrorText').textContent = data.error || 'Something went wrong.';
                document.getElementById('formError').style.display = 'flex';
            }
        })
        .catch(() => {
            btn.disabled = false;
            document.getElementById('submitBtnText').textContent = origText;
            document.getElementById('formErrorText').textContent = 'Network error. Please try again.';
            document.getElementById('formError').style.display = 'flex';
        });
}

// ── Refresh/add card in grid ──
function refreshBlogCard(p) {
    const grid = document.getElementById('blogGrid');
    // Remove any empty-state alert
    const existingAlert = grid.querySelector('.alert');
    if (existingAlert) existingAlert.remove();

    const imgHtml = p.image_url
        ? `<img src="${escHtml(p.image_url)}" alt="${escHtml(p.title)}" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">`
        : '';
    const noImgStyle = p.image_url ? 'display:none;' : 'display:flex;';
    const excerpt = (p.excerpt || '').substring(0, 90) + ((p.excerpt || '').length > 90 ? '…' : '');
    const dateStr = new Date(p.published_at).toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric'});
    const author  = p.author || 'Admin';

    const cardHtml = `
    <div class="blog-card card" id="blog-card-${p.id}" style="overflow:hidden;display:flex;flex-direction:column;animation:fadeIn 0.3s ease;">
        <div class="blog-card-img" style="height:180px;overflow:hidden;background:#f4f6fb;position:relative;">
            ${imgHtml}
            <div style="${noImgStyle}width:100%;height:100%;align-items:center;justify-content:center;background:linear-gradient(135deg,#f4f6fb,#e8ecf8);color:#c4c9d8;flex-direction:column;gap:0.5rem;">
                <i class="fas fa-image" style="font-size:2.5rem;"></i>
                <span style="font-size:0.75rem;font-weight:500;">No image set</span>
            </div>
        </div>
        <div style="padding:1.25rem 1.25rem 0.75rem;flex:1;display:flex;flex-direction:column;">
            <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.6rem;flex-wrap:wrap;">
                <span style="background:rgba(232,41,76,0.1);color:var(--accent);font-size:0.7rem;font-weight:700;padding:2px 9px;border-radius:20px;">BLOG</span>
                <span style="font-size:0.73rem;color:var(--text-muted);">${dateStr}</span>
            </div>
            <h3 style="font-size:0.98rem;font-weight:700;color:var(--text-primary);margin-bottom:0.4rem;line-height:1.4;">${escHtml(p.title)}</h3>
            <div style="display:flex;align-items:center;gap:0.4rem;margin-bottom:0.6rem;">
                <i class="fas fa-user-circle" style="color:var(--text-muted);font-size:0.8rem;"></i>
                <span style="font-size:0.78rem;color:var(--text-muted);font-weight:500;">${escHtml(author)}</span>
            </div>
            <p style="font-size:0.81rem;color:var(--text-secondary);line-height:1.55;flex:1;margin-bottom:0;">${escHtml(excerpt)}</p>
        </div>
        <div style="padding:0.75rem 1.25rem 1.25rem;display:flex;gap:0.5rem;border-top:1px solid var(--border);margin-top:0.75rem;">
            <button class="btn btn-ghost btn-sm" style="flex:1;" onclick="editPost(${p.id})"><i class="fas fa-edit"></i> Edit</button>
            <button class="btn btn-danger btn-sm" onclick="confirmDelete(${p.id}, '${escJs(p.title)}')"><i class="fas fa-trash"></i></button>
        </div>
    </div>`;

    const existing = document.getElementById('blog-card-' + p.id);
    if (existing) {
        existing.outerHTML = cardHtml;
    } else {
        grid.insertAdjacentHTML('afterbegin', cardHtml);
    }
}

// ── Delete ──
function confirmDelete(id, title) {
    _deleteId = id;
    document.getElementById('deletePostTitle').textContent = '"' + title + '"';
    document.getElementById('deleteModal').classList.add('open');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('open');
    _deleteId = null;
}
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!_deleteId) return;
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting…';
    const fd = new FormData();
    fd.append('ajax', '1');
    fd.append('action', 'delete');
    fd.append('id', _deleteId);

    fetch(BLOGS_PHP, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const card = document.getElementById('blog-card-' + _deleteId);
                if (card) {
                    card.style.transition = 'opacity 0.3s, transform 0.3s';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        card.remove();
                        if (!document.querySelector('#blogGrid .blog-card')) {
                            document.getElementById('blogGrid').innerHTML = '<div class="alert alert-info" style="grid-column:1/-1;"><i class="fas fa-info-circle"></i> No blog posts yet. Click <strong>New Post</strong> to create one.</div>';
                        }
                    }, 300);
                }
                showAlert('success', 'Blog post deleted.');
            }
            closeDeleteModal();
            this.innerHTML = '<i class="fas fa-trash"></i> Delete Permanently';
        });
});

// ── Alert Banner ──
function showAlert(type, msg) {
    const el = document.getElementById('alertBanner');
    el.className = 'alert alert-' + type;
    el.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${msg}`;
    el.style.display = 'flex';
    el.style.marginBottom = '1.5rem';
    setTimeout(() => { el.style.display = 'none'; }, 4000);
}

// ── Helpers ──
function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function escJs(s) {
    return String(s).replace(/'/g,"\\'");
}

// CSS animation
const style = document.createElement('style');
style.textContent = '@keyframes fadeIn { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }';
document.head.appendChild(style);
</script>
