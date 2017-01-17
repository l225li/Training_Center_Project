<?php 
$cookie_name = "loggedin";
if (isset($_COOKIE[$cookie_name]))
{
	if(empty($_GET['team_id'])){
		die('Team ID not defined');
	}
	require_once 'utils.php';
	$link = connectDB();
	$cookie_value = intval($_COOKIE[$cookie_name]);
	$team_id = $_GET['team_id'];

	//check if user is the owner of the team 
	$sql = "SELECT owner_id, project_id FROM team WHERE team_id=$team_id";
	$result = mysqli_query($link, $sql);
	if (!$result){
		echo mysqli_error($link);
		die("Database connection error");
		}
	$result_arr = mysqli_fetch_assoc($result);
	$owner_id = intval($result_arr['owner_id']);
	$project_id = $result_arr['project_id'];
	if ($cookie_value != $owner_id){
		echo "You are not authorized to add members to this team!";
	}else{ 
		// only allow activities from the owner of the team ?>
		
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
			die("Database connection failed");
		}
		$count = mysqli_num_rows($result);
		//if($count == 0){
		//	echo "No available class members!";
		//}
		//else{?>
		<h2>Students without Teams</h2>
		<table style='text-align: center;' border='1'>
		<tr><th>Student ID</th><th>Fist Name</th><th>Last Name</th><th>Details</th></tr>

		<?php
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
				<td><a href="addMember.php?person_id=<?php echo $person_id?>&team_id=<?php echo $team_id ?>">ADD TO TEAM</a></td>
			</tr>
			<?php
		}?>
	</table>
<?php
	}	
}
?>