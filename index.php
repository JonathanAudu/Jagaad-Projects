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

<?php
// Include the student functions file
require_once 'functions.php';

// Handle delete student request
if (isset($_GET['delete_student'])) {
    $registrationNumber = $_GET['delete_student'];
    deleteStudent($registrationNumber);
}

if (isset($_GET['delete_success']) && $_GET['delete_success'] === 'true') {
    showMessage('danger', 'Student deleted successfully.');
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_student'])) {
        $registrationNumber = $_POST['registrationNumber'];
        $name = $_POST['name'];
        $grade = $_POST['grade'];
        $classroom = $_POST['classroom'];

        addStudent($registrationNumber, $name, $grade, $classroom);
    } elseif (isset($_POST['update_student'])) {
        $registrationNumber = $_POST['registrationNumber'];
        $name = $_POST['name'];
        $grade = $_POST['grade'];
        $classroom = $_POST['classroom'];

        updateStudent($registrationNumber, $name, $grade, $classroom);
    } elseif (isset($_POST['delete_student'])) {
        $registrationNumber = $_POST['registrationNumber'];

        deleteStudent($registrationNumber);
    }
}

// Get the student data for update
$registrationNumberToUpdate = $_GET['registrationNumber'] ?? '';
$studentToUpdate = getStudentByRegistrationNumber($registrationNumberToUpdate);

?>

<div class="container">
    <h1>Student Form</h1>
    <form action="index.php" method="post" id="studentForm">
        <div class="mb-3">
            <label for="registrationNumber" class="form-label">Registration Number</label>
            <input type="text" name="registrationNumber" class="form-control" id="registrationNumber" required
                   value="<?php echo $studentToUpdate['registrationNumber'] ?? ''; ?>"
                <?php echo $studentToUpdate ? 'readonly' : ''; ?>>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" id="name" required
                   value="<?php echo $studentToUpdate['name'] ?? ''; ?>">
        </div>
        <div class="mb-3">
            <label for="grade" class="form-label">Grade</label>
            <input type="number" name="grade" class="form-control" id="grade" min="0" max="10" required
                   value="<?php echo $studentToUpdate['grade'] ?? ''; ?>">
        </div>
        <div class="mb-3">
            <label for="classroom" class="form-label">Classroom</label>
            <select class="form-select" name="classroom" id="classroom" required>
                <option value="">Select a Classroom</option>
                <option value="Classroom 1" <?php echo ($studentToUpdate['classroom'] ?? '') === 'Classroom 1' ? 'selected' : ''; ?>>
                    Classroom 1
                </option>
                <option value="Classroom 2" <?php echo ($studentToUpdate['classroom'] ?? '') === 'Classroom 2' ? 'selected' : ''; ?>>
                    Classroom 2
                </option>
                <option value="Classroom 3" <?php echo ($studentToUpdate['classroom'] ?? '') === 'Classroom 3' ? 'selected' : ''; ?>>
                    Classroom 3
                </option>
            </select>
        </div>
        <?php if ($studentToUpdate) : ?>
            <button type="submit" name="update_student" class="btn btn-secondary">Update Student</button>
        <?php else : ?>
            <button type="submit" name="add_student" class="btn btn-primary">Add Student</button>
        <?php endif; ?>
    </form>

    <h2>Student List</h2>
    <?php
    displayStudentList();
    ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="script.js"></script>
</body>

</html>
