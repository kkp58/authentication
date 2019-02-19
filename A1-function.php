<?php
function show ($user, $num, $mail_flag, &$output) {
	
	global $db;
	$a = "select * from T where user = '$user' ORDER BY date DESC LIMIT $num" ;
	$output .="SQL statement is $a<br>";                
	($t_n = mysqli_query($db, $a )) or die ( mysqli_error( $db ) );
	$num_2 = mysqli_num_rows ( $t_n ); 
	$output .="<br>There was $num_2 row retrievered <br>";
	
	
	$s = "select * from A where user = '$user'";
	( $t = mysqli_query($db, $s) ) or die ( mysqli_error( $db ) );
	$num = mysqli_num_rows ( $t );
	
	while ($r = mysqli_fetch_array ( $t, MYSQLI_ASSOC)) {
		$user = $r["user"];
		$emailid = $r["mail"];
		$pass = $r["pass"];
		$current = $r["current"];
		$recent_trans = $r["recent_trans"];
		$plainPass = $r["plainPass"];
		$output .= "<br>User is $user; ";
		$output .= "Hashed password is $pass; ";
		$output .= "Current balance is $current; ";
		$output .= "Password is $plainPass; ";
		$output .= "Recent transaction is $recent_trans; ";
		$output .= "Email is $emailid <br>";
	}
	
	while ( $m = mysqli_fetch_array ( $t_n, MYSQLI_ASSOC) ) {
		
		$type = $m["type"];
		$amount = $m["amount"];
		$date = $m["date"];
		$mail_receipt = $m["mail_receipt"];
		$output .= "<br>Type is $type  ";
		$output .= "Amount is $amount  ";
		$output .= "Date is $date  ";
		$output .= "Mail receipt type is $mail_receipt <br>";
	}
	
	
	echo $output;
	if($mail_flag == "Y")
	{
		mailer($emailid, $output);
	}
}

function auth ($user , $pass) {
	global $db;
    $pass = SHA1($pass);
	
	$s = "select * from A where user = '$user' and pass = '$pass'" ;
	$output = "<br>SQL statement is $s<br>";
	( $t = mysqli_query($db, $s) ) or die (mysqli_error($db));
	$num = mysqli_num_rows ( $t ); 
	if ($num == 0) { return false ;}
	return true;
}

function getdata ($name) {
	global $db;
	$temp = $_GET[$name];
	$user = mysqli_real_escape_string($db , $temp);
	$temp = trim($temp);
	return $temp;
}

function deposit($user, $amnt, $mail_flag, &$output) {
	global $db;
	$service = $_GET['choice'];
	
	$s = "insert into T values('$user', '$service', '$amnt', NOW(), '$mail_flag')";
	$output ="<br>SQL statement is $s<br>";                 
	( $t = mysqli_query($db, $s) ) or die ( mysqli_error( $db ) );
	
	
	$a = "update A SET current = current + '$amnt', recent_trans = NOW() where user = '$user'";
	$output .="<br>SQL statement is $a<br>"; 
	( $t1 = mysqli_query($db, $a) ) or die ( mysqli_error( $db ) );
	
	
	$s1 = "select * from A where user = '$user'";
	( $t_n = mysqli_query($db, $s1) ) or die ( mysqli_error( $db ) );
	$rows = mysqli_num_rows($t_n);
	
	
	while ($r = mysqli_fetch_array ( $t_n, MYSQLI_ASSOC)) {
		$user = $r["user"];
		$pass = $r["pass"];
		$current = $r["current"];
		$initial = $r["initial"];
		$emailid =  $r["mail"];
		$recent_trans = $r["recent_trans"];
		$plainPass = $r["plainPass"];
		$output .= "<br>Username is $user  ";
		$output .= "Hashed password is $pass  ";
		$output .= "Current balance is $current  ";
		$output .= "Initial balance is $initial  ";
		$output .= "Password is $plainPass  ";
		$output .= "Recent transaction is $recent_trans <br>";
	}
	
	echo $output;
	if($mail_flag == "Y")
	{
		mailer($emailid, $output);
	}
}

function withdraw($user, $amnt, $mail_flag, &$output) {
	
	global $db;
	$service = $_GET['choice'];
	
	$a = "select * from A where user = '$user'";
	( $t_n = mysqli_query($db, $a) ) or die ( mysqli_error( $db ) );
	$m = mysqli_fetch_array ( $t_n, MYSQLI_ASSOC);                   
	$current_balance = $m["current"];
	if ($amnt > $current_balance)                                           
	    {
		  echo "<br>Amount exceeds balance <br>";
		  return false;                                                          
		}
	$s1 = "insert into T values ('$user' , '$service', '$amnt', NOW(), '$mail_flag')";      
	$output ="<br> SQL statement is $s1";
	( $t1 = mysqli_query($db, $s1) ) or die ( mysqli_error( $db ) );
	
	
	$s2 = "update A SET current = current - '$amnt' where user = '$user'";    
	$output.="<br> SQL statement is $s2";
	( $t2 = mysqli_query($db, $s2) ) or die ( mysqli_error( $db ) );
	
	
	$s = "select * from A where user = '$user'";
	( $t = mysqli_query($db, $s) ) or die ( mysqli_error( $db ) );
	$rows = mysqli_num_rows($t);
	
	while ($r = mysqli_fetch_array ( $t, MYSQLI_ASSOC)) {
		$user = $r["user"];
		$pass = $r["pass"];
		$current = $r["current"];
		$initial = $r["initial"];
		$emailid =  $r["mail"];
		$recent_trans = $r["recent_trans"];
		$plainPass = $r["plainPass"];
		$output .= "<br>Username is $user  ";
		$output .= "Hashed password is $pass  ";
		$output .= "Current balance is $current  ";
		$output .= "Initial balance is $initial  ";
		$output .= "Password is $plainPass  ";
		$output .= "Recent transaction is $recent_trans <br>";
	}
	echo $output;
	if($mail_flag == "Y")
	{
		mailer($emailid, $output);
	}	
}

function mailer ($emailid , &$output) {             
	global $db;
	date_default_timezone_set('America/New_York');
	$to = $emailid;                                                                     
	$subj = "Hello" . "-" .date(DATE_RFC1123);            
	$message = $output;
	mail($to, $subj , $message);
}
?>
