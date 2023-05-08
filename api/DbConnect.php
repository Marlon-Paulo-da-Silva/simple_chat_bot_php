<?php 

	if(!defined('DB_SERVER')){
		require_once("../initialize.php");
	}

	// /**
	// * Database Connection
	// */
	// class DbConnect {
	// 	private $server = DB_SERVER;
	// 	private $dbname = DB_NAME;
	// 	private $user = DB_USERNAME;
	// 	private $pass = DB_PASSWORD;

	// 	public function connect() {
	// 		try {
	// 			$conn = new PDO('mysql:host=' .$this->server .';dbname=' . $this->dbname, $this->user, $this->pass);
	// 			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// 			return $conn;
	// 		} catch (\Exception $e) {
			
	// 			echo "Database Error: " . $e->getMessage();
	// 		}
	// 	}
	// }


class DbConnect{

    private $host = DB_SERVER;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;
    private $database = DB_NAME;
    
    public $conn;
    
    public function __construct(){

        if (!isset($this->conn)) {
            
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
            
            if (!$this->conn) {
                echo 'Cannot connect to database server';
                exit;
            }            
        }    
        
    }

		
    public function __destruct(){
        $this->conn->close();
    }
}
	
 ?>
