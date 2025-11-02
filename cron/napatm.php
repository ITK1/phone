<?php
require_once __DIR__ . '/../core/connect.php';

class PaymentModel{
    private $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function createPending(array $data) :array{
        $sql = "INSERT INTO transactions (user_id, bank_name, account_number, account_name, amount, content, status, created_at)
                VALUES (:user_id, :bank_name, :account_number, :account_name, :amount, :content, 'pending', NOW())";
    $data = $this->pdo->prepare($sql);
    $data->execute([
        'user_id' =>$data['user_id'],
        'bank_name' => $data['bank_name'],
        'account_number' => $data['account_number'],
        'account_name' => $data['account_name'],
        'amount' => $data['amount'],
        'content' => $data['content']
    ]);
    
    $dulieu['id'] = $this->pdo->lastInsertId();
     return $dulieu;
}
}


class PaymentController {
    private $model;
    public function __construct(PaymentModel $model) {
        $this->model = $model;
    }

    public function createPayment(int $userId, string $bankName, string $accountNumber, string $accountName, float $amount): array {
        $content = "NAP{$userId}_" . time() . "_" . random_int(1000, 9999);
        $data = [
            'user_id' => $userId,
            'bank_name' => $bankName,
            'account_number' => $accountNumber,
            'account_name' => $accountName,
            'amount' => $amount,
            'content' => $content
        ];
        $transaction = $this->model->createPending($data);
    
        //Tao QR
        $qrUrl = "https://img.vietqr.oi/image/{$bankname}-{$accountNumber}-compact2.png"
        . "?amount= {$amount}&addInfo={$content}&accountName=" . urlencode($accountName);
        
        return  [
        'transaction' => $transaction['id'],
        'qr_url' => $qrUrl,
        'content' => $content,
        'amount' => $amount,
        'bank_name' => $bankName,
        'account_number' => $accountNumber,
        'account_name' => $accountName, 
        ];


    }   
}


//=== ENTRY POINT ===

$model = new PaymentModel($pdo);
$controller= new PaymentController($model);

$userId = 1;
$result = null;
$erro = null;

if($_SERVER['REQUEST_METHOD']=== 'POST'){
    $bankName = trim($_POST['bank_name']);
    $accountNumber = trim($_POST['account_number']);    
    $accountName = trim($_POST['account_name']);
    $amount = trim($_POST['amount']);

    if($amount < 0 ||  !$bankName || $accountNumber || !$accountName){
        $error = "vui lòng nhập đủ thông tin !";
    }else{
        $result = $controller ->createPayment($userId, $bankName, $accountNumber, $accountName, $amount);
    }
}


?>
