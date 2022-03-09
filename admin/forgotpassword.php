<?php
  	session_start();
    
    include 'includes/conn.php';
	
  	if(isset($_SESSION['admin'])){
    	header('location: admin/home.php');
  	}

    if(isset($_SESSION['voter'])){
      header('location: home.php');
    }

    if($_POST){

        $email = $_POST['email'];
		
		$sql = "SELECT * FROM admin WHERE email = '$email'";
		$query = $conn->query($sql);

		if($query->num_rows < 1){
			$_SESSION['error'] = 'Cannot find voter with the email';
		}
		else{
            $row = $query->fetch_assoc();

            $voter_id = $row['id'];

             // send reset link
            $message="Hi there.<br /><br />";
            $message.="Please click the following link to reset your password: <a href='www.localhost/EVOTING/admin/password_reset.php?access=$voter_id'>Link</a>";
            $subj="Reset Password";
            $send_to_email=$_POST['email'];

                // send reset link
            $mailTo=   $send_to_email;
            $nameTo = 'User';
            $subject =  $subj;
            $body =  $message;
            
            include_once "./mail.php";
            if($mail->send()){
                $_SESSION['success'] = "Password reset link was sent to your email. Click that link to reset your password.";
            }
            // status if unable to send email for password reset link
            else{
                $_SESSION['error'] = 'Cannot send the password reset link. Try again.';
                }
			
		}

         

    }
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition login-page">
<div class="login-box">
  	<div class="login-logo">
  		<b>E-Voting System</b>
  	</div>
  
  	<div class="login-box-body">
    	<p class="login-box-msg">Provide your email to recover your password.</p>

    	<form action="forgotpassword.php" method="POST">
      		<div class="form-group has-feedback">
        		<input type="email" class="form-control" name="email" placeholder="Your Email" required>
        		<span class="glyphicon glyphicon-mail form-control-feedback"></span>
      		</div>
         
      		<div class="row">
    			<div class="col-xs-6">
          			<button type="submit" class="btn btn-primary btn-block btn-flat" name="reset_link">Reset Password</button>
        		</div>
      		</div>
    	</form>
  	</div>
  	<?php
  		if(isset($_SESSION['error'])){
  			echo "
  				<div class='callout callout-danger text-center mt20'>
			  		<p>".$_SESSION['error']."</p> 
			  	</div>
  			";
  			unset($_SESSION['error']);
  		}else if(isset($_SESSION['success'])){
            echo "
            <div class='callout callout-success text-center mt20'>
                <p>".$_SESSION['success']."</p> 
            </div>
        ";
        unset($_SESSION['success']);

          }
  	?>
</div>
	
<?php include 'includes/scripts.php' ?>
</body>
</html>