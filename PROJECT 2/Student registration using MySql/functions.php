<?php
require_once 'database.php';

// Function to check if the user is logged in
function checkLoggedIn()
{
    return isset($_SESSION["user_id"]);
}

// Function to display alert messages
function showMessage($type, $message)
{
    echo '<div class="alert alert-' . $type . ' text-center" role="alert">' . $message . '</div>';
}

// Function to read student data from the database
function readStudentData()
{
    global $conn;

    $students = [];

    $query = "SELECT * FROM students";
    $result = $conn->query($query);

    if ($result === false) {
        die('Error executing the query: ' . $conn->error);
    }

    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    return $students;
}

// Function to get the list of classrooms
function getClassrooms()
{
    global $conn;

    $classrooms = [];

    $query = "SELECT DISTINCT classroom FROM students";
    $result = $conn->query($query);

    if ($result === false) {
        die('Error executing the query: ' . $conn->error);
    }

    while ($row = $result->fetch_assoc()) {
        $classrooms[] = $row['classroom'];
    }

    return $classrooms;
}

function registerUser($username, $password)
{
    global $conn;

    // Validate username
    if (empty($username)) {
        $usernameError = "Username is required";
        return;
    }

    // Validate password
    if (empty($password)) {
        $passwordError = "Password is required";
        return;
    }

    // Check if the username already exists in the database
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $usernameError = "Username already exists";
        return;
    }

    // Hash the password using bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, ?)");
    $is_admin = 0;
    $stmt->bind_param("ssi", $username, $hashedPassword, $is_admin);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to login page with success message
        header("Location: login.php?success=1");
        exit();
    } else {
        die("Error executing the SQL statement: " . $stmt->error);
    }
}


// Function to authenticate user login 
function loginUser($username, $password)
{
    global $conn;

    // Prepare and bind the SQL statement with placeholders
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set user session or redirect to the appropriate page
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["is_admin"] = $user["is_admin"];

            // Close the statement
            $stmt->close();

            if ($user['is_admin'] == 1) {
                // Redirect to admin page
                header("Location: report.php");
                exit();
            } else {
                // Redirect to customer page
                header("Location: index.php");
                exit();
            }
        } else {
            // Invalid password
            return "Invalid password";
        }
    } else {
        // User not found
        return "User not found";
    }

    // Close the statement
    $stmt->close();

    return "Unknown error occurred";
}



// Function to write student data to the database


