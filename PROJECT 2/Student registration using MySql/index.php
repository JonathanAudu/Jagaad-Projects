<?php
require_once 'functions.php';

// Start the session
session_start();

// Check if the user is logged in
$loggedIn = checkLoggedIn();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $registrationNumber = $_POST["registrationNumber"];
    $name = $_POST["name"];
    $grade = $_POST["grade"];
    $classroom = $_POST["classroom"];

    addStudent($registrationNumber, $name, $grade, $classroom);
}
?>

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
    <div class="container-fluid header">
        <h1>STUDENT MANAGEMENT SYSTEM</h1>
        <?php if ($loggedIn) : ?>
            <p>Welcome, <?php echo $_SESSION["username"]; ?>!</p>
            <p class="btn btn-danger"><a href="logout.php" class="text-light">Logout</a></p>
        <?php endif; ?>
    </div>
    <div class="container">
        <h1>Student Form</h1>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" id="studentForm">
            <div class="mb-3">
                <label for="registrationNumber" class="form-label">Registration Number</label>
                <input type="text" class="form-control" id="registrationNumber" name="registrationNumber" required>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="grade" class="form-label">Grade</label>
                <input type="number" class="form-control" id="grade" name="grade" step="1" min="0" max="10" required>
            </div>
            <div class="mb-3">
                <label for="classroom" class="form-label">Classroom</label>
                <select class="form-select" id="classroom" name="classroom" required>
                    <?php
                    $classrooms = getClassrooms();
                    foreach ($classrooms as $classroom) {
                        echo '<option value="' . htmlspecialchars($classroom) . '">' . htmlspecialchars($classroom) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="add_student">Create</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="script.js"></script>
</body>

</html>
