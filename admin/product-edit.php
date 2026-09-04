<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdmin('../login.php');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id, name, description, price, image, category, stock FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: index.php');
    exit;
}

$errors = [];
$name = $product['name'];
$description = $product['description'];
$price = $product['price'];
$image = $product['image'];
$category = $product['category'];
$stock = $product['stock'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken();
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $category = $_POST['category'] ?? '';
    $stock = trim($_POST['stock'] ?? '');

    if ($name === '') {
        $errors[] = 'Name is required.';
    } elseif (strlen($name) > 150) {
        $errors[] = 'Name must be 150 characters or fewer.';
    }
    if (!is_numeric($price) || $price < 0 || $price > 99999999.99) {
        $errors[] = 'Price must be a positive number.';
    }
    if (!in_array($category, ['clothing', 'accessories'], true)) {
        $errors[] = 'Category must be clothing or accessories.';
    }
    if ($stock === '' || !ctype_digit($stock) || (int) $stock > 2147483647) {
        $errors[] = 'Stock must be a whole number.';
    }
    if ($image === '') {
        $image = 'assets/img/products/placeholder.svg';
    } elseif (strlen($image) > 255) {
        $errors[] = 'Image path must be 255 characters or fewer.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('UPDATE products SET name = ?, description = ?, price = ?, image = ?, category = ?, stock = ? WHERE id = ?');
        $stmt->execute([$name, $description, $price, $image, $category, (int) $stock, $id]);

        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Edit Product | Admin </title>
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../<?php echo asset('assets/css/admin.css'); ?>">
</head>
<body>
    <div id="adminBar">
        <div id="adminBrand"> SHOPLANE Admin </div>
        <div id="adminNav">
            <a href="index.php"> Back to Products </a>
            <a href="../logout.php"> Logout </a>
        </div>
    </div>

    <main id="adminContainer">
        <h1> Edit Product </h1>

        <?php if ($errors): ?>
            <div id="adminErrors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="adminForm" method="post" action="product-edit.php?id=<?php echo (int) $id; ?>" id="productForm" novalidate>
            <?php echo csrfField(); ?>
            <div class="formGroup">
                <label for="name"> Name </label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" maxlength="150" required>
            </div>
            <div class="formGroup">
                <label for="description"> Description </label>
                <textarea id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea>
            </div>
            <div class="formGroup">
                <label for="price"> Price </label>
                <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>" min="0" max="99999999.99" step="0.01" required>
            </div>
            <div class="formGroup">
                <label for="image"> Image Path </label>
                <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($image); ?>" maxlength="255">
            </div>
            <div class="formGroup">
                <label for="category"> Category </label>
                <select id="category" name="category">
                    <option value="clothing" <?php echo $category === 'clothing' ? 'selected' : ''; ?>>Clothing</option>
                    <option value="accessories" <?php echo $category === 'accessories' ? 'selected' : ''; ?>>Accessories</option>
                </select>
            </div>
            <div class="formGroup">
                <label for="stock"> Stock </label>
                <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($stock); ?>" min="0" max="2147483647" step="1" required>
            </div>
            <button type="submit" id="adminSubmit"> Save Changes </button>
        </form>
    </main>
    <script src="../<?php echo asset('assets/js/validation.js'); ?>"></script>
</body>
</html>
