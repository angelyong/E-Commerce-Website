<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

requireLogin();

$userId = currentUserId();

/*
 * Get the currently logged-in user's information.
 */
$stmt = $pdo->prepare(
    'SELECT name, email, role, created_at
     FROM users
     WHERE id = ?'
);
$stmt->execute([$userId]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

/*
 * If the session contains a user ID that no longer exists
 * in the database, log the user out and send them to login.
 */
if (!$user) {
    session_unset();
    session_destroy();

    header('Location: login.php');
    exit;
}

/*
 * Get the user's order history.
 */
$stmt = $pdo->prepare(
    'SELECT
        o.id,
        o.total,
        o.status,
        o.created_at,
        COALESCE(SUM(oi.quantity), 0) AS item_count
     FROM orders o
     LEFT JOIN order_items oi
        ON oi.order_id = o.id
     WHERE o.user_id = ?
     GROUP BY
        o.id,
        o.total,
        o.status,
        o.created_at
     ORDER BY o.created_at DESC'
);

$stmt->execute([$userId]);

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>My account | SHOPLANE</title>

    <link
        rel="stylesheet"
        href="<?php echo htmlspecialchars(asset('assets/css/main.css')); ?>"
    >

    <link
        rel="stylesheet"
        href="<?php echo htmlspecialchars(asset('assets/css/auth.css')); ?>"
    >
</head>

<body>

    <?php include __DIR__ . '/includes/header.php'; ?>

    <main id="profileContainer">

        <h1>My Account</h1>

        <!-- Account Details -->
        <section
            class="profile-details"
            aria-label="Account details"
        >

            <p>
                <strong>Name:</strong>

                <?php
                echo htmlspecialchars(
                    $user['name'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>
            </p>

            <p>
                <strong>Email:</strong>

                <?php
                echo htmlspecialchars(
                    $user['email'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>
            </p>

            <p>
                <strong>Member since:</strong>

                <?php
                if (!empty($user['created_at'])) {
                    echo htmlspecialchars(
                        date('F j, Y', strtotime($user['created_at'])),
                        ENT_QUOTES,
                        'UTF-8'
                    );
                } else {
                    echo 'N/A';
                }
                ?>
            </p>

            <?php if (!empty($user['role'])): ?>
                <p>
                    <strong>Role:</strong>

                    <?php
                    echo htmlspecialchars(
                        ucfirst($user['role']),
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </p>
            <?php endif; ?>

            <div class="profile-actions">
                <?php if (($user['role'] ?? '') === 'admin'): ?>
                    <a href="admin/index.php" id="adminDashboardBtn">
                        Admin Dashboard
                    </a>
                <?php endif; ?>

                <a href="logout.php" id="logoutBtn">
                    Logout
                </a>
            </div>

        </section>


        <!-- Order History -->
        <section
            class="order-history"
            aria-labelledby="orderHistoryHeading"
        >

            <h2 id="orderHistoryHeading">
                Order history
            </h2>

            <?php if (!empty($orders)): ?>

                <div class="order-list">

                    <?php foreach ($orders as $order): ?>

                        <a
                            class="order-row"
                            href="order-confirmation.php?order_id=<?php echo (int) $order['id']; ?>"
                        >

                            <span>
                                <strong>
                                    Order #<?php echo (int) $order['id']; ?>
                                </strong>

                                <small>
                                    <?php
                                    if (!empty($order['created_at'])) {
                                        echo htmlspecialchars(
                                            date(
                                                'F j, Y',
                                                strtotime($order['created_at'])
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                    } else {
                                        echo 'Date unavailable';
                                    }
                                    ?>
                                </small>
                            </span>

                            <span>
                                <?php echo (int) $order['item_count']; ?>
                                item(s)
                            </span>

                            <span>
                                Rs
                                <?php
                                echo htmlspecialchars(
                                    number_format(
                                        (float) $order['total'],
                                        2
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </span>

                            <span class="order-status">
                                <?php
                                echo htmlspecialchars(
                                    ucfirst($order['status'] ?? ''),
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </span>

                        </a>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="account-empty">

                    <p>
                        You have not placed an order yet.
                    </p>

                    <a href="products.php">
                        Start shopping
                    </a>

                </div>

            <?php endif; ?>
        </section>
    </main>
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
