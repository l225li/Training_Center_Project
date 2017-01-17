<?php
$cookie_name = "loggedin";
if (isset($_COOKIE[$cookie_name]))
{
	?>


	<!DOCTYPE html>
	<html>
	<head>
		<title></title>
	</head>
	<body>
		<h2>Person Detail</h2>
		<table style='text-align: center;' border='1'>
			<tr>
				<th>Person ID</th>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Address</th>
				<th>Zip Code</th>
				<th>Town</th>
				<th>Email</th>
				<th>Mobile Phone</th>
				<th>Category</th>
			</tr>

			<?php 
			if(empty($_GET['person_id'])){
				die('Person ID not defined');
			}

			require_once 'utils.php';

			$link = connectDB();

			$person_id = intval($_GET['person_id']);
			$result = mysqli_query($link, "SELECT * FROM person WHERE person_id = $person_id");
			if (!$result){
				die("Database connection error!");
			}
			$result_arr = mysqli_fetch_assoc($result);
			$person_id = $result_arr['person_id'];
			$first_name = $result_arr['first_name'];
			$last_name = $result_arr['last_name'];
			$address = $result_arr['address'];
			$zip_code = $result_arr['zip_code'];
			$town = $result_arr['town'];
			$email = $result_arr['email'];
			$phone = $result_arr['mobile_phone'];
			if($result_arr['is_admin']){
				$category = "Admin";
			}elseif ($result_arr['is_trainer']) {
				$category = "Trainer";
			}else{
				$category = "Student";
			}
			?>
			<tr>
				<td><?php echo $person_id ?></td>
				<td><?php echo $first_name ?></td>
				<td><?php echo $last_name ?></td>
				<td><?php echo $address ?></td>
				<td><?php echo $zip_code ?></td>
				<td><?php echo $town ?></td>
				<td><?php echo $email ?></td>
				<td><?php echo $phone ?></td>
				<td><?php echo $category ?></td>
				
			</tr>
		</table>
	</body>
	</html>
	<?php }?>