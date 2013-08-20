<?php

	require_once './scripts/connect_to_mysql.php';
	include_once './scripts/sendmail.php';
	
	session_start();
	
	if(isset($_POST['email'])&&isset($_POST['phone'])&&isset($_POST['captcha'])){
		
		$email=$_POST['email'];		
		$phone=$_POST['phone'];		
		$form_captcha=$_POST['captcha'];
		
		$email=strip_tags($email);
		$email=stripcslashes($email);
		$email=mysql_real_escape_string($email);
		
		$phone=strip_tags($phone);
		$phone=stripcslashes($phone);
		$phone=mysql_real_escape_string($phone);
		
		$charsa="abcdefghijklmnopqrstuvwxyz";
		$charsA="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$chars1="0123456789";
		$lena=strlen($charsa);
		$lenA=strlen($charsA);
		$len1=strlen($chars1);
		
		$pass="";
	
		for($i=0;$i<5;$i++){
			
			$pass.=$charsa[rand(0,$lena-1)];
		}
		
		for($i=0;$i<3;$i++){
			
			$pass.=$chars1[rand(0,$len1-1)];
		}
		
		for($i=0;$i<5;$i++){
			
			$pass.=$charsA[rand(0,$lenA-1)];
		}
		
		$md5Pass=md5($pass);
		
		$valid_email;
		$valid_phone;
		
		$query="SELECT id from user WHERE email='$email'";
		$result=mysql_query($query);

		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			
			$valid_email="true";	
			if(mysql_num_rows($result))$valid_email="abc";
		}
		else {
			
			$valid_email="false";
		}
		
		$query="SELECT id from user WHERE phone='$phone'";
		$result=mysql_query($query);
		
		if(preg_match("/^[7-9]{1}[0-9]{9}/", $phone)) {
			
			$valid_phone="true";
			if(mysql_num_rows($result))$valid_phone="abc";
		}	
		else {
				
			$valid_phone="false";
		}
		
		$error_email="";
		$error_phone="";
		$error_captcha="";
		$error_resend="";
		
		if(empty($email)||empty($phone)||empty($form_captcha)){
			
			if(empty($email)){
		     	 
		    	$error_email .= "<font color='#F80C0C' size='3'>* Enter an email</font>";
		    } 		 
			if(empty($phone)){
		     	 
		      	$error_phone .= "<font color='#F80C0C' size='3'>* Enter a phone no.</font>";
		    } 			
			if(empty($form_captcha)){
				
				$error_captcha .= "<font color='#F80C0C' size='3'>* Enter captcha</font>";
			}     
		}
		else if($valid_email!="abc"||$valid_phone!="abc"){
			
			if($valid_email=="true"){
				
				$error_email .= "<font color='#F80C0C' size='3'>* This email is not registered</font>";
			}
			if($valid_phone=="true"){
				
				$error_phone .= "<font color='#F80C0C' size='3'>* This phone no. is not registered</font>";
			}
			if($valid_email=="false"){
				
				$error_email .= "<font color='#F80C0C' size='3'>* Invalid email</font>";
			}
			if($valid_phone=="false"){
				
				$error_phone .= "<font color='#F80C0C' size='3'>* Invalid phone</font>";
			}
		}
		else if($form_captcha!=$_SESSION['cap']){
			
			$error_captcha .="<font color='#F80C0C' size='3'>* Wrong captcha</font>";							
		}
		else{
			
			$query="SELECT id, eng_name, active from user WHERE email='$email' AND phone='$phone'";
			$result=mysql_query($query);
			
			$row=mysql_fetch_assoc($result);
			$active=$row['active'];
				
			if(mysql_num_rows($result)){
						
				if($active=='1'){
						
					$error_resend="<font color='#F80C0C' size='3'>* Account already activated!</font>";
				}
				else{
							
					$engname=$row['eng_name'];
					
					$query="UPDATE user SET passw='$md5Pass' WHERE email='$email' AND phone='$phone'";
					$result=mysql_query($query);
					
					mysql_close();
					
					if($result){
						
						$to=$email;
						$subject="Registration email resend";
						$message = "<html>
									<head>
									</head>
									<body>
										<p>You have been successfully registered! Use your email as Login ID.</p>
										<p>Please use this one time temporary password to login and change it immediately<br/> after login!</p>
										<p>Otherwise your account will not be activated!</p>
										<p>Password: ".$pass."</p>
									</body>
									</html>
									";
										
						mail($to, $subject, $message, $headers);
														
						$error_email="";
						$error_phone="";
						$error_captcha="";
						$error_resend="";
						
						header("Location: success.php?id=8");
					}	
					else{
						
						header("Location: error.php?id=1");
					}
				}																																			
			}
			else{
						
					$error_resend="<font color='#F80C0C' size='3'>* Wrong email and phone combination!</font>";	
			}
		}
	}		

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Resend email</title>
</head>

<link rel="stylesheet" type="text/css" href="css/resend_style.css">
		
