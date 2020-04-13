<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Change Username and Password</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div class="header">
  	<h2>Change Username and Password</h2>
  </div>
	
  <form method="post" action="change.php">
  	<?php include('errors.php'); ?>
    <div class="input-group">
      <label>New Username</label>
      <input type="text" name="username" >
    </div>
  	<div class="input-group">
  	  <label>New Password</label>
  	  <input type="password" name="password">
  	</div>
  	<div class="input-group">
  	  <button type="submit" class="btn" name="change">Submit</button>
  	</div>
  	<p>
  		<a href="index.php">Go Back</a>
  	</p>
  </form>
</body>
</html>