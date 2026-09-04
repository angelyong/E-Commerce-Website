<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdmin('../login.php');

$search = trim($_GET['q'] ?? '');
$category = $_GET['category'] ?? '';
$allowedCategories = ['clothing', 'accessories'];

$sql = 'SELECT id, name, price, category, stock FROM products WHERE 1=1';
$params = [];

if ($search !== '') {
    $sql .= ' AND (name LIKE ? OR category LIKE ?)';
    $term = '%' . $search . '%';
    array_push($params, $term, $term);
}
if (in_array($category, $allowedCategories, true)) {
    $sql .= ' AND category = ?';
    $params[] = $category;
}
$sql .= ' ORDER BY id DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
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

    <main id="adminContainer">
        <div id="adminHeaderRow">
            <h1> Products </h1>
            <a href="product-add.php" id="adminAddBtn"> + Add Product </a>
        </div>

        <form class="adminSearch" method="get" action="index.php">
            <input type="search" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search product name">
            <select name="category">
                <option value="">All categories</option>
                <option value="clothing" <?php echo $category === 'clothing' ? 'selected' : ''; ?>>Clothing</option>
                <option value="accessories" <?php echo $category === 'accessories' ? 'selected' : ''; ?>>Accessories</option>
            </select>
            <button type="submit">Search</button>
            <a href="index.php">Clear</a>
        </form>

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
                                <?php echo csrfField(); ?>
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
    </main>
</body>
</html>
