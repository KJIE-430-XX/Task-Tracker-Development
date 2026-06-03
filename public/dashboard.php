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

        <!-- Task Dashboard Section -->

<div class="task-dashboard">

    <h2>My Tasks</h2>
>>>>>>> 64d356051007ec6ebf039d98e14d36b1ab54ddbb

    <div class="task-card">

        <div class="task-info">

            <h3>Software Engineering Report</h3>
            <p>Complete the reflective report documentation.</p>
        </div>

        <span class="status in-progress">
            In Progress
        </span>

    </div>

    <div class="task-card">

        <div class="task-info">
            <h3>Database Setup</h3>
            <p>Configure MySQL database tables.</p>
        </div>

        <span class="status done">
            Done
        </span>

    </div>

    <div class="task-card">

        <div class="task-info">
            <h3>UI Design</h3>
            <p>Improve dashboard appearance and layout.</p>
        </div>

        <span class="status pending">
            Pending
        </span>

    </div>

</div>
>>>>>>> 64d356051007ec6ebf039d98e14d36b1ab54ddbb
    </div>


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