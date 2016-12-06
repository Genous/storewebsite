#!/usr/local/bin/php

<html>

<head>
<title>PHP Test</title>
</head>

<body>
<?php
	$user = $_POST["uname"];
	
	if($user == "manager")
	{
		echo "You are a manager!";
		$connection = oci_connect($username = 'kjessup',
                          $password = 'd4taBas3r',
                          $connection_string = '//oracle.cise.ufl.edu/orcl');
		$statement = oci_parse($connection, 'SELECT * FROM EMPLOYEE WHERE EMPLOYEEID = 45');
		oci_execute($statement);
		while (($row = oci_fetch_array($statement, OCI_BOTH)) != false)
		{
				echo $row[0]. " ".$row[1]. " ".$row[2]." ".$row[3]." ".$row[4]." ".$row[5]."<br>"; // print out the full tuple, add newline
		}

		oci_free_statement($statement); // dont change
		oci_close($connection); // dont change
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
		header( 'Location: login.php?login=failed' ) ;
	}

?>


</body>

</html>
