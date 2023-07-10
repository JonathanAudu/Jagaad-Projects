<?php
require_once 'functions.php';

// Start the session
session_start();

// Check if the user is logged in
$loggedIn = checkLoggedIn();

// Get the students by classroom
function getStudentsByClassroom()
{
    $students = readStudentData();
    $studentsByClassroom = [];

    foreach ($students as $student) {
        $classroom = $student['classroom'];
        $studentsByClassroom[$classroom][] = $student;
    }

    return $studentsByClassroom;
}

$studentsByClassroom = getStudentsByClassroom();

// Handle delete student request
if (isset($_GET['delete_student'])) {
    $registrationNumber = $_GET['delete_student'];
    deleteStudent($registrationNumber);
}

// Handle form submission for updating student
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['update_student'])) {
        $registrationNumber = $_POST["registrationNumber"];
        $name = $_POST["name"];
        $grade = $_POST["grade"];
        $classroom = $_POST["classroom"];

        updateStudent($registrationNumber, $name, $grade, $classroom);
    }
}
?>

<!-- HTML code for report.php -->
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
        <h1>Students Report</h1>
        <?php foreach ($studentsByClassroom as $classroom => $students) : ?>
            <h2><?php echo $classroom; ?></h2>
            <?php if (empty($students)) : ?>
                <p>No students found in this classroom.</p>
            <?php else : ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Registration Number</th>
                            <th>Name</th>
                            <th>Grade</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['registrationNumber']); ?></td>
                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                <td><?php echo htmlspecialchars($student['grade']); ?></td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal-<?php echo $student['registrationNumber']; ?>">Update</button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-<?php echo $student['registrationNumber']; ?>">Delete</button>
                                </td>
                            </tr>
                            <!-- Update Modal -->
                            <div class="modal fade" id="updateModal-<?php echo $student['registrationNumber']; ?>" tabindex="-1" aria-labelledby="updateModalLabel-<?php echo $student['registrationNumber']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="updateModalLabel-<?php echo $student['registrationNumber']; ?>">Update Student Information</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                                <input type="hidden" name="registrationNumber" value="<?php echo htmlspecialchars($student['registrationNumber']); ?>">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Name</label>
                                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="grade" class="form-label">Grade</label>
                                                    <input type="number" class="form-control" id="grade" name="grade" step="0.1" min="0" max="10" value="<?php echo htmlspecialchars($student['grade']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="classroom" class="form-label">Classroom</label>
                                                    <input type="text" class="form-control" id="classroom" name="classroom" value="<?php echo htmlspecialchars($student['classroom']); ?>" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary" name="update_student">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal-<?php echo $student['registrationNumber']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel-<?php echo $student['registrationNumber']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel-<?php echo $student['registrationNumber']; ?>">Delete Student</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete the student with registration number: <?php echo htmlspecialchars($student['registrationNumber']); ?>?</p>
                                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                                                <input type="hidden" name="delete_student" value="<?php echo htmlspecialchars($student['registrationNumber']); ?>">
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>