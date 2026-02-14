<?php
include 'dbConnection.php';

// Create 'departments' table
$sql = "CREATE TABLE IF NOT EXISTS departments (
    dept_id INT AUTO_INCREMENT PRIMARY KEY,
    dept_name VARCHAR(100) NOT NULL UNIQUE,
    year_labels TEXT NOT NULL
)";

if (mysqli_query($con, $sql)) {
    echo "Table departments created (or already exists).\n";
} else {
    echo "Error creating table: " . mysqli_error($con) . "\n";
}

// Insert default departments if empty
$result = mysqli_query($con, "SELECT COUNT(*) as count FROM departments");
$row = mysqli_fetch_assoc($result);
if ($row['count'] == 0) {
    // Default: CS (4 years), Medicine (5 years)
    $stmt = $con->prepare("INSERT INTO departments (dept_name, year_labels) VALUES (?, ?)");

    // CS
    $name = "Computer Science";
    $years = "1st Year,2nd Year,3rd Year,4th Year";
    $stmt->bind_param("ss", $name, $years);
    $stmt->execute();

    // Medicine
    $name = "Medicine";
    $years = "1st Year,2nd Year,3rd Year,4th Year,5th Year";
    $stmt->bind_param("ss", $name, $years);
    $stmt->execute();

    echo "Default departments inserted.\n";
} else {
    echo "Departments already exist.\n";
}

echo "Schema update v3 completed.\n";
?>