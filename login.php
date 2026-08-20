<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$redirectPath = sanitizeRedirectPath($_REQUEST['redirect'] ?? '', 'index.php');

if (isLoggedIn()) {
    header('Location: ' . $redirectPath);
    exit;
}

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id, name, password_hash, role FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        header('Location: ' . $redirectPath);
        exit;
    }

    $errors[] = 'Invalid email or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Login | E-COMMERCE WEBSITE </title>
    <link rel="icon" href="https://yt3.ggpht.com/a/AGF-l78km1YyNXmF0r3-0CycCA0HLA_i6zYn_8NZEg=s900-c-k-c0xffffffff-no-rj-mo" type="image/gif" sizes="16x16">
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('assets/css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/auth.css'); ?>">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div id="authContainer">
        <h1> Log In </h1>

        <?php if ($errors): ?>
            <div id="authErrors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="login.php" id="loginForm">
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirectPath); ?>">
            <div class="formGroup">
                <label for="email"> Email </label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="formGroup">
                <label for="password"> Password </label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" id="authSubmit"> Log In </button>
        </form>

        <div id="authSwitch"> Don't have an account? <a href="register.php?redirect=<?php echo urlencode($redirectPath); ?>"> Register </a> </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="<?php echo asset('assets/js/validation.js'); ?>"></script>
</body>
</html>
