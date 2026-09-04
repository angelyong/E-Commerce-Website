<?php
require_once __DIR__ . '/includes/auth.php';

$errors = $_SESSION['contact_errors'] ?? [];
$old = $_SESSION['contact_old'] ?? ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
$success = $_SESSION['contact_success'] ?? false;
unset($_SESSION['contact_errors'], $_SESSION['contact_old'], $_SESSION['contact_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact us | SHOPLANE</title>
    <link rel="stylesheet" href="<?php echo asset('assets/css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/auth.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/contact.css'); ?>">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main id="contactContainer">
        <h1> Contact Us </h1>

        <div id="contactGrid">
            <div id="contactInfo">
                <h3> Get in Touch </h3>
                <p><svg class="contactIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="M2 6l10 7 10-7"></path></svg> support@shoplane.test</p>
                <p><svg class="contactIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> +1 (555) 123-4567</p>
                <p><svg class="contactIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> Building 101, Central Avenue, LA - 902722, United States</p>

                <div id="socialLinks">
                    <a href="#" title="Facebook"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7H8v-2.9h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6v1.9H16l-.4 2.9h-2.1v7A10 10 0 0 0 22 12z"></path></svg></a>
                    <a href="#" title="Instagram"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5"></rect><circle cx="12" cy="12" r="4"></circle><line x1="17.5" y1="6.5" x2="17.5" y2="6.5"></line></svg></a>
                    <a href="#" title="Twitter"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23 4.9a8.4 8.4 0 0 1-2.36.65 4.1 4.1 0 0 0 1.8-2.27 8.2 8.2 0 0 1-2.6 1 4.1 4.1 0 0 0-7 3.7A11.6 11.6 0 0 1 4.4 3.5a4.1 4.1 0 0 0 1.27 5.47A4 4 0 0 1 3.8 8.4v.05a4.1 4.1 0 0 0 3.3 4 4.1 4.1 0 0 1-1.85.07 4.1 4.1 0 0 0 3.83 2.85A8.2 8.2 0 0 1 2 16.9a11.6 11.6 0 0 0 6.29 1.84c7.55 0 11.68-6.26 11.68-11.68 0-.18 0-.36-.01-.53A8.3 8.3 0 0 0 23 4.9z"></path></svg></a>
                </div>

                <div id="mapEmbed">
                    <iframe src="https://www.google.com/maps?q=Los+Angeles&output=embed" width="100%" height="250" style="border:0;" loading="lazy" title="Store location map"></iframe>
                </div>
            </div>

            <div id="contactFormWrapper">
                <?php if ($success): ?>
                    <div id="contactSuccess"> Thanks! Your message has been sent. </div>
                <?php endif; ?>

                <?php if ($errors): ?>
                    <div id="authErrors">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" action="actions/submit-contact.php" id="contactForm" novalidate>
                    <?php echo csrfField(); ?>
                    <div class="formGroup">
                        <label for="name"> Name </label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($old['name']); ?>" maxlength="100" autocomplete="name" required>
                    </div>
                    <div class="formGroup">
                        <label for="email"> Email </label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($old['email']); ?>" maxlength="150" autocomplete="email" required>
                    </div>
                    <div class="formGroup">
                        <label for="subject"> Subject </label>
                        <input type="text" id="subject" name="subject" value="<?php echo htmlspecialchars($old['subject']); ?>" maxlength="200">
                    </div>
                    <div class="formGroup">
                        <label for="message"> Message </label>
                        <textarea id="message" name="message" maxlength="5000" required><?php echo htmlspecialchars($old['message']); ?></textarea>
                    </div>
                    <button type="submit" id="authSubmit"> Send Message </button>
                </form>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="<?php echo asset('assets/js/validation.js'); ?>"></script>
</body>
</html>
