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
    <title>Search results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; color: white; }
        .navbar { background: rgba(0,0,0,0.8) !important; border-bottom: 3px solid #2ecc71; }
        h1, h2, h3, h4 { color: #2ecc71; font-weight: 900; }
        .table { color: white; border-color: rgba(46,204,113,0.2); }
        .table-dark { background: rgba(0,0,0,0.6) !important; }
        .table-secondary { background: rgba(255,255,255,0.1) !important; color: white !important; }
        .table-striped > tbody > tr:nth-of-type(odd) > * { background: rgba(255,255,255,0.05); color: white; }
        .table-striped > tbody > tr:nth-of-type(even) > * { background: rgba(255,255,255,0.02); color: white; }
        .table td, .table th { border-color: rgba(46,204,113,0.2) !important; }
        .club-card { background: rgba(255,255,255,0.05); border: 2px solid rgba(46,204,113,0.3); border-radius: 15px; overflow: hidden; margin-bottom: 25px; }
        .club-header { background: rgba(0,0,0,0.4); padding: 20px 25px; border-bottom: 2px solid rgba(46,204,113,0.3); }
        .club-body { padding: 25px; }
        .stat-card { border-radius: 10px; padding: 15px 20px; }
        .alert-info { background: rgba(52,152,219,0.2); border: 1px solid #3498db; color: #3498db; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark">
        <div class="container d-flex justify-content-between">
            <a href="index.html" class="navbar-brand fw-bold">🚴 Cit-E Cycling</a>
            <a href="search_form.php" class="btn btn-outline-success btn-sm">← New Search</a>
        </div>
    </nav>
    <div class="container mt-4">
    <?php
        include 'dbconnect.php';
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            //checking which form has been posted
            if (isset($_POST['participant']) && $_POST['participant'] == "1") {

                $search = trim($_POST['firstname']);

                if (empty($search)) {
                    echo '<div class="alert alert-info">Please enter a name to search.</div>';
                } else {
                    $stmt = $conn->prepare("SELECT p.*, c.name FROM participant p LEFT JOIN club c ON p.club_id = c.id WHERE p.firstname LIKE :search OR p.surname LIKE :search");
                    $searchParam = '%' . $search . '%';
                    $stmt->bindParam(':search', $searchParam);
                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    echo '<h2 class="mb-3">🔍 Participant Search Results for: <em>' . htmlspecialchars($search) . '</em></h2>';

                    if (count($results) > 0) {
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-striped table-bordered">';
                        echo '<thead class="table-dark"><tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Surname</th>
                                <th>Email</th>
                                <th>Power Output (W)</th>
                                <th>Distance (KM)</th>
                                <th>Club</th>
                              </tr></thead><tbody>';
                        foreach ($results as $row) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['firstname']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['surname']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['power_output']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['distance']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['name'] ?? 'No Club') . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody></table></div>';
                    } else {
                        echo '<div class="alert alert-info">No participants found matching <strong>' . htmlspecialchars($search) . '</strong>.</div>';
                    }
                }
            }
            else{
                $clubSearch = trim($_POST['club']);

                if (empty($clubSearch)) {
                    echo '<div class="alert alert-info">Please enter a club name to search.</div>';
                } else {
                    $stmt = $conn->prepare("SELECT * FROM club WHERE name LIKE :club");
                    $clubParam = '%' . $clubSearch . '%';
                    $stmt->bindParam(':club', $clubParam);
                    $stmt->execute();
                    $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    echo '<h2 class="mb-4">🏆 Club Search Results for: <em>' . htmlspecialchars($clubSearch) . '</em></h2>';

                    if (count($clubs) > 0) {
                        foreach ($clubs as $club) {
                            echo '<div class="club-card">';
                            echo '<div class="club-header"><h4>🚴 ' . htmlspecialchars($club['name']) . '</h4></div>';
                            echo '<div class="club-body">';

                            $stmt2 = $conn->prepare("SELECT * FROM participant WHERE club_id = :club_id");
                            $stmt2->bindParam(':club_id', $club['id']);
                            $stmt2->execute();
                            $members = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                            if (count($members) > 0) {
                                $totalDistance = 0;
                                $totalPower = 0;
                                foreach ($members as $member) {
                                    $totalDistance += $member['distance'];
                                    $totalPower += $member['power_output'];
                                }
                                $avgDistance = $totalDistance / count($members);
                                $avgPower = $totalPower / count($members);

                                echo '<div class="table-responsive mb-4">';
                                echo '<table class="table table-striped table-bordered">';
                                echo '<thead class="table-dark"><tr>
                                        <th>First Name</th>
                                        <th>Surname</th>
                                        <th>Email</th>
                                        <th>Power Output (W)</th>
                                        <th>Distance (KM)</th>
                                      </tr></thead><tbody>';
                                foreach ($members as $member) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($member['firstname']) . '</td>';
                                    echo '<td>' . htmlspecialchars($member['surname']) . '</td>';
                                    echo '<td>' . htmlspecialchars($member['email']) . '</td>';
                                    echo '<td>' . htmlspecialchars($member['power_output']) . '</td>';
                                    echo '<td>' . htmlspecialchars($member['distance']) . '</td>';
                                    echo '</tr>';
                                }
                                echo '</tbody></table></div>';

                                echo '<div class="row">';
                                echo '<div class="col-md-6 mb-3">';
                                echo '<div class="stat-card" style="background: rgba(46,204,113,0.15); border: 1px solid #2ecc71;">';
                                echo '<h6 style="color:#2ecc71">📊 Club Totals</h6>';
                                echo '<p class="mb-1"><strong style="color:#2ecc71">Total Distance:</strong> <span style="color:white">' . number_format($totalDistance, 2) . ' KM</span></p>';
                                echo '<p class="mb-0"><strong style="color:#2ecc71">Total Power Output:</strong> <span style="color:white">' . number_format($totalPower, 2) . ' W</span></p>';
                                echo '</div></div>';
                                echo '<div class="col-md-6 mb-3">';
                                echo '<div class="stat-card" style="background: rgba(52,152,219,0.15); border: 1px solid #3498db;">';
                                echo '<h6 style="color:#3498db">📈 Club Averages</h6>';
                                echo '<p class="mb-1"><strong style="color:#3498db">Average Distance:</strong> <span style="color:white">' . number_format($avgDistance, 2) . ' KM</span></p>';
                                echo '<p class="mb-0"><strong style="color:#3498db">Average Power Output:</strong> <span style="color:white">' . number_format($avgPower, 2) . ' W</span></p>';
                                echo '</div></div>';
                                echo '</div>';
                            } else {
                                echo '<div class="alert alert-info">No participants found in this club.</div>';
                            }
                            echo '</div></div>';
                        }
                    } else {
                        echo '<div class="alert alert-info">No clubs found matching <strong>' . htmlspecialchars($clubSearch) . '</strong>.</div>';
                    }
                }
            }
        }
        catch(PDOException $e) {
                //put error stuff here
                echo '<p>' . $e->getMessage() . '</p>';
        }
    ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
