<?php 
$cookie_name = "loggedin";
if (isset($_COOKIE[$cookie_name]))
	{?>
<!DOCTYPE html>
<html>
<head>
	<title>Team Details</title>
</head>
<body>
	<h2>Team Detail</h2>
	<table style='text-align: center;' border='1'>
		<tr>
			<th>Team ID</th>
			<th>Project ID</th>
			<th>Project Title</th>
			<th>Class Name</th>
			<th>Owner ID</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Creation Date</th>
			<th>Summary</th>
		</tr>

		<?php 
		if(empty($_GET['team_id'])){
			die('Team ID not defined');
		}

		require_once 'utils.php';
		$link = connectDB();
		$team_id = intval($_GET['team_id']);
		// select all the teams related to this project 
		$result = mysqli_query($link, "SELECT team.team_id, project.project_id, project.title, team.owner_id,person.first_name, person.last_name, class.name, team.created_at, team.summary FROM team JOIN project JOIN person JOIN class ON team.project_id = project.project_id and team.owner_id = person.person_id and project.class_id = class.class_id WHERE team_id = $team_id");
		if(!$result)
		{
		echo mysqli_error($link);
		die();
		}
		$result_arr = mysqli_fetch_assoc($result);
		$team_id = $result_arr['team_id'];
		$project_id = $result_arr['project_id'];
		$title = $result_arr['title'];
		$owner_id = $result_arr['owner_id'];
		$first_name = $result_arr['first_name'];
		$last_name = $result_arr['last_name'];
		$class_name = $result_arr['name'];
		$created_at = $result_arr['created_at'];
		$summary = $result_arr['summary'];
		// List the details of the team
		?>
		<tr>
			<td><?php echo $team_id ?></td>
			<td><?php echo $project_id ?></td>
			<td><?php echo $title ?></td>
			<td><?php echo $class_name ?></td>
			<td><?php echo $owner_id ?></td>
			<td><?php echo $first_name ?></td>
			<td><?php echo $last_name ?></td>
			<td><?php echo $created_at ?></td>
			<td><?php echo $summary ?></td>
		</tr>
	</table>

	<h2>Team Members</h2>
	<?php
	$cookie_value = $_COOKIE[$cookie_name];?>

	<table style='text-align: center;' border='1'>
		<tr>

			<th>Person ID</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th></th>
		</tr>
		<?php
		// List the team member with their first name and last name
		$sql = "SELECT person.person_id, person.first_name, person.last_name FROM team_member JOIN person ON team_member.student_id=person.person_id WHERE team_member.team_id = $team_id";
		$result = mysqli_query($link, $sql);
		if(!$result)
		{
		echo mysqli_error($link);
		die();
		}
		$count = mysqli_num_rows($result);
		for($i=0;$i<$count;$i++){
			$result_arr = mysqli_fetch_assoc($result);
			$person_id = $result_arr['person_id'];
			$first_name = $result_arr['first_name'];
			$last_name = $result_arr['last_name'];
			?>
			<tr>
				<td><?php echo $person_id ?></td>
				<td><?php echo $first_name ?></td>
				<td><?php echo $last_name ?></td>
				<?php
				if($cookie_value == $owner_id){
					echo "<td><a href='removeMember.php?person_id=$person_id&team_id=$team_id'>REMOVE</a></td>";
				} ?>

			</tr>
			<?php 
		} ?>
	</table>
	<?php
	if($cookie_value == $owner_id){ ?>
	<a href='availableClassMembers.php?team_id=<?php echo $team_id ?>'>Add Member</a>
	<a href="editSummary.php?team_id=<?php echo $team_id ?>">Edit Summary</a>
	<a href='projectDetails.php?project_id=<?php echo $project_id ?>'>Back to Project</a>
	<?php 
	}?>
</div>
<?php
// Identify if user is owner of the team 
if($cookie_value == $owner_id){?>
<h3>You are the owner of this team</h3>
</body>
</html>
<?php
}
}
?>