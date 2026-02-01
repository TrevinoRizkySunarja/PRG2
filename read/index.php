<?php
// read/index.php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../user/login.php');
    exit();
}

require_once __DIR__ . "/../includes/database.php";

// Haal alle pokemon kaarten op
$query = "SELECT * FROM pokemoncards ORDER BY name ASC";
$result = mysqli_query($db, $query);

if (!$result) {
    die("Query error: " . mysqli_error($db));
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Pokemoncards Overzicht</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>

<h1>Welkom, <?php echo htmlspecialchars($_SESSION['user']['username']); ?></h1>
<h2>Cards Database</h2>

<p><a href="../create.php">Nieuwe kaart toevoegen</a></p>

<table border="1" cellpadding="8">
    <tr>
        <th>Naam</th>
        <th>Type</th>
        <th>Rarity</th>
        <th>HP</th>
        <th>Beschrijving</th>
        <th>Afbeelding</th>
        <th>Acties</th>
    </tr>

    <?php while ($card = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo htmlspecialchars($card['name']); ?></td>
            <td><?php echo htmlspecialchars($card['type']); ?></td>
            <td><?php echo htmlspecialchars($card['rarity']); ?></td>
            <td><?php echo (int)$card['hp']; ?></td>
            <td><?php echo nl2br(htmlspecialchars($card['description'])); ?></td>
            <td>
                <?php if (!empty($card['image_url'])): ?>
                    <img src="<?php echo htmlspecialchars($card['image_url']); ?>" width="80" alt="Card image">
                <?php else: ?>
                    Geen afbeelding
                <?php endif; ?>
            </td>
            <td>
                <a href="details.php?id=<?php echo (int)$card['id']; ?>">Details</a> |
                <a href="../edit.php?id=<?php echo (int)$card['id']; ?>">Edit</a> |
                <a href="../delete.php?id=<?php echo (int)$card['id']; ?>"
                   onclick="return confirm('Weet je zeker dat je deze kaart wilt verwijderen?');">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<br>
<a href="../user/logout.php">Uitloggen</a>

</body>
</html>
