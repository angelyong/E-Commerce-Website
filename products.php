<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

$search = trim($_GET['q'] ?? '');
$category = $_GET['category'] ?? '';
$allowedCategories = ['clothing', 'accessories'];

$sql = 'SELECT id, name, description, price, image, category, stock, created_at FROM products WHERE 1=1';
$params = [];

if ($search !== '') {
    $sql .= ' AND (name LIKE ? OR description LIKE ? OR category LIKE ?)';
    $term = '%' . $search . '%';
    array_push($params, $term, $term, $term);
}

if (in_array($category, $allowedCategories, true)) {
    $sql .= ' AND category = ?';
    $params[] = $category;
}

$sql .= ' ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$clothing = array_filter($products, function ($product) {
    return $product['category'] === 'clothing';
});
$accessories = array_filter($products, function ($product) {
    return $product['category'] === 'accessories';
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Products | SHOPLANE</title>

    <link rel="stylesheet" href="<?php echo asset('assets/css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/products.css'); ?>">

</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="catalog-page">

        <section class="catalog-tools" aria-labelledby="catalogToolsHeading">
            <div>
                <span>Find your next favourite</span>
                <h1 id="catalogToolsHeading">Shop all products</h1>
                <p id="catalogResultCount" aria-live="polite"><?php echo count($products); ?> product(s) found</p>
            </div>
            <form id="catalogFilterForm" method="get" action="products.php">
                <label><span>Search</span><input id="catalogSearch" type="search" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Product name or description"></label>
                <label><span>Category</span><select id="catalogCategory" name="category"><option value="">All categories</option><option value="clothing" <?php echo $category === 'clothing' ? 'selected' : ''; ?>>Clothing</option><option value="accessories" <?php echo $category === 'accessories' ? 'selected' : ''; ?>>Accessories</option></select></label>
                <label><span>Sort by</span><select id="catalogSort"><option value="newest">Newest</option><option value="price-low">Price: low to high</option><option value="price-high">Price: high to low</option><option value="name">Name: A–Z</option></select></label>
                <button type="submit">Apply</button>
                <button type="button" class="filter-clear" id="clearCatalogFilters">Clear</button>
            </form>
        </section>

        <section class="product-section catalog-group" id="clothing" data-catalog-group="clothing">
            <div class="section-heading"><div><span>New arrivals every week</span><h2>Clothing for men &amp; women</h2></div></div>
            <div class="product-grid">
            <?php foreach ($clothing as $product): include 'includes/product-card.php'; endforeach; ?>
                <p class="empty-state catalog-empty" <?php echo $clothing ? 'hidden' : ''; ?>>No clothing items match your filters.</p>
            </div>
        </section>

        <section class="product-section catalog-group" id="accessories" data-catalog-group="accessories">
            <div class="section-heading"><div><span>Finish the look</span><h2>Accessories for everyone</h2></div></div>
            <div class="product-grid">
            <?php foreach ($accessories as $product): include 'includes/product-card.php'; endforeach; ?>
                <p class="empty-state catalog-empty" <?php echo $accessories ? 'hidden' : ''; ?>>No accessories match your filters.</p>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="<?php echo asset('assets/js/catalog.js'); ?>"></script>
</body>
</html>
