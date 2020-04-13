<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Sign Up</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div class="header">
  	<h2>Sign Up</h2>
  </div>
	
  <form method="post" action="register.php">
  	<?php include('errors.php'); ?>
    <div class="input-group">
      <label>Email</label>
      <input type="text" name="email" value="<?php echo $email; ?>">
    </div>
    <div class="input-group">
      <label>Phone Number</label>
      <input type="text" name="phone_number" value="<?php echo $phone_number; ?>">
    </div>
    <div class="input-group">
      <label>First Name</label>
      <input type="text" name="first_name" value="<?php echo $first_name; ?>">
    </div>
    <div class="input-group">
      <label>Middle Name</label>
      <input type="text" name="middle_name" value="<?php echo $middle_name; ?>">
    </div>
    <div class="input-group">
      <label>Last Name</label>
      <input type="text" name="last_name" value="<?php echo $last_name; ?>">
    </div>
    <div class="input-group">
      <label>Mail Address</label>
      <input type="text" name="mail_address" value="<?php echo $mail_address; ?>">
    </div>
    <div class="input-group">
      <label>Occupation</label>
      <input type="text" name="occupation" value="<?php echo $occupation; ?>">
    </div>
    <div class="input-group">
      <label>Username</label>
      <input type="text" name="username" value="<?php echo $username; ?>">
    </div>
  	<div class="input-group">
  	  <label>Password</label>
  	  <input type="password" name="password">
  	</div>
  	<div class="input-group">
  	  <button type="submit" class="btn" name="reg_user">Submit</button>
  	</div>
  	<p>
  		Already a member? <a href="login.php">Sign in</a>
  	</p>
  </form>
</body>
</html>