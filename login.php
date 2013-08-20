<?php

	require_once './scripts/connect_to_mysql.php';
		
	session_start();
	
	if(isset($_SESSION['user_id'])&&!empty($_SESSION['user_id'])){
		
		echo '<script>parent.window.location.reload(true);</script>';
	}
	
	if(isset($_POST['uid'])&&isset($_POST['password'])){
		
		$uid=$_POST['uid'];
		$password=$_POST['password'];
		
		$error_uid="";
		$error_password="";
		$error_login="";
			
		if(empty($uid)||empty($password)){
			
			if(empty($uid)){
		     	 
		    	$error_uid .= "<font color='#F80C0C' size='3'>* Enter user id</font>";
		    } 		 
			if(empty($password)){
		     	 
		      	$error_password .= "<font color='#F80C0C' size='3'>* Enter password</font>";
		    } 			   
		}
		else if(!filter_var($uid, FILTER_VALIDATE_EMAIL)){
			
			$error_uid .= "<font color='#F80C0C' size='3'>* Invalid user id</font>";
		}
		else if(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s)[0-9a-zA-Z\W_]{6,25}$/', $password)){
			
			$error_password .= "<font color='#F80C0C' size='3'>* Invalid password</font>";
		}
		else{
			
			$uid=strip_tags($uid);
			$uid=stripcslashes($uid);
			$uid=mysql_real_escape_string($uid);
			
			$password=strip_tags($password);
			$password=stripcslashes($password);
			$password=mysql_real_escape_string($password);
			
			$md5Pass=md5($password);
								
			$query="SELECT id, eng_name, active, locked from user WHERE email='$uid' AND passw='$md5Pass'";
			$result=mysql_query($query);
			
			if($result){
				
				if(mysql_num_rows($result)){
				
					$row=mysql_fetch_assoc($result);
					
					$id=$row['id'];
					$uname=$row['eng_name'];
					$active=$row['active'];
					$locked=$row['locked'];
					
					mysql_close();
					
					$_SESSION['user_id']=$id;
					$_SESSION['user_name']=$uname;
					$_SESSION['active']=$active;
					$_SESSION['locked']=$locked;
					
					if($active=='1'){
						
						echo '<script>parent.window.location.reload(true);</script>';
					}
					else{
						
						header("Location: reset.php");
					}				
				}			
				else{
						
					$error_login .= "<font color='#F80C0C' size='3'>* Wrong user id and password combination</font>";
				}
			}
			else{
					
				header("Location: error.php?id=1");
			}
		}
	}		

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
</head>

<link rel="stylesheet" type="text/css" href="css/login_style.css">
		
<script src="js/jquery-latest.min.js"></script>
<script type="text/javascript">
	
	$(document).ready(function(){
		
		var uid_info=$("#uid_info");
		var password_info=$("#password_info");
		
		var password_check=/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s)[0-9a-zA-Z\W_]{6,25}$/;
		
		var t1=false;
		var t2=false;

		$("#uid").change(function(){
			
			$.ajax({
				
				type: "POST",
				data: "email="+$(this).attr("value"),
				url: "scripts/emailvalidation.php",
				beforeSend: function(){
				
					uid_info.html("&nbsp;&nbsp;<img src='images/loading_small.gif'/>&nbsp;");
				},
				success: function(result){
				
					if(result=="false") {
					
						uid_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Invalid</font>");
						t1=false;
						
					}	
					else{
						
						uid_info.html("");
						t1=true;
					}				
				}				
			});
		});
		
		$("#password").change(function(){
			
			var value=$(this).attr("value");
			
			if(value==""){
				
				password_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;");
				t2=false;
			}
			else{
				
				if(password_check.test(value)){
					
					password_info.html("");
					t2=true;
				}
				else{
					
					password_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;");
					t2=false;
				}				
			}
		});
		
		$("form[name=login]").bind('submit',function(){
   			
   			if((t1==true)&&(t2==true)){
   				  				
   				return true;
   			}
   			else{
   				
   				alert("First fill all details correctly!");
   				return false;
   			}
		});
	});
	
</script>

</head>
<body oncontextmenu="return false;" >
	
	<noscript>
			
			<style>
				
				#main_login{
					
					display: none;
				}
				
			</style>
			
			<center><h2><br/><br/><br/><br/><br/>OOPS! Javascript is disabled in your browser!<br/><br/> Please enable javascript and then <a href="login.php">click here</a> to try again!</h2></center>
			
	</noscript>

	<div id="main_login">
		<fieldset>
			<legend class="login"><h2>Login</h2></legend>
			<form name="login" action="login.php" method="POST" >
				<ul>
					<li class="login"><label for="uid" class="login">Enter user id</label>
						<input type="text" id="uid" name="uid" maxlength="40" size="30" class="login" required/><span id="uid_info"></span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_uid))echo $error_uid; ?>
						</li><br/>
						
						<li class="login"><label for="password" class="login">Enter password</label>
						<input type="password" id="password" name="password" maxlength="25" size="30" class="login" required/><span id="password_info"></span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_password))echo $error_password; ?>
						<em class="login">(Type/copy-paste carefully!)</em></li><br/>
																		
					<li class="login"><input type="submit" id="login_button" value="Login" /><span id="login_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_login))echo $error_login; ?>
					</li>				
				</ul>		
			</form>
		</fieldset>
	</div>
	
</body>
</html>
