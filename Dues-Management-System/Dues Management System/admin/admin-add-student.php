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
		$roll=mysqli_real_escape_string($connection,stripslashes($_POST['roll']));
		$pass=mysqli_real_escape_string($connection,stripslashes($_POST['password']));
		$pass1=mysqli_real_escape_string($connection,stripslashes($_POST['confirm-password']));
		$hostel=mysqli_real_escape_string($connection,stripslashes($_POST['hostel']));
		$email = mysqli_real_escape_string($connection,stripslashes($_POST['email']));
		if(strcmp($pass,$pass1)==0){
			$pass = encrypt($pass,ENCRYPTION_KEY);
			$query = mysqli_query($connection,"select * from student where roll='$roll'");
			if(mysqli_num_rows($query)==0){
				$query = mysqli_query($connection,"select * from email where email='$email'");
				if(mysqli_num_rows($query)==0){
					$keys = generateRandomString(10);
					$query = mysqli_query($connection,"insert into student (name,roll,password,hostel) values ('".$name."','".$roll."','".$pass."','".$hostel."')");
					//$query = mysql_query("insert into")
					$query = mysqli_query($connection,"insert into `email` (`roll`,`email`,`key`) values ('".$roll."','".$email."','".$keys."');");
					if($query){
						header('location:admin-student.php?error=none');
					}
					else{
						header('location:admin-student.php?error=none');
					}
				}
				else{
					header('location:admin-student.php?error=email');
				}
			}
			else{
				header('location:admin-student.php?error=duplicate');
			}
		}
		else{
			header('location:admin-student.php?error=match');
		}
	}
?>
