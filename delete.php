<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: user/login.php');
    exit();
}

require_once __DIR__ . "/includes/database.php";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    $id = (int)$_GET['id'];

    $query = "DELETE FROM pokemoncards WHERE id = ?";
    $stmt = mysqli_prepare($db, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
    }

    header("Location: read/index.php");
    exit();
}
