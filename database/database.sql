DROP DATABASE IF EXISTS php_poo;

CREATE DATABASE  php_poo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE  php_poo;

CREATE TABLE users (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    email VARCHAR(254) UNIQUE NOT NULL,
    username VARCHAR(56) NOT NULL,
    password CHAR(60) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    user_id CHAR(36) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO users (email, username, password, role) VALUES 
('admin@example.com', 'admin', '$2y$10$zTTuXML.ApZmYbEeWZzeSeC0KRDJRrf0OMTWGyxXF9D4Cpa68dxPm', 'admin');


