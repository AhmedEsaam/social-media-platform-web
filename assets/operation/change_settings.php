<?php

require '../classes.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$operation = $_POST['operation'];
	if ($operation == "email") {
		$connect = new connection();
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$email = $_POST['email'];
		$email_check = $connect->conn->query("SELECT * FROM users WHERE email='$email'");
		$row = mysqli_fetch_assoc($email_check);
		$matched_user = $row['username'];
		$userLoggedIn = $_SESSION['user']->get_id();
		if ($matched_user==""||$matched_user=="$userLoggedIn")
		{
			echo "Details Updated<br><br>";
			$query = $connect->conn->query("UPDATE users SET f_name='$first_name',l_name='$last_name',email='$email' WHERE username = '$userLoggedIn'");
		}
		else
		{
			echo "That E-mail Is Already In Use<br><br>";
		}
	}
	else if ($operation == "pass") {
		$connect = new connection();
		$old_pass = $_POST['current_pass'];
		$new_pass = $_POST['new_pass'];
		$new_pass2 = $_POST['new_pass2'];
		$userLoggedIn = $_SESSION['user']->get_id();
		$user_pass = $_SESSION['user']->get_password();
		if ($old_pass == $user_pass) {
			if ($new_pass==$new_pass2) {
					$query = $connect->conn->query("UPDATE users SET password='$new_pass' WHERE username = '$userLoggedIn'");
					echo "Password Changed Successfully";
			}
			else{
				echo "Passwords Don't Match";
			}
		}
		else
		{
			echo "Wrong Password";
		}
	}
	else if ($operation == "check")
	{
		$connect = new connection();
		$userLoggedIn = $_SESSION['user']->get_id();
		if (!empty($_POST["check0"])) {
			$query = $connect->conn->query("UPDATE users SET show_friends='1' WHERE username = '$userLoggedIn'");
		}
		else
		{
			$query = $connect->conn->query("UPDATE users SET show_friends='0' WHERE username = '$userLoggedIn'");
		}
		if (!empty($_POST["check1"])) {
			$query = $connect->conn->query("UPDATE users SET show_user_details='1' WHERE username = '$userLoggedIn'");
		}
		else
		{
			$query = $connect->conn->query("UPDATE users SET show_user_details='0' WHERE username = '$userLoggedIn'");
		}
		if (!empty($_POST["check2"])) {
			$query = $connect->conn->query("UPDATE users SET enable_market='1' WHERE username = '$userLoggedIn'");
		}
		else
		{
			$query = $connect->conn->query("UPDATE users SET enable_market='0' WHERE username = '$userLoggedIn'");
		}
		echo "Details Updated Successfully";
	}
}
?>