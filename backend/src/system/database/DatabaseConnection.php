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

    private $config_path = 'config/config.ini';
    
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
    

    //Applying the singleton pattern because it is a common way 
    //of handling classes that are used for configuration 
    //and we just want only one instance of it

    public static function getInstance()
    {
        if(!self::$instance)
        {
            self::$instance = new DatabaseConnection();
        }
    
        return self::$instance;
    }
    
    public function getConnection()
    {
        return $this->conn;
    }

    public function closeConnection()
    {
        return $this->conn = null;
    }

    public function setQueryFilter($filter)
    {
        return $this->conn->query($query);
    }

    public function setQueryOrder($order)
    {
        return $this->conn->query($query);
    }
}