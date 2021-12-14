<?php
include('config/config.php');

//Klass
class Course {
    private $db;
    public $code;
    public $course_name;
    public $progression;
    public $course_description;
   

//constructor
function __construct() {
    $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
    if($this->db->connect_errno > 0){
        die("Fel vid anslutning: " . $this->db->connect_error);
    }
}

//Lista alla kurser
public function read() : array {
    $sql = "SELECT * FROM courses";
    
    $result = mysqli_query($this->db, $sql);
    
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

//Hämta kurs med id
public function readOne(int $id) : array {
    $sql = "SELECT * FROM courses WHERE id=$id;";
    $result = mysqli_query($this->db, $sql);

    /*
    $row = $result->fetch_assoc();*/
    
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    }

//Registrera ny kurs
public function add(string $code, string $course_name, string $progression, string $course_description) : bool {
    //Tar bort specialtecken inför lagring i databasen
    $code = $this->db->real_escape_string($code);
    $course_name = $this->db->real_escape_string($course_name);
    $progression = $this->db->real_escape_string($progression);
    $course_description = $this->db->real_escape_string($course_description);

    //Prepared statement
    $stmt = $this->db->prepare("INSERT INTO courses(code, course_name, progression, course_description)VALUES('$code', '$course_name', '$progression', '$course_description')");
    $stmt->bind_param("ss", $this->code, $this->course_name, $this->progression, $this->course_description);

    //Execute statement
    if ($stmt->execute()){
        return true;
    } else 
    return false;

    //Close statement
    $stmt->close();
}


//Radera kurs
public function deleteCourse(int $id) : bool {
    $sql = "DELETE FROM courses WHERE id=$id;";
    $result = mysqli_query($this->db, $sql);

    return $result;

}


}