<?php
include 'dbConnection.php';

// Add 'year' to 'user' table
$result = mysqli_query($con, "SHOW COLUMNS FROM user LIKE 'year'");
if (mysqli_num_rows($result) == 0) {
    mysqli_query($con, "ALTER TABLE user ADD COLUMN year VARCHAR(50) DEFAULT '1st Year'");
    echo "year column added to user table.\n";
} else {
    echo "year column already exists in user table.\n";
}

// Add 'target_dept' to 'quiz' table
$result = mysqli_query($con, "SHOW COLUMNS FROM quiz LIKE 'target_dept'");
if (mysqli_num_rows($result) == 0) {
    mysqli_query($con, "ALTER TABLE quiz ADD COLUMN target_dept VARCHAR(100) DEFAULT NULL");
    echo "target_dept column added to quiz table.\n";
} else {
    echo "target_dept column already exists in quiz table.\n";
}

// Add 'target_year' to 'quiz' table
$result = mysqli_query($con, "SHOW COLUMNS FROM quiz LIKE 'target_year'");
if (mysqli_num_rows($result) == 0) {
    mysqli_query($con, "ALTER TABLE quiz ADD COLUMN target_year VARCHAR(50) DEFAULT NULL");
    echo "target_year column added to quiz table.\n";
} else {
    echo "target_year column already exists in quiz table.\n";
}

echo "Schema update completed.\n";
?>