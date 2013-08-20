<?php

	require_once './scripts/connect_to_mysql.php';
	include_once './scripts/sendmail.php';
		
	session_start();
	
	if(isset($_SESSION['user_id'])&&!empty($_SESSION['user_id'])){
		
		echo '<script>parent.window.location.reload(true);</script>';
	}
	
	if(isset($_POST['uid'])){
		
		$uid=$_POST['uid'];
		
		$error_uid="";
		$error_forgotpass="";
			
		if(empty($uid)){
			
			if(empty($uid)){
		     	 
		    	$error_uid .= "<font color='#F80C0C' size='3'>* Enter user id</font>";
		    } 		 			   
		}
		else if(!filter_var($uid, FILTER_VALIDATE_EMAIL)){
			
			$error_uid .= "<font color='#F80C0C' size='3'>* Invalid</font>";
		}
		else{
			
			$uid=strip_tags($uid);
			$uid=stripcslashes($uid);
			$uid=mysql_real_escape_string($uid);
								
			$query="SELECT id, eng_name from user WHERE email='$uid'";
			$result=mysql_query($query);
			
			if(mysql_num_rows($result)){
				
				$row=mysql_fetch_assoc($result);
				
				$id=$row['id'];
				$uname=$row['eng_name'];
				
				$pending=$uid;
				$md5Pending=md5($pending);
				
				$query="UPDATE user SET pending='$md5Pending' WHERE id='$id'";
				$result=mysql_query($query);
				
				if($result){
					
					$to=$uid;
					$subject="Forgot password confirmation";
					$message = "<html>
								<head>
								</head>
								<body>
									<p>Dear ".$uname.",</p>"."
									<p>You have requested to reset your forgotten password. Please confirm by clicking on this link.</p>
									<p>If you cannot click on link, just copy it, paste it on your address bar then press Enter.</p><br/>
									<p><a href='http://www.nitrr.ac.in/degree/verify.php?x123=".$id."&123y=".$md5Pending."'>Click to confirm</p>
									<p>If you didn't request to reset your password, please ignore this email and delete it!</p>
								</body>
								</html>
								";
									
					mail($to, $subject, $message, $headers);
					
					mysql_close();
					
					header("Location: success.php?id=6");
				}
				else{
					
					header("Location: error.php?id=1");
				}							
			}			
			else{
						
					$error_forgotpass .= "<font color='#F80C0C' size='3'>* No user with this ID</font>";
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

<link rel="stylesheet" type="text/css" href="css/forgotpass_style.css">
		
<script src="js/jquery-latest.min.js"></script>
<script type="text/javascript">
	
	$(document).ready(function(){
		
		$("#loading").hide("fast");
		
		var uid_info=$("#uid_info");
		
		var t1=false;

		$("#uid").change(function(){
			
			$.ajax({
				
				type: "POST",
				data: "email="+$(this).attr("value"),
				url: "scripts/emailvalidation.php",
				beforeSend: function(){
				
					uid_info.html("&nbsp;&nbsp;<img src='images/loading_small.gif'/>&nbsp;");
				},
				success: function(result){
				
					if(result=="false"){
					
						uid_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Invalid</font>");
						t1=false;
						
					}
					
					else if(result=="abc"){
					
						uid_info.html("&nbsp;&nbsp;<img src='images/right.png'/>&nbsp;");
						t1=true;
					}
					
					else if(result=="true"){
					
						uid_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>No user with this id</font>");
						t1=false;
					}
				}				
			});
		});
		
		$("form[name=forgotpass]").bind('submit',function(){
   			
   			if((t1==true)){
   				  				
   				$("#main_forgotpass").hide();
				$("#loading_text").html("<br/><br/><br/><br/><br/><font color='000000' size='10'>Working..</font>");
				$("#loading").show("fast");  				
   				return true;
   			}
   			else{
   				
   				alert("Give your user id first!");
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
				
				#main_forgotpass{
					
					display: none;
				}
				
				#main_loading{
					
					display: none;
				}
				
			</style>
			
			<center><h2><br/><br/><br/><br/><br/>OOPS! Javascript is disabled in your browser!<br/><br/> Please enable javascript and then <a href="forgotpass.php">click here</a> to try again!</h2></center>
			
	</noscript>

	<div id="main_forgotpass">
		<fieldset>
			<legend class="forgotpass"><h2>Forgot Password</h2></legend>
			<form name="forgotpass" action="forgotpass.php" method="POST" >
				<ul>
					<li class="forgotpass"><label for="uid" class="forgotpass">Enter user id</label>
						<input type="text" id="uid" name="uid" maxlength="40" size="30" class="forgotpass" required/><span id="uid_info"></span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_uid))echo $error_uid; ?>
						</li><br/>
																		
					<li class="forgotpass"><input type="submit" id="forgotpass_button" value="Send Email" /><span id="forgotpass_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_forgotpass))echo $error_forgotpass; ?>
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
