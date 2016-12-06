#!/usr/local/bin/php

<?php
	session_start();
	
	$connection = oci_connect($username = 'kjessup', // username - dont change
		                      $password = 'd4taBas3r', // password - dont change
		                      $connection_string = '//oracle.cise.ufl.edu/orcl'); // database URL - dont change
	$x = $_SESSION['userId'];
	$statement = oci_parse($connection, 'SELECT * FROM EMPLOYEE WHERE EMPLOYEEID = :x'); // SQL query
	oci_bind_by_name($statement, ":x", $x);
	oci_execute($statement); // execute the query
	echo "Welcome, ";
	while (($row = oci_fetch_array($statement, OCI_BOTH)) != false)
	{
		    echo $row[1]." ".$row[2]."<br>";
	}

	$statement = oci_parse($connection, 'select IT.itemID, IT.itemType, IT.itemSize, IT.wholeSaleCost FROM ITEM IT, EMPLOYEE EMP WHERE EMP.EMPLOYEEID = :x AND EMP.DEPARTMENT = IT.DEPARTMENT');

	oci_bind_by_name($statement, ':x', $x);
	oci_execute($statement);
	
	echo "you sell:<br>";

        while (($row = oci_fetch_array($statement, OCI_BOTH)) != false)
        {
                    echo $row[0]." ".$row[1]." ".$row[2]." ".$row[3]."<br>";
        }

	oci_free_statement($statement); // dont change
	oci_close($connection); // dont change
?>
