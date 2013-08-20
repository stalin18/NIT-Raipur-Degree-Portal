<?php
	
	include 'connect_to_mysql.php';
		
	if(isset($_POST['email']))$email=$_POST['email'];
	
	$valid;
	
	$email=stripslashes($_POST['email']);
	$email=strip_tags($email);
	$email=mysql_real_escape_string($email);
	
	$query="SELECT id from user WHERE email='$email' AND (active='1' OR active='2')";
	$result=mysql_query($query);

	if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
		
		$valid="true";	
		if(mysql_num_rows($result))$valid="abc";
	}
	else {
		
		$valid="false";
	}
    
	echo $valid;
?>