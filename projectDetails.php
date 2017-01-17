<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

	<h2>Project Details</h2>
	<table style='text-align: center;' border='1'>
		<tr><th>Project ID</th><th>Title</th><th>Creation Date</th><th>Deadline</th><th>Subject</th></tr>
		<?php 
		$cookie_name = "loggedin";
		if (isset($_COOKIE[$cookie_name]))
		{
			if(empty($_GET['project_id'])){
				die('Project ID not defined');
			}

			require_once 'utils.php';

			$link = connectDB();

			$project_id = intval($_GET['project_id']);
			$result = mysqli_query($link, "SELECT * FROM project WHERE project_id = $project_id");
			if (!$result){
				echo mysqli_error($link);
				die("Database connection failed");
			}
			$result_arr = mysqli_fetch_assoc($result);
			$title = $result_arr["title"];
			$created_at = $result_arr["created_at"];
			$deadline = $result_arr["deadline"];
			$subject = $result_arr["subject"];
			?>
			<tr>
				<td><?php echo $project_id ?></td>
				<td><?php echo $title ?></td>
				<td><?php echo $created_at ?></td>
				<td><?php echo $deadline ?></td>
				<td><?php echo $subject ?></td>
			</tr> 
		</table>

		<h2>List of Teams</h2>
		<table style='text-align: center;' border='1'>
			<tr><th>Team ID</th><th>Team Leader</th><th>Creation Date</th><th>Summary</th><th>Details</th></tr>
			<?php
			$sql = "SELECT * FROM team WHERE project_id = $project_id";
			$result = mysqli_query($link, $sql);
			if(!$result){
				echo mysqli_error($link);
				die("Database connection failed");
			}
			$count = mysqli_num_rows($result);
			for($i=0;$i<$count;$i++){
				$result_arr = mysqli_fetch_assoc($result);
				$team_id = $result_arr['team_id'];
				$owner_id = $result_arr['owner_id'];
				$created_at = $result_arr['created_at'];
				$summary = $result_arr['summary'];
				?>
				<tr>
					<td><?php echo $team_id ?></td>
					<td><?php echo $owner_id ?></td>
					<td><?php echo $created_at ?></td>
					<td><?php echo $summary ?></td>
					<td><a href="teamDetails.php?team_id=<?php echo $team_id?>">DETAILS</a></td>
				</tr>
				<?php
			}?>
		</table>
		<h2>Students without Teams</h2>
		<table style='text-align: center;' border='1'>
			<tr><th>Student ID</th><th>Fist Name</th><th>Last Name</th><th>Details</th></tr>
			<?php
		// select student in the class of the project but without a team 
			$sql = "SELECT * 
			FROM person JOIN (SELECT subquery1.person_id
			FROM 
			(SELECT class_member.person_id
			FROM class_member JOIN class JOIN project
			ON class_member.class_id = class.class_id and project.class_id = class.class_id
			WHERE project.project_id = $project_id) as subquery1 LEFT JOIN  (SELECT team_member.student_id
			FROM team_member join team
			ON team_member.team_id = team.team_id
			where team.project_id = $project_id) as subquery2 
			ON subquery1.person_id = subquery2.student_id
			WHERE subquery2.student_id is null) as subquery3 
			ON person.person_id = subquery3.person_id
			WHERE person.person_id = subquery3.person_id;";
			$result = mysqli_query($link, $sql);
			if(!$result){
				echo mysqli_error($link);
				die("Database connection failed");
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
					<td><a href="personDetails.php?person_id=<?php echo $person_id?>">DETAILS</a></td>
				</tr>
				<?php
			}?>
		</table>
		<?php $cookie_value = $_COOKIE[$cookie_name];
		$sql = "SELECT is_trainer, is_admin FROM person WHERE person_id = $cookie_value";
		$result = mysqli_query($link, $sql);
		if(!$result){
			echo mysqli_error($link);
		}
		$result_arr = mysqli_fetch_assoc($result);
		$is_admin = intval($result_arr['is_admin']);
		$is_trainer = intval($result_arr['is_trainer']);
		if($is_trainer == 1 || $is_admin == 1){
			echo "<a href='trainer_home.php'>Back to Home</a>";
		}else{
			echo "<a href='student_home.php'>Back to Home</a>";
		}
	}
?>

	</body>
	</html>
