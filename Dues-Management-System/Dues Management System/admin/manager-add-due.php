<?php
	include('db_connection.php');
	if(session_id()==""){
		session_start();
	}
	if(!isset($_SESSION['login_as'])){
		header('location:index.php');
	}
	if(isset($_SESSION['login_as'])&&strcmp($_SESSION['login_as'],"manager")!=0){
		header('location:index.php');
	}
	if(isset($_POST['submit'])){
		$roll = mysqli_real_escape_string($connection,stripslashes($_POST['roll']));
		$amount = mysqli_real_escape_string($connection,stripslashes($_POST['amount']));
		$reason = mysqli_real_escape_string($connection,stripslashes($_POST['reason']));
		$student_query = mysqli_query($connection,"select * from student where roll='$roll'");

		if(mysqli_num_rows($student_query)!=1){
			header('location:manager-profile.php?error=roll');
		}
		else{
			$student_row = mysqli_fetch_assoc($student_query);
			if(strcasecmp($_SESSION['role'],"other")==0){
				$query = mysqli_query($connection,"select * from other_due where roll_number='$roll'");
				if(mysqli_num_rows($query)==0){
					$query = mysqli_query($connection,"insert into other_due (roll_number,due_amount,added_by,added_on,reason) values ('".$roll."','".$amount."','".$_SESSION['name']."',NOW(),'".$reason."')");
					if($query){
						header('location:manager-profile.php?error=none');
					}
					else{
						header('location:manager-profile.php?error=connection');
					}
				}
				else{
					$query = mysqli_query($connection,"update other_due set due_amount=due_amount+'$amount', added_by='".$_SESSION['name']."', added_on=NOW(), reason='$reason' where roll_number='$roll'");
					if($query){
						header('location:manager-profile.php?error=none');
					}
					else{
						header('location:manager-profile.php?error=connection');
					}
				}
			}
			else if(strcasecmp($_SESSION['role'],"mess")==0){
				if(strcasecmp($_SESSION['hostel'],$student_row['hostel'])==0){
					$query = mysqli_query($connection,"select * from mess_due where roll_number='$roll'");
					if(mysqli_num_rows($query)==0){
						$query = mysqli_query($connection,"insert into mess_due (roll_number,due_amount,added_by,added_on,reason) values ('".$roll."','".$amount."','".$_SESSION['name']."',NOW(),'".$reason."')");
						if($query){
							header('location:manager-profile.php?error=none');
						}
						else{
							header('location:manager-profile.php?error=connection');
						}
					}
					else{
						$query = mysqli_query($connection,"update mess_due set due_amount=due_amount+'$amount', added_by='".$_SESSION['name']."', added_on=NOW(), reason='$reason' where roll_number='$roll'");
						if($query){
							header('location:manager-profile.php?error=none');
						}
						else{
							header('location:manager-profile.php?error=connection');
						}
					}		
				}
				else{
					header('location:manager-profile.php?error=hostel');
				}
			}
			else{
				if(strcasecmp($_SESSION['hostel'],$student_row['hostel'])==0){
					$query = mysqli_query($connection,"select * from hostel_due where roll_number='$roll'");
					if(mysqli_num_rows($query)==0){
						$query = mysqli_query($connection,"insert into hostel_due (roll_number,due_amount,added_by,added_on,reason) values ('".$roll."','".$amount."','".$_SESSION['name']."',NOW(),'".$reason."')");
						if($query){
							header('location:manager-profile.php?error=none');
						}
						else{
							header('location:manager-profile.php?error=connection');
						}
					}
					else{
						$query = mysqli_query($connection,"update hostel_due set due_amount=due_amount+'$amount', added_by='".$_SESSION['name']."', added_on=NOW(), reason='$reason' where roll_number='$roll'");
						if($query){
							header('location:manager-profile.php?error=none');
						}
						else{
							header('location:manager-profile.php?error=connection');
						}
					}		
				}
				else{
					header('location:manager-profile.php?error=hostel');
				}
			}
		}
	}
	else{
		header('location:index.php');
	}
?>