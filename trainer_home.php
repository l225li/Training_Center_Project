
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
	$sql = "SELECT * FROM project WHERE owner_id='$cookie_value'";
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
		<h1> Welcome to trainer's personal page, <?php echo $first_name?>!</h1>
		<div>
		<a href="addProject.html">New Project</a><br/>
		<a href="logout.php">logout</a></div>
		<table style='text-align: left;' border='1'>
		<tr><th>Project ID</th><th>Title</th><th>Creation Date</th><th>Deadline</th><th>Subject</th></tr>
		<?php
	//echo "<table style='text-align:left";
		for($i=0;$i<$count;$i++){
			$result_arr = mysqli_fetch_assoc($result);
			$project_id = $result_arr["project_id"];
			$title = $result_arr["title"];
			$created_at = $result_arr["created_at"];
			$deadline = $result_arr["deadline"];
			$subject = $result_arr["subject"];
			echo "<tr><td>$project_id</td><td>$title</td><td>$created_at</td><td>$deadline</td><td>$subject</td></tr>";

		}
	}?>
	</table>
	</body>
	</html>

