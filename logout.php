<?php
	
	session_start();
	session_destroy();
	
	session_start();
	$_SESSION['done']=true;
	
	echo '<script>parent.window.location.reload(true);</script>';

?>

