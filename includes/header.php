<?php
// includes/header.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Musanze Skill-Share - Connecting SMEs with Student Talent</title>
    <link rel="stylesheet" href="/sme_stack/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="/sme_stack/index.php">Musanze Skill-Share</a>
            </div>
            <ul class="nav-menu">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if($_SESSION['user_role'] == 'sme'): ?>
                        <li><a href="/sme_stack/index.php?url=sme/dashboard">Dashboard</a></li>
                        <li><a href="/sme_stack/index.php?url=sme/postRequest">Post Request</a></li>
                    <?php elseif($_SESSION['user_role'] == 'student'): ?>
                        <li><a href="/sme_stack/index.php?url=student/dashboard">Dashboard</a></li>
                        <li><a href="/sme_stack/index.php?url=student/browseRequests">Browse Projects</a></li>
                    <?php elseif($_SESSION['user_role'] == 'admin'): ?>
                        <li><a href="/sme_stack/index.php?url=admin/dashboard">Dashboard</a></li>
                        <li><a href="/sme_stack/index.php?url=admin/projects">All Projects</a></li>
                        <li><a href="/sme_stack/index.php?url=admin/users">Users</a></li>
                    <?php endif; ?>
                    <li><a href="/sme_stack/index.php?url=auth/logout">Logout (<?php echo htmlspecialchars($_SESSION['user_name']); ?>)</a></li>
                <?php else: ?>
                    <li><a href="/sme_stack/index.php?url=auth/login">Login</a></li>
                    <li><a href="/sme_stack/index.php?url=auth/register">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <main class="container">