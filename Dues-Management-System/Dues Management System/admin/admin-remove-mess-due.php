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
	if(isset($_GET['roll'])){
		$roll = mysqli_real_escape_string($connection,stripslashes($_GET['roll']));
		$query = mysqli_query($connection,"select * from student where roll='$roll'");
		if(mysqli_num_rows($query)==1){
			
			
			$query = mysqli_query($connection,"delete from `mess_due` where `roll_number`='$roll';");
			
			header("location:admin-profile.php?error=Deleted");
		}
		
	}
	else{
		header('location:index.php');
	}
?>