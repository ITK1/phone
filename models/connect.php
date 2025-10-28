<?php
require_once "../config/config.php";
class Database{
   private static $getsql = null;
   private $pdo;


   private function __construct(){  
      try{
         $dns ="mysql:host=" . host. ";dbname=". db . ";charset=utf8mb4";
         $this->pdo = new PDO($dns,user,pass);
         $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
      }catch(PDOException $e){
         die("connect failed:". $e->getMessage());
      }
}


   public static function getsql(){
      if(self::$getsql ==null){
         self::$getsql = new Database();
      }
      return self::$getsql;
   }

   public function getConnection(){
      return $this->pdo;
   }
   }
?>