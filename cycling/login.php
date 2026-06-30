<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; color: white; }
        .navbar { background: rgba(0,0,0,0.8) !important; border-bottom: 3px solid #2ecc71; }
        .alert-danger { background: rgba(231,76,60,0.2); border: 1px solid #e74c3c; color: #e74c3c; }
        h1 { color: #2ecc71; font-weight: 900; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark">
        <div class="container d-flex justify-content-between">
            <span class="navbar-brand fw-bold">🚴 Cit-E Cycling</span>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
    <?php
        
        include 'dbconnect.php';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Check credentials against the user table
                $inputUsername = trim($_POST['username']);
                $inputPassword = trim($_POST['password']);

                $stmt = $conn->prepare("SELECT * FROM user WHERE username = :username");
                $stmt->bindParam(':username', $inputUsername);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && $user['password'] === $inputPassword) {
                    // Login successful - set session and redirect to admin menu
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $user['username'];
                    header('Location: admin_menu.php');
                    exit();
                } else {
                    // Invalid credentials
                    echo '<div class="alert alert-danger">❌ Invalid username or password. Please try again.</div>';
                    echo '<a href="admin_login.html" class="btn btn-success">Back to Login</a>';
                }

                }
            catch(PDOException $e)
                {
                echo $e->getMessage(); //If we are not successful in connecting or running the query we will see an error
                }
        }
        else{
            echo "You're here by mistake" ;
        }
        ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
