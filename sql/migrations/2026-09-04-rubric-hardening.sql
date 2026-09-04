USE ecommerce_web;

-- Upgrade existing WAMP installations from the legacy MyISAM default.
-- InnoDB is required for foreign keys, transactions and row locking.
ALTER TABLE users ENGINE=InnoDB;
ALTER TABLE products ENGINE=InnoDB;
ALTER TABLE contact_messages ENGINE=InnoDB;
ALTER TABLE cart_items ENGINE=InnoDB;
ALTER TABLE wishlist_items ENGINE=InnoDB;
ALTER TABLE orders ENGINE=InnoDB;
ALTER TABLE order_items ENGINE=InnoDB;

-- Keep a product-name snapshot so historical orders survive product deletion.
ALTER TABLE order_items ADD COLUMN product_name VARCHAR(150) NULL AFTER product_id;
UPDATE order_items oi
LEFT JOIN products p ON p.id = oi.product_id
SET oi.product_name = COALESCE(p.name, 'Unavailable product');
ALTER TABLE order_items
  MODIFY product_id INT NULL,
  MODIFY product_name VARCHAR(150) NOT NULL;

ALTER TABLE cart_items
  ADD CONSTRAINT fk_cart_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  ADD CONSTRAINT fk_cart_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE;

ALTER TABLE wishlist_items
  ADD CONSTRAINT fk_wishlist_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  ADD CONSTRAINT fk_wishlist_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE;

ALTER TABLE orders
  ADD CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT;

ALTER TABLE order_items
  ADD CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  ADD CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL;

ALTER TABLE products
  MODIFY image VARCHAR(255) NOT NULL DEFAULT 'assets/img/products/placeholder.svg',
  ADD CONSTRAINT chk_products_price CHECK (price >= 0),
  ADD CONSTRAINT chk_products_stock CHECK (stock >= 0);

ALTER TABLE cart_items
  ADD CONSTRAINT chk_cart_quantity CHECK (quantity > 0);

ALTER TABLE orders
  ADD CONSTRAINT chk_orders_total CHECK (total >= 0);

ALTER TABLE order_items
  ADD CONSTRAINT chk_order_items_quantity CHECK (quantity > 0),
  ADD CONSTRAINT chk_order_items_price CHECK (price >= 0);
