<?php
session_start();
require_once 'db.php';

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT *
    FROM tasks
    ORDER BY created_at DESC
");

$stmt->execute();

$result = $stmt->get_result();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

    <link rel="stylesheet" href="assets/css/dashboard.css">

</head>

<body>

    <div class="container">

        <!-- Header -->

        <div class="header">

            <h1>Task Management System</h1>

            <p class="dashboard-subtitle">
                Manage your company tasks efficiently and stay productive.
            </p>

            <div class="header-actions">

                <a href="project_create.php" class="create-btn">
                    + Create Task
                </a>

                <a href="logout.php" class="logout-btn">
                    Logout
                </a>

            </div>

        </div>

        <!-- Welcome Section -->

        <div class="welcome-section">

            <h2>
                Welcome back,
                <?php echo htmlspecialchars($_SESSION['name']); ?>
                👋
            </h2>

            <p>
                Here's an overview of your current tasks.
            </p>

        </div>

        <!-- Task Dashboard -->

        <div class="task-dashboard">

            <h2>Your Tasks</h2>
<?php if($result->num_rows > 0): ?>

    <?php while($task = $result->fetch_assoc()): ?>

        <div class="task-card">

            <div class="task-info">

                <h3>
                    <?php echo htmlspecialchars($task['title']); ?>
                </h3>

                <p>
                    <?php echo htmlspecialchars($task['description']); ?>
                </p>

            </div>

            <span class="status in-progress">

                <?php echo htmlspecialchars($task['status']); ?>

            </span>

        </div>

    <?php endwhile; ?>

<?php else: ?>

    <div class="task-card">

        <div class="task-info">

            <h3>No Tasks Yet</h3>

            <p>
                Create your first company task.
            </p>

        </div>

    </div>

<?php endif; ?>
            <!-- Task Card -->

           

                <span class="status in-progress">
                    In Progress
                </span>

            </div>

            <!-- Task Card -->

            <div class="task-card">

                <div class="task-info">

                    <h3>Database Integration</h3>

                    <p>
                        Connect tasks from database into dashboard.
                    </p>

                </div>

                <span class="status pending">
                    Pending
                </span>

            </div>

            <!-- Task Card -->

            <div class="task-card">

                <div class="task-info">

                    <h3>Responsive Design</h3>

                    <p>
                        Optimize dashboard for mobile devices.
                    </p>

                </div>

                <span class="status done">
                    Done
                </span>

            </div>

        </div>

    </div>

</body>

</html>