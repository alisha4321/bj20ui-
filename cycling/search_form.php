<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: admin_login.html');
    exit();
}
include 'dbconnect.php';
$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$clubs = $conn->prepare("SELECT * FROM club ORDER BY name ASC");
$clubs->execute();
$clubList = $clubs->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Search for participants or clubs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; color: white; }
        .navbar { background: rgba(0,0,0,0.8) !important; border-bottom: 3px solid #2ecc71; }
        h1, h2 { color: #2ecc71; font-weight: 900; }
        .search-card { background: rgba(255,255,255,0.05); border: 2px solid rgba(46,204,113,0.3); border-radius: 15px; padding: 30px; margin-bottom: 25px; }
        .form-control { background: rgba(255,255,255,0.1); border: 1px solid rgba(46,204,113,0.3); color: white; border-radius: 8px; }
        .form-control:focus { background: rgba(255,255,255,0.15); border-color: #2ecc71; color: white; box-shadow: 0 0 10px rgba(46,204,113,0.3); }
        .form-control::placeholder { color: #7f8c8d; }
        label, p { color: #bdc3c7; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark">
        <div class="container d-flex justify-content-between">
            <a href="index.html" class="navbar-brand fw-bold">🚴 Cit-E Cycling</a>
            <a href="admin_menu.php" class="btn btn-outline-success btn-sm">← Admin Menu</a>
        </div>
    </nav>
    <div class="container mt-4">
    <h1 class="mb-4">🔍 Search</h1>

    <h2 class="mb-3">Search for an individual participant</h2>
    <form action="search_result.php" method="POST" class="search-card">
        <div class="mb-3">
        <p>Participant firstname or surname</p>
        <input type="text" name="firstname" class="form-control" placeholder="Enter firstname or surname" required minlength="2"><br>
        </div>
        <input type="hidden" name="participant" value="1">
        <input type="Submit" class="btn btn-success fw-bold px-4" value="Search Participant">
    </form>
    
    <h2 class="mb-3">Search for a club / team</h2>
    <form action="search_result.php" method="POST" class="search-card">
        <div class="mb-3">
        <p>Club name</p>
        <input type="text" name="club" class="form-control" placeholder="Enter club name" required minlength="2" list="clublist">
        <datalist id="clublist">
            <?php foreach ($clubList as $club): ?>
                <option value="<?php echo htmlspecialchars($club['name']); ?>">
            <?php endforeach; ?>
        </datalist>
        </div>
        <input type="Submit" class="btn btn-warning fw-bold px-4" value="Search Club">
    </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
