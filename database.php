<?php
ini_set('display_errors','On'); 
error_reporting(E_ALL); 

$servername = "localhost";
$username = "root";
$password = "root";

// Create connection
$conn = new mysqli($servername, $username, $password, 'ed_demo');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

echo "Connected successfully...";

$tName = "";
if ($_FILES['file']['size'] > 0) {
	$sourcePath = $_FILES['file']['tmp_name'];       // Storing source path of the file in a variable
	$tName = $_FILES['file']['name'];
	$targetPath = "upload/".$tName; // Target path where file is to be stored
	move_uploaded_file($sourcePath,$targetPath) ;    // Moving Uploaded file
} else {
	echo "No file";
}

if(isset($_POST))
{
	print_r($_POST);
}

$sql = "INSERT INTO `ed_template_file` (`config`, `image`) VALUES ('" . json_encode($_POST) . "', '" . $tName . "')";

print_r($sql);

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>