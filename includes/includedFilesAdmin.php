<?php

if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
	include("includes/config.php");
	include("includes/classes/User.php");

	if(isset($_GET['userLoggedIn'])){
		$userLoggedIn = new User($con, $_GET['userLoggedIn']);
	}
	else{
		echo "Username variable was bot passed into page. Check the openPage JS function";
		exit();
	}
}
else{
	include("includes/headerAdmin.php");

	$url = $_SERVER['REQUEST_URI'];
	echo "<script>openPage('$url')</script>";
	exit();

}


?>