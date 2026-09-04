CREATE DATABASE IF NOT EXISTS ecommerce_web;
USE ecommerce_web;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('customer','admin') DEFAULT 'customer',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  image VARCHAR(255) NOT NULL DEFAULT 'assets/img/products/placeholder.svg',
  category VARCHAR(80),
  stock INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT chk_products_price CHECK (price >= 0),
  CONSTRAINT chk_products_stock CHECK (stock >= 0)
) ENGINE=InnoDB;

CREATE INDEX idx_products_name ON products(name);
CREATE INDEX idx_products_category ON products(category);
CREATE INDEX idx_products_created_at ON products(created_at);

CREATE TABLE cart_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT DEFAULT 1,
  CONSTRAINT fk_cart_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_cart_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  CONSTRAINT chk_cart_quantity CHECK (quantity > 0),
  UNIQUE KEY uq_cart_user_product (user_id, product_id)
) ENGINE=InnoDB;

CREATE TABLE wishlist_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  CONSTRAINT fk_wishlist_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_wishlist_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  UNIQUE KEY uq_wishlist_user_product (user_id, product_id)
) ENGINE=InnoDB;

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  status VARCHAR(30) DEFAULT 'placed',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
  CONSTRAINT chk_orders_total CHECK (total >= 0)
) ENGINE=InnoDB;

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NULL,
  product_name VARCHAR(150) NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL,
  CONSTRAINT chk_order_items_quantity CHECK (quantity > 0),
  CONSTRAINT chk_order_items_price CHECK (price >= 0)
) ENGINE=InnoDB;

CREATE TABLE contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  subject VARCHAR(200),
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO users (name, email, password_hash, role) VALUES
('Admin', 'admin@shoplane.test', '$2y$10$Fo.7p6PA0RP/pNtiQNVBlOks5wBXtqM2IcX6B72E8JRgg8Nj/9G1q', 'admin');

INSERT INTO products (name, description, price, image, category, stock) VALUES
('Classic Denim Jacket', 'A timeless denim jacket that pairs well with any outfit.', 89.90, 'assets/img/products/placeholder.svg', 'clothing', 25),
('Men''s Casual Shirt', 'Breathable cotton shirt for everyday wear.', 45.50, 'assets/img/products/placeholder.svg', 'clothing', 40),
('Women''s Summer Dress', 'Lightweight floral dress, perfect for warm days.', 65.00, 'assets/img/products/placeholder.svg', 'clothing', 18),
('Slim Fit Chinos', 'Comfortable slim fit chinos in navy blue.', 55.90, 'assets/img/products/placeholder.svg', 'clothing', 30),
('Leather Wallet', 'Genuine leather bifold wallet with card slots.', 29.90, 'assets/img/products/placeholder.svg', 'accessories', 60),
('Aviator Sunglasses', 'UV-protected classic aviator sunglasses.', 39.90, 'assets/img/products/placeholder.svg', 'accessories', 45),
('Canvas Tote Bag', 'Durable canvas tote bag for daily essentials.', 24.90, 'assets/img/products/placeholder.svg', 'accessories', 50),
('Stainless Steel Watch', 'Water-resistant analog watch with steel strap.', 120.00, 'assets/img/products/placeholder.svg', 'accessories', 15);
