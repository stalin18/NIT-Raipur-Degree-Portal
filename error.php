<?php

	$id=$_GET['id'];

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Error</title>
</head>

<body>
	
	<?php if($id==1) { ?>
		<div align="center">
			<h2><br/><br/><br/><br/><br/>There was an internal error!<br/>Please try later.<br /> 
			<br /></h2>
		</div>
	<?php } ?>
	
	<?php if($id==2) { ?>
		<div align="center">
			<h2><br/><br/><br/><br/><br/>You have locked your account! <br/>You cannot edit your details now.<br /> 
			<br /></h2>				
		</div>
	<?php } ?>
	
	<?php if($id==3) { ?>
		<div align="center">
			<h2><br/><br/><br/><br/><br/>Your account is not active! <br/>Activate your accoutn by changing first time temporary password.<br /> 
			<br /></h2>				
		</div>
	<?php } ?>
	
	<?php if($id==4) { ?>
		<div align="center">
			<h2><br/><br/><br/><br/><br/>Your account is already locked!<br /> 
			<br /></h2>				
		</div>
	<?php } ?>
	
	<?php if($id==5) { ?>
		<div align="center">
			<h2><br/><br/><br/><br/><br/>The link you want to access is expired!<br /> 
			<br /></h2>				
		</div>
	<?php } ?>
	
	<?php if($id==6) { ?>
		<div align="center">
			<h2><br/><br/><br/><br/><br/>The page you want to access does not exist!<br /> 
			<br /></h2>				
		</div>
	<?php } ?>
	
</body>
</html>