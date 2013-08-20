<?php

	require_once './scripts/connect_to_mysql.php';
	include_once './scripts/sendmail.php';
	
	session_start();
	
	if(!isset($_GET['x123'])||!isset($_GET['123y'])||empty($_GET['x123'])||empty($_GET['123y'])){
			
		$_SESSION['verify']=true;
		$_SESSION['verify_id']=6;
		header("Location: index.php");
	}
	else{
		
		$id=$_GET['x123'];
		$md5Pending=$_GET['123y'];
		
		$query="SELECT eng_name, email from user where id='$id' AND pending='$md5Pending'";
		$result=mysql_query($query);
		
		if(mysql_num_rows($result)){
			
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
			
			$row=mysql_fetch_assoc($result);
			$email=$row['email'];
			$engname=$row['eng_name'];
			
			$query="UPDATE user SET passw='$md5Pass', active='2', pending=NULL WHERE id='$id'";
			$result=mysql_query($query);
			
			if($result){
				
				$to=$email;
				$subject="Reset your password";
				$message = "<html>
							<head>
							</head>
							<body>
								<p>Your password has been successfully reset!</p>
								<p>Please use this one time temporary password to login and change it immediately<br/> after login!</p>
								<p>Otherwise your account will not be activated!</p>
								<p>Password: ".$pass."</p>
							</body>
							</html>
							";
								
				mail($to, $subject, $message, $headers);
				
				$_SESSION['verify']=true;
				$_SESSION['verify_id']=7;
				header("Location: index.php");
			}
			else{
				
				$_SESSION['verify']=true;
				$_SESSION['verify_id']=1;
				header("Location: index.php");
			}
		}
		else{
			
			$_SESSION['verify']=true;
			$_SESSION['verify_id']=5;
			header("Location: index.php");

		}
	}

?>