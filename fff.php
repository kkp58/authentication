<?php

function auth ($username , $password) {
	global $db;
    $pass = SHA1($pass);
	
	$s = "select * from testtable where username = '$username' and password = '$password'" ;
	print "<br>SQL statement is $s<br>";
	
	( $t = mysqli_query($db, $s) ) or die ( mysqli_error( $db ) );
	$num = mysqli_num_rows ( $t ); 
	print " <br>There was $num row retrieved <br>";
	
	
	if ($num == 0) { return false ;}
	return true;
}
function getdata ($name) {
	global $db;
	
	$temp = $_GET["username"];
	$temp_p=$_GET["password"];
	
	
	
	print "<br>User is: $temp";
	print "<br>Password is: $temp_p";

}
?>