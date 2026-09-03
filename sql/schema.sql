CREATE DATABASE IF NOT EXISTS ecommerce_web;
USE ecommerce_web;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('customer','admin') DEFAULT 'customer',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  image VARCHAR(255),
  category VARCHAR(80),
  stock INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_products_name ON products(name);
CREATE INDEX idx_products_category ON products(category);
CREATE INDEX idx_products_created_at ON products(created_at);

CREATE TABLE cart_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT DEFAULT 1,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  UNIQUE KEY uq_cart_user_product (user_id, product_id)
);

CREATE TABLE wishlist_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  UNIQUE KEY uq_wishlist_user_product (user_id, product_id)
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  status VARCHAR(30) DEFAULT 'placed',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  subject VARCHAR(200),
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, email, password_hash, role) VALUES
('Admin', 'admin@shoplane.test', '$2y$10$Fo.7p6PA0RP/pNtiQNVBlOks5wBXtqM2IcX6B72E8JRgg8Nj/9G1q', 'admin');

INSERT INTO products (name, description, price, image, category, stock) VALUES
('Classic Denim Jacket', 'A timeless denim jacket that pairs well with any outfit.', 89.90, 'assets/img/products/placeholder.png', 'clothing', 25),
('Men''s Casual Shirt', 'Breathable cotton shirt for everyday wear.', 45.50, 'assets/img/products/placeholder.png', 'clothing', 40),
('Women''s Summer Dress', 'Lightweight floral dress, perfect for warm days.', 65.00, 'assets/img/products/placeholder.png', 'clothing', 18),
('Slim Fit Chinos', 'Comfortable slim fit chinos in navy blue.', 55.90, 'assets/img/products/placeholder.png', 'clothing', 30),
('Leather Wallet', 'Genuine leather bifold wallet with card slots.', 29.90, 'assets/img/products/placeholder.png', 'accessories', 60),
('Aviator Sunglasses', 'UV-protected classic aviator sunglasses.', 39.90, 'assets/img/products/placeholder.png', 'accessories', 45),
('Canvas Tote Bag', 'Durable canvas tote bag for daily essentials.', 24.90, 'assets/img/products/placeholder.png', 'accessories', 50),
('Stainless Steel Watch', 'Water-resistant analog watch with steel strap.', 120.00, 'assets/img/products/placeholder.png', 'accessories', 15);
