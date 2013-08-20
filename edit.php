<?php
    
    require_once './scripts/connect_to_mysql.php';
	include_once './scripts/find_ip_address.php';
	include_once './scripts/sendmail.php';

	session_start();
	
	if(!isset($_SESSION['user_id'])||empty($_SESSION['user_id'])){
		
		echo '<script>parent.window.location.reload(true);</script>';
	}
	
	if(isset($_SESSION['locked'])&&$_SESSION['locked']==1){
		
		header("Location: error.php?id=2");
	}
	
	if(isset($_SESSION['active'])&&$_SESSION['active']!=1){
		
		header("Location: error.php?id=3");
	}
		
	$id=$_SESSION['user_id'];
	$t=FALSE;
	
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
	
	if(isset($_POST['check'])){
		
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
		
		$format="d-m-Y";
		
		$mod_date=date($format);
		
		$last_ip=$ip_address;

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
		$error_edit="";
		
		if (empty($engname)||empty($hinname)||empty($rollno)||empty($enrollno)||empty($batch)||empty($studyprog)||empty($address)||empty($pincode)) { 
				  
		    if(empty($engname)){
		     	 
		    	$error_engname .= "<font color='#F80C0C' size='3'>* Name (in english)</font>";
		    }	
  			if(empty($hinname)){
		     	 
		    	$error_engname .= "<font color='#F80C0C' size='3'>* Name (in hindi)</font>";
		    }		  		    
			if(empty($rollno)){
		     	 
		    	$error_rollno .= "<font color='#F80C0C' size='3'>* Enter roll no.</font>";
		    } 	      
		    if(empty($enrollno)){
		     	 
		    	$error_rollno .= "<font color='#F80C0C' size='3'>* Enter enrollment no.</font>";
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
		else if(!preg_match('/^[a-zA-Z ]+$/',$engname)){
			
			$error_engname="<font color='#F80C0C' size='3'>* Invalid</font>";
		}
		else if(!preg_match('/^[0-9]{3,20}$/',$rollno)){
			
			$error_rollno="<font color='#F80C0C' size='3'>* Invalid</font>";
		}
		else if(!preg_match('/^[0-9]{3,20}$/',$enrollno)){
			
			$error_enrollno="<font color='#F80C0C' size='3'>* Invalid</font>";
		}
		else if(!preg_match('/^[0-9]{3,9}$/',$pincode)){
			
			$error_pincode="<font color='#F80C0C' size='3'>* Invalid</font>";
		}
		else if(($studyprog=="PhD")&&(!preg_match('/^[,0-9a-zA-Z ]{3,200}$/', $thesis))){
				
			$error_thesis="<font color='#F80C0C' size='3'>* Invalid</font>";
		}
		else if(!preg_match('/^[-,\/0-9a-zA-Z ]{3,100}$/', $address)){
				
			$error_address="<font color='#F80C0C' size='3'>* Invalid</font>";
		}
		else {
				
			$rollno=strip_tags($rollno);
			$rollno=stripcslashes($rollno);
			$rollno=mysql_real_escape_string($rollno);
			
			$engname=strip_tags($engname);
			$engname=stripcslashes($engname);
			$engname=mysql_real_escape_string($engname);
			
			$hinname=strip_tags($hinname);
			$hinname=stripcslashes($hinname);
			$hinname=mysql_real_escape_string($hinname);
			
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
								
			$query="UPDATE user SET eng_name='$engname', hin_name='$hinname', rollno='$rollno', enrollno='$enrollno', batch='$batch', program='$studyprog', dept='$dept', thesis='$thesis', address='$address', pin='$pincode', mod_date='$mod_date', last_ip='$last_ip' where id='$id'";
			$result=mysql_query($query);
			
			if($result){
					
				$query="Select email from user where id='$id'";
				$result=mysql_query($query);
				$row=mysql_fetch_assoc($result);
				
				$email=$row['email'];
				
				mysql_close();
				
				$to=$email;
				$subject="Details for degree updated";
				$message = "<html>
							<head>
							</head>
							<body>
								<p>Dear ".$engname.",</p>"."
							    <p>Your details have been updated successfully!</p>
							    <p>You updated your details on ".$mod_date." from ".$last_ip;"</p>
							</body>
							</html>
							";
								
				mail($to, $subject, $message, $headers);
						
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
				$error_edit="";						
				
				header("Location: success.php?id=4");																									
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
<title>Edit Details</title>
		
<link rel="stylesheet" type="text/css" href="css/edit_style.css">
		
<script src="js/jquery-latest.min.js"></script>
<script src="js/common.js"></script>
<script src="js/hindi.js"></script>
<script type="text/javascript">

	var t1=true;
	var t2=true;
	var t4=true;
	var t5=true;
	var t6=true;
	var t7=true;
	var t8=true;
	var t9=true;
	var t10=true;
	var t11=true;
		
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
		
		var engname_check=/^[a-zA-Z ]+$/;
		var rollno_check=/^[0-9]{3,20}$/;
		var enrollno_check=/^[0-9a-zA-Z]{3,20}$/;
		var thesis_check=/^[,0-9a-zA-Z ]{3,200}$/;
		var address_check=/^[-,\/0-9a-zA-Z ]{3,100}$/
		var pincode_check=/^[0-9]{3,7}$/;		
		
		
		var selected=$("#studyprog option:selected").text();
			
		if(selected=="B.Tech/BE"||selected=="M.Tech/ME"||selected=="B.Arch"||selected=="MCA"){
			
			if(selected=="B.Arch"||selected=="MCA"){
				
				$("#dept").attr("disabled","disabled");
				dept_info.html("<font color='#F80C0C' size='2'>&nbsp;&nbsp;Not required</font>");
			}
			else{
				
				$("#dept").removeAttr("disabled");
				dept_info.html("<font color='#F80C0C' size='2'>&nbsp;&nbsp;Required</font>");
			}
			
			$("#thesis").attr("disabled","disabled");
			thesis_info.html("<font color='#F80C0C' size='2'>&nbsp;&nbsp;Not required</font>");
		}
		else if(selected=="PhD"){
			
			$("#dept").removeAttr("disabled");
			$("#thesis").removeAttr("disabled");
			dept_info.html("<font color='#F80C0C' size='2'>&nbsp;&nbsp;Required</font>");
			thesis_info.html("<font color='#F80C0C' size='2'>&nbsp;&nbsp;Required</font>");
		}
		
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
		
		$("form[name=edit]").bind('submit',function(){
   			
   			if((t1==true)&&(t2==true)&&(t4==true)&&(t5==true)&&(t6==true)&&(t7==true)&&(t10==true)&&(t11==true)){
   				
   				if(!($("#dept").prop("disabled"))&&(t8==false)){
   					
   					alert("First fill all details correctly!");
   					return false;
   				}
   				
   				if(!($("#thesis").prop("disabled"))&&(t9==false)){
   					
   					alert("First fill all details correctly!");
   					return false;
   				}
   				
   				if(!($("#check").is(":checked"))){
   				  				
   					alert("Tick on 'Update my details'!");
   					return false;
   				}
   				
   				
   				$("#main_edit").hide();
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
				
				#main_edit{
					
					display: none;
				}
				
				#main_loading{
					
					display: none;
				}
				
			</style>
			
			<center><h2><br/><br/><br/><br/><br/>OOPS! Javascript is disabled in your browser!<br/><br/> Please enable javascript and then <a href="edit.php">click here</a> to try again!</h2></center>			
		
		</noscript>
		
		<div id="main_edit">

			<fieldset>
				<legend class="edit"><h2>Edit</h2></legend>
				<form name="edit" action="edit.php" method="POST" enctype="multipart/form-data">		
						
					<ul class="edit">			
						<li class="edit"><label for="engname" class="edit">Name</label>
						<input type="text" id="engname" name="engname" maxlength="40" size="30" class="edit" required value="<?php if(isset($engname))echo $engname; ?>" />
						<span id="engname_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_engname))echo $error_engname; $t=TRUE;?>
						<em class="edit">(in English. Separate first and last  by spaces)</em></li><br/>
						
						<li class="edit"><label for="hinname" class="edit">Name (you can also try it here: <a href="http://www.google.com/transliterate" target="_blank">http://www.google.com/transliterate</a>)</label>
						<input type="text" id="hinname" name="hinname" charset="UTF-8" maxlength="40" size="30" class="edit" required value="<?php if(isset($hinname))echo $hinname; ?>" onkeydown="toggleKBMode(event)" onkeypress="convertThis(event)"></input>
						<span id="hinname_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_hinname))echo $error_hinname; ?><em class="edit">(in Hindi. See help while typing, use caps lock/shift for more hindi letters.)</em></li><br/>
						
						<li class="edit"><label for="rollno" class="edit">Roll no.</label>
						<input type="text" id="rollno" name="rollno" maxlength="20" size="30" class="edit" required value="<?php if(isset($rollno))echo $rollno; ?>" />
						<span id="rollno_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_rollno))echo $error_rollno; ?>
						<em class="edit">(Only numbers)</em></li><br/>
						
						
						<li class="edit"><label for="enrollno" class="edit">Enrollment no.</label>
						<input type="text" id="enrollno" name="enrollno" maxlength="20" size="30" class="edit" required value="<?php if(isset($enrollno))echo $enrollno; ?>" />
						<span id="enrollno_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_enrollno))echo $error_enrollno; ?>
						<em class="edit">(Can be a combination of letters and numbers)</em></li><br/>
						
						
						<li class="edit"><label for="batch" class="edit">Passout batch</label>
						<select id="batch" name="batch" required>
							  <option value=""></option>
					          <option <?php if(isset($batch)&&$batch=="APRIL-MAY 2012") echo "selected='selected'" ?> value="APRIL-MAY 2012">APRIL-MAY 2012</option>
					          <option <?php if(isset($batch)&&$batch=="NOV-DEC 2012") echo "selected='selected'" ?> value="NOV-DEC 2012">NOV-DEC 2012</option>			
			            </select><span id="batch_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_batch))echo $error_batch; ?>
			            </li><br/>
			            
			            <li class="edit"><label for="studyprog" class="edit">Program of study</label>
			            <select id="studyprog" name="studyprog" required>
			            	  <option value=""></option>
					          <option <?php if(isset($studyprog)&&$studyprog=="B.Tech/BE") echo "selected='selected'" ?> value="B.Tech/BE">B.Tech/BE</option>
					          <option <?php if(isset($studyprog)&&$studyprog=="M. Tech/ME") echo "selected='selected'" ?> value="M. Tech/ME">M.Tech/ME</option>
					          <option <?php if(isset($studyprog)&&$studyprog=="B. Arch") echo "selected='selected'" ?> value="B. Arch">B.Arch</option>
					          <option <?php if(isset($studyprog)&&$studyprog=="MCA") echo "selected='selected'" ?> value="MCA">MCA</option>
					          <option <?php if(isset($studyprog)&&$studyprog=="PhD") echo "selected='selected'" ?> value="PhD">PhD</option>			          
			            </select><span id="studyprog_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_studyprog))echo $error_studyprog; ?>
			            </li><br/>
			            
			            <?php if(isset($studyprog)&&($studyprog=="B.Tech/BE"||$studyprog=="M.Tech/ME")){ ?>
			            
			            <li class="edit"><label for="dept" class="edit">Department</label>
				            <select id="dept" name="dept" required>
				            	  <option value=""></option>
						          <option <?php if(isset($dept)&&$dept=="Applied Geology") echo "selected='selected'" ?> value="Applied Geology">Applied Geology</option>
								  <option <?php if(isset($dept)&&$dept=="Applied Mechanics") echo "selected='selected'" ?> value="Applied Mechanics">Applied Mechanics</option>
								  <option <?php if(isset($dept)&&$dept=="Bio-Informatics") echo "selected='selected'" ?> value="Bio-Informatics">Bio-Informatics</option>
								  <option <?php if(isset($dept)&&$dept=="Bio Medical Engineering") echo "selected='selected'" ?> value="Bio Medical Engineering">Bio Medical Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Biotechnology Engineering") echo "selected='selected'" ?> value="Biotechnology Engineering">Biotechnology Engineering</option>
						  		  <option <?php if(isset($dept)&&$dept=="Chemical Engineering") echo "selected='selected'" ?> value="Chemical Engineering">Chemical Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Civil Engineering") echo "selected='selected'" ?> value="Civil Engineering">Civil Engineering</option>
						  		  <option <?php if(isset($dept)&&$dept=="Computer Science Engineering") echo "selected='selected'" ?> value="Computer Science Engineering">Computer Science Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Electrical Engineering") echo "selected='selected'" ?> value="Electrical Engineering">Electrical Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Electronics & Telecommunications Engineering") echo "selected='selected'" ?> value="Electronics & Telecommunications Engineering">Electronics &amp; Telecommunications Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Information Technology") echo "selected='selected'" ?> value="Information Technology">Information Technology</option>				
								  <option <?php if(isset($dept)&&$dept=="Mechanical Engineering") echo "selected='selected'" ?> value="Mechanical Engineering">Mechanical Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Mining Engineering") echo "selected='selected'" ?> value="Mining Engineering">Mining Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Metallurgical Engineering") echo "selected='selected'" ?> value="Metallurgical Engineering">Metallurgical Engineering</option>			          
							</select><span id="dept_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_dept))echo $error_dept; ?>
						</li><br/>
						
						<li class="edit"><label for="thesis" class="edit">Title of the thesis</label>
						<textarea id="thesis" name="thesis" maxlength="200" rows="5" cols="35" placeholder="Use 0-9, a-z, A-Z, COMMA and SPACES only" class="edit" ><?php if(isset($thesis))echo $thesis; ?></textarea><span id="thesis_info"></span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_thesis))echo $error_thesis; ?>
						<em class="edit">(Only for PhD degree. Use 0-9, a-z, A-Z, COMMA and SPACES only)</em></li><br/>
						
						<script> t9=false; </script>
						
						<?php } 
							  else if(isset($studyprog)&&($studyprog=="B. Arch"||$studyprog=="MCA")){
							  		
						?>
						
						<li class="edit"><label for="dept" class="edit">Department</label>
				            <select id="dept" name="dept" required>
				            	  <option value=""></option>
						          <option <?php if(isset($dept)&&$dept=="Applied Geology") echo "selected='selected'" ?> value="Applied Geology">Applied Geology</option>
								  <option <?php if(isset($dept)&&$dept=="Applied Mechanics") echo "selected='selected'" ?> value="Applied Mechanics">Applied Mechanics</option>
								  <option <?php if(isset($dept)&&$dept=="Bio-Informatics") echo "selected='selected'" ?> value="Bio-Informatics">Bio-Informatics</option>
								  <option <?php if(isset($dept)&&$dept=="Bio Medical Engineering") echo "selected='selected'" ?> value="Bio Medical Engineering">Bio Medical Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Biotechnology Engineering") echo "selected='selected'" ?> value="Biotechnology Engineering">Biotechnology Engineering</option>
						  		  <option <?php if(isset($dept)&&$dept=="Chemical Engineering") echo "selected='selected'" ?> value="Chemical Engineering">Chemical Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Civil Engineering") echo "selected='selected'" ?> value="Civil Engineering">Civil Engineering</option>
						  		  <option <?php if(isset($dept)&&$dept=="Computer Science Engineering") echo "selected='selected'" ?> value="Computer Science Engineering">Computer Science Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Electrical Engineering") echo "selected='selected'" ?> value="Electrical Engineering">Electrical Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Electronics & Telecommunications Engineering") echo "selected='selected'" ?> value="Electronics & Telecommunications Engineering">Electronics &amp; Telecommunications Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Information Technology") echo "selected='selected'" ?> value="Information Technology">Information Technology</option>				
								  <option <?php if(isset($dept)&&$dept=="Mechanical Engineering") echo "selected='selected'" ?> value="Mechanical Engineering">Mechanical Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Mining Engineering") echo "selected='selected'" ?> value="Mining Engineering">Mining Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Metallurgical Engineering") echo "selected='selected'" ?> value="Metallurgical Engineering">Metallurgical Engineering</option>			          
							</select><span id="dept_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_dept))echo $error_dept; ?>
						</li><br/>
						
						<li class="edit"><label for="thesis" class="edit">Title of the thesis</label>
						<textarea id="thesis" name="thesis" maxlength="200" rows="5" cols="35" placeholder="Use 0-9, a-z, A-Z, COMMA and SPACES only" class="edit" ><?php if(isset($thesis))echo $thesis; ?></textarea><span id="thesis_info"></span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_thesis))echo $error_thesis; ?>
						<em class="edit">(Only for PhD degree. Use 0-9, a-z, A-Z, COMMA and SPACES only)</em></li><br/>
						
						<script> t8=false; t9=false; </script>
						
						<?php	  	
							  }
							  else{
						?>
						
						<li class="edit"><label for="dept" class="edit">Department</label>
				            <select id="dept" name="dept" required>
				            	  <option value=""></option>
						          <option <?php if(isset($dept)&&$dept=="Applied Geology") echo "selected='selected'" ?> value="Applied Geology">Applied Geology</option>
								  <option <?php if(isset($dept)&&$dept=="Applied Mechanics") echo "selected='selected'" ?> value="Applied Mechanics">Applied Mechanics</option>
								  <option <?php if(isset($dept)&&$dept=="Architecture") echo "selected='selected'" ?> value="Architecture">Architecture</option>						          
								  <option <?php if(isset($dept)&&$dept=="Bio-Informatics") echo "selected='selected'" ?> value="Bio-Informatics">Architecture</option>
								  <option <?php if(isset($dept)&&$dept=="Biotechnology") echo "selected='selected'" ?> value="Biotechnology">Biotechnology</option>
								  <option <?php if(isset($dept)&&$dept=="Chemical Engineering") echo "selected='selected'" ?> value="Chemical Engineering">Chemical Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Civil Engineering") echo "selected='selected'" ?> value="Civil Engineering">Civil Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Electrical Engineering") echo "selected='selected'" ?> value="Electrical Engineering">Electrical Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Mathematics") echo "selected='selected'" ?> value="Mathematics">Mathematics</option>				
								  <option <?php if(isset($dept)&&$dept=="Mechanical Engineering") echo "selected='selected'" ?> value="Mechanical Engineering">Mechanical Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Metallurgical & Materials Engineering") echo "selected='selected'" ?> value="Metallurgical & Materials Engineering">Metallurgical &#38; Materials Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Mining Engineering") echo "selected='selected'" ?> value="Mining Engineering">Mining Engineering</option>
								  <option <?php if(isset($dept)&&$dept=="Physics") echo "selected='selected'" ?> value="Physics">Physics</option>			          
				            </select><span id="dept_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_dept))echo $error_dept; ?>
						</li><br/>
						
						<li class="edit"><label for="thesis" class="edit">Title of the thesis</label>
						<textarea id="thesis" name="thesis" maxlength="200" rows="5" cols="35" placeholder="Use 0-9, a-z, A-Z, COMMA and SPACES only" class="edit" ><?php if(isset($thesis))echo $thesis; ?></textarea><span id="thesis_info"></span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_thesis))echo $error_thesis; ?>
						<em class="edit">(Only for PhD degree. Use 0-9, a-z, A-Z, COMMA and SPACES only)</em></li><br/>
							
						<?php	  									
							  }	
						?>			
						
						<li class="edit"><label for="address" class="edit">Current mailing address</label>
						<textarea id="address" name="address" maxlength="100" rows="5" cols="35" placeholder="No special characters except SPACES, COMMA, - and /" class="reg" required ><?php if(isset($address))echo $address; ?></textarea>
						<span id="address_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_address))echo $error_address; ?>
						<em class="edit">(For mailing intimation letter/degree. No special characters except SPACES, COMMA, - and /)</em></li><br/>
						 
						<li class="edit"><label for="pincode" class="edit">Pincode</label>
						<input type="text" id="pincode" name="pincode" maxlength="7" size="7" class="edit" required value="<?php if(isset($pincode))echo $pincode; ?>" />
						<span id="pincode_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_pincode))echo $error_pincode; ?>
						</li><br/>
						
						<li class="edit">
						<input type="checkbox" " id="check" name="check" value="check" class="lock">&nbsp;&nbsp;Update my details!</input>
						</li><br/>
												
						<li class="edit"><input type="submit" name="edit" id="edit_button" value="Update" on/><span id="edit_info"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($error_edit))echo $error_edit; ?>
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