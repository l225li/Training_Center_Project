
<?php 
$cookie_name = "loggedin";
if (isset($_COOKIE[$cookie_name]))
{

	$cookie_value = $_COOKIE[$cookie_name];
	echo "Welcome to student's personal page, $cookie_value!";
	echo '<a href="logout.php">logout</a>';
}
 ?>