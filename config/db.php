 <?php
class database{
    private $host ="localhost";
    private $db_name = "student_mgmt";
    private $username = "root";
    private $pass = "";
    private static $pdo = null;

    public function connect(){
        if(!self::$pdo){
            try{
                $dsn ="mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
                self::$pdo = new PDO($dsn, $this->username,$this->pass);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e){
                die('Connection failed:'. $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
?>