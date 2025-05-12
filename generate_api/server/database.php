<?php
class db{
    private $server="localhost";
    private $username="root";
    private $password="";
    private $db_name="api";
    private $database;

    function __construct(){
        $this->database = new mysqli($this->server,$this->username,$this->password,$this->db_name);
        if($this->database->connect_error){
            die("connection failed");
        }
    }
    function connect(){
        return $this->database;
    }
}
?>