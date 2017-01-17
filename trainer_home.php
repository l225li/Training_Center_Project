
<?php 
$cookie_name = "loggedin";
require_once 'utils.php';

// Home page for trainers / admins to manage the projects 
if (isset($_COOKIE[$cookie_name]))
{

	$cookie_value = $_COOKIE[$cookie_name];
	$link = connectDB();
	$sql = "SELECT first_name FROM person WHERE person_id = '$cookie_value';";
	$result_arr = mysqli_fetch_assoc(mysqli_query($link, $sql));
	$first_name = $result_arr["first_name"];
	$sql = "SELECT * FROM project WHERE owner_id='$cookie_value' ORDER BY project_id";
	$result = mysqli_query($link, $sql);
	if (!$result){
		echo mysqli_error($link);
	}else{
	$count = mysqli_num_rows($result);

	// echo "Welcome to trainer's personal page, $first_name!<br/>";
	
	// echo '<a href="project_creation.php">New Project</a><br/>'; ?>

	<!DOCTYPE html>
	<html>
	<head>
		<title>Trainer Home</title>
	</head>
	<body>
		<h1> Welcome to home page for trainers, <?php echo $first_name?>!</h1>
		<div style='text-align: right;'>
			<a href="addProject.html">New Project</a> <a href="logout.php">logout</a></div>

			<table style='text-align: center;' border='1'>
				<h2>Current Projects</h2>
				<tr><th>Project ID</th>
					<th>Title</th>
					<th>Creation Date</th>
					<th>Deadline</th>
					<th>Subject</th>
					<th>EDIT</th>
					<th>DELETE</th>
					<th>DETAILS</th>
				</tr>

				<?php
				for($i=0;$i<$count;$i++){
					$result_arr = mysqli_fetch_assoc($result);
					$project_id = $result_arr["project_id"];
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
						<td><a href='editProject.php?project_id=<?php echo $project_id ?>'>EDIT</a></td>
						<td><a href='deleteProject.php?project_id=<?php echo $project_id ?>'>DELETE</a></td>
						<td><a href='projectDetails.php?project_id=<?php echo $project_id ?>'>DETAILS</a></td>
					</tr>
					<?php
				}
			}
			}?>
		</table>
	</body>
	</html>

