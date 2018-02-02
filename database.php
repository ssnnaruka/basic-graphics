<?php
// ini_set('display_errors','On'); 
// error_reporting(E_ALL); 

$servername = "localhost";
$username = "root";
$password = "root";

// Create connection
$conn = new mysqli($servername, $username, $password, 'ed_demo');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Connected successfully...";

	$request_method=$_SERVER["REQUEST_METHOD"];
	switch($request_method)
	{
		case 'GET':
			// Retrive Products
			if(!empty($_GET["temp_id"]))
			{
				$product_id=intval($_GET["temp_id"]);
				get_templates($product_id);
			}
			else
			{
				get_templates();
			}
			break;
		case 'POST':
			// Insert Product
			insert_template($_FILES, $_POST, $conn);
			break;
		case 'PUT':
			// Update Product
			// $product_id=intval($_GET["product_id"]);
			// update_product($product_id);
			break;
		case 'DELETE':
			// Delete Product
			// $product_id=intval($_GET["product_id"]);
			// delete_product($product_id);
			break;
		default:
			// Invalid Request Method
			header("HTTP/1.0 405 Method Not Allowed");
			break;
	}

function get_templates($temp_id = 0){
	global $conn;
	$query = "SELECT * FROM `ed_template_file`;";
	if($temp_id != 0)
	{
		$query.=" WHERE id=".$temp_id." LIMIT 1";
	}
	$response = array();
	$result = mysqli_query($conn, $query);
	while($row = mysqli_fetch_array($result))
	{
		$response[] = $row;
	}
	header('Content-Type: application/json');
	echo json_encode($response);
}

function insert_template($file, $post, $conn) {
	$tName = "";
	if ($file['file']['size'] > 0) {
		$sourcePath = $file['file']['tmp_name'];       // Storing source path of the file in a variable
		$tName = $file['file']['name'];
		$targetPath = "upload/".$tName; // Target path where file is to be stored
		move_uploaded_file($sourcePath,$targetPath) ;    // Moving Uploaded file
	} else {
		echo "No file";
	}

	if(isset($post))
	{
		// print_r($post);
	}
	$sql = "";
	if(isset($post['id']))
	{
		// print_r($post['id']);
		// $sql = "INSERT INTO `ed_template_file` (`config`, `id`) VALUES ('" . json_encode($post) . "', '" . $post['id'] . "')";

		$sql = "UPDATE `ed_template_file` SET `config`='" . $post['data'] . "' WHERE `id`=" . $post['id'];
	} else {
		// $sql = "INSERT INTO `ed_template_file` (`config`, `image`) VALUES ('" . json_encode($post) . "', '" . $tName . "')";
		$sql = "INSERT INTO `ed_template_file` (`config`, `image`) VALUES ('" . $post['data'] . "', '" . $tName . "')";
	}

	

	// $sql = "INSERT INTO `ed_template_file` (`config`, `image`) VALUES ('" . json_encode($post) . "', '" . $tName . "')";

	// print_r($sql);

	print_r($post);
	print_r($post['data']);

	// $js1 = json_encode($post);
	// print_r($js1);
	// $js2 = json_decode($js1);
	// print_r($js2);
	// print_r($js1['data']);
	// print_r($js2['data']);

	if ($conn->query($sql) === TRUE) {
	    echo "New record created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

?>