-- Create Database if not exists
CREATE DATABASE IF NOT EXISTS real_estate;
USE real_estate;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Locations Table
CREATE TABLE IF NOT EXISTS locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(100) NOT NULL,
    locality VARCHAR(100) NOT NULL
);

-- Properties Table
CREATE TABLE IF NOT EXISTS properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    type ENUM('Buy', 'Rent', 'Sell') NOT NULL,
    price DECIMAL(15, 2) NOT NULL,
    city VARCHAR(100) NOT NULL,
    locality VARCHAR(100) NOT NULL,
    area_sqft INT,
    bedrooms INT,
    bathrooms INT,
    status ENUM('active', 'inactive', 'sold', 'rented') DEFAULT 'active',
    featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Property Images Table
CREATE TABLE IF NOT EXISTS property_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_thumbnail TINYINT(1) DEFAULT 0,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);

-- Amenities Table
CREATE TABLE IF NOT EXISTS amenities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    amenity_name VARCHAR(100) NOT NULL,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);

-- Inquiries Table
CREATE TABLE IF NOT EXISTS inquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT,
    sender_name VARCHAR(100) NOT NULL,
    sender_email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read', 'resolved') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE SET NULL
);

-- Dummy Data for Admin User
-- Password is 'admin123' (bcrypt hash)
INSERT INTO users (name, email, password, role, phone) VALUES 
('Admin User', 'admin@realestate.com', '$2y$10$tZ2/tE3t1H7wUuXg9fXvYOC7P/z7E8R1V.f.E.k.iY8n2gZ5c3vXG', 'admin', '1234567890');

-- Dummy Data for Locations
INSERT INTO locations (city, locality) VALUES
('Chandigarh', 'Sector 17'),
('Chandigarh', 'Sector 22'),
('Chandigarh', 'Sector 35'),
('Chandigarh', 'IT Park'),
('Mumbai', 'Bandra West'),
('Mumbai', 'Andheri East'),
('Delhi', 'Connaught Place'),
('Delhi', 'Hauz Khas'),
('Bangalore', 'Indiranagar'),
('Bangalore', 'Koramangala'),
('Hyderabad', 'Banjara Hills'),
('Chennai', 'Adyar'),
('Kolkata', 'Park Street'),
('Pune', 'Koregaon Park'),
('Ahmedabad', 'Satellite'),
('Jaipur', 'Malviya Nagar'),
('Gurgaon', 'DLF Phase 3'),
('Noida', 'Sector 62');

-- Dummy Data for Properties
INSERT INTO properties (user_id, title, description, type, price, city, locality, area_sqft, bedrooms, bathrooms, featured) VALUES 
(1, 'Luxury Villa in Sector 35', 'A premium 5BHK villa with modern amenities and a private garden.', 'Buy', 45000000, 'Chandigarh', 'Sector 35', 4500, 5, 4, 1),
(1, 'Modern 2BHK Apartment', 'A spacious 2BHK apartment in the heart of the city.', 'Rent', 35000, 'Chandigarh', 'Sector 22', 1200, 2, 2, 1),
(1, 'Sea View Apartment in Bandra', 'Luxurious 3BHK with stunning Arabian Sea views.', 'Buy', 75000000, 'Mumbai', 'Bandra West', 2200, 3, 3, 1),
(1, 'Tech Hub Studio', 'Perfect for IT professionals, walking distance to EGL.', 'Rent', 25000, 'Bangalore', 'Indiranagar', 800, 1, 1, 1),
(1, 'Luxury Villa in Banjara Hills', 'Exclusive gated community villa with private pool.', 'Buy', 120000000, 'Hyderabad', 'Banjara Hills', 6000, 5, 6, 1),
(1, 'Penthouse in Koregaon Park', 'High-end living with premium amenities.', 'Buy', 50000000, 'Pune', 'Koregaon Park', 3500, 4, 4, 1),
(1, 'Executive Villa in IT Park', 'High-end corporate housing near the IT park.', 'Rent', 75000, 'Chandigarh', 'IT Park', 3200, 4, 3, 1),
(1, 'Sector 44 Independent Floor', 'Spacious floor with independent parking.', 'Buy', 32000000, 'Chandigarh', 'Sector 44', 2100, 3, 3, 0),
(1, 'Luxury Duplex in Sector 17', 'Rare find! Duplex in the shopping district.', 'Buy', 55000000, 'Chandigarh', 'Sector 17', 2800, 4, 4, 1),
(1, 'Elite Stay in Hauz Khas', 'Stylish apartment overlooking the lake.', 'Rent', 85000, 'Delhi', 'Hauz Khas', 1800, 3, 3, 1),
(1, 'Koramangala Tech Studio', 'Minimalist studio for the modern professional.', 'Rent', 30000, 'Bangalore', 'Koramangala', 900, 1, 1, 0),
(1, 'Gachibowli High-Rise', 'Luxurious living near the Hitech City.', 'Buy', 18000000, 'Hyderabad', 'Gachibowli', 1600, 3, 2, 0),
(1, 'Garden View Home in Anna Nagar', 'Classic home with a beautiful garden.', 'Buy', 22000000, 'Chennai', 'Anna Nagar', 2400, 4, 3, 0),
(1, 'Salt Lake Executive Flat', 'Modern flat in the corporate hub.', 'Rent', 40000, 'Kolkata', 'Salt Lake', 1400, 2, 2, 0),
(1, 'Sky Villa in Prahlad Nagar', 'Highest point in the locality.', 'Buy', 42000000, 'Ahmedabad', 'Prahlad Nagar', 3800, 5, 5, 1),
(1, 'Wakad Riverside Apartment', 'Peaceful living by the riverside.', 'Rent', 35000, 'Pune', 'Wakad', 1300, 2, 2, 0);

-- Dummy Images
INSERT INTO property_images (property_id, image_path, is_thumbnail) VALUES
(1, 'villa.png', 1), (2, 'studio.png', 1), (3, 'apartment.png', 1),
(4, 'studio.png', 1), (5, 'villa.png', 1), (6, 'villa.png', 1),
(7, 'villa.png', 1), (8, 'apartment.png', 1), (9, 'villa.png', 1),
(10, 'apartment.png', 1), (11, 'studio.png', 1), (12, 'apartment.png', 1),
(13, 'apartment.png', 1), (14, 'studio.png', 1), (15, 'villa.png', 1),
(16, 'studio.png', 1);

-- Dummy Amenities
INSERT INTO amenities (property_id, amenity_name) VALUES 
(1, 'Parking'), (1, 'Garden'), (1, 'Security'),
(2, 'Parking'), (2, 'Elevator'),
(3, 'Parking'), (3, 'Security'), (3, 'Gym'),
(4, 'Parking'), (4, 'Security'),
(5, 'Swimming Pool'), (5, 'Gym'), (5, 'Security'),
(6, 'Swimming Pool'), (6, 'Gym'), (6, 'Parking');
