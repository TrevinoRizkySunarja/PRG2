<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "pokemoncards_db";

$db = mysqli_connect($host, $user, $password, $database);

if (!$db) {
    die("Databaseverbinding mislukt: " . mysqli_connect_error());
}
?>