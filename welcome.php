#!/usr/local/bin/php

<html>

<head>
<title>PHP Test</title>
</head>

<body>
<?php
	$user = $_GET["uname"];
	
	if($user == "manager")
	{
		echo "You are a manager!";
	}
	elseif($user == "customer")
	{
		echo "You are a customer";
	}
	elseif($user == "employee")
	{
		echo "You are an employee";
	}
	else
	{
		echo "Wrong info";
	}

?>


</body>

</html>
