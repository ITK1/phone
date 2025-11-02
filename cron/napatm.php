<?php
require_once __DIR__ . '/../core/connect.php';

class PaymentModels{
    private $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function createPendding(array $data) :array{
        $sql = "INSERT INTO transactions (user_id, bank_name, account_number, account_name, amount, content, status, created_at)
        VALUES (:user_id, :bank_name, :account_number, :account_name, :amount, :content, 'pending', NOW())";
    $data = $this->pdo->prepare($sql);
    $data->execute([
        'user_id' =>$data["user_id"],
        'bank_name' => $data["bank_name"],
        'account_number' => $data["account_number"],
        'account_name' => $data["account_name"],
        'amount' => $data["amount"],
        'content' => $data["content"],
        'pending' => $data["pending"];
    ])
}

}

?>