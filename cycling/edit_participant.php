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
    <title>Update participants score</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; color: white; }
        .navbar { background: rgba(0,0,0,0.8) !important; border-bottom: 3px solid #2ecc71; }
        .alert-success { background: rgba(46,204,113,0.2); border: 1px solid #2ecc71; color: #2ecc71; border-radius: 10px; padding: 20px; }
        .alert-danger { background: rgba(231,76,60,0.2); border: 1px solid #e74c3c; color: #e74c3c; border-radius: 10px; padding: 20px; }
        .info-row { background: rgba(255,255,255,0.05); border-radius: 8px; padding: 10px 15px; margin-top: 10px; }
        .info-label { color: #2ecc71; font-weight: 700; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark">
        <div class="container d-flex justify-content-between">
            <a href="index.html" class="navbar-brand fw-bold">🚴 Cit-E Cycling</a>
            <a href="view_participants_edit_delete.php" class="btn btn-outline-success btn-sm">← Back to Participants</a>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
    <?php
        include 'dbconnect.php';
        try {
            if($_SERVER['REQUEST_METHOD'] == 'POST') //has the user submitted the form and edited the participant
            {
                //TODO - UPDATE section
                $id = $_POST['id'];
                $power_output = trim($_POST['power_output']);
                $distance_travelled = trim($_POST['distance_travelled']);

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if (!is_numeric($power_output) || !is_numeric($distance_travelled)) {
                    echo '<div class="alert alert-danger">⚠️ Power output and distance must be numeric values.</div>';
                    echo '<a href="view_participants_edit_delete.php" class="btn btn-success mt-3">Back to participants</a>';
                } elseif ($power_output < 0 || $distance_travelled < 0) {
                    echo '<div class="alert alert-danger">⚠️ Power output and distance cannot be negative values.</div>';
                    echo '<a href="view_participants_edit_delete.php" class="btn btn-success mt-3">Back to participants</a>';
                } else {
                    $stmt = $conn->prepare("UPDATE participant SET power_output = :power_output, distance = :distance WHERE id = :id");
                    $stmt->bindParam(':power_output', $power_output);
                    $stmt->bindParam(':distance', $distance_travelled);
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    echo '<div class="alert alert-success">';
                    echo '✅ <strong>Participant scores updated successfully!</strong><br><br>';
                    echo '<div class="info-row"><span class="info-label">New Power Output: </span>' . htmlspecialchars($power_output) . ' W</div>';
                    echo '<div class="info-row"><span class="info-label">New Distance: </span>' . htmlspecialchars($distance_travelled) . ' KM</div>';
                    echo '<p class="mt-3">Redirecting back to participants list in 3 seconds...</p>';
                    echo '</div>';
                    echo '<a href="view_participants_edit_delete.php" class="btn btn-success mt-3">Back to participants now</a>';
                    echo '<script>setTimeout(function(){ window.location.href = "view_participants_edit_delete.php"; }, 3000);</script>';
                }
            }
            else{
                //TODO - SELECT section
                $id = $_GET['id'];
                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $conn->prepare("SELECT * FROM participant WHERE id = :id");
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                $participant = $stmt->fetch(PDO::FETCH_ASSOC);
                include "edit_participant_form.php";
            }
        }
        catch(PDOException $e) {
                //error stuff here
                echo '<div class="alert alert-danger">' . $e->getMessage() . '</div>';
        }

            /**
            * For the brave souls who get this far: You are the chosen ones,
            * the valiant knights of programming who toil away, without rest,
            * fixing our most awful code. To you, true saviors, kings of men,
            * I say this: never gonna give you up, never gonna let you down,
            * never gonna run around and desert you. Never gonna make you cry,
            * never gonna say goodbye. Never gonna tell a lie and hurt you.
            */
        ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
