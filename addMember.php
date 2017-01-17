<?php 
$cookie_name = "loggedin";
if (isset($_COOKIE[$cookie_name]))
{
	if(empty($_GET['person_id'])){
		die('Person ID of member not defined');
	}
	if(empty($_GET['team_id'])){
		die('Team ID not defined');
	}
	require_once 'utils.php';
	$link = connectDB();
	$cookie_value = intval($_COOKIE[$cookie_name]);
	$person_id = intval($_GET['person_id']);
	$team_id = intval($_GET['team_id']);
	//check if user is the owner of the team 
	$sql = "SELECT owner_id FROM team WHERE team_id = $team_id";
	$result = mysqli_query($link, $sql);
	$owner_id = intval(mysqli_fetch_assoc($result)['owner_id']);
	if ($cookie_value != $owner_id){
		echo "You are not authorized to add members to this team!";
	}else{
		// only allow activities from the owner of the team
		$sql = "INSERT INTO team_member(team_id, student_id) VALUES ($team_id, $person_id);";
		$result = mysqli_query($link, $sql);
		if (!$result){
			echo mysqli_error($link);
			die("Fail to add team member with student_id $person_id");
		}else{
			header("Location: teamDetails.php?team_id=$team_id");
		}
	}
	
}
?>