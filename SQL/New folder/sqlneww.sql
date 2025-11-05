CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    balance DECIMAL(15,2) DEFAULT 0
);

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    amount DECIMAL(15,2),
    bank_code VARCHAR(20),
    account_no VARCHAR(50),
    account_name VARCHAR(100),
    description VARCHAR(100),
    status ENUM('pending','success','failed') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    confirmed_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
