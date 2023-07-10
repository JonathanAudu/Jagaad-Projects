# Student Management System

The Student Management System is a web-based application that allows users to manage student records. It provides functionalities to add, update, and delete student information. The system also includes user authentication to ensure secure access to the application.

## Features

- User registration and login: Users can create an account and log in to the system.
- Add students: Users can add new students to the system by entering their registration number, name, grade, and classroom.
- Update students: Users can update the information of existing students, including their name, grade, and classroom.
- Delete students: Users can delete students from the system.
- Students report: Users can view a report of all students grouped by their respective classrooms.

## Technologies Used

The Student Management System is built using the following technologies:

- PHP: The server-side scripting language used to handle form submissions, database operations, and user authentication.
- MySQL: The relational database management system used to store student and user data.
- HTML/CSS: The markup language and styling used for creating the user interface.
- Bootstrap: The CSS framework used for responsive and visually appealing design.

## Getting Started

To run the Student Management System on your local machine, follow these steps:

1. Ensure you have PHP and MySQL installed on your system.
2. Clone this repository or download the source code as a ZIP file.
3. Import the provided SQL file (`database.sql`) into your MySQL database.
4. Configure the database connection settings in the `database.php` file.
5. Start a local server or web server (e.g., Apache) and make sure it can execute PHP files.
6. Open the application in your web browser by accessing the appropriate URL.

## Usage

- Upon accessing the application, you will be presented with a login page. If you don't have an account, click on the "Register" link to create a new user account.
- After logging in, you will be directed to the index page where you can add new students using the provided form. Fill in the student details and click "Create" to add a new student to the system.
- The index page also displays a list of existing students grouped by their classrooms. You can update a student's information by clicking the "Update" button next to the student's entry and delete a student by clicking the "Delete" button.
- To view a report of all students grouped by classrooms, navigate to the "Students Report" page. The report provides an overview of all classrooms and the students enrolled in each classroom.

## Contributing

Contributions to the Student Management System are welcome! If you find any issues or have suggestions for improvements, please open an issue or submit a pull request.


