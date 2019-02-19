<?php
error_reporting(E_ERROR | E_WARNING| E_PARSE | E_NOTICE);
ini_set('display_errors',1);

include ( "A1-function.php");
include ( "login.php");

$db = mysqli_connect($hostname, $username, $password, $project);
if (mysqli_connect_errno())
  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  exit();
  }
print "Successfully connected to MySQL.<br><br>";
mysqli_select_db($db,$project ); 


$user = getdata("user");
$pass = getdata("pass");
$amnt = getdata("amnt");
$num = getdata("num"); 
$service = getdata("choice");               

if (auth ($user , $pass) == false) 
{  
	echo "False<br><br>";
	exit ("Bad credentials") ;                              
}      

$mail_receipt = "";
if ( isset( $_GET ["mail"] ) ) { $mail = true; }
	else { $mail = false; }                                   

$mail_flag = "";
if (!$mail) {                                                
	$mail_flag = "N";
}
else {
	$mail_flag = "Y";
}


if ($service == "D") {                            
	deposit($user, $amnt, $mail_flag, $output);              
}
else if ($service == "W") {
	withdraw($user, $amnt, $mail_flag, $output);
}
else if ($service == "S") {
	show ($user, $num, $mail_flag, $output);
}
else if ($service == "0") {
	echo("Sorry,You didn't select any service");
	exit;
}

mysqli_close($db);
exit ("<br>Completed. <br>");

?>