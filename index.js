
// $sql = "INSERT INTO ".$tableName." (image, config)
// VALUES ('" . $_POST . "', '" . $tName . "'')";

// print_r($sql);

// CREATE TABLE `ed_demo`.`ed_template_file` ( `id` INT NOT NULL AUTO_INCREMENT , `created_on` DATE NOT NULL , `image` VARCHAR(256) NULL DEFAULT NULL , `config` VARCHAR(512) NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;




<?php
ini_set('display_errors','On'); 
error_reporting(E_ALL); 

$servername = "localhost";
$username = "root";
$password = "root";
$dbName = "ed_name";
$tableName = "ed_template_file"

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 




function saveImageTemp() {
    echo "Hello world!";
}

echo "Connected successfully...";

function display($cars)
{
	// foreach($cars as $x => $x_value) {
 //    	echo "Key=" . $x . ", Value=" . $x_value;
 //    	echo "<br>";
	// }
	// echo $cars[0];
    // echo count($cars); //.$_POST["studentname"];
}

// print_r($_FILES["file"]);
$tName = "";
if ($_FILES['file']['size'] > 0) {
	// print_r($_FILES["file"]["size"]);
	$sourcePath = $_FILES['file']['tmp_name'];       // Storing source path of the file in a variable
	$tName = $_FILES['file']['name'];
	// $parts = explode('.', $tName);
	// print_r($parts)
	// print_r($tName);
	$targetPath = "upload/".$tName; // Target path where file is to be stored
	move_uploaded_file($sourcePath,$targetPath) ;    // Moving Uploaded file
} else {
	echo "No file";
}

if(isset($_POST))
{
	print_r($_POST);
	// display($_POST);
} 


?>