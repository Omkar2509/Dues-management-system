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
	if(isset($_POST['submit'])){
		$roll = mysqli_real_escape_string($connection,stripslashes($_POST['roll']));
		$amount = mysqli_real_escape_string($connection,stripslashes($_POST['amount']));
		$reason = mysqli_real_escape_string($connection,stripslashes($_POST['reason']));
		$student_query = mysqli_query($connection,"select * from student where roll='$roll'");
	    if(mysqli_num_rows($student_query)!=1){
			header('location:admin-profile-other.php?error=roll');
		}
		else{
			$query = mysqli_query($connection,"select * from other_due where roll_number='$roll'");
			if(mysqli_num_rows($query)==0){
				$query = mysqli_query($connection,"insert into other_due (roll_number,due_amount,added_by,added_on,reason) values ('".$roll."','".$amount."','".$_SESSION['name']."',NOW(),'".$reason."')");
				if($query){
					header('location:admin-profile-other.php?error=none');
				}
				else{
					header('location:admin-profile-other.php?error=connection');
				}
			}
			else{
				$query = mysqli_query($connection,"update other_due set due_amount=due_amount+'$amount', added_by='".$_SESSION['name']."', added_on=NOW(), reason='$reason' where roll_number='$roll'");
				if($query){
					header('location:admin-profile-other.php?error=none');
				}
				else{
					header('location:admin-profile-other.php?error=connection');
				}
			}
		}
	}
	else{
		header('location:index.php');
	}
?>