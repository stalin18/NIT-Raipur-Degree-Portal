<?php

	session_start();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome</title>

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
				
				#welcome{
					
					display: none;
				}
				
			</style>
			
			<center><h2><br/><br/><br/><br/><br/>OOPS! Javascript is disabled in your browser!<br/><br/> Please enable javascript and then <a href="welcome.php">click here</a> to try again!</h2></center>
			
	</noscript>
	
	<div id="welcome" align="center">
		
		<?php if(!isset($_SESSION['user_id'])){ ?>
		<marquee behavior="alternate"><h3><font color="red" style="bold" >Note: </font>Only students who passed out in the year 2012 may apply.</h3></marquee> 
		<?php } ?>
		
		<h2><br/><br/>Welcome to online application for degree!</h2>  <br/><br />
		
		<img src="images/degree.jpg" />
				
	</div>
	
</body>
</html>
