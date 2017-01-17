<?php 
$cookie_name = "loggedin";
if (isset($_COOKIE[$cookie_name]))
{
	
	if(!isset($_POST['class_id']) || empty($_POST['class_id'])){
		die('Class ID not defined');
	}
	if(!isset($_POST['title']) || empty($_POST['title'])){
		die('Title not defined');
	}
	if(!isset($_POST['deadline']) || empty($_POST['deadline'])){
		die('Deadline not defined');
	}
	if(!isset($_POST['subject']) || empty($_POST['subject'])){
		die('Subject not defined');
	}

	$class_id = $_POST['class_id'];
	$title = $_POST['title'];
	$deadline = $_POST['deadline'];
	$subject = $_POST['subject'];

	require_once 'utils.php';
	$link = connectDB();
	$cookie_value = $_COOKIE[$cookie_name];
	// check if user is admin (admin can add project to all classes)
	$sql = "SELECT is_admin FROM person where person_id = $cookie_value";
	$result = mysqli_query($link, $sql);
	if(!$result)
	{
		echo mysqli_error($link);
		die();
	}
	$is_admin = mysqli_fetch_assoc($result)['is_admin'];
	// check if user if trainer of the class (trainers can only add project to classes they are members of)
	$sql = "SELECT is_trainer FROM class_member JOIN person ON person.person_id = class_member.person_id WHERE person.person_id = $cookie_value AND class_member.class_id = $class_id;";
	$result = mysqli_query($link, $sql);
	if(!$result)
	{
		echo mysqli_error($link);
		die();
	}
	$result_arr = mysqli_fetch_assoc($result);
	$is_trainer = $result_arr['is_trainer'];
	// if are qualified user, then add the project to DB
	if ($is_admin == 1 || $is_trainer == 1 )
	{
		$result=mysqli_query($link,"INSERT INTO project(owner_id, class_id, title, deadline, subject) VALUES('$cookie_value','$class_id','$title','$deadline','$subject');");
		if(!$result)
		{
			echo mysqli_error($link);
			die();
		}
		else
		{
		header("Location:trainer_home.php");
		}

	} else {

		echo "You are not authorized to add project to this class.";
	}


}

?>