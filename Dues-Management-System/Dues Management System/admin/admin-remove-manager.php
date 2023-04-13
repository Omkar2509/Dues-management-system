<?php
	include('db_connection.php');
	if(session_id()==""){
		session_start();
	}
	if(!isset($_SESSION['login_as'])){
		header('location:index.php');
	}
	if(isset($_SESSION['login_as'])&&strcmp($_SESSION['login_as'],"admin")!=0){
		header('location:index.php');
	}
	if(isset($_GET['username'])){
		$username = mysqli_real_escape_string($connection,stripslashes($_GET['username']));
		$query = mysqli_query($connection,"select * from manager where username='$username'");
		if(mysqli_num_rows($query)==1){
			$query = mysqli_query($connection,"delete from admin_email where email='$username'");
			$query = mysqli_query($connection,"delete from manager where username='$username'");
			if($query){
				header("location:admin-manager.php?error=noneRemove");
			}
			else{
				header('location:admin-manager.php?error=connection');
			}
			
		}
		else{
			header('location:admin-manager.php?error=invalidRemove');
		}
	}
	else{
		header('location:index.php');
	}
?>