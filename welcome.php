#!/usr/local/bin/php

<html>

<head>
<title>PHP Test</title>
</head>

<body>
<?php
	session_start();
	
	
	$user = $_POST["uname"];
	$psw = $_POST["psw"];
	$userId = substr($user, 7);
	
	if(strpos($user, 'user100') !== false  && $psw == '1000')
	{
		echo "You are a manager!";
		$_SESSION['userId'] = $userId;
	}
	elseif(strpos($user, 'user200') !== false  && $psw == '2000')
	{
		echo "You are a data analyst";
		$_SESSION['userId'] = $userId;
	}
	elseif(strpos($user, 'user300') !== false  && $psw == '3000')
	{
		echo "You are an employee";
		$_SESSION['userId'] = $userId;
		header( 'Location: employee.php') ;
		
	}
	else
	{
		echo "Wrong info";
		header( 'Location: login.php?login=failed' ) ;
	}

?>


</body>

</html>
