CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account VARCHAR(50),
    name VARCHAR(100),
    amount DECIMAL(15,2),
    content VARCHAR(255),
    bank VARCHAR(50),
    transaction_id VARCHAR(100) UNIQUE,
    status ENUM('pending','success') DEFAULT 'success',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
