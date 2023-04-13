<?php
	include('db_connection.php');
	if(session_id()==""){
		session_start();
	}
	if(isset($_POST['submit'])){
		$user = mysqli_real_escape_string($connection,stripslashes($_POST['username']));
		$password = mysqli_real_escape_string($connection,stripslashes($_POST['password']));
		$as = mysqli_real_escape_string($connection,stripslashes($_POST['login_as']));
		$enc_password = encrypt($password,ENCRYPTION_KEY);
		//echo $enc_password;
		//echo decrypt($enc_password,ENCRYPTION_KEY);
		if(strcmp($as,"admin")==0){
			$query = mysqli_query($connection,"select * from admin where username='$user' and password='$enc_password'");
			if(mysqli_num_rows($query)==1)
			{
				$row = mysqli_fetch_assoc($query);
				$_SESSION['login_as']="admin";
				$_SESSION['username'] = $user;
				$_SESSION['password'] = $enc_password;
				$_SESSION['name'] = $row['name'];
				header('location:admin-profile.php');
			}
			else if(mysqli_num_rows($query)==0){
				header('location:index.php?error=credential');
			}
			//echo $enc_password;	
		}
		else{
			$query = mysqli_query($connection,"select * from manager where username='$user' and password='$enc_password'");
			if(mysqli_num_rows($query)==1)
			{
				$row = mysqli_fetch_assoc($query);
				$_SESSION['login_as'] = "manager";
				$_SESSION['username'] = $user;
				$_SESSION['password'] = $enc_password;
				$_SESSION['name'] = $row['name'];
				$_SESSION['hostel'] = $row['hostel'];
				$_SESSION['role'] = $row['role'];
				header('location:manager-profile.php');
			}
			else if(mysqli_num_rows($query)==0){
				header('location:index.php?error=credential');
			}
			//echo $enc_password;	
		}
	}
	else{
		header('location:index.php');
	}
?>