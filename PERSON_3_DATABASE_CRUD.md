# Person 3 — Database & CRUD Operations

## Completed features

- Database schema: `sql/schema.sql`
- Seven related tables: users, products, cart items, wishlist items, orders, order items, and contact messages
- Eight sample products and one sample administrator
- Create: products, users, cart/wishlist rows, orders, and contact messages
- Read: product lists/details, cart, wishlist, orders, profile, and admin product table
- Update: admin product editing and cart quantity editing
- Delete: admin product deletion and cart/wishlist removal
- Search: public product keyword search and admin keyword/category search
- Security: PDO prepared statements, foreign keys, password hashing, escaped output, and role checks

## Main CRUD files

| Operation | Files |
|---|---|
| Create | `admin/product-add.php`, `register.php`, `actions/add-to-cart.php`, `actions/add-to-wishlist.php`, `actions/place-order.php`, `actions/submit-contact.php` |
| Read | `products.php`, `product-details.php`, `admin/index.php`, `cart.php`, `wishlist.php`, `order-confirmation.php` |
| Update | `admin/product-edit.php`, `actions/update-cart.php` |
| Delete | `admin/product-delete.php`, `actions/remove-from-cart.php`, `actions/remove-from-wishlist.php` |
| Search | `products.php`, `admin/index.php` |

## Search queries used

Public search matches product name, description, or category:

```sql
SELECT id, name, description, price, image, category, stock
FROM products
WHERE name LIKE ? OR description LIKE ? OR category LIKE ?
ORDER BY created_at DESC;
```

Admin search optionally combines a keyword with a category:

```sql
SELECT id, name, price, category, stock
FROM products
WHERE name LIKE ? AND category = ?
ORDER BY id DESC;
```

## Demonstration checklist

1. Import `sql/schema.sql` in phpMyAdmin for a fresh database.
2. Log in as `admin@shoplane.test` / `Admin@12345`.
3. Open `admin/index.php` and demonstrate Create, Read, Update, and Delete on one temporary product.
4. Search for `watch` on the public navigation search box.
5. Search/filter products in the admin panel.
6. In phpMyAdmin, show the `products` table before and after each CRUD action.

Do not delete a real product during the demonstration. Create a temporary product such as “CRUD Demo Product,” edit it, then delete that same row.
