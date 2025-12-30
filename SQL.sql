-- قاعدة بيانات مشروع Foodly المحدثة والكاملة

-- حذف الجداول القديمة لو موجودة
CREATE DATABASE db;
-- جدول المستخدمين (Users)
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول التجار (Merchants)
CREATE TABLE merchants (
    merchant_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    business_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول الطلبات (Orders)
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'visa') NOT NULL DEFAULT 'cash',
    order_status ENUM('pending', 'completed', 'cancelled', 'delivered') DEFAULT 'pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول تفاصيل الطلبات (Order Items)
CREATE TABLE order_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_name VARCHAR(100) NOT NULL,
    product_price DECIMAL(10,2) NOT NULL,
    quantity INT DEFAULT 1,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول المنتجات (Products) - للتجار
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    merchant_id INT NOT NULL,
    product_name VARCHAR(100) NOT NULL,
    product_price DECIMAL(10,2) NOT NULL,
    product_category VARCHAR(50),
    product_description TEXT,
    product_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (merchant_id) REFERENCES merchants(merchant_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- إدراج بيانات تجريبية للمستخدمين
-- كلمة المرور: 123456 (مشفرة)
INSERT INTO users (username, email, password, phone) VALUES
('ahmed', 'ahmed@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01234567890'),
('mohamed', 'mohamed@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01098765432');

-- إدراج بيانات تجريبية للتجار
-- كلمة المرور: merchant123 (مشفرة)
INSERT INTO merchants (username, email, password, business_name) VALUES
('merchant1', 'merchant@foodly.com', 'merchant123', 'Foodly Restaurant');

-- إدراج طلبات تجريبية لعرضها في الـ Profile
INSERT INTO orders (user_id, total_price, payment_method, order_status, order_date) VALUES
(1, 150.00, 'visa', 'delivered', '2025-01-12 14:30:00'),
(1, 80.00, 'cash', 'cancelled', '2025-01-08 11:20:00'),
(1, 120.00, 'visa', 'delivered', '2025-01-05 19:45:00'),
(1, 30.00, 'cash', 'cancelled', '2025-01-02 16:10:00');

-- إدراج تفاصيل الطلبات التجريبية
INSERT INTO order_items (order_id, product_name, product_price, quantity) VALUES
(1, 'Pizza Margherita', 120.00, 1),
(1, 'Coca Cola', 30.00, 1),
(2, 'Burger Meal', 80.00, 1),
(3, 'Chicken Shawarma', 120.00, 1),
(4, 'Cold Drink - Lemon', 30.00, 1);
