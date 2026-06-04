<?php
/**
 * Admin Projects Management
 * CRUD operations for portfolio projects
 */
require_once __DIR__ . '/../config/database.php';

// Auth check with session timeout
$sessionTimeout = 3600; // 1 hour

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['admin_last_activity']) && (time() - $_SESSION['admin_last_activity'] > $sessionTimeout)) {
    session_destroy();
    header('Location: login.php');
    exit;
}
$_SESSION['admin_last_activity'] = time();

$message = '';
$messageType = '';

// Handle Delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    if (!isset($_GET['csrf_token']) || !verifyCSRFToken($_GET['csrf_token'])) {
        $message = 'Invalid request. Please try again.';
        $messageType = 'error';
    } else {
        try {
            $project = dbGetRow("SELECT * FROM projects WHERE id = ?", 'i', [(int)$_GET['delete']]);
            if ($project && $project['thumbnail'] && file_exists($project['thumbnail'])) {
                unlink($project['thumbnail']);
            }
            dbExecute("DELETE FROM projects WHERE id = ?", 'i', [(int)$_GET['delete']]);
            $message = 'Project deleted successfully.';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Failed to delete project.';
            $messageType = 'error';
        }
    }
    // Regenerate token after delete
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $message = 'Invalid form submission. Please try again.';
        $messageType = 'error';
    } else {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $fullDescription = trim($_POST['full_description'] ?? '');
    $categoryId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $githubUrl = trim($_POST['github_url'] ?? '');
    $liveUrl = trim($_POST['live_url'] ?? '');
    $technologies = json_encode(array_filter(array_map('trim', explode(',', $_POST['technologies'] ?? ''))));
    $featured = isset($_POST['featured']) ? 1 : 0;
    $status = $_POST['status'] ?? 'completed';
    
    // Auto-generate slug if empty
    if (empty($slug)) {
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9-]/', '-', $title));
        $slug = preg_replace('/-+/', '-', trim($slug, '-'));
    }
    
    // Handle thumbnail upload
    $thumbnail = null;
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
        
        // Validate by file content (server-side)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['thumbnail']['tmp_name']);
        finfo_close($finfo);
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        
        // Enforce file size limit (5MB)
        $maxSize = 5 * 1024 * 1024;
        
        if (in_array($ext, $allowedExts) && in_array($mime, $allowedMimes) && $_FILES['thumbnail']['size'] <= $maxSize) {
            $filename = uniqid('project_') . '.' . $ext;
            $dest = __DIR__ . '/../uploads/' . $filename;
            
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $dest)) {
                $thumbnail = 'uploads/' . $filename;
            }
        }
    }
    
    try {
        if ($_POST['action'] === 'edit' && !empty($_POST['id'])) {
            $id = (int)$_POST['id'];
            if ($thumbnail) {
                // Delete old thumbnail
                $old = dbGetRow("SELECT thumbnail FROM projects WHERE id = ?", 'i', [$id]);
                if ($old && $old['thumbnail'] && file_exists($old['thumbnail'])) {
                    unlink($old['thumbnail']);
                }
                dbExecute(
                    "UPDATE projects SET title=?, slug=?, description=?, full_description=?, thumbnail=?, github_url=?, live_url=?, category_id=?, technologies=?, featured=?, status=? WHERE id=?",
                    'ssssssssssii',
                    [$title, $slug, $description, $fullDescription, $thumbnail, $githubUrl, $liveUrl, $categoryId, $technologies, $featured, $status, $id]
                );
            } else {
                dbExecute(
                    "UPDATE projects SET title=?, slug=?, description=?, full_description=?, github_url=?, live_url=?, category_id=?, technologies=?, featured=?, status=? WHERE id=?",
                    'ssssssssssi',
                    [$title, $slug, $description, $fullDescription, $githubUrl, $liveUrl, $categoryId, $technologies, $featured, $status, $id]
                );
            }
            $message = 'Project updated successfully.';
        } else {
            dbExecute(
                "INSERT INTO projects (title, slug, description, full_description, thumbnail, github_url, live_url, category_id, technologies, featured, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                'sssssssssss',
                [$title, $slug, $description, $fullDescription, $thumbnail, $githubUrl, $liveUrl, $categoryId, $technologies, $featured, $status]
            );
            $message = 'Project added successfully.';
        }
        $messageType = 'success';
    } catch (Exception $e) {
        $message = 'Error saving project: ' . $e->getMessage();
        $messageType = 'error';
    }
    }
}

// Get project for editing
$editProject = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $editProject = dbGetRow("SELECT * FROM projects WHERE id = ?", 'i', [(int)$_GET['edit']]);
}

$projects = dbGetAll(
    "SELECT p.*, c.name as category_name 
     FROM projects p 
     LEFT JOIN categories c ON p.category_id = c.id 
     ORDER BY p.featured DESC, p.sort_order ASC, p.created_at DESC"
);

