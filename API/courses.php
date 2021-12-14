<?php

include ('config/config.php');

//Skapar instans av klassen för att skicka SQL-frågor till databasen
$courses = new Course($db);

/* Inställningar för Rest-webbtjänst */
header('Content-Type: application/json');

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');

header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

//Läser in vilken metod som skickats och lagrar i en variabel
$method = $_SERVER['REQUEST_METHOD'];

//Om en parameter av id finns i urlen lagras det i en variabel
if(isset($_GET['id'])){
    $id = $_GET['id'];
}

switch($method){
    case 'GET':
        if(isset($id)){
            //Kör funktion för att läsa rad efter id
            $result = $courses->readOne($id);
        } else {
            //Funktion för att läsa data från tabellen
            $result = $courses->read();
        }

        //Kontroll om resultatet innehåller rader
        if(sizeof($result) > 0){
            http_response_code(200); //OK
        } else {
            http_response_code(404); //Not found
            $result = array("message" => "No courses listed");
        }
        break;

        case 'POST':
            $json = file_get_contents('php://input');
            $data = json_decode($json);

            //Removes tags and makes special character available to store
            //Sends input to the class properties
            $courses->code = $data->code;
            $courses->course_name = $data->course_name;
            $courses->progression = $data->progression;
            $courses->course_description = $data->course_description; 

            if ($data->code == "" || $data->course_name == "" || $data->progression == "" || $data->course_description == "") {
                $response = array("message" => "Enter all data required");

                http_response_code(400); //user error

            } else {
            //Kör funktion för att skapa rad i databasen
            if($courses->add($data->code, $data->course_name, $data->progression, $data->course_description)){
                http_response_code(201); //Created
                $result = array("message" => "Course added");
            } else {
                http_response_code(503); //Server error
                $result = array("message" => "Course could not be added");
            } 
        }
            break;
/* Används ej i detta moment
case 'PUT':
    //Om inget id är skickat, skicka error
    if(!isset($id)){
        http_response_code(510); //Not extended
        $result = array("message" => "No id is sent");
        //Om kod är skickad
    } else {
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        //Removes tags and makes special character available to store
            //Sends input to the class properties
            //$courses->id = $id; 
            $courses->code = $data->code;
            $courses->course_name = $data->course_name;
            $courses->progression = $data->progression;
            $courses->course_description = $data->course_description;

            //Kör funktion för att uppdatera en rad
            if($courses->updateCourse($id, $data->code, $data->course_name, $data->progression, $data->course_description)){
                http_response_code(200); //OK
                $result = array("message" => "Course updated");
            } else {
                http_response_code(503); //server error
                $result = array("message" => "Course could not be updated");
            }
    }
    break; */

    case 'DELETE': 
        //Om inget id är medskickat, skicka felmeddelande
        if(!isset($id)){
            http_response_code(510); //Not extended
            $result = array("message" => "No id is sent");
            //Om id har skickats
        } else {
            //Kör funktion för att radera en rad
            if($courses->deleteCourse($id)){
                http_response_code(200); //OK
                $result = array("message" => "Course deleted");
            } else {
                http_response_code(503); //server error
                $result = array("message" => "Course could not be deleted");
            }
        }
        break; 

}

//Return result av JSON
echo json_encode($result);
