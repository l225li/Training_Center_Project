<?php 
$cookie_name = "loggedin";
if (isset($_COOKIE[$cookie_name]))
{
	$class_id = $_POST['class_id'];
	$title = $_POST['title'];
	$deadline = $_POST['deadline'];
	$subject = $_POST['subject'];
	if(!isset($class_id) || empty($class_id)){
		die('Class ID not defined');
	}
	if(!isset($title) || empty($title)){
		die('Title not defined');
	}
	if(!isset($deadline) || empty($deadline)){
		die('Deadline not defined');
	}
	if(!isset($subject) || empty($subject)){
		die('Subject not defined');
	}

	require_once 'utils.php';
	$link = connectDB();
	$cookie_value = $_COOKIE[$cookie_name];
	$result=mysqli_query($link,"INSERT INTO project(owner_id,class_id, title, deadline, subject) VALUES('$cookie_value','$class_id','$title','$deadline','$subject');");
	if(!$result){
		echo mysqli_error($link);
	}else{
		header("Location:trainer_home.php");
	}


}

 ?>