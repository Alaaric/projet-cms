DROP DATABASE IF EXISTS php_poo;

CREATE DATABASE php_poo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE php_poo;

CREATE TABLE users (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    email VARCHAR(254) UNIQUE NOT NULL,
    username VARCHAR(56) NOT NULL,
    password CHAR(60) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE templates (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name VARCHAR(255) NOT NULL,
    structure TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pages (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    user_id CHAR(36) NOT NULL,
    template_id CHAR(36) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (template_id) REFERENCES templates(id)
);

INSERT INTO users (email, username, password, role) VALUES 
('admin', 'admin', '$2y$10$zTTuXML.ApZmYbEeWZzeSeC0KRDJRrf0OMTWGyxXF9D4Cpa68dxPm', 'admin'),
('user', 'user', '$2y$10$zTTuXML.ApZmYbEeWZzeSeC0KRDJRrf0OMTWGyxXF9D4Cpa68dxPm', 'user');

INSERT INTO templates (name, structure) VALUES
('Template de base', '<header><h1>{{title}}</h1><nav><a href="/">Accueil</a></nav></header><main>{{mainContent}}</main><footer>{{footerContent}} <p>&copy; <?= date("Y") ?> Projet CMS</p></footer>');