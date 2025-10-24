<?php
 class data{
   // giữ thể hiện duy nhất của class
    private static $instance =null;
   //giữ kết nối PDO
    private $pdo;

    // cấu hình kết nối data
    private $host = "localhost";

    private $db ="student_mgmt";

    private $user = "root";

    private $pass ="";
// private ngăn việc tạo new data mới bên ngoài, chỉ cho phép tạo class này tự khởi động qua getInstance().

   //  private khởi tạo trực tiếp
    private function __construct(){
      
 $connect = "mysql:host=$this->host;dbname=$this->db;charset=utf8mb4";
  $erro=[
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES =>false,

  ];
    try{
      $this->pdo= new PDO($connect,$this->user,$this->pass,$erro);
    }catch(PDOException $a){
      die(" connect fail, plase reconnect again".$a->getMessage());
    }
   
 }
 

 public static function getInstance(){
  if(self::$dbconnect === null){
    self::$sbconnect === new Data();
  }
  return self::$dbconnect;
  }

  // getconnect láy đối tượng pdo để truy vấn

  public function getConnection(){
    return $this->pdo;
  }

  // __clone ngăn sao chép đối tượng

  private function __clone(){
  }

  // ___wakeup ngăn unserialize đối tượng, bảo vệ $dbconnect không bị tạo lại qua unserialize()

  private function __wakeup(){
    throw new Exception("Cannot unserialize singleton.");
  }

 }
?>