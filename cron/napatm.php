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

<<<<<<< HEAD
}

?>
=======
      <?php if ($amount > 0): ?>
      <div class="box-qr">
      <h3>👉 Quét mã QR bên dưới để thanh toán</h3>
      <div class="img-qr">
        <img src="https://img.vietqr.io/image/<?= $bank ?>-<?= $account ?>-compact2.jpg?accountName=<?= urlencode($account_name) ?>&amount=<?= $amount ?>&addInfo=<?= urlencode($noidung) ?>" width="300">
      </div>
      <div class="box-tt">
        <div class="box">
          <div>
            <div>Ngân hàng:</div>
          </div>
          <div class="input-nhap"> <?= $bank ?></div>
        </div>
        <div class="box">
          <div>
            <div>STK:</div>
          </div>
          <div class="input-nhap"> <?= $account ?></div>
        </div>
        <div class="box">
          <div>
            <div>Chủ TK:</div>
          </div>
          <div class="input-nhap"> <?= $account_name ?></div>
        </div> 
         <div class="box"> 
          <div>
            <div for="">Nội dung chuyển khoản:</div>
          </div>
          <div class="input-nhap"> <?= $noidung ?></div>
         </div> 
        <div class="box"> 
          <div>
              <div>Số tiền:</div>
          </div>
          <div class="input-nhap"> <?= number_format($amount) ?>đ</div>
        </div>  
          <?php endif; ?>
      </div>
    </div>
    </div>i
  </div>  
</body>
</html>
>>>>>>> 529f8ef2b77473a08fcde4f6936d02c9bb15662e
