<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';
include 'csrf.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!validateCSRFToken($csrf_token)) {
        $error = "Security validation failed.";
    } else {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        // 🔥 NEW: Capture project due date from the form
        $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
        $user_id = $_SESSION['user_id'];

        if (empty($name)) {
            $error = "Project name is required.";
        } else {
            // 🔥 UPDATED: Wrap project and owner membership in a transaction
            try {
                $conn->begin_transaction();
                
                $stmt = $conn->prepare("INSERT INTO projects (name, description, owner_id, due_date) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssis", $name, $description, $user_id, $due_date);
                
                if (!$stmt->execute()) {
                    throw new Exception("Error creating project: " . $stmt->error);
                }
                
                $project_id = $stmt->insert_id;
                $stmt->close();
                
                // Establish creator automatically with 'owner' privileges 
                $member_stmt = $conn->prepare("INSERT INTO project_members (project_id, user_id, role) VALUES (?, ?, 'owner')");
                $member_stmt->bind_param("ii", $project_id, $user_id);
                
                if (!$member_stmt->execute()) {
                    throw new Exception("Error adding owner to project members: " . $member_stmt->error);
                }
                
                $member_stmt->close();
                
                // Commit transaction
                $conn->commit();
                
                $_SESSION['success'] = "Project created successfully!";
                header("Location: project_view.php?project_id=" . $project_id);
                exit;
            } catch (Exception $e) {
                // Rollback on error
                $conn->rollback();
                $error = $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProManage - Create Project</title>
    <link rel="stylesheet" href="assets/css/task-create.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h1><span class="pro-text">Pro</span><span class="manage-text">Manage</span></h1>
                <p class="form-subtitle">Create a new project</p>
            </div>

            <?php if(!empty($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
                
                <div class="form-group">
                    <label for="name">Project Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" placeholder="Enter project name" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Describe your project..."></textarea>
                </div>

                <div class="form-group">
                    <label for="due_date">Due Date</label>
                    <input type="date" id="due_date" name="due_date">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Create Project</button>
                    <a href="dashboard.php" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>