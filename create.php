<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: user/login.php');
    exit();
}

require_once __DIR__ . "/includes/database.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $type        = trim($_POST['type'] ?? '');
    $rarity      = trim($_POST['rarity'] ?? '');
    $hp          = (int)($_POST['hp'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $image_url   = trim($_POST['image_url'] ?? '');

    if ($name === '' || $type === '' || $rarity === '') {
        $errors[] = "Naam, type en rarity zijn verplicht.";
    } else {
        $query = "INSERT INTO pokemoncards (user_id, name, type, rarity, hp, description, image_url)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($db, $query);
        if (!$stmt) {
            $errors[] = "Prepare fout: " . mysqli_error($db);
        } else {
            $userId = (int)($_SESSION['user']['id'] ?? 0);

            mysqli_stmt_bind_param(
                $stmt,
                "isssiss",
                $userId,
                $name,
                $type,
                $rarity,
                $hp,
                $description,
                $image_url
            );

            if (mysqli_stmt_execute($stmt)) {
                header("Location: read/index.php");
                exit();
            } else {
                $errors[] = "Database fout: " . mysqli_stmt_error($stmt);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Nieuwe Pokémon Card</title>
</head>
<body>

<h1>Nieuwe Pokémon Card Toevoegen</h1>

<form method="POST">
    <label>Naam:</label>
    <input type="text" name="name" required>

    <label>Type:</label>
    <input type="text" name="type" required>

    <label>Rarity:</label>
    <input type="text" name="rarity" required>

    <label>HP:</label>
    <input type="number" name="hp" value="0">

    <label>Beschrijving:</label>
    <textarea name="description"></textarea>

    <label>Afbeelding URL:</label>
    <input type="text" name="image_url">

    <button type="submit">Opslaan</button>
</form>

<?php if (!empty($errors)): ?>
    <div style="color:red;">
        <?php foreach ($errors as $e): ?>
            <p><?php echo htmlspecialchars($e); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

</body>
</html>
