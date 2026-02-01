<?php
session_start();
require_once '../includes/database.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = mysqli_real_escape_string($db, $_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validatie
    if (empty($email) || empty($password)) {
        $errors['fields'] = "Vul alle velden in.";
    } else {

        $query = "SELECT * FROM users WHERE email = ?";
        $stmt  = mysqli_prepare($db, $query);

        if (!$stmt) {
            $errors['db'] = "Database fout: " . mysqli_error($db);
        } else {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user   = mysqli_fetch_assoc($result);

            if ($user) {

                // 👇 BELANGRIJK: gebruik hier de kolomnaam zoals hij in je database staat:
                // - als je kolom 'password_hash' heet: gebruik $user['password_hash']
                // - als je kolom 'password' heet: gebruik $user['password']

                $hashFromDb = $user['password_hash'] ?? $user['password'] ?? null;

                if ($hashFromDb && password_verify($password, $hashFromDb)) {

                    session_regenerate_id(true);

                    $_SESSION['user'] = [
                        'id'       => $user['id'],
                        'username' => $user['username'],
                        'email'    => $user['email']
                    ];

                    header("Location: ../read/index.php");
                    exit;

                } else {
                    $errors['login'] = "Ongeldig wachtwoord.";
                }

            } else {
                $errors['login'] = "Gebruiker niet gevonden.";
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h1>Login</h1>

<form method="POST" action="">
    <label for="email">Email:</label>
    <input type="email" name="email" required><br>

    <label for="password">Wachtwoord:</label>
    <input type="password" name="password" required><br>

    <button type="submit" name="submit">Login</button>
</form>
<a href="register.php">Register</a>

<?php if (!empty($errors)): ?>
    <div style="color: red;">
        <?php foreach ($errors as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

</body>
</html>