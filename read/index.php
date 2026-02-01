<?php
session_start();

// ⛔ Niet ingelogd? Terug naar login
if (!isset($_SESSION['user'])) {
    header('Location: ../user/login.php');
    exit();
}

require_once "../includes/database.php";

// Haal alle wapens op
$query = "SELECT * FROM weapons ORDER BY name ASC";
$result = mysqli_query($db, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Weapons Overzicht</title>
</head>
<body>

<h1>Welkom, <?php echo htmlspecialchars($_SESSION['user']['username']); ?></h1>
<h2>Exotic Weapons Database</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>Naam</th>
        <th>Type</th>
        <th>Element</th>
        <th>Slot</th>
        <th>Beschrijving</th>
        <th>Afbeelding</th>
    </tr>

    <?php while ($weapon = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo htmlspecialchars($weapon['name']); ?></td>
            <td><?php echo htmlspecialchars($weapon['type']); ?></td>
            <td><?php echo htmlspecialchars($weapon['element']); ?></td>
            <td><?php echo htmlspecialchars($weapon['slot']); ?></td>
            <td><?php echo htmlspecialchars($weapon['description']); ?></td>
            <td>
                <?php if (!empty($weapon['image_url'])): ?>
                    <img src="<?php echo htmlspecialchars($weapon['image_url']); ?>" width="80">
                <?php else: ?>
                    Geen afbeelding
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>

</table>

<br>
<a href="../user/logout.php">Uitloggen</a>

</body>
</html>