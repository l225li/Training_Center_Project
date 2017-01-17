
<?php 
$cookie_name = "loggedin";
require_once 'utils.php';

if (isset($_COOKIE[$cookie_name]))
{

	$cookie_value = $_COOKIE[$cookie_name];
	$link = connectDB();
	$sql = "SELECT first_name FROM person WHERE person_id = '$cookie_value';";
	$result_arr = mysqli_fetch_assoc(mysqli_query($link, $sql));
	$first_name = $result_arr["first_name"];
	$sql = "SELECT * FROM team WHERE owner_id='$cookie_value' ORDER BY team_id";
	$result = mysqli_query($link, $sql);
	$count = mysqli_num_rows($result);

	// echo "Welcome to trainer's personal page, $first_name!<br/>";
	
	// echo '<a href="project_creation.php">New Project</a><br/>'; ?>

	<!DOCTYPE html>
	<html>
	<head>
		<title></title>
	</head>
	<body>
		<h1> Welcome to home page for students, <?php echo $first_name?>!</h1>
		<div style='text-align: right;'>
			<a href="addTeam.html">Create a Team</a> <a href="logout.php">logout</a></div>

			<table style='text-align: center;' border='1'>
				<h2>Teams you own: </h2>
				<tr><th>Team ID</th>
					<th>Project ID</th>
					<th>Creation Date</th>
					<th>Summary</th>
					<th>DETAILS</th>
				</tr>

				<?php
				for($i=0;$i<$count;$i++){
					$result_arr = mysqli_fetch_assoc($result);
					$team_id = $result_arr["team_id"];
					$project_id = $result_arr["project_id"];
					$created_at = $result_arr["created_at"];
					$summary = $result_arr["summary"];
					?>

					<tr>
						<td><?php echo $team_id ?></td>
						<td><?php echo $project_id ?></td>
						<td><?php echo $created_at ?></td>
						<td><?php echo $summary ?></td>
						<td><a href='teamDetails.php?team_id=<?php echo $team_id ?>'>Details</a></td>
					</tr>
					<?php
				}
			}?>
		</table>
	</body>
	</html>

