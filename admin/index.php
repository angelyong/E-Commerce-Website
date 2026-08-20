<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdmin('../login.php');

$products = $pdo->query('SELECT id, name, price, category, stock FROM products ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Admin - Products | E-COMMERCE WEBSITE </title>
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../<?php echo asset('assets/css/admin.css'); ?>">
</head>
<body>
    <div id="adminBar">
        <div id="adminBrand"> SHOPLANE Admin </div>
        <div id="adminNav">
            <a href="../index.php"> View Site </a>
            <a href="../logout.php"> Logout </a>
        </div>
    </div>

    <div id="adminContainer">
        <div id="adminHeaderRow">
            <h1> Products </h1>
            <a href="product-add.php" id="adminAddBtn"> + Add Product </a>
        </div>

        <div id="adminTableWrap">
        <table id="adminTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo (int) $product['id']; ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                        <td>Rs <?php echo htmlspecialchars(number_format($product['price'], 2)); ?></td>
                        <td><?php echo (int) $product['stock']; ?></td>
                        <td>
                            <a href="product-edit.php?id=<?php echo (int) $product['id']; ?>"> Edit </a>
                            <form class="inlineForm" method="post" action="product-delete.php" onsubmit="return confirm('Delete this product?');">
                                <input type="hidden" name="id" value="<?php echo (int) $product['id']; ?>">
                                <button type="submit" class="adminDeleteBtn"> Delete </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$products): ?>
                    <tr><td colspan="6"> No products yet. </td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</body>
</html>
