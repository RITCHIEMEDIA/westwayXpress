-- Create database
CREATE DATABASE IF NOT EXISTS westway_express;
USE westway_express;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Shipments table
CREATE TABLE shipments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tracking_id VARCHAR(20) UNIQUE NOT NULL,
    sender_name VARCHAR(100) NOT NULL,
    sender_phone VARCHAR(20) NOT NULL,
    sender_address TEXT NOT NULL,
    receiver_name VARCHAR(100) NOT NULL,
    receiver_phone VARCHAR(20) NOT NULL,
    receiver_address TEXT NOT NULL,
    package_description TEXT,
    weight DECIMAL(10,2),
    dimensions VARCHAR(50),
    service_type ENUM('standard', 'express', 'overnight') DEFAULT 'standard',
    status ENUM('pending', 'picked_up', 'in_transit', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_cost DECIMAL(10,2) NOT NULL,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Quotes table
CREATE TABLE quotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quote_number VARCHAR(20) UNIQUE NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    pickup_address TEXT NOT NULL,
    delivery_address TEXT NOT NULL,
    package_description TEXT,
    weight DECIMAL(10,2),
    dimensions VARCHAR(50),
    service_type ENUM('standard', 'express', 'overnight') DEFAULT 'standard',
    estimated_cost DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'accepted', 'converted', 'expired') DEFAULT 'pending',
    valid_until DATE,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password, role, full_name, phone) VALUES 
('admin', 'admin@westwayexpress.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'System Administrator', '+1234567890');

-- Insert sample data
INSERT INTO shipments (tracking_id, sender_name, sender_phone, sender_address, receiver_name, receiver_phone, receiver_address, package_description, weight, service_type, status, shipping_cost, created_by) VALUES
('WE001234567', 'John Smith', '+1234567890', '123 Main St, New York, NY 10001', 'Jane Doe', '+0987654321', '456 Oak Ave, Los Angeles, CA 90210', 'Electronics Package', 2.5, 'express', 'in_transit', 45.99, 1),
('WE001234568', 'Alice Johnson', '+1122334455', '789 Pine St, Chicago, IL 60601', 'Bob Wilson', '+5566778899', '321 Elm St, Miami, FL 33101', 'Documents', 0.5, 'standard', 'delivered', 15.99, 1);
