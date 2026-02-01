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

$id = (int)$_GET['id'];

$query = "SELECT * FROM pokemoncards WHERE id = ?";
$stmt = mysqli_prepare($db, $query);

if (!$stmt) {
    die("Prepare error: " . mysqli_error($db));
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$card = mysqli_fetch_assoc($result);

if (!$card) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <title>Card Details</title>
</head>
<body>

<p><a href="index.php">← Terug naar overzicht</a></p>

<h1><?php echo htmlspecialchars($card['name']); ?></h1>

<ul>
    <li><strong>Type:</strong> <?php echo htmlspecialchars($card['type']); ?></li>
    <li><strong>Rarity:</strong> <?php echo htmlspecialchars($card['rarity']); ?></li>
    <li><strong>HP:</strong> <?php echo (int)$card['hp']; ?></li>
</ul>

<h3>Beschrijving</h3>
<p><?php echo nl2br(htmlspecialchars($card['description'])); ?></p>

<h3>Afbeelding</h3>
<?php if (!empty($card['image_url'])): ?>
    <img src="<?php echo htmlspecialchars($card['image_url']); ?>" width="220" alt="Card image">
<?php else: ?>
    <p>Geen afbeelding</p>
<?php endif; ?>

<p>
    <a href="../edit.php?id=<?php echo (int)$card['id']; ?>">Edit</a> |
    <a href="../delete.php?id=<?php echo (int)$card['id']; ?>"
       onclick="return confirm('Weet je zeker dat je deze kaart wilt verwijderen?');">Delete</a>
</p>

</body>
</html>
