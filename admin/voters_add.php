<?php
	include 'includes/session.php';

	if(isset($_POST['add'])){
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
		$filename = $_FILES['photo']['name'];
		if(!empty($filename)){
			move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$filename);	
		}
		//generate voters id
		$set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$voter = substr(str_shuffle($set), 0, 15);

		$sql = "INSERT INTO voters (voters_id, password, firstname, lastname,email, photo) VALUES ('$voter', '$password', '$firstname', '$lastname', '$email', '$filename')";
		if($conn->query($sql)){
			// send reset link
			$password = $_POST['password'];
            $message="Hi $firstname.<br /><br />";
            $message.="You have been added into the E-Voting chapter: Your voter id is $voter and temporary password is $password. Change the password to a more secure one. <a href='www.localhost/EVOTING/index.php?'>Login</a>";
            $subj="Account Setup";
            $send_to_email=$_POST['email'];

                // send reset link
            $mailTo=   $send_to_email;
            $nameTo = 'User';
            $subject =  $subj;
            $body =  $message;
            
            include_once "./mail.php";
            if($mail->send()){
                $_SESSION['success'] = "Voter registered and email sent successfully.";
            }
            // status if unable to send email for password reset link
            else{
                $_SESSION['error'] = 'Voter registered successfully but we could not send email.';
                }
			
			
		}
		else{
			$_SESSION['error'] = $conn->error;
		}

	}
	else{
		$_SESSION['error'] = 'Fill up add form first';
	}

	header('location: voters.php');
?>