<script src="js/jquery-latest.min.js"></script>
<script type="text/javascript">
	
	$(document).ready(function(){
		
		$("#loading").hide("fast");
		var email_info=$("#email_info");
		var phone_info=$("#phone_info");
		var captcha_info=$("#captcha_info");
		
		var captcha_check=/^[0-9]{1,5}$/;
		
		var t1=false;
		var t2=false;
		var t3=false;

		$("#email").change(function(){
			
			$.ajax({
				
				type: "POST",
				data: "email="+$(this).attr("value"),
				url: "scripts/emailvalidation_r.php",
				beforeSend: function(){
				
					email_info.html("&nbsp;&nbsp;<img src='images/loading_small.gif'/>&nbsp;");
				},
				success: function(result){
				
					if(result=="false"){
					
						email_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Invalid</font>");
						t1=false;
					}
					
					else if(result=="abc"){
					
						email_info.html("&nbsp;&nbsp;<img src='images/right.png'/>&nbsp;<font color='#F80C0C' size='3'>Valid and Inactive</font>");
						t1=true;
					}
					
					else if(result=="true"){
					
						email_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Email is unregistered</font>");
						t1=false;
					}
					else if(result=="xyz"){
						
						email_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Email is activated</font>");
						t1=false;
					}
				}				
			});
		});
		
		$("#phone").change(function(){
		
			$.ajax({
				
				type: "POST",
				data: "phone="+$(this).attr("value"),
				url: "scripts/phonevalidation_r.php",
				beforeSend: function(){
				
					phone_info.html("&nbsp;&nbsp;<img src='images/loading_small.gif'/>&nbsp;");
				},
				success: function(result){

					if(result=="false"){
					
						phone_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Invalid</font>");
						t2=false;
					}
					
					else if(result=="abc"){
					
						phone_info.html("&nbsp;&nbsp;<img src='images/right.png'/>&nbsp;<font color='#F80C0C' size='3'>Valid and Inactive</font>");
						t2=true;
					}
					
					else if(result=="true"){
					
						phone_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Phone no. is unregistered</font>");
						t2=false;
					}
					else if(result=="xyz"){
						
						phone_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Phone is activated</font>");
						t2=false;
					}
				}				
			});
		});
		
		$("#captcha").blur(function(){
				
				var value=$(this).attr("value");
				
				if(value==""){
					
					captcha_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Enter captcha</font>");
					t3=false;
				}
				else{
				
					if(captcha_check.test(value)){
					
						captcha_info.html("");
						t3=true;
					}
					else{
					
						captcha_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Invalid</font>");
						t3=false;
					}	
				}
		});	
		
		$("form[name=resend]").bind('submit',function(){
   			
   			if((t1==true)&&(t2==true)&&(t3==true)){
   				
   				
   				$("#main_resend").hide();
				$("#loading_text").html("<br/><br/><br/><br/><br/><font color='000000' size='10'>Working..</font>");
				$("#loading").show("fast");
   				return true;
   			}
   			else{
   				
   				alert("First fill all details correctly!");
   				return false;
   			}
		});
	});
	
</script>

<script type="text/javascript">
	
    window.history.forward();
    function noBack(){ 
    	
    	window.history.forward(); 
    }
	
</script>

</head>
<body oncontextmenu="return false;" onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">
	
	<noscript>
			
			<style>
				
				#main_resend{
					
					display: none;
				}
				
				#main_loading{
					
					display: none;
				}
				
			</style>
			
			<center><h2><br/><br/><br/><br/><br/>OOPS! Javascript is disabled in your browser!<br/><br/> Please enable javascript and then <a href="resend.php">click here</a> to try again!</h2></center>
			
	</noscript>
	
	<div id="main_resend">

		<fieldset>
			<legend class="reg"><h2>Resend password</h2></legend>
			<form name="resend" action="resend.php" method="POST" >
				<ul>
					<li class="resend"><label for="email" class="resend">Enter email</label>
						<input type="text" id="email" name="email" maxlength="40" size="30" class="resend" required/><span id="email_info"></span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_email))echo $error_email; ?>
						</li><br/>
						
						<li class="resend"><label for="phone" class="resend">Enter mobile no.</label>
						<input type="tel" id="phone" name="phone" maxlength="10" size="30" class="resend" required/><span id="phone_info"></span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_phone))echo $error_phone; ?>
						<em class="resend">( must be 10 digit number)</em></li><br/><br/>
						
						<li class="resend">								
								<img src="generate_captcha.php" id="cap"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							
								<a href="javascript:;" onclick="document.getElementById('cap').src = 'generate_captcha.php?' + Math.random(); return false">
	   								<img src="images/refresh.png" />
								</a>
						</li>
						
						<li class="resend"><label for="captcha" class="resend">Enter the text you see above</label>
						<input type="text" id="captcha" name="captcha" maxlength="5" size="15" class="resend" required/><span id="captcha_info"></span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_captcha))echo $error_captcha; ?>
						</li><br/><br/>
																		
					<li class="resend"><input type="submit" id="resend_button" value="Resend" /><span id="resend_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_resend))echo $error_resend; ?>
					</li>				
				</ul>		
			</form>
		</fieldset>
	</div>
	
	<div id="main_loading" align="center">
				
		<span id="loading_text"></span><br /><br />
		<span id="loading"><img src="images/loading_big.gif" /></span>
					
	</div>
	
</body>
</html>
