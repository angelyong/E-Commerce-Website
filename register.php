<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$redirectPath = sanitizeRedirectPath($_REQUEST['redirect'] ?? '', 'index.php');

if (isLoggedIn()) {
    header('Location: ' . $redirectPath);
    exit;
}

$errors = [];
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($name === '') {
        $errors[] = 'Name is required.';
    } elseif (strlen($name) > 100) {
        $errors[] = 'Name must be 100 characters or fewer.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email is required.';
    } elseif (strlen($email) > 150) {
        $errors[] = 'Email must be 150 characters or fewer.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'An account with that email already exists.';
        }
    }

    if (!$errors) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
        $stmt->execute([$name, $email, $passwordHash]);

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $pdo->lastInsertId();
        $_SESSION['user_name'] = $name;
        $_SESSION['user_role'] = 'customer';

        header('Location: ' . $redirectPath);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create account | SHOPLANE</title>
    <link rel="stylesheet" href="<?php echo asset('assets/css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/auth.css'); ?>">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main id="authContainer">
        <h1> Create Account </h1>

        <?php if ($errors): ?>
            <div id="authErrors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="register.php" id="registerForm" novalidate>
            <?php echo csrfField(); ?>
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirectPath); ?>">
            <div class="formGroup">
                <label for="name"> Full Name </label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" maxlength="100" autocomplete="name" required>
            </div>
            <div class="formGroup">
                <label for="email"> Email </label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" maxlength="150" autocomplete="email" required>
            </div>
            <div class="formGroup">
                <label for="password"> Password </label>
                <input type="password" id="password" name="password" minlength="8" autocomplete="new-password" required>
            </div>
            <div class="formGroup">
                <label for="confirm_password"> Confirm Password </label>
                <input type="password" id="confirm_password" name="confirm_password" minlength="8" autocomplete="new-password" required>
            </div>
            <button type="submit" id="authSubmit"> Register </button>
        </form>

        <div id="authSwitch"> Already have an account? <a href="login.php?redirect=<?php echo urlencode($redirectPath); ?>"> Log in </a> </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="<?php echo asset('assets/js/validation.js'); ?>"></script>
</body>
</html>
