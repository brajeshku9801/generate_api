<?php
//header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");

class main{
    private static $db;
    private static $datas;
    private static $returned;
    private static $api_key;
    private static $roll_no;
    private static $student_name;

    static function generate_api(){
        require_once("database.php");
        $ob=new db();
        self::$db = $ob->connect();

        self::$datas = isset($_POST['api_key']) && isset($_POST['roll_no']) && isset($_POST['student_name']);

        if(self::$datas){

            self::$api_key=mysqli_real_escape_string(self::$db,trim($_POST['api_key']));
            self::$api_key=strip_tags($_POST['api_key']);

            self::$roll_no=mysqli_real_escape_string(self::$db,trim($_POST['roll_no']));
            self::$roll_no=strip_tags($_POST['roll_no']);

            self::$student_name=mysqli_real_escape_string(self::$db,trim($_POST['student_name']));
            self::$student_name=strip_tags($_POST['student_name']);

            self::$returned = user::verify_api(self::$api_key);
            if(self::$returned){

                self::$returned = user::student_info(self::$roll_no,self::$student_name);
                echo self::$returned;
            }
            else{
                self::$datas = array(
                    "status" => "error",
                    "cause" => "Incorrect api_key",
                );
    
                self::$datas=json_encode(self::$datas);
                echo self::$datas;
            }
        }
        else{

            self::$datas = array(
                "status" => "error",
                "cause" => "Missing properties into data",
            );

            self::$datas=json_encode(self::$datas);
            echo self::$datas;
        }
    }
}
class user{
    private static $db_name;
    private static $response;
    private static $api_key;
    private static $result;
    private static $roll_no;
    private static $student_name;
    private static $data;

    static function database(){
        require_once("database.php");
        $ob=new db();
        self::$db_name = $ob->connect();
    }
    static function verify_api($x){
        self::database();
        self::$api_key = $x;
        self::$response = self::$db_name->prepare("select * from users where api_key=? ");

        self::$response->bind_param('s',self::$api_key);
        self::$response->execute();
        self::$result = self::$response->get_result();
        self::$response->close();

        if(self::$result->num_rows != 0){
            return true;
        }
        else{
            return false;
        } 
    }
    static function student_info($y,$z){
        self::database();
        self::$roll_no = $y;
        self::$student_name = $z;

        self::$response = self::$db_name->prepare("select * from result where roll_no=? and student_name=?");
        self::$response->bind_param('is',self::$roll_no,self::$student_name);

        self::$response->execute();
        self::$result = self::$response->get_result();
        self::$response->close();

        if(self::$result->num_rows != 0){

            self::$result = self::$result->fetch_assoc();

            self::$data = array(
                "status" => "ok",
                "roll_no" => self::$result['roll_no'],
                "student_name" => self::$result['student_name'],
                "father_name" => self::$result['father_name'],
                "roll_no" => self::$result['roll_no'],
                "mathematics" => self::$result['mathematics'],
                "science" => self::$result['science'],
                "english" => self::$result['english'],
                "history" => self::$result['history'],
                "dob" => self::$result['dob'],
                "grade" => self::$result['grade'],
                "total" => self::$result['total']
            );
            self::$data = json_encode(self::$data);
            return self::$data;
        }
        else{
            self::$data = array(
                "status" => "error",
                "cause" => "Student record not found"
            );
            self::$data = json_encode(self::$data);
            return self::$data;
        }
    }
}
main::generate_api();
?>