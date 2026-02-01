<?php
require_once '../includes/database.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email)||
 empty($password)) {
        $errors[] = "Alle velden zijn verplicht.";
    } else {
        $username = mysqli_real_escape_string($db, $username);
        $email = mysqli_real_escape_string($db, $email);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, password_hash)
                  VALUES (?, ?, ?)";

        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $passwordHash);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: login.php");
            exit;
        } else {
            $errors[] = "Database fout: " . mysqli_stmt_error($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registreren</title>
</head>
<body>

<h1>Pokémon Cards – Registreren</h1>

<form method="POST">
    <label>Username:</label>
    <input type="text" name="name" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Wachtwoord:</label>
    <input type="password" name="password" required>

    <button type="submit">Registreren</button>
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