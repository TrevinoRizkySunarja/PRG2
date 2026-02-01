<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../user/login.php');
    exit();
}
require_once "../includes/database.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM reservations WHERE id = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$reservation = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Details</title>
</head>
<body>
<h1>Afspraak Details</h1>
<?php if ($reservation): ?>
    <p><strong>Datum:</strong> <?php echo $reservation['date']; ?></p>
    <p><strong>Tijd:</strong> <?php echo $reservation['time']; ?></p>
    <p><strong>Beschrijving:</strong> <?php echo $reservation['description']; ?></p>
<?php else: ?>
    <p>Afspraak niet gevonden.</p>
<?php endif; ?>
<a href="index.php">Terug naar overzicht</a>
</body>
</html>