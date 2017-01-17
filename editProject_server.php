<?php 
$cookie_name = "loggedin";
if (isset($_COOKIE[$cookie_name]))
{
	require_once 'utils.php';

	$title=$_POST['title'];
	$deadline=$_POST['deadline'];
	$subject=$_POST['subject'];
	$project_id=intval($_POST['project_id']);

	if(empty($title)){	
		die('Title is empty');
	}

	if(empty($deadline)){
		die('Deadline is empty');
	}

	if(empty($subject)){
		die('Subject is empty');
	}
	$link = connectDB();
	$cookie_value = intval($_COOKIE[$cookie_name]);
	//check if user is the owner of the team 
	$sql = "SELECT owner_id FROM project WHERE project_id = $project_id";
	$result = mysqli_query($link, $sql);
	$owner_id = intval(mysqli_fetch_assoc($result)['owner_id']);
	if ($cookie_value != $owner_id){
		echo "You are not authorized to edit this project!";
	}else{
		$result = mysqli_query($link, "UPDATE project SET title='$title', deadline='$deadline', subject='$subject' WHERE project_id='$project_id'");
		if(!$result){
			echo mysqli_error($link);
		}
		else{
			header("Location:trainer_home.php");
		}
	}
}
?>