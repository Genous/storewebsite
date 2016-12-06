#!/usr/local/bin/php

<html>

<head>
<title>Welcome To A-Apparel</title>
</head>

<body>
<?php
	session_start();
	
	$user = $_POST["user_name"];
	$psw = $_POST["user_psw"];
	
	if($user == 'user1000'  && $psw == '1000')
	{
		echo "You are a manager!";
	}
	elseif($user == 'user2000' && $psw == '2000')
	{
		echo "You are a data analyst";
	}
	elseif(strpos($user, 'user3') !== false  && $psw == '3000')
	{
		$_SESSION['userId'] = substr($user, 4) - 3000;
		header( 'Location: employee.php') ;
	}
	else
	{
		header( 'Location: login.php?login=failed' ) ;
	}
?>


</body>

</html>
