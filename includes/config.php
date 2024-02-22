<?php
	ob_start();
	session_start();


	$timezone = date_default_timezone_set("Europe/Riga");

	$con = mysqli_connect("localhost", "root", "", "pulseplay");

	if(mysqli_connect_errno()){
		echo "failled to connect: " . mysqli_connect_errno();
	}


?>