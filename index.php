<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<form action="logreg.php" method="post" accept-charset="utf-8">
	<label>Login: </label><input type="email" name="login_email" value="" placeholder="Login Email">
	<br/><br/>
	<label>Password: </label><input type="password" name="password" value="" placeholder="Password">
	<br/> <br/>
	<input type="submit" name="login" value="Login">
	<input type="submit" name="register" value="Register">
</body>
</html>


<?php 
$user = 'root';
$password = 'root';
$db = 'training_center';
$host = 'localhost';
$port = 3306;

$link = mysqli_init();
$success = mysqli_real_connect(
   $link, 
   $host, 
   $user, 
   $password, 
   $db,
   $port
);
//check if database is connected
// if($link){
// 	echo 'connected';
// }else{
// 	echo 'failure';
// }


 ?>