<?php
    
    require_once './scripts/connect_to_mysql.php';
	include_once './scripts/find_ip_address.php';
	include_once './scripts/sendmail.php';

	session_start();
	
	if(!isset($_SESSION['user_id'])||empty($_SESSION['user_id'])){
		
		echo '<script>parent.window.location.reload(true);</script>';
	}
	
	if(isset($_SESSION['locked'])&&$_SESSION['locked']==1){
		
		header("Location: error.php?id=4");
	}
	
	if(isset($_SESSION['active'])&&$_SESSION['active']!=1){
		
		header("Location: error.php?id=3");
	}
	
	$id=$_SESSION['user_id'];
					
	$query="SELECT eng_name, hin_name, rollno, enrollno, batch, program, dept, thesis, address, pin, email from user WHERE id='$id'";
	$result=mysql_query($query);
	
	if(!$result){
		
		header("Location: error.php?id=1");
	}
	
	$row=mysql_fetch_assoc($result);
	
	$engname=$row['eng_name'];
	$hinname=$row['hin_name'];
	$rollno=$row['rollno'];
	$enrollno=$row['enrollno'];
	$batch=$row['batch'];
	$studyprog=$row['program'];
	$dept=$row['dept'];
	$thesis=$row['thesis'];
	$address=$row['address'];
	$pincode=$row['pin'];	
	$email=$row['email'];
	
	$format="d-m-Y";
		
	$mod_date=date($format);	
	$last_ip=$ip_address;
				
	if(isset($_POST['check'])){
		
		$query="UPDATE user SET mod_date='$mod_date', last_ip='$last_ip', locked='1' WHERE id='$id'";
		$result=mysql_query($query);
		
		if($result){
		
			$to=$email;
			$subject="Account locked";
			$message = "<html>
						<head>
						</head>
						<body>
							<p>Your degree details have been successfully locked!</p>
							<p>Now you cannot edit/update your details. You are now eligible to pay fees.</p>
							<p>You locked your details on ".$mod_date." from ".$last_ip;"</p>
						</body>
						</html>
						";
							
			mail($to, $subject, $message, $headers);
			
			mysql_close();
			
			$_SESSION['locked']=1;
			
			header("Location: success.php?id=5");
		}	
		else{
				
			header("Location: error.php?id=1");
		}																							
	}			
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lock Details</title>
		
<link rel="stylesheet" type="text/css" href="css/lock_style.css">
		
<script src="js/jquery-latest.min.js"></script>
<script type="text/javascript">

	$(document).ready(function(){
		
		$("#loading").hide("fast");
		
		$("form[name=lock]").bind('submit',function(){
   			
   			if(($("#check").is(":checked"))){
   				     				  
   				$("#main_lock").hide();
				$("#loading_text").html("<br/><br/><br/><br/><br/><font color='000000' size='10'>Working..</font>");
				$("#loading").show("fast");  				
   				return true;
   			}
   			else{
   				
   				alert("Tick 'Lock my account' first!");
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
				
				#main_lock{
					
					display: none;
				}
				
				#main_loading{
					
					display: none;
				}
				
			</style>
			
			<center><h2><br/><br/><br/><br/><br/>OOPS! Javascript is disabled in your browser!<br/><br/> Please enable javascript and then <a href="lock.php">click here</a> to try again!</h2></center>
			
		</noscript>
		
		<div id="main_lock">

			<fieldset>
				<legend class="lock"><h2>Lock Details</h2></legend>
				<form name="lock" action="lock.php" method="POST" >		
						
					<ul class="lock">			
						<li class="lock"><label for="engname" class="lock">Name(in English)</label>
						<input type="text" id="engname" name="engname" maxlength="40" size="30" value="<?php if(isset($engname))echo $engname; ?>" class="lock" readonly="readonly" /></li><br/>
						
						<li class="lock"><label for="hinname" class="lock">Name(in Hindi)</label>
						<input type="text" id="hinname" name="hinname" charset="UTF-8" maxlength="40" size="30" class="lock" value="<?php if(isset($hinname))echo $hinname; ?>" readonly="readonly" ></input>
						</li><br/><br/>

						<li class="lock"><label for="rollno" class="lock">Roll no.</label>
						<input type="text" id="rollno" name="rollno" maxlength="15" size="30" value="<?php if(isset($rollno))echo $rollno; ?>" class="lock" readonly="readonly" />
						</li><br/>
						
						
						<li class="lock"><label for="enrollno" class="lock">Enrollment no.</label>
						<input type="text" id="enrollno" name="enrollno" maxlength="15" size="30" value="<?php if(isset($enrollno))echo $enrollno; ?>" class="lock" readonly="readonly" />
						</li><br/>
						
						
						<li class="lock"><label for="batch" class="lock">Passout batch</label>
						<input type="text" id="batch" name="batch" maxlength="15" size="30" value="<?php if(isset($batch))echo $batch; ?>" class="lock" readonly="readonly" />
			            </li><br/>
			            
			            <li class="lock"><label for="studyprog" class="lock">Program of study</label>
			            <input type="text" id="batch" name="batch" maxlength="15" size="30" value="<?php if(isset($studyprog))echo $studyprog; ?>" class="lock" readonly="readonly" />
			            </li><br/>
			            
			            <?php if(isset($studyprog)&&($studyprog=="B.Tech/BE"||$studyprog=="M.Tech/ME")){?>
			            
			            <li class="lock"><label for="dept" class="lock">Department</label>
			           	<input type="text" id="batch" name="batch" maxlength="15" size="30" value="<?php if(isset($dept))echo $dept; ?>" class="lock" readonly="readonly" />
				        </li><br/>
				        
				        <?php }
							  else if(isset($studyprog)&&($studyprog=="B. Arch"||$studyprog=="MCA")){}
							  else{
						 ?>
						 
						<li class="lock"><label for="dept" class="lock">Department</label>
			           	<input type="text" id="batch" name="batch" maxlength="15" size="30" value="<?php if(isset($dept))echo $dept; ?>" class="lock" readonly="readonly" />
				        </li><br/>
											
						<li class="lock"><label for="thesis" class="lock">Title of the thesis</label>
						<textarea id="thesis" name="thesis" maxlength="200" rows="5" cols="35" class="lock" readonly="readonly" ><?php if(isset($thesis))echo $thesis; ?></textarea>
						</li><br/>
						
						<?php } ?>
						
						<li class="lock"><label for="address" class="lock">Current mailing address</label>
						<textarea id="address" name="address" maxlength="100" rows="5" cols="35" class="lock" readonly="readonly" ><?php if(isset($address))echo $address; ?></textarea>
						</li><br/>
						 
						<li class="lock"><label for="pincode" class="lock">Pincode</label>
						<input type="text" id="pincode" name="pincode" maxlength="10" size="30" value="<?php if(isset($pincode))echo $pincode; ?>" class="lock" readonly="readonly" />
						</li><br/><br/><br/>
						
						<li class="lock">
						<input type="checkbox" " id="check" name="check" value="check" class="lock">&nbsp;&nbsp;Lock my account! I agree that once my account is locked, I will not be able to change my information later.</input>
						</li><br/>
																		
						<li class="lock"><input type="submit" id="lock_button" value="Lock" /><span id="lock_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_lock))echo $error_lock; ?>
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