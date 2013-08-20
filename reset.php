<?php

	require_once './scripts/connect_to_mysql.php';
		
	session_start();
	
	if(!isset($_SESSION['user_id'])||empty($_SESSION['user_id'])){
		
		echo '<script>parent.window.location.reload(true);</script>';
	}
	
	if(isset($_POST['oldpass'])&&isset($_POST['newpass'])&&isset($_POST['confirmpass'])){
		
		$oldpass=$_POST['oldpass'];
		$newpass=$_POST['newpass'];
		$confirmpass=$_POST['confirmpass'];
			
		$error_oldpass="";
		$error_newpass="";
		$error_confirmpass="";
		$error_reset="";
		
		$user_id=$_SESSION['user_id'];
		
		$md5oldPass=md5($oldpass);
		
		$query="SELECT id from user WHERE id='$user_id' AND passw='$md5oldPass'";
		$result=mysql_query($query);
		
		if(empty($oldpass)||empty($newpass)||empty($confirmpass)){
			
			if(empty($oldpass)){
		     	 
		    	$error_oldpass .= "<font color='#F80C0C' size='3'>* Enter old password</font>";
		    } 		 
			if(empty($newpass)){
		     	 
		      	$error_newpass .= "<font color='#F80C0C' size='3'>* Enter new password</font>";
		    }
		    if(empty($confirmpass)){
		     	 
		      	$error_confirmpass .= "<font color='#F80C0C' size='3'>* Confirm new password</font>";
		    } 	 			   
		}
		else if(!mysql_num_rows($result)){
			
			$error_oldpass .="<font color='#F80C0C' size='3'>* Wrong Password!</font>";
		}
		else if(!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s)[0-9a-zA-Z\W_]{6,25}$/", $newpass)||!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s)[0-9a-zA-Z\W_]{6,25}$/", $confirmpass)){
			
			if(!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s)[0-9a-zA-Z\W_]{6,25}$/", $newpass)){
				
				$error_newpass .= "<font color='#F80C0C' size='3'>* Invalid Password!</font>";
			}
			else{
				
				$error_confirmpass .= "<font color='#F80C0C' size='3'>* Invalid Password!</font>";
			}
		}
		else if($newpass!=$confirmpass){
			
			$error_confirmpass .= "<font color='#F80C0C' size='3'>* Passwords don't match!</font>";
		}
		else{
			
			$confirmpass=strip_tags($confirmpass);
			$confirmpass=stripcslashes($confirmpass);
			$confirmpass=mysql_real_escape_string($confirmpass);	
			
			$md5Pass=md5($confirmpass);	
								
			$query="UPDATE user SET passw='$md5Pass', active='1' WHERE id='$user_id'";
			$result=mysql_query($query);
			
			mysql_close();
			
			if($result){
				
				$_SESSION['active']=1;
								
				header("Location: success.php?id=3");
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
<title>Reset Password</title>
		
<link rel="stylesheet" type="text/css" href="css/reset_style.css">
		
<script src="js/jquery-latest.min.js"></script>
<script type="text/javascript">
	
	$(document).ready(function(){
		
		$("#loading").hide("fast");
		
		var oldpass_info=$("#oldpass_info");
		var newpass_info=$("#newpass_info");
		var confirmpass_info=$("#confirmpass_info");
		
		var passtest=/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s)[0-9a-zA-Z\W_]{6,25}$/;
		
		var t1=false;
		var t2=false;
		var t3=false;
		
		$("#oldpass").change(function(){
			
			var value=$(this).attr("value");
			
			if(value==""){
				
				oldpass_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Enter your current password</font>");
				t1=false;
			}
			else{

				oldpass_info.html("");
				t1=true;
			}
		});
		
		$("#newpass").change(function(){
			
			var value=$(this).attr("value");
			var confirmvalue=$("#confirmpass").attr("value");
			
			if(value==""){
				
				newpass_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Enter new password</font>");
				t2=false;
			}
			else{
				
				if(passtest.test(value)){
					
					newpass_info.html("&nbsp;&nbsp;<img src='images/right.png'/>&nbsp;");
					t2=true;
				}
				else{
					
					newpass_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Invalid</font>");
					t2=false;
				}
			}
			
			if(confirmvalue==""){
				
				confirmpass_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Confirm new password</font>");
				t3=false;
			}
			else{
				
				if((passtest.test(confirmvalue))&&(confirmvalue==($("#newpass").attr("value")))){
					
					confirmpass_info.html("&nbsp;&nbsp;<img src='images/right.png'/>&nbsp;");
					t3=true;
				}
				else if(!passtest.test(confirmvalue)){
					
					confirmpass_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Invalid</font>");
					t3=false;
				}
				else{
					
					confirmpass_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Passwords donot match!</font>");
					t3=false;
				}
			}
		});
		
		$("#confirmpass").blur(function(){
			
			var value=$(this).attr("value");
			
			if(value==""){
				
				confirmpass_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Confirm new password</font>");
				t3=false;
			}
			else{
				
				if((passtest.test(value))&&(value==($("#newpass").attr("value")))){
					
					confirmpass_info.html("&nbsp;&nbsp;<img src='images/right.png'/>&nbsp;");
					t3=true;
				}
				else if(!passtest.test(value)){
					
					confirmpass_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Invalid</font>");
					t3=false;
				}
				else{
					
					confirmpass_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Passwords donot match!</font>");
					t3=false;
				}
			}
		});
		
		$("form[name=reset]").bind('submit',function(){
   			
   			if((t1==true)&&(t2==true)&&(t3==true)){
   				  				
   				$("#main_reset").hide();
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
<body oncontextmenu="return false;" onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="" >
	
	<noscript>
			
			<style>
				
				#main_reset{
					
					display: none;
				}
				
				#main_loading{
					
					display: none;
				}
				
			</style>
			
			<center><h2><br/><br/><br/><br/><br/>OOPS! Javascript is disabled in your browser!<br/><br/> Please enable javascript and then <a href="reset.php">click here</a> to try again!</h2></center>
			
	</noscript>

	<div id="main_reset">
		<fieldset>
			<legend class="reset"><h2>Reset</h2></legend>
			<form name="reset" action="reset.php" method="POST" >
				<ul>
					<li class="reset"><label for="oldpass" class="reset">Enter old password</label>
						<input type="password" id="oldpass" name="oldpass" maxlength="25" size="30" class="reset" required/><span id="oldpass_info"></span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_oldpass))echo $error_oldpass; ?>
						</li><br/>
						
						<li class="reset"><label for="newpass" class="reset">Enter new password</label>
						<input type="password" id="newpass" name="newpass" maxlength="25" size="30" class="reset" required/><span id="newpass_info"></span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_newpass))echo $error_newpass; ?>
						<em class="reset">(Must be 6-25 characters long and contain atleast: 1 uppercase letter, 1 lowercase letter and 1 digit)</em></li><br/>
						
						<li class="reset"><label for="confirmpass" class="reset">Confirm new password</label>
						<input type="password" id="confirmpass" name="confirmpass" maxlength="25" size="30" class="reset" required/><span id="confirmpass_info"></span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_confirmpass))echo $error_confirmpass; ?>
						</li><br/><br/>
																		
					<li class="reset"><input type="submit" id="reset_button" value="Update" /><span id="reset_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_reset))echo $error_reset; ?>
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
