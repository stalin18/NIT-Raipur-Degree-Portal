<?php
    
    require_once './scripts/connect_to_mysql.php';
	include_once './scripts/find_ip_address.php';
	include_once 'scripts\sendmail.php';
	
	session_start();
	
	if(isset($_SESSION['user_id'])&&!empty($_SESSION['user_id'])){
		
		echo '<script>parent.window.location.reload(true);</script>';
	}
	
	if(isset($_POST['engname'])){
		
		$engname=$_POST['engname'];
		$hinname=$_POST['hinname'];

		$rollno=$_POST['rollno'];
		$enrollno=$_POST['enrollno'];
		
		$batch=$_POST['batch'];
		$studyprog=$_POST['studyprog'];
		
		$dept=NULL;
		if(isset($_POST['dept']))$dept=$_POST['dept'];
		
		$thesis=NULL;
		if(isset($_POST['thesis']))$thesis=$_POST['thesis'];
		
		$address=$_POST['address'];
		
		$pincode=$_POST['pincode'];
		
		$email=$_POST['email'];
		
		$phone=$_POST['phone'];
		
		$form_captcha=$_POST['captcha'];
		
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
		
		$format="d-m-Y";
		
		$reg_date=date($format);
		$mod_date=date($format);
		
		$last_ip=$ip_address;
		$locked=0;
		$active=0;
		
		$valid_email;
		$valid_phone;
		
		$query="SELECT id, active from user WHERE email='$email'";
		$result=mysql_query($query);
		$rows=mysql_fetch_assoc($result);
		$active=$rows['active'];

		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			
			$valid_email="true";	
			if(mysql_num_rows($result)&&$active!='0')$valid_email="abc";
		}
		else {
			
			$valid_email="false";
		}
		
		$query="SELECT id from user WHERE phone='$phone'";
		$result=mysql_query($query);
		$rows=mysql_fetch_assoc($result);
		$active=$rows['active'];
		
		if(preg_match("/^[7-9]{1}[0-9]{9}/", $phone)) {
			
			$valid_phone="true";
			if(mysql_num_rows($result)&&$active!='0')$valid_phone="abc";
		}	
		else {
				
			$valid_phone="false";
		}
		
		$error_engname="";
		$error_hinname="";
		$error_rollno="";
		$error_enrollno="";
		$error_batch="";
		$error_studyprog="";
		$error_dept="";
		$error_thesis="";
		$error_address="";
		$error_pincode="";
		$error_email="";
		$error_phone="";
		$error_captcha="";
		
		if (empty($engname)||empty($hinname)||empty($rollno)||empty($enrollno)||empty($batch)||empty($studyprog)||empty($address)||empty($pincode)||empty($email)||empty($phone)||empty($form_captcha)) { 
				  
		    if(empty($engname)){
		     	 
		    	$error_engname .= "<font color='#F80C0C' size='3'>* Name (in english)</font>";
		    }	
			if(empty($hinname)){
		     	 
		    	$error_hinname .= "<font color='#F80C0C' size='3'>* Name (in hindi)</font>";
		    }			  		    
			if(empty($rollno)){
		     	 
		    	$error_rollno .= "<font color='#F80C0C' size='3'>* Enter roll no.</font>";
		    } 	      
		    if(empty($enrollno)){
		     	 
		    	$error_enrollno .= "<font color='#F80C0C' size='3'>* Enter enrollment no.</font>";
		    } 		 
			if(empty($batch)){
		     	 
		    	$error_batch .= "<font color='#F80C0C' size='3'>* Select batch</font>";
		    } 			  
			if(empty($studyprog)){
		     	 
		    	$error_studyprog .= "<font color='#F80C0C' size='3'>* Select program of study</font>";
		    } 			
		    if(empty($address)){
		     	 
		    	$error_address .= "<font color='#F80C0C' size='3'>* Give address</font>";
		    } 	     
		    if(empty($pincode)){
		     	 
		    	$error_pincode .= "<font color='#F80C0C' size='3'>* Give pincode";
		    } 			 
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
		else if(($studyprog=="B.Tech/BE"||$studyprog=="M.Tech/ME"||$studyprog=="PhD")&&empty($dept)){
			
			$error_dept .= "<font color='#F80C0C' size='3'>* Select department</font>";
			
			if($studyprog=="PhD"&&empty($thesis)){
				
				$error_thesis .= "<font color='#F80C0C' size='3'>* Enter thesis</font>";
			}  	
		}
		else if(($studyprog=="B.Arch"&&$studyprog=="MCA")&&!empty($dept)){
			
			$error_dept .= "<font color='#F80C0C' size='3'>* Department not required</font>";
			
			if(($studyprog=="B.Arch"&&$studyprog=="MCA")&&!empty($thesis)){
				
				$error_thesis .= "<font color='#F80C0C' size='3'>* Thesis not required</font>";
			}  	
		}
		else if(($studyprog=="B.Arch"&&$studyprog=="MCA")&&!empty($thesis)){
				
				$error_thesis .= "<font color='#F80C0C' size='3'>* Thesis not required</font>";
		}  
		else if ($studyprog=="PhD"&&empty($thesis)) {
			
			$error_thesis .= "<font color='#F80C0C' size='3'>* Enter thesis</font>";	
		}
		else if (($studyprog=="B.Tech/BE"||$studyprog=="M.Tech/ME"||$studyprog=="B.Arch"||$studyprog=="MCA")&&!empty($thesis)) {
			
			$error_thesis .= "<font color='#F80C0C' size='3'>* Thesis is not required</font>";
		}
		else if($valid_email!="true"||$valid_phone!="true"){
			
			if($valid_email=="abc"){
				
				$error_email .= "<font color='#F80C0C' size='3'>* Email already registered</font>";
			}
			if($valid_phone=="abc"){
				
				$error_phone .= "<font color='#F80C0C' size='3'>* Phone no. already registered</font>";
			}
			if($valid_email=="false"){
				
				$error_email .= "<font color='#F80C0C' size='3'>* Invalid email</font>";
			}
			if($valid_phone=="false"){
				
				$error_phone .= "<font color='#F80C0C' size='3'>* Invalid phone</font>";
			}
		}
		else if(!preg_match('/^[a-zA-Z ]+$/',$engname)){
			
			$error_engname="<font color='#F80C0C' size='3'>* Invalid</font>";
		}
		else if(!preg_match('/^[0-9a-zA-Z]{3,20}$/',$rollno)){
			
			$error_rollno="<font color='#F80C0C' size='3'>* Invalid</font>";
		}
		else if(!preg_match('/^[0-9]{3,20}$/',$enrollno)){
			
			$error_enrollno="<font color='#F80C0C' size='3'>* Invalid</font>";
		}
		else if(!preg_match('/^[0-9]{3,7}$/',$pincode)){
			
			$error_pincode="<font color='#F80C0C' size='3'>* Invalid</font>";
		}
		else if(($studyprog=="PhD")&&(!preg_match('/^[,0-9a-zA-Z ]{3,200}$/', $thesis))){
				
			$error_thesis="<font color='#F80C0C' size='3'>* Invalid</font>";
		}
		else if(!preg_match('/^[-,\/0-9a-zA-Z ]{3,100}$/', $address)){
				
			$error_address="<font color='#F80C0C' size='3'>* Invalid</font>";
		}
		else if($form_captcha!=$_SESSION['cap']){
			
			$error_captcha .="<font color='#F80C0C' size='3'>* Wrong captcha!</font>";							
		}
		else {
				
			$engname=strip_tags($engname);
			$engname=stripcslashes($engname);
			$engname=mysql_real_escape_string($engname);
			
			$hinname=strip_tags($hinname);
			$hinname=stripcslashes($hinname);
			$hinname=mysql_real_escape_string($hinname);
			
			$rollno=strip_tags($rollno);
			$rollno=stripcslashes($rollno);
			$rollno=mysql_real_escape_string($rollno);	
			
			$enrollno=strip_tags($enrollno);
			$enrollno=stripcslashes($enrollno);
			$enrollno=mysql_real_escape_string($enrollno);
			
			$thesis=strip_tags($thesis);
			$thesis=stripcslashes($thesis);
			$thesis=mysql_real_escape_string($thesis);
			
			$address=strip_tags($address);
			$address=stripcslashes($address);
			$address=mysql_real_escape_string($address);
			
			$pincode=strip_tags($pincode);
			$pincode=stripcslashes($pincode);
			$pincode=mysql_real_escape_string($pincode);
			
			$email=strip_tags($email);
			$email=stripcslashes($email);
			$email=mysql_real_escape_string($email);
			
			$phone=strip_tags($phone);
			$phone=stripcslashes($phone);
			$phone=mysql_real_escape_string($phone);
			
			$check_query="SELECT id from user where email='$email'";
			$check_result=mysql_query($check_query);
			
			if($check_result){
				
				if(mysql_num_rows($check_result)){
					
					$query="UPDATE user SET eng_name='$engname', hin_name='$hinname', rollno='$rollno', enrollno='$enrollno', batch='$batch', program='$studyprog', dept='$dept', thesis='$thesis', address='$address', pin='$pincode', email='$email', passw='$md5Pass', phone='$phone', reg_date='$reg_date', mod_date='$mod_date', last_ip='$last_ip', locked='$locked', active='$active'";
					$result=mysql_query($query);
					
					if($result){
						
						$to=$email;
						$subject="Registered successfully";
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
						
						mysql_close();
								
						$error_engname="";
						$error_hinname="";
						$error_rollno="";
						$error_enrollno="";
						$error_batch="";
						$error_studyprog="";
						$error_dept="";
						$error_thesis="";
						$error_address="";
						$error_pincode="";
						$error_email="";
						$error_phone="";
						$error_captcha="";
						$error_register="";
						
						header("Location: success.php?id=1");																									
					}
					else{
						
						header("Location: error.php?id=3");
					}
				}
				else{
					
					$query="INSERT INTO user(eng_name,hin_name,rollno,enrollno,batch,program,dept,thesis,address,pin,email,passw,phone,reg_date,mod_date,last_ip,locked,active) VALUES('$engname','$hinname','$rollno','$enrollno','$batch','$studyprog','$dept','$thesis','$address','$pincode','$email','$md5Pass','$phone','$reg_date','$mod_date','$last_ip','$locked','$active')";
					$result=mysql_query($query);
					
					if($result){
						
						$to=$email;
						$subject="Registered successfully";
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
						
						mysql_close();
								
						$error_engname="";
						$error_hinname="";
						$error_rollno="";
						$error_enrollno="";
						$error_batch="";
						$error_studyprog="";
						$error_dept="";
						$error_thesis="";
						$error_address="";
						$error_pincode="";
						$error_email="";
						$error_phone="";
						$error_captcha="";
						$error_register="";
						
						header("Location: success.php?id=1");																									
					}
					else{
						
						header("Location: error.php?id=1");
					}
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
<title>Register</title>
		
<link rel="stylesheet" type="text/css" href="css/register_style.css">
		
<script src="js/jquery-latest.min.js"></script>
<script src="js/common.js"></script>
<script src="js/hindi.js"></script>
<script type="text/javascript">

	$(document).ready(function(){
		
		$("#loading").hide("fast");
		
		var engname_info=$("#engname_info");
		var hinname_info=$("#hinname_info");
		var rollno_info=$("#rollno_info");
		var enrollno_info=$("#enrollno_info");
		var batch_info=$("#batch_info");
		var studyprog_info=$("#studyprog_info");
		var dept_info=$("#dept_info");
		var thesis_info=$("#thesis_info");
		var address_info=$("#address_info");
		var pincode_info=$("#pincode_info");
		var email_info=$("#email_info");
		var phone_info=$("#phone_info");
		var captcha_info=$("#captcha_info");
		
		var engname_check=/^[a-zA-Z ]+$/;
		var rollno_check=/^[0-9]{3,20}$/;
		var enrollno_check=/^[0-9a-zA-Z]{3,20}$/;
		var thesis_check=/^[,0-9a-zA-Z ]{3,200}$/;
		var address_check=/^[-,\/0-9a-zA-Z ]{3,100}$/
		var pincode_check=/^[0-9]{3,7}$/;	
		var captcha_check=/^[0-9]{1,5}$/;	
		
		var t1=false;
		var t2=false;
		var t4=false;
		var t5=false;
		var t6=false;
		var t7=false;
		var t8=false;
		var t9=false;
		var t10=false;
		var t11=false;
		var t12=false;
		var t13=false;
		var t14=false;
		
		$("#engname").change(function(){
			
			var value=$(this).attr("value");
			
			if(value==""){
				
				engname_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;");
				t1=false;
			}
			else{
				
				if(engname_check.test(value)){
					
					engname_info.html("&nbsp;&nbsp;<img src='images/right.png'/>&nbsp;");
					t1=true;
				}
				else{
					
					engname_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Invalid name</font>");
					t1=false;
				}
			}
		});
		
		$("#hinname").change(function(){
			
			var value=$(this).attr("value");
			
			if(value==""){
				
				hinname_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;");
				t2=false;
			}
			else{
				
				hinname_info.html("&nbsp;&nbsp;<img src='images/right.png'/>&nbsp;");
				t2=true;
			}
		});
		
		$("#rollno").change(function(){
			
			var value=$(this).attr("value");
			
			if(value==""){
				
				rollno_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;");
				t4=false;
			}
			else{
				
				if(rollno_check.test(value)){
					
					rollno_info.html("&nbsp;&nbsp;<img src='images/right.png'/>&nbsp;");
					t4=true;
				}
				else{
					
					rollno_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;");
					t4=false;
				}				
			}
		});
		
		$("#enrollno").change(function(){
			
			var value=$(this).attr("value");
			
			if(value==""){
				
				enrollno_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;");
				t5=false;
			}
			else{
				
				if(enrollno_check.test(value)){
					
					enrollno_info.html("&nbsp;&nbsp;<img src='images/right.png'/>&nbsp;");
					t5=true;
				}
				else{
					
					enrollno_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;");
					t5=false;
				}	
			}
		});
		
		$("#batch").change(function(){
			
			var selected=$("#batch option:selected").text();
			
			if(selected==""){
				
				batch_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;");
				t6=false;
			}
			else{
				
				batch_info.html("&nbsp;&nbsp;<img src='images/right.png'/>&nbsp;");
				t6=true;
			}
		});
		
		$("#studyprog").change(function(){
			
			var selected=$("#studyprog option:selected").text();
			
			if(selected==""){
				
				studyprog_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;");
				t7=false;
			}
			else{
				
				studyprog_info.html("&nbsp;&nbsp;<img src='images/right.png'/>&nbsp;");
				t7=true;
			}
		});

		$("#studyprog").change(function(){
			
			var selected=$("#studyprog option:selected").text();
			
			if(selected=="B.Tech/BE"||selected=="M.Tech/ME"||selected=="B.Arch"||selected=="MCA"){
				
				if(selected=="B.Arch"||selected=="MCA"){
					
					$("#dept").attr("disabled","disabled");
					dept_info.html("<font color='#F80C0C' size='2'>&nbsp;&nbsp;Not required</font>");
				}
				else{
					
					$("#dept").removeAttr("disabled");
					dept_info.html("<font color='#F80C0C' size='2'>&nbsp;&nbsp;Required</font>");
					
					$("#dept").html("");
					$("<option value=''></option>").appendTo("#dept");
					$("<option value='Applied Geology'>Applied Geology</option>").appendTo("#dept");
					$("<option value='Applied Mechanics'>Applied Mechanics</option>").appendTo("#dept");
					$("<option value='Bio-Informatics'>Bio-Informatics</option>").appendTo("#dept");
					$("<option value='Bio Medical Engineering'>Bio Medical Engineering</option>").appendTo("#dept");
					$("<option value='Biotechnology Engineering'>Biotechnology Engineering</option>").appendTo("#dept");
					$("<option value='Chemical Engineering'>Chemical Engineering</option>").appendTo("#dept");
					$("<option value='Civil Engineering'>Civil Engineering</option>").appendTo("#dept");
					$("<option value='Computer Science Engineering'>Computer Science Engineering</option>").appendTo("#dept");
					$("<option value='Electrical Engineering'>Electrical Engineering</option>").appendTo("#dept");
					$("<option value='Electronics & Telecommunications Engineering'>Electronics &amp; Telecommunications Engineering</option>").appendTo("#dept");
					$("<option value='Information Technology'>Information Technology</option>").appendTo("#dept");
					$("<option value='Mechanical Engineering'>Mechanical Engineering</option>").appendTo("#dept");
					$("<option value='Mining Engineering'>Mining Engineering</option>").appendTo("#dept");
					$("<option value='Metallurgical Engineering'>Metallurgical Engineering</option>").appendTo("#dept");
				}
				
				$("#thesis").attr("disabled","disabled");
				thesis_info.html("<font color='#F80C0C' size='2'>&nbsp;&nbsp;Not required</font>");
			}
			else if(selected=="PhD"){
				
				$("#dept").removeAttr("disabled");
				$("#thesis").removeAttr("disabled");
				dept_info.html("<font color='#F80C0C' size='2'>&nbsp;&nbsp;Required</font>");
				thesis_info.html("<font color='#F80C0C' size='2'>&nbsp;&nbsp;Required</font>");
				
				$("#dept").html("");
				$("<option value=''></option>").appendTo("#dept");
				$("<option value='Applied Geology'>Applied Geology</option>").appendTo("#dept");
				$("<option value='Applied Mechanics'>Applied Mechanics</option>").appendTo("#dept");
				$("<option value='Architecture'>Architecture</option>").appendTo("#dept");
				$("<option value='Bio-informatics'>Bio-informatics</option>").appendTo("#dept");
				$("<option value='Biotechnology'>Biotechnology</option>").appendTo("#dept");
				$("<option value='Chemical Engineering'>Chemical Engineering</option>").appendTo("#dept");
				$("<option value='Chemistry'>Chemistry</option>").appendTo("#dept");
				$("<option value='Civil Engineering'>Civil Engineering</option>").appendTo("#dept");
				$("<option value='Electrical Engineering'>Electrical Engineering</option>").appendTo("#dept");
				$("<option value='Mathematics'>Mathematics</option>").appendTo("#dept");
				$("<option value='Mechanical Engineering'>Mechanical Engineering</option>").appendTo("#dept");
				$("<option value='Metallurgical & Materials Engineering'>Metallurgical & Materials Engineering</option>").appendTo("#dept");
				$("<option value='Mining Engineering'>Mining Engineering</option>").appendTo("#dept");				
				$("<option value='Physics'>Physics</option>").appendTo("#dept");
			}
		});
				
		$("#dept").change(function(){
			
			var selected=$("#dept option:selected").text();
			
			if(selected==""){
				
				dept_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;");
				t8=false;
			}
			else{
				
				dept_info.html("&nbsp;&nbsp;<img src='images/right.png'/>&nbsp;");
				t8=true;
			}
		});
		
		$("#thesis").change(function(){
			
			var value=$(this).attr("value");
			
			if(value==""){
				
				thesis_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;");
				t9=false;
			}
			else{
				
				if(thesis_check.test(value)){
				
					thesis_info.html("&nbsp;&nbsp;<img src='images/right.png'/>&nbsp;");
					t9=true;
				}
				else{
					
					thesis_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;");
					t9=false;
				}
			}
		});
		
		$("#address").change(function(){
			
			var value=$(this).attr("value");
			
			if(value==""){
				
				$("#address_info").html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;");
				t10=false;
			}
			else{
				
				if(address_check.test(value)){
				
					address_info.html("&nbsp;&nbsp;<img src='images/right.png'/>&nbsp;");
					t10=true;
				}
				else{
					
					address_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;");
					t10=false;
				}
			}
		});
		
		$("#pincode").change(function(){
			
			var value=$(this).attr("value");
			
			if(value==""){
				
				pincode_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;");
				t11=false;
			}
			else{
				
				if(pincode_check.test(value)){
					
					pincode_info.html("&nbsp;&nbsp;<img src='images/right.png'/>&nbsp;");
					t11=true;
				}
				else{
					
					pincode_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;");
					t11=false;
				}				
			}
		});
		
		$("#email").change(function(){
			
			$.ajax({
				
				type: "POST",
				data: "email="+$(this).attr("value"),
				url: "scripts/emailvalidation.php",
				beforeSend: function(){
				
					email_info.html("&nbsp;&nbsp;<img src='images/loading_small.gif'/>&nbsp;");
				},
				success: function(result){
				
					if(result=="false"){
					
						email_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Invalid</font>");
						t12=false;
						
					}
					
					else if(result=="abc"){
					
						email_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Already registered</font>");
						t12=false;
					}
					
					else if(result=="true"){
					
						email_info.html("&nbsp;&nbsp;<img src='images/right.png'/>");
						t12=true;
					}
				}				
			});
		});
		
		$("#phone").change(function(){
		
			$.ajax({
				
				type: "POST",
				data: "phone="+$(this).attr("value"),
				url: "scripts/phonevalidation.php",
				beforeSend: function(){
				
					phone_info.html("&nbsp;&nbsp;<img src='images/loading_small.gif'/>&nbsp;");
				},
				success: function(result){

					if(result=="false"){
					
						phone_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Invalid</font>");
						t13=false;
					}
					
					else if(result=="abc"){
					
						phone_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Already registered</font>");
						t13=false;
					}
					
					else if(result=="true"){
					
						phone_info.html("&nbsp;&nbsp;<img src='images/right.png'/>");
						t13=true;
					}
				}				
			});
		});
		
		$("#captcha").change(function(){
				
				var value=$(this).attr("value");
				
				if(value==""){
					
					captcha_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Enter captcha</font>");
					t14=false;
				}
				else{
				
					if(captcha_check.test(value)){
					
						captcha_info.html("");
						t14=true;
					}
					else{
					
						captcha_info.html("&nbsp;&nbsp;<img src='images/wrong.png'/>&nbsp;<font color='#F80C0C' size='3'>Invalid</font>");
						t14=false;
					}	
				}
		});		
		
		$("form[name=register]").bind('submit',function(){
   			
   			if((t1==true)&&(t2==true)&&(t4==true)&&(t5==true)&&(t6==true)&&(t7==true)&&(t10==true)&&(t11==true)&&(t12==true)&&(t13==true)&&(t14==true)){
   				
   				if(!($("#dept").prop("disabled"))&&(t8==false)){
   					
   					alert("First fill all details correctly!");
   					return false;
   				}
   				
   				if(!($("#thesis").prop("disabled"))&&(t9==false)){
   					
   					alert("First fill all details correctly!");
   					return false;
   				}
   				
   				$("#main_register").hide();
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
				
				#main_register{
					
					display: none;
				}
				
				#main_loading{
					
					display: none;
				}
				
			</style>
			
			<center><h2><br/><br/><br/><br/><br/>OOPS! Javascript is disabled in your browser!<br/><br/> Please enable javascript and then <a href="register.php">click here</a> to try again!</h2></center>
			
		</noscript>
	
		<div id="main_register">
			<fieldset>
				<legend class="reg"><h2>Register</h2></legend>			
					<form name="register" action="register.php" method="POST" enctype="multipart/form-data">		
							
						<ul class="reg">			
							<li class="reg"><label for="engname" class="reg">Name</label>
							<input type="text" id="engname" name="engname" maxlength="40" size="30" class="reg" required />
							<span id="engname_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_engname))echo $error_engname; ?>
							<em class="reg">(in English. Separate first and last name by spaces)</em></li><br/>
							
							<li class="reg"><label for="hinname" class="reg">Name (you can also try it here: <a href="http://www.google.com/transliterate" target="_blank">http://www.google.com/transliterate</a>)</label>
							<input type="text" id="hinname" name="hinname" charset="UTF-8" maxlength="40" size="30" class="reg" required onkeydown="toggleKBMode(event)" onkeypress="convertThis(event)"></input><span id="hinname_info"></span>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_hinname))echo $error_hinname; ?>
							<em class="reg">(in Hindi. See help while typing, use CAPS LOCK/SHIFT for more hindi letters)</em></li><br/>
							
							<li class="reg"><label for="rollno" class="reg">Roll no.</label>
							<input type="text" id="rollno" name="rollno" maxlength="20" size="30" class="reg" required />
							<span id="rollno_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_rollno))echo $error_rollno; ?>
							<em class="reg">(Only numbers)</em></li><br/>
							
							
							<li class="reg"><label for="enrollno" class="reg">Enrollment no.</label>
							<input type="text" id="enrollno" name="enrollno" maxlength="20" size="30" class="reg" required />
							<span id="enrollno_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_enrollno))echo $error_enrollno; ?>
							<em class="reg">(Can be a combination of letters and numbers)</em></li><br/>
							
							
							<li class="reg"><label for="batch" class="reg">Passout batch</label>
							<select id="batch" name="batch" required>
								  <option value=""></option>
						          <option value="APRIL-MAY 2012">APRIL-MAY 2012</option>
						          <option value="NOV-DEC 2012">NOV-DEC 2012</option>					          
				            </select><span id="batch_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_batch))echo $error_batch; ?>
				            </li><br/>
				            
				            <li class="reg"><label for="studyprog" class="reg">Program of study</label>
				            <select id="studyprog" name="studyprog" required>
				            	  <option value=""></option>
						          <option value="B.Tech/BE">B.Tech/BE</option>
						          <option value="M. Tech/ME">M.Tech/ME</option>
						          <option value="B. Arch">B.Arch</option>
						          <option value="MCA">MCA</option>
						          <option value="PhD">PhD</option>			          
				            </select><span id="studyprog_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_studyprog))echo $error_studyprog; ?>
				            </li><br/>
				            
				            <li class="reg"><label for="dept" class="reg">Department</label>
					            <select id="dept" name="dept" required>
					            	  <option value=""></option>
							          <option value="Applied Geology">Applied Geology</option>
							          <option value="Applied Mechanics">Applied Mechanics</option>
							          <option value="Bio-Informatics">Bio-Informatics</option>
							          <option value="Bio Medical Engineering">Bio Medical Engineering</option>
							          <option value="Biotechnology Engineering">Biotechnology Engineering</option>
							          <option value="Chemical Engineering">Chemical Engineering</option>
							          <option value="Civil Engineering">Civil Engineering</option>
							          <option value="Computer Science Engineering">Computer Science Engineering</option>
							          <option value="Electrical Engineering">Electrical Engineering</option>
							          <option value="Electronics & Telecommunications Engineering">Electronics &amp; Telecommunications Engineering</option>
							          <option value="Information Technology">Information Technology</option>							          
							          <option value="Mechanical Engineering">Mechanical Engineering</option>
							          <option value="Mining Engineering">Mining Engineering</option>
							          <option value="Metallurgical Engineering">Metallurgical Engineering</option>			          
					            </select><span id="dept_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_dept))echo $error_dept; ?>
							</li><br/>
												
							<li class="reg"><label for="thesis" class="reg">Title of the thesis</label>
							<textarea id="thesis" name="thesis" maxlength="200" rows="5" cols="35" placeholder="Use 0-9, a-z, A-Z, COMMA and SPACES only" class="reg"></textarea><span id="thesis_info"></span>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_thesis))echo $error_thesis; ?>
							<em class="reg">(Only for PhD degree. Use 0-9, a-z, A-Z, COMMA and SPACES only)</em></li><br/>
							
							<li class="reg"><label for="address" class="reg">Current mailing address</label>
							<textarea id="address" name="address" maxlength="100" rows="5" cols="35" placeholder="No special characters except SPACES, COMMA, - and /" class="reg" required></textarea>
							<span id="address_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_address))echo $error_address; ?>
							<em class="reg">(For mailing intimation letter/degree. No special characters except SPACES, COMMA, - and /)</em></li><br/>
							 
							<li class="reg"><label for="pincode" class="reg">Pincode</label>
							<input type="text" id="pincode" name="pincode" maxlength="7" size="7" class="reg" required />
							<span id="pincode_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_pincode))echo $error_pincode; ?>
							</li><br/>
							
							<li class="reg"><label for="email" class="reg">Enter email</label>
							<input type="email" id="email" name="email" maxlength="40" size="30" class="reg" required/><span id="email_info"></span>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_email))echo $error_email; ?>
							</li><br/>
							
							<li class="reg"><label for="phone" class="reg">Enter mobile no.</label>
							<input class="reg" readonly="readonly" size="2" value="+91"/><input type="tel" id="phone" name="phone" maxlength="10" size="10" class="reg" required/><span id="phone_info"></span>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_phone))echo $error_phone; ?>
							<em class="reg">( must be a 10 digit number)</em></li><br/><br/>
														
							<li class="reg">								
								<img src="generate_captcha.php" id="cap"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							
								<a href="javascript:;" onclick="document.getElementById('cap').src = 'generate_captcha.php?' + Math.random(); return false">
	   								<img src="images/refresh.png" />
								</a>
							</li>

							<li class="reg"><label for="captcha" class="reg">Enter the text you see above</label>
							<input type="text" id="captcha" name="captcha" maxlength="5" size="5" class="reg" required/><span id="captcha_info"></span>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_captcha))echo $error_captcha; ?>
							</li><br/><br/>
																			
							<li class="reg"><input type="submit" id="register_button" value="Register" /><span id="register_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_register))echo $error_register; ?>
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

