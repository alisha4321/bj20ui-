<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update participant scores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; color: white; }
        .navbar { background: rgba(0,0,0,0.8) !important; border-bottom: 3px solid #2ecc71; }
        h2 { color: #2ecc71; font-weight: 900; }
        .form-card { background: rgba(255,255,255,0.05); border: 2px solid rgba(46,204,113,0.3); border-radius: 15px; padding: 40px; }
        .form-control { background: rgba(255,255,255,0.1); border: 1px solid rgba(46,204,113,0.3); color: white; border-radius: 8px; }
        .form-control:focus { background: rgba(255,255,255,0.15); border-color: #2ecc71; color: white; box-shadow: 0 0 10px rgba(46,204,113,0.3); }
        .form-control:disabled { background: rgba(255,255,255,0.05); color: #7f8c8d; }
        label, p { color: #bdc3c7; font-weight: 600; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark">
        <div class="container d-flex justify-content-between">
            <span class="navbar-brand fw-bold">🚴 Cit-E Cycling</span>
            <a href="view_participants_edit_delete.php" class="btn btn-outline-success btn-sm">← Back to Participants</a>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mb-4">✏️ Update Participant Scores</h2>
    <form action="edit_participant.php" method="POST" class="form-card">
        <div class="mb-3">
        Particpant Firstname<br>
        <input type="text" name="firstname" disabled value="<?php echo htmlspecialchars($participant['firstname']); ?>" class="form-control"> <br>
        </div>
        <div class="mb-3">
        Particpant Surname <br>
        <input type="text" name="surname" disabled value="<?php echo htmlspecialchars($participant['surname']); ?>" class="form-control"> <br>
        </div>
        <div class="mb-3">
        Power output in watts<br>
        <input type="number" name="power_output" value="<?php echo htmlspecialchars($participant['power_output']); ?>" class="form-control" required min="0" step="0.01"> <br>
        </div>
        <div class="mb-3">
        Distance in KM<br>
        <input type="number" name="distance_travelled" value="<?php echo htmlspecialchars($participant['distance']); ?>" class="form-control" required min="0" step="0.01"> <br>
        </div>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($participant['id']); ?>">
        <input type="submit" value="Update this rider 🚴" class="btn btn-warning w-100 fw-bold py-3">
    </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
