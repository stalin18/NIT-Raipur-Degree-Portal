<?php

	$conn_error="There is an internal error! Please try later.";
	
	$mysql_host="localhost";
	$mysql_user="root";
	$mysql_passw="ShreeGanesh";
	
	$mysql_db="register";
	
	if(!@mysql_connect($mysql_host,$mysql_user,$mysql_passw)||!@mysql_select_db($mysql_db)){
		
		header("Location error.php?id=1");
	}

?>