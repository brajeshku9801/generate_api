<?php
class signin{
    private $db_name;
    private $username;
    private $password;
    private $response;
    private $result;
    private $api_key;
    function __construct(){
        require_once("database.php");
        $obj = new db();
        $this->db_name = $obj->__construct();

        $this->username = trim($_POST['username']);
        $this->username = mysqli_real_escape_string($this->db_name,$_POST['username']);
        $this->username = strip_tags($_POST['username']);

        $this->password = trim($_POST['password']);
        $this->password = mysqli_real_escape_string($this->db_name,$_POST['password']);
        $this->password = strip_tags($_POST['password']);
        $this->password = md5($_POST['password']);

        $this->response = $this->db_name->prepare("select * from users where username=? and password=?");

        $this->response->bind_param('ss',$this->username,$this->password);

        $this->response->execute();
        $this->result = $this->response->get_result();
        $this->response->close();

        if($this->result->num_rows != 0){
            // genearate and store api keys
            $this->api_key = md5(rand());
            $this->response = $this->db_name->prepare("update users set api_key=? where username=?");
            $this->response->bind_param('ss',$this->api_key,$this->username);
            $this->response->execute();
            $this->result = $this->response->affected_rows;
            $this->response->close();
            if($this->result != 0){
                echo $this->api_key;
            }
            else{
                echo "Unable to generate api_key !";
            }
        }
        else{
            echo "Wrong username or password !";
        }
    }
}
new signin();
?>