$categories = dbGetAll("SELECT * FROM categories ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects | RobiCodes Admin</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <h2>RobiCodes</h2>
            <nav>
                <a href="dashboard.php"><i class="fas fa-chart-bar"></i> Dashboard</a>
                <a href="projects.php" class="active"><i class="fas fa-code"></i> Projects</a>
                <a href="dashboard.php?logout=1" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1><?php echo $editProject ? 'Edit Project' : 'Manage Projects'; ?></h1>
                <a href="projects.php" class="btn btn--ghost btn--sm">
                    <?php echo $editProject ? '<i class="fas fa-arrow-left"></i> Back to List' : '+ Add New'; ?>
                </a>
            </div>

            <?php if ($message): ?>
                <div class="admin-alert <?php echo $messageType; ?>"><?php echo sanitizeOutput($message); ?></div>
            <?php endif; ?>

            <!-- Add/Edit Form -->
            <div class="glass-card" style="padding:1.5rem;margin-bottom:2rem">
                <form method="POST" action="" enctype="multipart/form-data" class="admin-form">
                    <input type="hidden" name="action" value="<?php echo $editProject ? 'edit' : 'add'; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <?php if ($editProject): ?>
                        <input type="hidden" name="id" value="<?php echo $editProject['id']; ?>">
                    <?php endif; ?>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                        <div class="form-group">
                            <label for="title">Project Title *</label>
                            <input type="text" id="title" name="title" required
                                   value="<?php echo $editProject ? sanitizeOutput($editProject['title']) : ''; ?>"
                                   placeholder="e.g., DreamBD">
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug (leave empty to auto-generate)</label>
                            <input type="text" id="slug" name="slug"
                                   value="<?php echo $editProject ? sanitizeOutput($editProject['slug']) : ''; ?>"
                                   placeholder="e.g., dreambd">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Short Description *</label>
                        <textarea id="description" name="description" required
                                  placeholder="Brief description of the project"><?php echo $editProject ? sanitizeOutput($editProject['description']) : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="full_description">Full Description</label>
                        <textarea id="full_description" name="full_description"
                                  placeholder="Detailed project description"><?php echo $editProject ? sanitizeOutput($editProject['full_description'] ?? '') : ''; ?></textarea>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select id="category_id" name="category_id">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"
                                        <?php echo ($editProject && $editProject['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                        <?php echo sanitizeOutput($cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="completed" <?php echo ($editProject && $editProject['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                                <option value="in-progress" <?php echo ($editProject && $editProject['status'] === 'in-progress') ? 'selected' : ''; ?>>In Progress</option>
                                <option value="archived" <?php echo ($editProject && $editProject['status'] === 'archived') ? 'selected' : ''; ?>>Archived</option>
                            </select>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                        <div class="form-group">
                            <label for="github_url">GitHub URL</label>
                            <input type="url" id="github_url" name="github_url"
                                   value="<?php echo $editProject ? sanitizeOutput($editProject['github_url'] ?? '') : ''; ?>"
                                   placeholder="https://github.com/...">
                        </div>
                        <div class="form-group">
                            <label for="live_url">Live Demo URL</label>
                            <input type="url" id="live_url" name="live_url"
                                   value="<?php echo $editProject ? sanitizeOutput($editProject['live_url'] ?? '') : ''; ?>"
                                   placeholder="https://...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="technologies">Technologies (comma separated)</label>
                        <input type="text" id="technologies" name="technologies"
                               value="<?php 
                                   if ($editProject) {
                                       $techs = json_decode($editProject['technologies'] ?? '[]', true);
                                       echo sanitizeOutput(implode(', ', $techs));
                                   }
                               ?>"
                               placeholder="e.g., PHP, MySQL, JavaScript">
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;align-items:end">
                        <div class="form-group">
                            <label for="thumbnail">Thumbnail Image</label>
                            <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                            <?php if ($editProject && $editProject['thumbnail']): ?>
                                <p style="font-size:0.75rem;color:#6c6c8a;margin-top:0.25rem">
                                    Current: <?php echo basename($editProject['thumbnail']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer">
                                <input type="checkbox" name="featured" value="1"
                                    <?php echo ($editProject && $editProject['featured']) ? 'checked' : ''; ?>>
                                Featured Project
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn--primary">
                        <?php echo $editProject ? '<i class="fas fa-save"></i> Update Project' : '<i class="fas fa-save"></i> Add Project'; ?>
                    </button>
                </form>
            </div>

            <!-- Projects List -->
            <div class="glass-card" style="padding:1.5rem">
                <h3 style="margin-bottom:1rem">All Projects (<?php echo count($projects); ?>)</h3>
                <?php if (empty($projects)): ?>
                    <p style="color:#6c6c8a;text-align:center;padding:2rem">No projects yet. Add your first project above.</p>
                <?php else: ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Featured</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projects as $proj): ?>
                            <tr>
                                <td style="font-weight:500"><?php echo sanitizeOutput($proj['title']); ?></td>
                                <td><?php echo sanitizeOutput($proj['category_name'] ?? 'Uncategorized'); ?></td>
                                <td><?php echo sanitizeOutput(ucfirst($proj['status'])); ?></td>
                                <td><?php echo $proj['featured'] ? '<i class="fas fa-star" style="color:#fdcb6e"></i>' : '<i class="far fa-star" style="color:#6c6c8a"></i>'; ?></td>
                                <td class="actions">
                                    <a href="?edit=<?php echo $proj['id']; ?>" class="btn btn--ghost btn--sm">Edit</a>
                                    <a href="?delete=<?php echo $proj['id']; ?>&amp;csrf_token=<?php echo generateCSRFToken(); ?>" 
                                       class="btn btn--ghost btn--sm" 
                                       style="color:#fd79a8"
                                       onclick="return confirm('Delete this project?')">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
