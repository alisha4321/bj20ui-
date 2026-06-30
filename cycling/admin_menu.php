<?php
session_start();
// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.html');
    exit();
}
// Protect admin area - redirect to login if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: admin_login.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; color: white; }
        .navbar { background: rgba(0,0,0,0.8) !important; border-bottom: 3px solid #2ecc71; }
        .page-title { color: #2ecc71; font-weight: 900; }
        .menu-card {
            background: rgba(255,255,255,0.05);
            border: 2px solid rgba(46,204,113,0.3);
            border-radius: 15px;
            padding: 25px 30px;
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
            display: block;
            margin-bottom: 15px;
        }
        .menu-card:hover {
            background: rgba(46,204,113,0.2);
            border-color: #2ecc71;
            transform: translateY(-3px);
            color: white;
            box-shadow: 0 10px 30px rgba(46,204,113,0.3);
        }
        .menu-card h5 { color: #2ecc71; font-weight: 700; margin-bottom: 5px; }
        .menu-card p { color: #bdc3c7; margin: 0; font-size: 0.9rem; }
        .welcome-badge {
            background: rgba(46,204,113,0.2);
            border: 1px solid #2ecc71;
            color: #2ecc71;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="index.html" class="navbar-brand fw-bold">🚴 Cit-E Cycling</a>
            <span class="welcome-badge">👤 Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="page-title mb-2">Admin Dashboard</h1>
                <p class="text-muted mb-4">Cit-E Cycling web portal</p>

                <a href="search_form.php" class="menu-card">
                    <h5>🔍 Search Participants or Clubs</h5>
                    <p>Search for individual participants or cycling clubs</p>
                </a>
                <a href="view_participants_edit_delete.php" class="menu-card">
                    <h5>👥 View All Participants</h5>
                    <p>View, edit or delete participant records</p>
                </a>

                <form method="POST" action="admin_menu.php" class="mt-3">
                    <button type="submit" name="logout" class="btn btn-danger w-100 py-3 fw-bold">🚪 Logout</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
