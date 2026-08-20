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
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($name === '') {
        $errors[] = 'Name is required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email is required.';
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
    <title> Register | E-COMMERCE WEBSITE </title>
    <link rel="icon" href="https://yt3.ggpht.com/a/AGF-l78km1YyNXmF0r3-0CycCA0HLA_i6zYn_8NZEg=s900-c-k-c0xffffffff-no-rj-mo" type="image/gif" sizes="16x16">
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('assets/css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/auth.css'); ?>">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div id="authContainer">
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

        <form method="post" action="register.php" id="registerForm">
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirectPath); ?>">
            <div class="formGroup">
                <label for="name"> Full Name </label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            <div class="formGroup">
                <label for="email"> Email </label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="formGroup">
                <label for="password"> Password </label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="formGroup">
                <label for="confirm_password"> Confirm Password </label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" id="authSubmit"> Register </button>
        </form>

        <div id="authSwitch"> Already have an account? <a href="login.php?redirect=<?php echo urlencode($redirectPath); ?>"> Log in </a> </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="<?php echo asset('assets/js/validation.js'); ?>"></script>
</body>
</html>
