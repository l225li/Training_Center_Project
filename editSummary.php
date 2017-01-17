
<!DOCTYPE html>
<html>
<head>
	<title>Edit Team Summary</title>
</head>
<body>

	<?php 
	require_once 'utils.php';
	$cookie_name = "loggedin";
	if (isset($_COOKIE[$cookie_name]))
	{
		if(!empty($_GET['team_id'])){
			$link = connectDB();
			$team_id = intval($_GET['team_id']);
			$result = mysqli_query($link, "SELECT * FROM team WHERE team_id= $team_id");
			if(!$result){
				echo mysqli_error($link);
				die('Database connection error!');
			}
			$result_arr = mysqli_fetch_assoc($result);
		}else{
			die('Project ID not defined');
		}
		?>

		<h2>Team Summary Update</h2>
		<form action="editSummary_server.php" method="post">
			<div> Team ID:
				<input type="text" name="team_id" value="<?php echo $team_id ?>" readonly>(not changeable)</div>

				<div>Summary:
					<input type="text" name="summary" value="<?php echo $result_arr['summary']; ?>">
				</div>

				<input type="submit" value="Submit">
			</form>
		</body>
		</html>
		<?php } ?>