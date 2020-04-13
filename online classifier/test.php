<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "email_cls";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else {
	echo "db connected <br>";
}

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "username: " . $row["username"]. " - password: " . $row["password"]. "<br>";
    }
} else {
    echo "0 results";
}


$query = "INSERT INTO users (email, phone_number, first_name, middle_name, last_name, mail_address, username, password) 
  			  VALUES('$email', '$phone_number', '$first_name', '$middle_name', '$last_name', '$mail_address', '$username', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>