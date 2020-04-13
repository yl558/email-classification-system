<?php
session_start();

// initializing variables
$email = "";
$phone_number = "";
$first_name = "";
$middle_name = "";
$last_name = "";
$mail_address = "";
$occupation = "";
$username = "";
$password = "";
$errors = array(); 
$recover_msg = "";

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'email_cls');

// REGISTER USER
if (isset($_POST['reg_user'])) 
{
  // receive all input values from the form
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $phone_number = mysqli_real_escape_string($db, $_POST['phone_number']);
  $first_name = mysqli_real_escape_string($db, $_POST['first_name']);
  $middle_name = mysqli_real_escape_string($db, $_POST['middle_name']);
  $last_name = mysqli_real_escape_string($db, $_POST['last_name']);
  $mail_address = mysqli_real_escape_string($db, $_POST['mail_address']);
  $occupation = mysqli_real_escape_string($db, $_POST['occupation']);
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);
  
  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($phone_number)) { array_push($errors, "Phone number is required"); }
  if (empty($first_name)) { array_push($errors, "First name is required"); }
  if (empty($last_name)) { array_push($errors, "Last name is required"); }
  if (empty($mail_address)) { array_push($errors, "Mail Address is required"); }
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($password)) { array_push($errors, "Password is required"); }
  

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) 
  { // if user exists
    if ($user['username'] === $username) 
    {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) 
  {
  	#$password = md5($password);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (email, phone_number, first_name, middle_name, last_name, mail_address, occupation, username, password) 
  			  VALUES('$email', '$phone_number', '$first_name', '$middle_name', '$last_name', '$mail_address', '$occupation', '$username', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";

    $_SESSION['email'] = $email;
    $_SESSION['phone_number'] = $phone_number;
    $_SESSION['first_name'] = $first_name;
    $_SESSION['middle_name'] = $middle_name;
    $_SESSION['last_name'] = $last_name;
    $_SESSION['mail_address'] = $mail_address;
    $_SESSION['occupation'] = $occupation;
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;

  	header('location: index.php');
  }
}

// LOGIN USER
if (isset($_POST['login_user'])) 
{
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);


  if (empty($username)) 
  {
    array_push($errors, "Username is required");
  }
  if (empty($password)) 
  {
    array_push($errors, "Password is required");
  }

  if (count($errors) == 0) 
  {
    #$password = md5($password);
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $results = mysqli_query($db, $query);
    if (mysqli_num_rows($results) == 1) 
    {
      $_SESSION['username'] = $username;
      $_SESSION['success'] = "You are now logged in";

      while($row = $results->fetch_assoc()) {
        $_SESSION['email'] = $row['email'];
        $_SESSION['phone_number'] = $row['phone_number'];
        $_SESSION['first_name'] = $row['first_name'];
        $_SESSION['middle_name'] = $row['middle_name'];
        $_SESSION['last_name'] = $row['last_name'];
        $_SESSION['mail_address'] = $row['mail_address'];
        $_SESSION['occupation'] = $row['occupation'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['password'] = $row['password'];
      }


      header('location: index.php');
    }
    else 
    {
      array_push($errors, "Wrong username/password combination");
    }
  }
}

  // CHANGE USERNAME AND PASSWORD
if (isset($_POST['change'])) 
{
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
    array_push($errors, "Username is required");
  }
  if (empty($password)) {
    array_push($errors, "Password is required");
  }

  $old_username = $_SESSION['username'];
  if ($username != $old_username) 
  {
    $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);
  
    if ($user) 
    { // if user exists
      if ($user['username'] === $username) {
        array_push($errors, "Username already exists");
      }

      if ($user['email'] === $email) {
        array_push($errors, "email already exists");
      }
    }
  }


  if (count($errors) == 0) {
    #$password = md5($password);
    $query = "UPDATE users SET username='$username', password='$password' WHERE username='$old_username' ";

    $results = mysqli_query($db, $query);
    if ($results) {
      $_SESSION['username'] = $username;
      $_SESSION['success'] = "Welcome Back";

      header('location: index.php');
    }
    else 
    {
      array_push($errors, "Failed changing username and password");
    }
  }
}


if (isset($_POST['recover'])) {

  $email = mysqli_real_escape_string($db, $_POST['email']);

  if (empty($email)) {
    array_push($errors, "Email is required");
  }

  if (count($errors) == 0) {
    $user_check_query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) 
    { // if user exists
      $username = $user['username'];
      $password = $user['password'];
      $msg = "Your username is: ".$username.", and your passowrd is: ".$password;
      $recover_msg = "Please check your email for account recovery info.";
      mail($email,"Account Recovery",$msg);
    }
    else{
      array_push($errors, "email doesn't exist");
    }
  }

}


?>