<?php

class DatabaseConnection {

    protected $ini_array; 
    // Hold the class instance.
    private static $instance = null;
    private $conn;
    private $host;
    private $user;
    private $pass;
    private $name;
    private $port;

    private $config_path = '../config/config.ini';
    
    // The db connection is established in the private constructor.
    public function __construct()
    {
        $ini_array = parse_ini_file($this->config_path);

        $this->host = $ini_array["db_host"];
        $this->user = $ini_array["db_user"];
        $this->pass = $ini_array["db_pass"];
        $this->name = $ini_array["db_name"];
        $this->port = $ini_array["db_port"];

        try{
            $this->conn = new PDO("mysql:host={$this->host};port={$this->port};dbname={$this->name}", $this->user,$this->pass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
        
    }
    
    public static function getInstance()
    {
        if(!self::$instance)
        {
            self::$instance = new ConnectDb();
        }
    
        return self::$instance;
    }
    
    public function getConnection()
    {
        return $this->conn;
    }
}