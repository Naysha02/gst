
-- init.sql : create tables and sample data
CREATE DATABASE IF NOT EXISTS findusedcarz DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE findusedcarz;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150),
    email VARCHAR(150) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin','dealer','customer') DEFAULT 'dealer',
    approved TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE dealers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    company_name VARCHAR(255),
    logo VARCHAR(255),
    about TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE listings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dealer_id INT,
    title VARCHAR(255),
    make VARCHAR(100),
    model VARCHAR(100),
    year YEAR,
    mileage INT,
    fuel_type VARCHAR(50),
    transmission VARCHAR(50),
    price DECIMAL(12,2),
    location VARCHAR(150),
    images TEXT,
    approved TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (dealer_id) REFERENCES dealers(id) ON DELETE CASCADE
);

-- sample admin user (password: admin123)
INSERT INTO users (name,email,password,role,approved) VALUES ('Admin','admin@local.test',SHA2('admin123',256),'admin',1);
