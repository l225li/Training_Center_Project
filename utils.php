<?php
require_once 'config.php';

function connectDB(){
	$link = mysqli_init();
	$success = mysqli_real_connect(
		$link, MYSQL_HOST, MYSQL_USER, MYSQL_PW, MYSQL_DB, MYSQL_PORT);
	if(!$link){
		die("Database connection failed: ".mysql_connect_error());
	} else{
		return $link;
	}

}
?>