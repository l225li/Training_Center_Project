<?php 
$cookie_name = "loggedin";

$user = 'root';
$pass = 'root';
$db = 'training_center';
$host = 'localhost';
$port = 3306;

$link = mysqli_init();
$success = mysqli_real_connect(
   $link, 
   $host, 
   $user, 
   $pass, 
   $db,
   $port
);
// check if database is connected
if(!$link){
	die("Database connection failed: ".mysql_connect_error());
}

if(isset($_POST['login'])){

	$login_email = $_POST['login_email'];
	$password = $_POST['password'];
	//$phash = sha1(sha1($pass."salt")."salt");

	$sql = "SELECT * FROM person WHERE email='$login_email' AND password='$password';";
	$result = mysqli_query($link, $sql);
	$count = mysqli_num_rows($result);

	if($count == 1){

		$cookie_value = $login_email;
		setcookie($cookie_name, $cookie_value, time()+(180), "/");
		header("Location: personal.php");		
	}
	else
	{
		echo "Username or password is incorrect!";
	}
}
else if(isset($_POST['register'])){

	$login_email = $_POST['login_email'];
	$password = $_POST['password'];
	//$phash = sha1(sha1($pass."salt")."salt");

	$sql = "INSERT INTO person(email, password) VALUES email='$login_email' AND password='$password';";
	$result = mysqli_query($link, $sql);

}
?>

