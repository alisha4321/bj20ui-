<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register your interest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; color: white; }
        .navbar { background: rgba(0,0,0,0.8) !important; border-bottom: 3px solid #2ecc71; }
        .alert-success { background: rgba(46,204,113,0.2); border: 1px solid #2ecc71; color: #2ecc71; border-radius: 10px; padding: 20px; }
        .alert-danger { background: rgba(231,76,60,0.2); border: 1px solid #e74c3c; color: #e74c3c; border-radius: 10px; padding: 20px; }
        h1 { color: #2ecc71; font-weight: 900; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark">
        <div class="container d-flex justify-content-between">
            <span class="navbar-brand fw-bold">🚴 Cit-E Cycling</span>
            <a href="." class="btn btn-outline-success btn-sm">← Back to Home</a>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
    <?php
    //including connection variables  
    include 'dbconnect.php';

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                //TODO INSERT - complete the functionality
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $firstname = trim($_POST['firstname']);
                    $surname   = trim($_POST['surname']);
                    $email     = trim($_POST['email']);
                    $terms     = isset($_POST['terms']) ? 1 : 0;

                    // Server-side validation
                    if (empty($firstname) || empty($surname) || empty($email)) {
                        echo '<div class="alert alert-danger">⚠️ All fields are required.</div>';
                        echo '<a href="register_form.html" class="btn btn-success mt-3">Go Back</a>';
                    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        echo '<div class="alert alert-danger">⚠️ Please enter a valid email address.</div>';
                        echo '<a href="register_form.html" class="btn btn-success mt-3">Go Back</a>';
                    } elseif ($terms != 1) {
                        echo '<div class="alert alert-danger">⚠️ You must accept the terms and conditions.</div>';
                        echo '<a href="register_form.html" class="btn btn-success mt-3">Go Back</a>';
                    } else {
                        // Check if email already registered
                        $checkStmt = $conn->prepare("SELECT id FROM interest WHERE email = :email");
                        $checkStmt->bindParam(':email', $email);
                        $checkStmt->execute();
                        if ($checkStmt->fetch()) {
                            echo '<div class="alert alert-danger">⚠️ This email address has already been registered. Please use a different email.</div>';
                            echo '<a href="register_form.html" class="btn btn-success mt-3">Go Back</a>';
                        } else {
                            // Insert into interest table
                            $stmt = $conn->prepare("INSERT INTO interest (firstname, surname, email, terms) VALUES (:firstname, :surname, :email, :terms)");
                            $stmt->bindParam(':firstname', $firstname);
                            $stmt->bindParam(':surname', $surname);
                            $stmt->bindParam(':email', $email);
                            $stmt->bindParam(':terms', $terms);
                            $stmt->execute();
                            echo '<div class="alert alert-success">🎉 Thank you, <strong>' . htmlspecialchars($firstname) . '</strong>! Your interest has been registered successfully.</div>';
                            echo '<a href="." class="btn btn-success mt-3">Back to Home</a>';
                        }
                    }
                } else {
                    header('Location: register_form.html');
                }

                }
            catch(PDOException $e)
                {
                echo $e->getMessage(); //If we are not successful we will see an error
                }




                //made you look
        
        
        ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
