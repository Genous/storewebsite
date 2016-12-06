#!/usr/local/bin/php

<html>
<head>
<title>Sales Analyst</title>
<link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>

<?php
	session_start();

	if(!isset($_SESSION["user_name"]) || !isset($_SESSION["user_psw"]))
	{
		header('Location: login.php') ;
        exit();
    }
    
    echo " <div class =\"welcomemsg\"> <h1> Date Analyst </h1> </div>";
	echo "<button class=\"logout\" onclick=\"location.href = 'logout.php';\" id=\"myButton\" class=\"float-left submit-button\" >Logout</button> </div>";
    	
	echo "<table> <tr>";
	//All buttons
	
    echo "<td> <button class=\"option\" onclick=\"location.href = 'sales_analyst.php?query=1';\" id=\"quer1\" class=\"float-left submit-button\" >Get Current Stock</button> </div> </td>";
    
    echo "<td>
    		<form action=\"sales_analyst.php\" method=\"get\">

				<input type=\"hidden\" name=\"query\" value=\"2\">
				
				<label><b>Start Date</b></label>
				<input type=\"text\" placeholder=\"Enter Start Date\" name=\"q2_start_date\" required>

				<label><b>End Date</b></label>
				<input type=\"text\" placeholder=\"Enter End Date\" name=\"q2_end_date\" required>
				
				<select name=\"q2_department\">
					<option value=\"MEN\"> Men </option>
					<option value=\"WOMEN\"> Women </option>
					<option value=\"BOYS\"> Boys </option>
					<option value=\"GIRLS\"> Girls </option>
				</select>			

				<button class=\"option\" type=\"submit\">Get Daily Items Sell Rate</button>

			</form>
    	</td>";
	

	    echo "<td>
    		<form action=\"sales_analyst.php\" method=\"get\">

				<input type=\"hidden\" name=\"query\" value=\"3\">
				
				<label><b>Start Date</b></label>
				<input type=\"text\" placeholder=\"Enter Start Date\" name=\"q3_start_date\" required>

				<label><b>End Date</b></label>
				<input type=\"text\" placeholder=\"Enter End Date\" name=\"q3_end_date\" required>
				
				<select name=\"q3_department\">
					<option value=\"MEN\"> Men </option>
					<option value=\"WOMEN\"> Women </option>
					<option value=\"BOYS\"> Boys </option>
					<option value=\"GIRLS\"> Girls </option>
				</select>

				<select name=\"q3_rate\">
					<option value=\"1\"> Daily </option>
					<option value=\"7\"> Weekly </option>
					<option value=\"30\"> Monthly </option>
					<option value=\"365\"> Year </option>
				</select>
				
				<button class=\"option\" type=\"submit\">Get Employee Sell Rate</button>

			</form>
    	</td>";
	
	
	
	
	echo "</tr> </table>";
	
	
	
	if(isset($_GET["query"]) && $_GET["query"] == 1)
	{
        $connection = oci_connect($username = 'kjessup', // username - dont change
        $password = 'd4taBas3r', // password - dont change
        $connection_string = '//oracle.cise.ufl.edu/orcl'); // database URL - dont change
		$statement = oci_parse($connection, 'select r1.itemID, r1.rescnt - p1.purcnt as stock from (select itemID, count(*) as purcnt from PURCHASEEVENT group by itemID) p1, (select itemID, sum(amountrestocked) + 100 as rescnt from RESTOCKEVENT group by ItemID) r1 where r1.itemID = p1.itemID order by itemID');
		oci_execute($statement);	
	
		echo "<div class=\"tableContainer\">";
		echo "<table>";
		echo  "<div class=\"rowHeader\">
		   <tr>
    		<th>Item ID</th>
    		<th>Stock</th> 
  		  </tr> </div>";
  			
        while (($row = oci_fetch_array($statement, OCI_BOTH)) != false)
        {
	       	echo "<div class=\"row\"> <tr> <td>";
			echo $row[0]." </td><td> ".$row[1]." </td></tr></div>";
        }
		echo "</table> </div>";
        
		oci_free_statement($statement); // dont change
		oci_close($connection); // dont change
	}
    
    elseif(isset($_GET["query"]) && isset($_GET["q2_start_date"]) && isset($_GET["q2_end_date"]) && isset($_GET["q2_department"]) && $_GET["query"] == 2)
    {
    	$connection = oci_connect($username = 'kjessup', // username - dont change
		$password = 'd4taBas3r', // password - dont change
		$connection_string = '//oracle.cise.ufl.edu/orcl'); // database URL - dont change
		$statement =  oci_parse($connection, "select itemID, trunc(purcnt / (to_date(:endDate) - to_date(:startDate)), 3) as rate from (select it.itemID, count(pr.purchaseID) as purcnt from PURCHASEEVENT pr, ITEM it where (pr.purchaseDate between to_date(:startDate) and to_date(:endDate) and it.itemID = pr.itemID and it.department = :department) group by it.itemID order by it.itemID)");
		
		oci_bind_by_name($statement, ":startDate", $_GET["q2_start_date"]);
		oci_bind_by_name($statement, ":endDate", $_GET["q2_end_date"]);
		oci_bind_by_name($statement, ":department", $_GET["q2_department"]);
		
		oci_execute($statement);
		echo "<div class=\"tableContainer\">";
		echo "<table>";
		echo  "<div class=\"rowHeader\">
		   <tr>
			    <th>Item ID</th>
			    <th>Purchase Rate</th> 
		  </tr> </div>";
		while (($row = oci_fetch_array($statement, OCI_BOTH)) != false)
		{
		        echo "<div class=\"row\"> <tr> <td>";
		        echo $row[0]." </td><td> ".$row[1]." </td></tr></div>";
		}
		echo "</table> </div>";
		oci_free_statement($statement); // dont change
		oci_close($connection); // dont change
    }
   
    elseif(isset($_GET["query"]) && isset($_GET["q3_start_date"]) && isset($_GET["q3_end_date"]) && isset($_GET["q3_department"])&& isset($_GET["q3_rate"]) && $_GET["query"] == 3)
    {
    	$connection = oci_connect($username = 'kjessup', // username - dont change
		$password = 'd4taBas3r', // password - dont change
		$connection_string = '//oracle.cise.ufl.edu/orcl'); // database URL - dont change
		$statement =  oci_parse($connection, "select e1.employeeID, e2.salary, e2.department, e1.sellcnt, TRUNC(e1.sellrate, 2) as sellrate from (select employeeID, sellcnt, sellcnt / ((to_date(:endDate) - to_date(:startDate))/:rate) as sellrate from (select employeeID, count(*) as sellcnt from PURCHASEEVENT where (purchaseDate between to_date(:startDate) and to_date(:endDate))  group by employeeID)) e1, EMPLOYEE e2 where e1.employeeID = e2.employeeID and e2.department = :department order by department, employeeID");
		
		oci_bind_by_name($statement, ":startDate", $_GET["q3_start_date"]);
		oci_bind_by_name($statement, ":endDate", $_GET["q3_end_date"]);
		oci_bind_by_name($statement, ":department", $_GET["q3_department"]);
		oci_bind_by_name($statement, ":rate", $_GET["q3_rate"]);
		
		
		oci_execute($statement);
		echo "<div class=\"tableContainer\">";
		echo "<table>";
		echo  "<div class=\"rowHeader\">
		   <tr>
			    <th>Employee ID</th>
			    <th>Salary Level</th>
			    <th>Department</th> 
			    <th>Total Sales</th> 
			    <th>Rate</th> 
		  </tr> </div>";
		while (($row = oci_fetch_array($statement, OCI_BOTH)) != false)
		{
		        echo "<div class=\"row\"> <tr> <td>";
		        echo $row[0]." </td><td> ".$row[1]." </td><td> ".$row[2]." </td><td> ".$row[3]." </td><td> ".$row[4]." </td></tr></div>";
		}
		echo "</table> </div>";
		oci_free_statement($statement); // dont change
		oci_close($connection); // dont change
    }
    
?>
</body>
</html>
