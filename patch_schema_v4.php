<?php
include 'dbConnection.php';

// Add 'status' column to 'quiz' table
$sql = "SHOW COLUMNS FROM quiz LIKE 'status'";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) == 0) {
    $sql = "ALTER TABLE quiz ADD COLUMN status VARCHAR(20) DEFAULT 'active'";
    if (mysqli_query($con, $sql)) {
        echo "Column 'status' added to 'quiz' table successfully.<br>";
    } else {
        echo "Error adding column: " . mysqli_error($con) . "<br>";
    }
} else {
    echo "Column 'status' already exists in 'quiz' table.<br>";
}

echo "Schema update v4 completed.";
?>