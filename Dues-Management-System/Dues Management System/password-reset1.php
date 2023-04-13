<?php
	include('db_connection.php');
	if(session_id()==""){
		session_start();
	}
	if(isset($_SESSION['login_as'])&&strcmp($_SESSION['login_as'],"student")==0){
    	header('location:student-profile.php');
  	}
	if(isset($_POST['submit'])){
		$pass = mysqli_real_escape_string(stripslashes($connection,$_POST['password']));
		$pass1 = mysqli_real_escape_string(stripslashes($connection,$_POST['confirm-password']));
		$roll = mysqli_real_escape_string(stripslashes($connection,$_POST['roll']));
		if(strcmp($pass,$pass1)==0){
			$pass = encrypt($pass,ENCRYPTION_KEY);
			$query = mysqli_query($connection,"update student set password='$pass' where roll='$roll'");
			if($query){
				$keys = generateRandomString(10);
      			$query = mysqli_query($connection,"update `email` set `key`='$keys' where `roll`='$roll';");
				header('location:index.php?error=noneReset');
			}
			else{
				header('location:index.php?error=connection');
			}
		}
		else{
			header('location:index.php?error=matchReset');
		}
	}
	else{
		header('location:index.php');
	}
?>