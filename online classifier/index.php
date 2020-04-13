<?php 
  session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }


  $class_label = "";

  // Check if image file is a actual image or fake image
  if(isset($_POST["upload"])) {
      $target_dir = "C:\\wamp64\\www\\email_cls\\uploads\\";
      $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
      move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
      $python = 'C:\\Users\\yl558\\AppData\\Local\\Programs\\Python\\Python37\\python.exe';
      $pyscript = "test_model.py";

      $cmd = "$python $pyscript $target_file";
      exec("$cmd", $output);
      $class_label =  $output[0];
  }

?>
<!DOCTYPE html>
<html>
<meta http-equiv="refresh" content="180;url=index.php?logout='1'" />
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="header">
	<h2>Home Page</h2>
</div>
<div class="content">
  	<!-- notification message -->
  	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>

    <!-- logged in user information -->
    <?php  if (isset($_SESSION['username'])) : ?>
    	<p>Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
      <p>Your Profile: </p>
      <p>Email: <?php echo $_SESSION['email'] ;?></p>
      <p>Phone Number: <?php echo $_SESSION['phone_number'] ;?></p>
      <p>First Name: <?php echo $_SESSION['first_name'] ;?></p>
      <p>Middle Name: <?php echo $_SESSION['middle_name'] ;?></p>
      <p>Last Name: <?php echo $_SESSION['last_name'] ;?></p>
      <p>Mail Address: <?php echo $_SESSION['mail_address'] ;?></p>
      <p>Occupation: <?php echo $_SESSION['occupation'] ;?></p>

      <br>
      <p>Run Email Classification Test</p>


      <form action="index.php" method="post" enctype="multipart/form-data">
        Select file to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Run" name="upload">
      </form>

      <p>Predicted class label is: <?php echo $class_label ;?></p>
      <br>
      <p> <a href="change.php?" style="color: blue;">Change Username and Password</a> </p>
      <p> <a href="index.php?logout='1'" style="color: red;">Sign Out</a> </p>
    	

    <?php endif ?>



</div>
		
</body>
</html>