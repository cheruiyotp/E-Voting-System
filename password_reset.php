<?php
  	session_start();
    
    include 'includes/conn.php';

  	if(isset($_SESSION['admin'])){
    	header('location: admin/home.php');
  	}

    if(isset($_SESSION['voter'])){
      header('location: home.php');
    }

    $voter_id = isset($_GET['access']) ? $_GET['access'] : exit();

    if($_POST){
        

        $password = $_POST['password'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $sql = "UPDATE voters SET password='$password' WHERE voters_id='$voter_id'";

        if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Password updated successfully.<a href='index.php'>Login</a>";
        } else {
            $_SESSION['error'] = "Error updating record: " . $conn->error;
        }

    }
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition login-page">
<div class="login-box">
  	<div class="login-logo">
  		<b>UASU</b>
  	</div>
  
  	<div class="login-box-body">
    	<p class="login-box-msg">Provide your new password.</p>

    	<form action="password_reset.php?access=<?php echo $voter_id;?>" method="POST">
      		<div class="form-group has-feedback">
        		<input type="password" class="form-control" name="password" placeholder="New Password" required>
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