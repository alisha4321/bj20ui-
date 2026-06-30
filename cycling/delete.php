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
    <title>Delete participant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; color: white; }
        .navbar { background: rgba(0,0,0,0.8) !important; border-bottom: 3px solid #2ecc71; }
        .confirm-card { background: rgba(255,255,255,0.05); border: 2px solid rgba(231,76,60,0.4); border-radius: 15px; padding: 40px; }
        .alert-success { background: rgba(46,204,113,0.2); border: 1px solid #2ecc71; color: #2ecc71; }
        .alert-danger { background: rgba(231,76,60,0.2); border: 1px solid #e74c3c; color: #e74c3c; }
        .alert-warning { background: rgba(243,156,18,0.2); border: 1px solid #f39c12; color: #f39c12; }
        h4 { color: #e74c3c; font-weight: 900; }
        p strong { color: #2ecc71; }
        p { color: #bdc3c7; }
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
                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                //TODO DELETE - complete the functionality
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
                    $id = $_POST['id'];
                    $stmt = $conn->prepare("DELETE FROM participant WHERE id = :id");
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    echo '<div class="alert alert-success">✅ Participant has been deleted successfully.</div>';
                    echo '<a href="view_participants_edit_delete.php" class="btn btn-success mt-3">Back to participants now</a>';
                    echo '<script>setTimeout(function(){ window.location.href = "view_participants_edit_delete.php"; }, 3000);</script>';

                } elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $stmt = $conn->prepare("SELECT * FROM participant WHERE id = :id");
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    $participant = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($participant) {
                        echo '<div class="confirm-card">';
                        echo '<h4>⚠️ Are you sure you want to delete this participant?</h4>';
                        echo '<hr style="border-color: rgba(231,76,60,0.3)">';
                        echo '<p><strong>Name:</strong> ' . htmlspecialchars($participant['firstname']) . ' ' . htmlspecialchars($participant['surname']) . '</p>';
                        echo '<p><strong>Email:</strong> ' . htmlspecialchars($participant['email']) . '</p>';
                        echo '<p><strong>Power Output:</strong> ' . htmlspecialchars($participant['power_output']) . ' W</p>';
                        echo '<p><strong>Distance:</strong> ' . htmlspecialchars($participant['distance']) . ' KM</p>';
                        echo '<form method="POST" action="delete.php" class="mt-4">';
                        echo '<input type="hidden" name="id" value="' . htmlspecialchars($participant['id']) . '">';
                        echo '<button type="submit" class="btn btn-danger fw-bold me-2">🗑️ Yes, Delete</button>';
                        echo '<a href="view_participants_edit_delete.php" class="btn btn-success fw-bold">✅ Cancel</a>';
                        echo '</form>';
                        echo '</div>';
                    } else {
                        echo '<div class="alert alert-warning">Participant not found.</div>';
                    }
                } else {
                    echo '<div class="alert alert-warning">No participant selected.</div>';
                }
            }
            catch(PDOException $e) {
                echo '<div class="alert alert-danger">' . $e->getMessage() . '</div>';
            }
        ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
