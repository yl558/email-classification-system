<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Recover Username And Password</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div class="header">
  	<h2>Recover Username And Password</h2>
  </div>
	
  <form method="post" action="recover.php">
  	<?php include('errors.php'); ?>
    <div class="input-group">
      <label>Your Registered Email Address:</label>
      <input type="text" name="email" >
    </div>
  	<div class="input-group">
  	  <button type="submit" class="btn" name="recover">Submit</button>
  	</div>
    <p><?php echo $recover_msg;?></p>
  	<p>
      <a href="login.php">Go Back</a>
    </p>
  </form>
</body>
</html>