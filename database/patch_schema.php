<?php
include 'dbConnection.php';
// Check if column exists first to avoid error
$result = mysqli_query($con, "SHOW COLUMNS FROM quiz LIKE 'access_code'");
if (mysqli_num_rows($result) == 0) {
    mysqli_query($con, "ALTER TABLE quiz ADD COLUMN access_code VARCHAR(50) DEFAULT NULL");
    echo "access_code column added to quiz table.\n";
} else {
    echo "access_code column already exists.\n";
}

// Check question_type in questions table too just in case
$result_q = mysqli_query($con, "SHOW COLUMNS FROM questions LIKE 'question_type'");
if (mysqli_num_rows($result_q) == 0) {
    mysqli_query($con, "ALTER TABLE questions ADD COLUMN question_type VARCHAR(20) DEFAULT 'mcq'");
    echo "question_type column added to questions table.\n";
} else {
    echo "question_type column already exists.\n";
}
?>