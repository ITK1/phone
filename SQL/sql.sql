CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    bank_name VARCHAR(100),
    account_number VARCHAR(50),
    account_name VARCHAR(100),
    amount DECIMAL(15,2),
    content VARCHAR(255),
    status ENUM('pending','success','failed') DEFAULT 'pending',
    webhook_code VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Giao dịch mẫu
INSERT INTO transactions (user_id, bank_name, account_number, account_name, amount, content, status)
VALUES
(1, 'MB Bank', '123456789', 'NGUYEN VAN A', 200000, 'NAP_001', 'success');