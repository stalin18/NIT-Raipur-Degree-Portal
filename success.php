<?php

	$id=$_GET['id'];

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Success</title>
</head>

<body>
	
	<?php if($id==1) { ?>
		<div align="center">
			<h2><br/><br/><br/><br/><br/>You have been successfully registered! <br/>Please check your email(also check spam folder) for your one time temporary password.<br /> 
			Change this password after you login.<br /></h2>
				
			<h3>If you have not received your email, <a href="resend.php" >click here</a></h3>
		</div>
	<?php } ?>
	
	<?php if($id==2) { ?>
		<div align="center">
			<h2><br/><br/><br/><br/><br/>Your password has been successfully reset! <br/>Please check your email(also check spam folder) for your one time temporary password.<br /> 
			Change this password after you login.<br /></h2>
		</div>
	<?php } ?>
	
	<?php if($id==3) { ?>
		<div align="center">
			<h2><br/><br/><br/><br/><br/>Your password has been successfully reset! </h2>
		</div>
	<?php } ?>
	
	<?php if($id==4) { ?>
		<div align="center">
			<h2><br/><br/><br/><br/><br/>Your details have been updated successfully! </h2>
		</div>
	<?php } ?>
	
	<?php if($id==5) { ?>
		<div align="center">
			<h2><br/><br/><br/><br/><br/>Your account has been successfully locked!<br/> You can no more edit/update your details.<br/><br/> You are now eligible to pay your fees. </h2>
		</div>
	<?php } ?>
	
	<?php if($id==6) { ?>
		<div align="center">
			<h2><br/><br/><br/><br/><br/>A confirmation email(also check spam folder) has been sent to reset your password!<br/> Check your mail and click on the confirmation link.</h2>
		</div>
	<?php } ?>
	
	<?php if($id==7) { ?>
		<div align="center">
			<h2><br/><br/><br/><br/><br/>Your forgotten passsword has been successfully reset!<br/> Check your email(also check spam folder) for your new password.<br/> Please change this password after login else your account will remain inactive!</h2>
		</div>
	<?php } ?>
	
	<?php if($id==8) { ?>
		<div align="center">
			<h2><br/><br/><br/><br/><br/>Email successfully sent!<br/>Please check your email(also check spam folder) for your one time temporary password.<br /> 
			Change this password after you login.<br /></h2>
		</div>
	<?php } ?>
	
</body>
</html>
