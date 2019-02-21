<?php

print"Banking System ";

error_reporting(E_ERROR | E_WARNING| E_PARSE | E_NOTICE);
ini_set('display_errors',1);

include ("login.php");
include ("fff.php");

$db = mysqli_connect($hostname, $username, $password, $project);
if (mysqli_connect_errno())
  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  exit();
  }
  
print "<br><br>Successfully connected to MySQL.<br><br>";
mysqli_select_db($db, $project); 

$user = getdata("user"); 
$pass = getdata("pass");

//$service = $_GET["choice"];



if ( auth ($user, $pass) == false )
{ 
	exit ("Username and password does not match in Database"); 
}



//calling function depending on service choice with if statement

mysqli_close($db);
exit ("<br> Transaction Completed<br>");

?>