<?php 
$cookie_name = "loggedin";
require_once 'utils.php';

$link = connectDB();

if(isset($_POST['login']))
{

	$userid = $_POST['userid'];
	$password = $_POST['password'];
	//$phash = sha1(sha1($pass."salt")."salt");

	$sql = "SELECT * FROM person WHERE person_id='$userid' AND password='$password';";
	$result = mysqli_query($link, $sql);
	if (!$result)
	{
		echo mysqli_error($link);
	}
	else
	{
		$count = mysqli_num_rows($result);
		$result_arr = mysqli_fetch_assoc($result);
		$is_trainer = $result_arr["is_trainer"];
		$is_admin = $result_arr["is_admin"];

		if($count == 1)
		{
			$cookie_value = $userid;
			setcookie($cookie_name, $cookie_value, time()+(86400 * 30), "/");
			if($is_trainer == 1 || $is_admin == 1)
			{
				header("Location: trainer_home.php");
			}
			else
			{
				header("Location: student_home.php");
			}		
		}
		else
		{
			echo "Username or password is incorrect!";
		}
	}
}
elseif(isset($_POST['register']))
{
		$userid = $_POST['userid'];
		$password = $_POST['password'];
	//$phash = sha1(sha1($pass."salt")."salt");
		$sql = "INSERT INTO person(person_id, password) VALUES person_id='$userid' AND password='$password';";
		$result = mysqli_query($link, $sql);

}
?>

