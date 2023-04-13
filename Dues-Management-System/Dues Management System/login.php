<?php
	include('db_connection.php');
	if(session_id()==""){
		session_start();
	}
	if(isset($_POST['submit'])){
		$roll = mysqli_real_escape_string($connection,stripslashes($_POST['roll']));
		$password = mysqli_real_escape_string($connection,stripslashes($_POST['password']));
		$enc_password = encrypt($password,ENCRYPTION_KEY);
		//echo $enc_password;
		//echo decrypt($enc_password,ENCRYPTION_KEY);
		$query = mysqli_query($connection,"select * from student where roll='$roll' and password='$enc_password'");
		if(mysqli_num_rows($query)==1)
		{
			$query1 = mysqli_query($connection,"select * from email where roll='$roll'");
			$row1 = mysqli_fetch_assoc($query1);
			$row = mysqli_fetch_assoc($query);
			$_SESSION['login_as']="student";
			$_SESSION['roll'] = $roll;
			$_SESSION['email'] = $row1['email'];
			$_SESSION['password'] = $enc_password;
			$_SESSION['name'] = $row['name'];
			$_SESSION['hostel'] = $row['hostel'];
			header('location:student-profile.php');
		}
		else if(mysqli_num_rows($query)==0){
			header('location:index.php?error=credential');
		}
	}
	else{
		header('location:index.php');
	}
?>