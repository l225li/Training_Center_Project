<?php 
// to delete a project, only owner of the project can delete
$cookie_name = "loggedin";
if (isset($_COOKIE[$cookie_name]))
{
	if(empty($_GET['project_id']))
	{
		die('Project ID not defined');
	}
	require_once 'utils.php';
	$link = connectDB();
	$cookie_value = intval($_COOKIE[$cookie_name]);
	$project_id = intval($_GET['project_id']);
	//check if user is the owner of the team 
	$sql = "SELECT owner_id FROM project WHERE project_id = $project_id";
	$result = mysqli_query($link, $sql);
	$owner_id = intval(mysqli_fetch_assoc($result)['owner_id']);
	if ($cookie_value != $owner_id)
	{
		echo "You are not authorized to delete this project!";
	}
	else
	{
		$sql = "DELETE FROM project WHERE project_id = $project_id;";
		$result = mysqli_query($link, $sql);
		if (!$result)
		{
			echo mysqli_error($link);
			die("Fail to delete project with project_id $project_id");
		}
		else
		{
			header("Location: trainer_home.php");
		}
	}
}
?>