<?php
// include config
require_once('../includes/config.php');

//redirect the unauthorized user to login page
if(!$user->is_logged_in())
{
	header('Location: login.php');
	exit;
}

ini_set('display_errors',1);
error_reporting(E_ALL);


$target_dir= "/filesupload/"; 
$target_file = $_SERVER['DOCUMENT_ROOT'] . $target_dir . basename($_FILES['uploadfile']['name']);
$uploadok=1;
$filetype= pathinfo($target_file,PATHINFO_EXTENSION);

if(isset($_POST['submit'])){
	if(file_exists($target_file)){
	echo '<p align="center">Sorry, file already exists.</p>';
    $uploadok = 0;
	}
	
	if($uploadok == 0){
	 	echo '<p align="center">Your file was not uploaded.</p>';
	} else {
	
		if(move_uploaded_file($_FILES['uploadfile']['tmp_name'], $target_file)){ // Be careful of SELinux security Setting
			echo '<p align="center">The file '. basename( $_FILES["uploadfile"]["name"]). " has been uploaded.</p>";
			echo '<p align="center">The file link is http://blog.recognize.tech/filesupload/'. basename( $_FILES["uploadfile"]["name"]).'</p>';
		} else {
			echo '<p align="center">Sorry, failure to upload the file.</p>';
		}

	}
}

?>