-- init_db.sql
CREATE DATABASE IF NOT EXISTS airbook CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE airbook;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','admin') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS flights (
  id INT AUTO_INCREMENT PRIMARY KEY,
  flight_code VARCHAR(20) NOT NULL,
  origin VARCHAR(100) NOT NULL,
  destination VARCHAR(100) NOT NULL,
  depart_time DATETIME NOT NULL,
  arrive_time DATETIME NOT NULL,
  duration_minutes INT NOT NULL,
  price DECIMAL(8,2) NOT NULL,
  seats_total INT NOT NULL DEFAULT 150,
  seats_booked INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  flight_id INT NOT NULL,
  seats INT DEFAULT 1,
  total_price DECIMAL(10,2) NOT NULL,
  booked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (flight_id) REFERENCES flights(id) ON DELETE CASCADE
);

-- Seed admin (demo uses md5 for simplicity)
INSERT IGNORE INTO users (name,email,password,role)
VALUES ('Admin','skips@gmail.com', MD5('12345'), 'admin');

-- Seed a few flights
INSERT INTO flights (flight_code, origin, destination, depart_time, arrive_time, duration_minutes, price, seats_total)
VALUES 
('AB101','Mumbai','Delhi', DATE_ADD(NOW(), INTERVAL 1 DAY), DATE_ADD(NOW(), INTERVAL 1 DAY + INTERVAL 2 HOUR), 120, 4500.00, 180),
('AB102','Delhi','Mumbai', DATE_ADD(NOW(), INTERVAL 2 DAY), DATE_ADD(NOW(), INTERVAL 2 DAY + INTERVAL 2 HOUR), 120, 4600.00, 180),
('AB201','Bengaluru','Hyderabad', DATE_ADD(NOW(), INTERVAL 1 DAY), DATE_ADD(NOW(), INTERVAL 1 DAY + INTERVAL 1 HOUR), 60, 2500.00, 150),
('AB301','Kolkata','Chennai', DATE_ADD(NOW(), INTERVAL 3 DAY), DATE_ADD(NOW(), INTERVAL 3 DAY + INTERVAL 2 HOUR), 130, 5200.00, 160);
