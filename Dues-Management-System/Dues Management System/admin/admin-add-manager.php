<?php
	include('db_connection.php');
	if(session_id()==""){
		session_start();
	}
	if(!isset($_SESSION['login_as'])){
		header('location:index.php');
	}
	else if(isset($_SESSION['login_as'])&&strcmp($_SESSION['login_as'],"admin")!=0){
		header('location:index.php');
	}
	if(isset($_POST['submit'])){
		$name=mysqli_real_escape_string($connection,stripslashes($_POST['name']));
		$username=mysqli_real_escape_string($connection,stripslashes($_POST['username']));
		$pass=mysqli_real_escape_string($connection,stripslashes($_POST['password']));
		$pass1=mysqli_real_escape_string($connection,stripslashes($_POST['confirm-password']));
		$hostel=mysqli_real_escape_string($connection,stripslashes($_POST['hostel']));
		$role=mysqli_real_escape_string($connection,stripslashes($_POST['role']));
		if(strcmp($pass,$pass1)==0){
			$pass = encrypt($pass,ENCRYPTION_KEY);
			$query = mysqli_query($connection,"select * from manager where username='$username'");
			if(mysqli_num_rows($query)==0){
				if(strcmp($role,"other")==0){
					$hostel = "none";
				}
				$keys = generateRandomString(10);

				$query = mysqli_query($connection,"insert into manager (name,username,password,role,hostel) values ('".$name."','".$username."','".$pass."','".$role."','".$hostel."')");
				$query = mysqli_query($connection,"insert into `admin_email` (`email`,`key`) values ('".$username."','".$keys."');");
				if($query){
					confirmMail($username.'@iitg.ernet.in');
					header('location:admin-manager.php?error=none');
				}
				else{
					header('location:admin-manager.php?error=connection');
				}
			}
			else{
				header('location:admin-manager.php?error=duplicate');
			}
		}
		else{
			header('location:admin-manager.php?error=match');
		}
	}
?>
