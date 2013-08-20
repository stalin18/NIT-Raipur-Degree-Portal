<?php

	include 'connect_to_mysql.php';
	
    if(isset($_POST['phone']))$phone=$_POST['phone'];
	
	$valid;
	
	$phone=stripslashes($_POST['phone']);
	$phone=strip_tags($phone);
	$phone=mysql_real_escape_string($phone);
	
	$query="SELECT id from user WHERE phone='$phone' AND (active='1' OR active='2')";
	$result=mysql_query($query);
	
	if(preg_match("/^[7-9]{1}[0-9]{9}/", $phone)) {
		
		$valid="true";
		if(mysql_num_rows($result))$valid="abc";
	}	
	else {
			
		$valid="false";
	}

	echo $valid;
?>