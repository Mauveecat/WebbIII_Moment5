<?php

spl_autoload_register(function ($class_name) {
    include 'classes/' . $class_name . '.class.php'; //sökväg till mappen för dina klasser
});

/*Visa felmeddelanden
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  
*/

  //DB-settings 
  define("DBHOST", "studentmysql.miun.se");
  define("DBUSER", "jens2001");
  define("DBPASS", "a6kdkk8vrm");
  define("DBDATABASE", "jens2001");


/*Starta session
session_start();  */