<?php 
$cookie_name = "loggedin";
require_once 'utils.php';

$link = connectDB();

if(isset($_POST['login'])){

	$userid = $_POST['userid'];
	$password = $_POST['password'];
	//$phash = sha1(sha1($pass."salt")."salt");

	$sql = "SELECT * FROM person WHERE person_id='$userid' AND password='$password';";
	$result = mysqli_query($link, $sql);
	$count = mysqli_num_rows($result);
	$is_trainer = mysqli_fetch_assoc($result)["is_trainer"];

	// for ($i=0;$i<$count;$i++){
	// 	$result_arr = mysqli_fetch_assoc($result);
	// 	print_r ($result_arr["is_trainer"]);
	// }

	if($count == 1){

		$cookie_value = $userid;
		setcookie($cookie_name, $cookie_value, time()+(86400 * 30), "/");
		if($is_trainer == 1){
		header("Location: trainer_home.php");}
		else{
			header("Location: student_home.php");
		}		
	}
	else
	{
		echo "Username or password is incorrect!";
	}
}
else if(isset($_POST['register'])){

	$userid = $_POST['userid'];
	$password = $_POST['password'];
	//$phash = sha1(sha1($pass."salt")."salt");

	$sql = "INSERT INTO person(person_id, password) VALUES person_id='$userid' AND password='$password';";
	$result = mysqli_query($link, $sql);

}
?>

