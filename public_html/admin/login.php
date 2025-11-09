<?php
session_start();
include_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_key = $_POST['secret_key'] ?? '';
    
    if ($input_key === ADMIN_SECRET_KEY) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid secret key!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4><i class="fas fa-lock"></i> Admin Access</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="secret_key" class="form-label">Secret Key</label>
                                <input type="password" class="form-control" id="secret_key" name="secret_key" required>
                                <div class="form-text">Enter the admin secret key to access the panel</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login <i class="fas fa-sign-in-alt"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>