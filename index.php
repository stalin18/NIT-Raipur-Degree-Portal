<?php

	session_start(); 
	ob_start();
   
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Online Application for Degree</title>

<link rel="stylesheet" type="text/css" href="css/index_style.css">

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
				
				#main{
					
					display: none;
				}
				
			</style>
			
			<center><h2><br/><br/><br/><br/><br/>OOPS! Javascript is disabled in your browser!<br/><br/> Please enable javascript and then <a href="index.php">click here</a> to try again!</h2></center>
			
	</noscript>
	
	<div id="main">
 
  		<div id="header"></div>
   
   
   		<div id="user-content">
		    <ul>
		    <li> Welcome | 
		    <?php if(isset($_SESSION['user_id'])){
		    	
				  		$namearray=explode(" ", $_SESSION['user_name']);
						echo $namearray[0];	 	
		    	  } 
		          else echo "Guest";
			?></li>
		    </ul>
	    </div>

    
		<div id="left-bar">
			<br /><br /><br />
			<?php if(isset($_SESSION['user_id'])) { ?>
			      <ul>
					  <a href="index.php" ><li>Home</li></a><br />
					  <a href="edit.php" target="if"><li>Edit Details</li></a><br />
					  <a href="lock.php" target="if"><li>Lock Details</li></a><br />
					  <a href="payfees.php" target="if"><li>Pay Fees</li></a><br />
					  <a href="print.php" target="if"><li>Print Receipt</li></a><br />
					  <a href="reset.php" target="if"><li>Reset Password</li></a><br />					  
					  <a href="logout.php" target="if"><li>Logout</li></a>
				  </ul>
			<?php } else{ ?>
			      <ul>
			      	  <a href="index.php" ><li>Home</li></a><br />
					  <a href="register.php" target="if"><li>Register</li></a><br />
					  <a href="login.php" target="if"><li>Login</li></a><br />	
					  <a href="resend.php" target="if"><li>Resend Email</li></a><br />
					  <a href="forgotpass.php" target="if"><li>Forgot Password?</li></a><br />		  
				  </ul>
		    <?php } ?>
		</div>
	
		<?php 
			if(isset($_SESSION['verify'])&&($_SESSION['verify']!=false)){
				
				if($_SESSION['verify_id']==6){
						
					$_SESSION['verify']==false;
					$_SESSION['verify_id']=0;
		?>				
					<iframe name="if" id="if" src="error.php?id=6"></iframe>
		<?php
				}
				else if($_SESSION['verify_id']==7){
					
					$_SESSION['verify']==false;
					$_SESSION['verify_id']=0;
		?>				
					<iframe name="if" id="if" src="success.php?id=7"></iframe>
		<?php
				}
				else if($_SESSION['verify_id']==1){
					
					$_SESSION['verify']==false;
					$_SESSION['verify_id']=0;
		?>			
					<iframe name="if" id="if" src="error.php?id=1"></iframe>
		<?php
				}
				else if($_SESSION['verify_id']==5){
					
					$_SESSION['verify']==false;
					$_SESSION['verify_id']=0;
		?>		
					<iframe name="if" id="if" src="error.php?id=5"></iframe>		
		<?php
				}
				else{
		?>		
					<iframe name="if" id="if" src="welcome.php"></iframe>
		<?php
				}
			}
			else{
		?>		
				<iframe name="if" id="if" src="welcome.php"></iframe>
		<?php
			}
		?>
				
		<div id="footer">
			<br/>
			<center>Copyright &#169; 2012-13<br/>
			All rights reserved with NIT Raipur</center>
		</div>		
	</div>
	
</body>
</html>

<?php

	ob_end_flush(); 

?>