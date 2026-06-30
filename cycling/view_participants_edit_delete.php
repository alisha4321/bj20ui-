<?php
session_start();
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
    <title>View participants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; color: white; }
        .navbar { background: rgba(0,0,0,0.8) !important; border-bottom: 3px solid #2ecc71; }
        h1 { color: #2ecc71; font-weight: 900; }
        .table { color: white; border-color: rgba(46,204,113,0.2); }
        .table-dark { background: rgba(0,0,0,0.6) !important; }
        .table-striped > tbody > tr:nth-of-type(odd) > * { background: rgba(255,255,255,0.05); color: white; }
        .table-striped > tbody > tr:nth-of-type(even) > * { background: rgba(255,255,255,0.02); color: white; }
        .table td, .table th { border-color: rgba(46,204,113,0.2) !important; }
        .alert-info { background: rgba(52,152,219,0.2); border: 1px solid #3498db; color: #3498db; }
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
    <h1>View all of the participants for edit or delete</h1>
    <?php
    include 'dbconnect.php';
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            //TODO SELECT - view the participants with links to edit or delete them.
            $stmt = $conn->prepare("SELECT * FROM participant ORDER BY surname ASC");
            $stmt->execute();
            $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($participants) > 0) {
                echo '<div class="table-responsive mt-3">';
                echo '<table class="table table-striped table-bordered">';
                echo '<thead class="table-dark"><tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Surname</th>
                        <th>Email</th>
                        <th>Power Output (W)</th>
                        <th>Distance (KM)</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr></thead><tbody>';
                foreach ($participants as $row) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['firstname']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['surname']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['power_output']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['distance']) . '</td>';
                    echo '<td><a href="edit_participant.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning fw-bold">✏️ Edit</a></td>';
                    echo '<td><a href="delete.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger fw-bold">🗑️ Delete</a></td>';
                    echo '</tr>';
                }
                echo '</tbody></table></div>';
            } else {
                echo '<div class="alert alert-info mt-3">No participants found.</div>';
            }
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
