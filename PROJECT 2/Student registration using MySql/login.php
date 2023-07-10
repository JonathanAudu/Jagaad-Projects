<?php
require_once 'functions.php';

// Start the session
session_start();

$loginError = ""; // Initialize the login error message

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Login the user and get the login error message
    $loginError = loginUser($username, $password);
}
?>

<!-- HTML code for login.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd !important;">
    <div class="container-md">
        <a class="navbar-brand" href="#">
            <h1 class="text-center">STUDENT MANAGEMENT SYSTEM</h1>
        </a>
    </div>
</nav>
    <div class="container">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="alert alert-success text-center ">Registration successful! You can now log in.</div>
<?php endif; ?>
    <?php if (!empty($loginError)): ?>
    <div class="alert alert-danger text-center"><?php echo $loginError; ?></div>
<?php endif; ?>
        <h1>Login</h1>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <br>
            <p>Don't have an account? <a href="signup.php">Register</a></p>
        </form>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
