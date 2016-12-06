#!/usr/local/bin/php

<html>

<head>
<title> A-Apperal </title>
<link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>

<?php
	session_start();
	
	if(!isset($_SESSION["user_name"]) || !isset($_SESSION["user_psw"]) || !isset($_SESSION["userId"]))
	{
		header('Location: login.php') ;
		exit();
	}
	
	$connection = oci_connect($username = 'kjessup', // username - dont change
		                      $password = 'd4taBas3r', // password - dont change
		                      $connection_string = '//oracle.cise.ufl.edu/orcl'); // database URL - dont change
	$x = $_SESSION['userId'];
	$statement = oci_parse($connection, 'SELECT * FROM EMPLOYEE WHERE EMPLOYEEID = :x'); // SQL query
	oci_bind_by_name($statement, ":x", $x);
	oci_execute($statement); // execute the query
	echo "<div class=\"welcomemsg\"> <h1> Welcome, ";
	while (($row = oci_fetch_array($statement, OCI_BOTH)) != false)
	{
		    echo $row[1]." ".$row[2]."";
	}
	echo "<button class=\"logout\" onclick=\"location.href = 'logout.php';\" id=\"myButton\" class=\"float-left submit-button\" >Logout</button>
	</h1> </div>";
	
	$statement = oci_parse($connection, 'SELECT EI.itemID, EI.itemType, EI.itemSize, EI.wholeSaleCost, NS.cnt FROM (select IT.itemID, IT.itemType, IT.itemSize, IT.wholeSaleCost FROM ITEM IT, EMPLOYEE EMP WHERE EMP.EMPLOYEEID = :x AND EMP.DEPARTMENT = IT.DEPARTMENT) EI, (select itemID, count(itemID) as cnt from PURCHASEEVENT WHERE employeeID = :x GROUP BY itemID ORDER BY itemID) NS where EI.itemID = NS.itemID');
	
	oci_bind_by_name($statement, ':x', $x);
	oci_execute($statement);
	
	echo "<div class=\"tableContainer\">";

	echo "<table style=\"width:70%\">";
	echo  "<div class=\"rowHeader\">
		   <tr>
    		<th>Item Id</th>
    		<th>Type</th> 
    		<th>Size</th>
    		<th>Wholesale price</th>
    		<th>Number Sold</th>
  		  </tr> </div>";
  			
        while (($row = oci_fetch_array($statement, OCI_BOTH)) != false)
        {
        	echo "<div class=\"row\"> <tr> <td>";
            echo $row[0]." </td><td> ".$row[1]." </td><td> ".$row[2]." </td><td> ".$row[3]." </td><td> ".$row[4]."</td> </tr> </div>";
        }
    echo "</table> </div>";
        
	oci_free_statement($statement); // dont change
	oci_close($connection); // dont change
?>

</body>

</html>

