<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	require '../classes.php';
	$connect = new connection();
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$email = $_POST['email'];
	$email_check = $connect->conn->query("SELECT * FROM users WHERE email='$email'");
	$row = mysqli_fetch_assoc($email_check);
	$matched_user = $row['username'];
	$userLoggedIn = $_SESSION['username'];
	if ($matched_user==""||$matched_user=="$userLoggedIn")
	{
		$message="Details Updated<br><br>";
		$query = $connect->conn->query("UPDATE users SET f_name='$first_name',l_name='$last_name',email='$email' WHERE username = '$userLoggedIn'");
	}
	else
	{
		$message="That E-mail Is Already In Use<br><br>";
	}
}
header("Location: ../../settings/settings.php");
?>