// Function to add a new student
function addStudent($registrationNumber, $name, $grade, $classroom)
{
    global $conn;

    // Validate the registration number
    $registrationNumber = filter_var($registrationNumber);
    if (empty($registrationNumber)) {
        showMessage('danger', 'Registration number is required.');
        return;
    }

    // Validate the name
    $name = filter_var($name);
    if (empty($name)) {
        showMessage('danger', 'Name is required.');
        return;
    }

    // Validate the grade
    $grade = filter_var($grade, FILTER_VALIDATE_FLOAT);
    if ($grade === false || $grade < 0 || $grade > 10) {
        showMessage('danger', 'Grade should be a number from 0 to 10.');
        return;
    }

    // Validate the classroom
    $classroom = filter_var($classroom);
    if (empty($classroom)) {
        showMessage('danger', 'Classroom is required.');
        return;
    }

    // Get the user ID from the session
    $user_id = $_SESSION['user_id'];


    // Check if the registration number already exists
    $stmt = $conn->prepare("SELECT * FROM students WHERE registrationNumber = ?");
    $stmt->bind_param("s", $registrationNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        showMessage('danger', 'Student with the same registration number already exists.');
        return;
    }

    // Add the student to the database
    $stmt = $conn->prepare("INSERT INTO students (registrationNumber, name, grade, classroom, user_id)
     VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsi", $registrationNumber, $name, $grade, $classroom, $user_id);
    $stmt->execute();
    $stmt->close();

    showMessage('success', 'Student added successfully.');
    
}


// Function to update an existing student
function updateStudent($registrationNumber, $name, $grade, $classroom)
{
    global $conn;

    // Validate the registration number
    $registrationNumber = filter_var($registrationNumber);
    if (empty($registrationNumber)) {
        showMessage('danger', 'Registration number is required.');
        return;
    }

    // Validate the name
    $name = filter_var($name);
    if (empty($name)) {
        showMessage('danger', 'Name is required.');
        return;
    }

    // Validate the grade
    $grade = filter_var($grade, FILTER_VALIDATE_FLOAT);
    if ($grade === false || $grade < 0 || $grade > 10) {
        showMessage('danger', 'Grade should be a number from 0 to 10.');
        return;
    }

    // Validate the classroom
    $classroom = filter_var($classroom);
    if (empty($classroom)) {
        showMessage('danger', 'Classroom is required.');
        return;
    }

    // Check if the registration number exists
    $query = "SELECT * FROM students WHERE registrationNumber = '$registrationNumber'";
    $result = $conn->query($query);
    if ($result->num_rows === 0) {
        showMessage('danger', 'Student not found.');
        return;
    }

    // Update the student in the database
    $query = "UPDATE students SET name = '$name', grade = '$grade', classroom = '$classroom'
              WHERE registrationNumber = '$registrationNumber'";
    $conn->query($query);
    echo '<meta http-equiv="refresh" content="0; url=\'report.php\'" />';
    showMessage('success', 'Student updated successfully.');
}

// Function to delete a student
function deleteStudent($registrationNumber)
{
    global $conn;

    // Check if the registration number exists
    $query = "SELECT * FROM students WHERE registrationNumber = '$registrationNumber'";
    $result = $conn->query($query);
    if ($result->num_rows === 0) {
        showMessage('danger', 'Student not found.');
        return;
    }

    // Delete the student from the database
    $query = "DELETE FROM students WHERE registrationNumber = '$registrationNumber'";
    $conn->query($query);
    echo '<meta http-equiv="refresh" content="0; url=\'report.php\'" />';
    showMessage('success', 'Student deleted successfully.');
}


// Function to display the student list
function displayStudentList()
{
    $students = readStudentData();

    if (empty($students)) {
        echo '<p>No students found.</p>';
        return;
    }

    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Registration Number</th>';
    echo '<th>Name</th>';
    echo '<th>Grade</th>';
    echo '<th>Classroom</th>';
    echo '<th>Actions</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($students as $student) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($student['registrationNumber']) . '</td>';
        echo '<td>' . htmlspecialchars($student['name']) . '</td>';
        echo '<td>' . htmlspecialchars($student['grade']) . '</td>';
        echo '<td>' . htmlspecialchars($student['classroom']) . '</td>';
        echo '<td>';
        echo '<a href="index.php?registrationNumber=' . htmlspecialchars($student['registrationNumber']) . '"><button class="btn btn-info">Update</button></a>';
        echo ' | ';
        echo '<a href="index.php?delete_student=' . htmlspecialchars($student['registrationNumber']) . '"><button type="button" name="delete_student" class="btn btn-danger">Delete</button></a>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
}


// Function to display the student update form
function displayUpdateForm($student)
{
    echo '<form action="index.php" method="post">';
    echo '<div class="mb-3">';
    echo '<label for="registrationNumber" class="form-label">Registration Number</label>';
    echo '<input type="text" class="form-control" id="registrationNumber" name="registrationNumber" value="' . htmlspecialchars($student['registrationNumber']) . '" required>';
    echo '</div>';
    echo '<div class="mb-3">';
    echo '<label for="name" class="form-label">Name</label>';
    echo '<input type="text" class="form-control" id="name" name="name" value="' . htmlspecialchars($student['name']) . '" required>';
    echo '</div>';
    echo '<div class="mb-3">';
    echo '<label for="grade" class="form-label">Grade</label>';
    echo '<input type="number" class="form-control" id="grade" name="grade" value="' . htmlspecialchars($student['grade']) . '" step="0.1" min="0" max="10" required>';
    echo '</div>';
    echo '<div class="mb-3">';
    echo '<label for="classroom" class="form-label">Classroom</label>';
    echo '<input type="text" class="form-control" id="classroom" name="classroom" value="' . htmlspecialchars($student['classroom']) . '" required>';
    echo '</div>';
    echo '<input type="hidden" name="update_student" value="1">';
    echo '<button type="submit" class="btn btn-primary">Update Student</button>';
    echo '</form>';
}
