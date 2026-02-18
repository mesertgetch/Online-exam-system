<?php
include_once __DIR__ . '/../config/dbConnection.php';
$ref = @$_GET['q'];
//$name = $_POST['name'];
//$name= ucwords(strtolower($name));
//$gender = $_POST['gender'];
$email = $_POST['email'];

$password = md5($_POST['password']);



$q = mysqli_query($con, "INSERT INTO admin VALUES  ('$email' , '$password' , 'admin')");


header("location:../$ref?q=Succesfully registered");


?>