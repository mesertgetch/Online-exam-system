<?php
include 'dbConnection.php';
echo "QUIZ: ";
$result = mysqli_query($con, "SHOW COLUMNS FROM quiz");
while ($row = mysqli_fetch_array($result)) {
    echo $row['Field'] . " ";
}
echo "\nQUESTIONS: ";
$result = mysqli_query($con, "SHOW COLUMNS FROM questions");
while ($row = mysqli_fetch_array($result)) {
    echo $row['Field'] . " ";
}
echo "\n";
?>