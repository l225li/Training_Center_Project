<?php 
$cookie_name = "loggedin";
if (isset($_COOKIE[$cookie_name]))
{
	$project_id = $_POST['project_id'];
	$summary = $_POST['summary'];

	if(!isset($project_id) || empty($project_id)){
		die('Project ID not defined');
	}
	if(!isset($summary) || empty($summary)){
		die('Summary not defined');
	}

	require_once 'utils.php';
	$link = connectDB();
	$cookie_value = $_COOKIE[$cookie_name];
	// Check if the student is a member of the corresponding class of the project
	$sql1 = "SELECT * FROM class_member JOIN class JOIN project ON class_member.class_id = class.class_id and class.class_id = project.class_id WHERE project.project_id = $project_id and class_member.person_id = $cookie_value;";
	$result1=mysqli_query($link,$sql1);
	$count1 = mysqli_num_rows($result1);
	// Check if the student already has a team for this project
	$sql2 = "SELECT * FROM team_member JOIN team JOIN project ON project.project_id = team.project_id and team.team_id = team_member.team_id WHERE project.project_id = $project_id and team_member.student_id = $cookie_value;";
	$result2=mysqli_query($link,$sql2);
	$count2 = mysqli_num_rows($result2);
	if ($count1 == 1 && $count2 == 0){
		// Create a team 
		$sql = "INSERT INTO team(project_id, owner_id, summary) VALUES('$project_id','$cookie_value','$summary');";
		$result = mysqli_query($link, $sql);
		if(!$result){
			echo mysqli_error($link);
		}else{
			// Get the auto-incremented team_id in order to...
			$sql = "SELECT team_id FROM team WHERE project_id = $project_id and owner_id = $cookie_value;";
			$result = mysqli_query($link, $sql);
			$result_arr = mysqli_fetch_assoc($result);
			$team_id = $result_arr['team_id'];
			// ...add the owner of the team into team_member
			$sql = "INSERT INTO team_member(team_id, student_id) VALUES($team_id, $cookie_value);";
			$result = mysqli_query($link, $sql);
			if(!$result){
				echo mysqli_error($link);
			}
			else{
				header("Location:student_home.php");
			}
		}
	}elseif($count1 != 1){
		echo "You do not have this project!";
	}elseif($count2 != 0){
		echo "You already have a team for this project!";
	}else{
		echo "You are not authorized to create this team!";
	}
}

?>