<?php
$host ='localhost';
$username ='root';
$password = '';
$db ='chatpay';
$conn = mysqli_connect ($host,$username,$password,$db);
if(!$conn){
    die("CONNECTION FAILED!");
}

?>