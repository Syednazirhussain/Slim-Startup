<?php
class dbconnect extends config{
	private $credentials = array();
    private $serverAddress;
    private $username;
    private $password;
    private $dbname;
    private $conn;
    public function connect(){
		$this->credentials = $this->getDbCredentials();
        $this->serverAddress = $this->credentials['host'];
        $this->username = $this->credentials['username'];
        $this->password = $this->credentials['password'];
        $this->dbname = $this->credentials['db'];
        try {
/*            $this->conn = new PDO("mysql:host=localhost;dbname=fyp",'root','');*/
            $this->conn = new PDO("mysql:host=$this->serverAddress;dbname=$this->dbname", $this->username, $this->password, Array(PDO::MYSQL_ATTR_INIT_COMMAND =>'SET SESSION group_concat_max_len=3423543543'));
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            echo "Connection failed: " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>
