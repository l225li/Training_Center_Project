<?php 
$cookie_name = "loggedin";
if (isset($_COOKIE[$cookie_name]))
{
	require_once 'utils.php';
	$summary=$_POST['summary'];
	$team_id=intval($_POST['team_id']);


	if(empty($summary)){
		die('Summary is empty');
	}
	$link = connectDB();
	$cookie_value = intval($_COOKIE[$cookie_name]);
	//check if user is the owner of the team 
	$sql = "SELECT owner_id FROM team WHERE team_id = $team_id";
	$result = mysqli_query($link, $sql);
	$owner_id = intval(mysqli_fetch_assoc($result)['owner_id']);
	if ($cookie_value != $owner_id){
		echo "You are not authorized to edit the summary!";
	}else{
		$result = mysqli_query($link, "UPDATE team SET summary='$summary' WHERE team_id='$team_id'");
		if(!$result){
			echo mysqli_error($link);
		}else{
			header("Location:teamDetails.php?team_id=$team_id");
		}
	}
}
?>