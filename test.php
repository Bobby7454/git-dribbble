<?php
$servername = "localhost";
$username = "admin";
$password = "";
$dbname = "dribbble";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if ($conn) {
    echo"connection ok!";
} else { 
    echo "failed";
}
?>