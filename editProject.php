
<!DOCTYPE html>
<html>
<head>
	<title>Edit Project</title>
</head>
<body>


<?php 

require_once 'utils.php';
$cookie_name = "loggedin";
if (isset($_COOKIE[$cookie_name]))
{
if(!empty($_GET['project_id'])){
	$link = connectDB();

	$project_id = intval($_GET['project_id']);
	$result = mysqli_query($link, "SELECT * FROM project WHERE project_id= $project_id");
	if(!$result){
		echo mysqli_error($link);
		die('Database connection error!');
	}
	$result_arr = mysqli_fetch_assoc($result);
	// print_r($result_arr);

}else{
	die('Project ID not defined');
}

?>

<h2>Project: <?php echo $result_arr['title']?></h2>
<form action="editProject_server.php" method="post">
<div> ID:
<input type="text" name="project_id" value="<?php echo $project_id ?>" readonly>(unchangeable)</div>
	<div>Title:
		<input type="text" name="title" value="<?php echo $result_arr['title']; ?>">
	</div>
	<div>Deadline:
		<input type="datetime" name="deadline" value="<?php echo $result_arr['deadline']; ?>">
	</div>
	<div>Subject:
		<input type="text" name="subject" value="<?php echo $result_arr['subject']; ?>">
	</div>

	<input type="submit" value="Submit">
</form>
</body>
</html>
<?php } ?>