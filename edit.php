<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../user/login.php');
    exit();
}

require_once "../includes/database.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../read/index.php");
    exit();
}

$id = intval($_GET['id']);

$query = "SELECT * FROM pokemoncards WHERE id = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$card = mysqli_fetch_assoc($result);

if (!$card) {
    header("Location: ../read/index.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $type = trim($_POST['type']);
    $rarity = trim($_POST['rarity']);
    $hp = intval($_POST['hp']);
    $description = trim($_POST['description']);
    $image_url = trim($_POST['image_url']);

    if (empty($name)||  empty($type) || empty($rarity)) {
        $errors[] = "Naam, type en rarity zijn verplicht.";
    } else {

        $updateQuery = "UPDATE pokemoncards
                        SET name=?, type=?, rarity=?, hp=?, description=?, image_url=?
                        WHERE id=?";

        $stmt2 = mysqli_prepare($db, $updateQuery);
        mysqli_stmt_bind_param(
            $stmt2,
            "sssissi",
            $name,
            $type,
            $rarity,
            $hp,
            $description,
            $image_url,
            $id
        );

        mysqli_stmt_execute($stmt2);

        header("Location: ../read/details.php?id=$id");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pokémon Card Bewerken</title>
</head>
<body>

<h1>Pokémon Card Bewerken</h1>

<form method="POST">
    <label>Naam:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($card['name']) ?>" required>

    <label>Type:</label>
    <input type="text" name="type" value="<?= htmlspecialchars($card['type']) ?>" required>

    <label>Rarity:</label>
    <input type="text" name="rarity" value="<?= htmlspecialchars($card['rarity']) ?>" required>

    <label>HP:</label>
    <input type="number" name="hp" value="<?= htmlspecialchars($card['hp']) ?>">

    <label>Beschrijving:</label>
    <textarea name="description"><?= htmlspecialchars($card['description']) ?></textarea>

    <label>Afbeelding URL:</label>
    <input type="text" name="image_url" value="<?= htmlspecialchars($card['image_url']) ?>">

    <button type="submit">Opslaan</button>
</form>

<?php if (!empty($errors)): ?>
    <div style="color:red;">
        <?php foreach ($errors as $e): ?>
            <p><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

</body>
</html>