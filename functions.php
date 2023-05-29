<?php
// File path for storing student data
$filePath = 'studentsData.json';

// Function to read student data from the JSON file
function readStudentData()
{
    global $filePath;

    // Check if the file exists
    if (file_exists($filePath)) {
        // Read the JSON file
        $data = file_get_contents($filePath);

        // Decode the JSON data to an associative array
        // Return the students array
        return json_decode($data, true);
    }

    // If the file doesn't exist, return an empty array
    return [];
}

// Function to write student data to the JSON file
function writeStudentData($students)
{
    global $filePath;

    // Encode the students array to JSON format
    $data = json_encode($students, JSON_PRETTY_PRINT);

    // Write the JSON data to the file
    file_put_contents($filePath, $data);
}

// Function to add a new student
function addStudent($registrationNumber, $name, $grade, $classroom)
{
    $students = readStudentData();

    // Check if the registration number already exists
    if (isset($students[$registrationNumber])) {
        showMessage('danger', 'Student with the same registration number already exists.');
       return;
    }

    // Validate the grade
    if (!is_numeric($grade) || $grade < 0 || $grade > 10) {
        showMessage('danger', 'Grade should be a number from 0 to 10.');
        return;
    }

    // Add the student to the array
    $students[$registrationNumber] = [
        'registrationNumber' => $registrationNumber,
        'name' => $name,
        'grade' => $grade,
        'classroom' => $classroom
    ];

    // Save the updated student data to the JSON file
    writeStudentData($students);

    showMessage('success', 'Student added successfully.');
}

// Function to update an existing student
function updateStudent($registrationNumber, $name, $grade, $classroom)
{
    $students = readStudentData();

    // Check if the registration number exists
    if (!isset($students[$registrationNumber])) {
        showMessage('danger', 'Student not found.');
        header("Location: index.php"); // Redirect to avoid resubmission on refresh
        exit();
    }

    // Validate the grade
    if (!is_numeric($grade) || $grade < 0 || $grade > 10) {
        showMessage('danger', 'Grade should be a number from 0 to 10.');
        header("Location: index.php"); // Redirect to avoid resubmission on refresh
        exit();
    }

    // Update the student in the array
    $students[$registrationNumber] = [
        'registrationNumber' => $registrationNumber,
        'name' => $name,
        'grade' => $grade,
        'classroom' => $classroom
    ];

    // Save the updated student data to the JSON file
    writeStudentData($students);

    showMessage('success', 'Student updated successfully.');
}

function deleteStudent($registrationNumber)
{
    $students = readStudentData();

    // Check if the registration number exists
    if (!isset($students[$registrationNumber])) {
        showMessage('danger', 'Student not found.');
        return;
    }

    // Remove the student from the array
    unset($students[$registrationNumber]);

    // Save the updated student data to the JSON file
    writeStudentData($students);

    header("Location: index.php?delete_success=true"); // Redirect to indicate successful deletion
    exit();
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

    foreach ($students as $registrationNumber => $student) {
        echo '<tr>';
        echo '<td>' . $registrationNumber . '</td>';
        echo '<td>' . $student['name'] . '</td>';
        echo '<td>' . $student['grade'] . '</td>';
        echo '<td>' . $student['classroom'] . '</td>';
        echo '<td>';
        echo '<a href="index.php?registrationNumber=' . $registrationNumber . '"><button class="btn btn-info">Update</button></a>';
        echo ' | ';
        echo '<a href="index.php?delete_student=' . $registrationNumber . '"><button type="button" name="delete_student" class="btn btn-danger">Delete</button></a>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
}

// Function to get a student by registration number
function getStudentByRegistrationNumber($registrationNumber)
{
    $students = readStudentData();
    return $students[$registrationNumber] ?? null;
}

// Function to show alert message
function showMessage($type, $message)
{
    echo '<div class="alert alert-' . $type . ' text-center" role="alert">' . $message . '</div>';
}

