<?php
require_once  "/db.php";
public function connect(){
      $this->conn=null;
      try{
         $this->conn = new PDO(
            "mysql:host=$this->host;dbname=$this->db;charset=utf8mb4",
            $this->name,
            $this->pass
         );
         $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }catch(PDOException $e){
         echo "connect failed:" . $e->getMessage();
      }
      return $this->conn;

     }
    
 
?>