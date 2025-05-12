<?php
class signup{
    private $name;
    private $username;
    private $password;
    private $query;
    private $result;
    private $db_name;
    function __construct(){
        require_once("database.php");
        $obj = new db();
        $this->db_name = $obj->__construct();

        $this->name = trim($_POST['name']);
        $this->name = mysqli_real_escape_string($this->db_name,$_POST['name']);
        $this->name = strip_tags($_POST['name']);
        
        $this->username = trim($_POST['username']);
        $this->username = mysqli_real_escape_string($this->db_name,$_POST['username']);
        $this->username = strip_tags($_POST['username']);

        $this->password = trim($_POST['password']);
        $this->password = mysqli_real_escape_string($this->db_name,$_POST['password']);
        $this->password = strip_tags($_POST['password']);
        $this->password = md5($_POST['password']);

        $this->query = $this->db_name->prepare("select * from users where username=?");

        $this->query->bind_param('s',$this->username);
        $this->query->execute();
        $this->result = $this->query->get_result();
        $this->query->close();
        
        if($this->result->num_rows != 0){
            echo "User already exists !";
        }
        else{
            $this->query=$this->db_name->prepare("insert into users(name,username,password)values(?,?,?)");

            $this->query->bind_param('sss',$this->name,$this->username,$this->password);

            $this->query->execute();
            $this->result = $this->query->affected_rows;
            $this->query->close();
            if($this->result != 0){
                echo "Signup success !";
            }
            else{
                echo "Signup failed !";
            }
        }
    }
}
new signup();

?>