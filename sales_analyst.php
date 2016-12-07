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
    
    echo " <div class =\"welcomemsg\"> <h1> Data Analyst </h1> </div>";
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
    	
    	
	echo "<td> <button class=\"option\" onclick=\"location.href = 'sales_analyst.php?query=4';\" id=\"quer4\" class=\"float-left submit-button\" >Get Monthly Sales Comparision</button> </div> </td>";
	
    echo "<td>
		<form action=\"sales_analyst.php\" method=\"get\">
			<input type=\"hidden\" name=\"query\" value=\"5\">
			
			<label><b>Start Date</b></label>
			<input type=\"text\" placeholder=\"Enter Start Date\" name=\"q5_start_date\" required>
			<label><b>End Date</b></label>
			<input type=\"text\" placeholder=\"Enter End Date\" name=\"q5_end_date\" required>		
			<button class=\"option\" type=\"submit\">Get Total Marginal Profit</button>
		</form>
	</td>";
	
	echo "<td> <button class=\"option\" onclick=\"location.href = 'sales_analyst.php?query=6';\" id=\"quer6\" class=\"float-left submit-button\" >Get Size Comparision</button> </div> </td>";
	
    echo "<td> <button class=\"option\" onclick=\"location.href = 'sales_analyst.php?query=7';\" id=\"quer7\" class=\"float-left submit-button\" >Total Tuple Count</button> </div> </td>";

	echo "</tr></table>";
	
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
    elseif(isset($_GET["query"]) && $_GET["query"] == 4)
    {
    	$connection = oci_connect($username = 'kjessup', // username - dont change
		$password = 'd4taBas3r', // password - dont change
		$connection_string = '//oracle.cise.ufl.edu/orcl'); // database URL - dont change
		$statement =  oci_parse($connection, "select to_char(purchaseDate, 'mm-yyyy') month, count(*) from
(select pur.purchaseID, pur.employeeID, pur.itemID, pur.purchaseDate, ite.department 
from PURCHASEEVENT pur, ITEM ite where pur.itemID = ite.itemID) 
group by to_char(purchaseDate, 'mm-yyyy') order by 
CAST(SUBSTR(to_char(purchaseDate, 'mm-yyyy'), 4, 4) AS INT), CAST(SUBSTR(to_char(purchaseDate, 'mm-yyyy'), 1, 2) AS INT)");	
		
		oci_execute($statement);
		echo "<div class=\"tableContainer\">";
		echo "<table>";
		echo  "<div class=\"rowHeader\">
		   <tr>
			    <th>Month </th>
			    <th>Total Sales %</th>
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
    
    
    
    
    elseif(isset($_GET["query"]) && isset($_GET["q5_start_date"]) && isset($_GET["q5_end_date"]) && $_GET["query"] == 5)
    {
    	$connection = oci_connect($username = 'kjessup', // username - dont change
		$password = 'd4taBas3r', // password - dont change
		$connection_string = '//oracle.cise.ufl.edu/orcl'); // database URL - dont change
		$statement =  oci_parse($connection, "select itemID, sum(marginal) as totalmarginal from (select it.itemID, pur.price - it.wholesalecost as marginal from ITEM it, PURCHASEEVENT pur 
where it.itemid = pur.itemid and purchaseDate between to_date(:startDate) and to_date(:endDate)) group by itemID order by itemID");
		
		oci_bind_by_name($statement, ":startDate", $_GET["q5_start_date"]);
		oci_bind_by_name($statement, ":endDate", $_GET["q5_end_date"]);
		
		oci_execute($statement);
		echo "<div class=\"tableContainer\">";
		echo "<table>";
		echo  "<div class=\"rowHeader\">
		   <tr>
			    <th>Item ID</th>
			    <th>Marginal Profit</th> 
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
    
    
    
    elseif(isset($_GET["query"]) && $_GET["query"] == 6)
    {
    	$connection = oci_connect($username = 'kjessup', // username - dont change
		$password = 'd4taBas3r', // password - dont change
		$connection_string = '//oracle.cise.ufl.edu/orcl'); // database URL - dont change
		$statement =  oci_parse($connection, "select department, trunc(SMALL*100/(SMALL+MEDIUM+LARGE+XL+XXL),2) as SMALLPERCENT, trunc(MEDIUM*100/(SMALL+MEDIUM+LARGE+XL+XXL),2) as MEDIUMPERCENT, trunc(LARGE*100/(SMALL+MEDIUM+LARGE+XL+XXL),2) as LARGEPERCENT, trunc(XL*100/(SMALL+MEDIUM+LARGE+XL+XXL),2) as XLPERCENT, trunc(XXL*100/(SMALL+MEDIUM+LARGE+XL+XXL),2) as XXLPERCENT from
(select department, count(*) as SMALL from (select pur.purchaseID, pur.employeeID, pur.itemID, pur.purchaseDate, ite.itemSize, ite.department 
from PURCHASEEVENT pur, ITEM ite where pur.itemID = ite.itemID and ite.itemSize = 'SMALL') group by department)
natural join
(select department, count(*) as MEDIUM from (select pur.purchaseID, pur.employeeID, pur.itemID, pur.purchaseDate, ite.itemSize, ite.department 
from PURCHASEEVENT pur, ITEM ite where pur.itemID = ite.itemID and ite.itemSize = 'MEDIUM') group by department)
natural join
(select department, count(*) as LARGE from (select pur.purchaseID, pur.employeeID, pur.itemID, pur.purchaseDate, ite.itemSize, ite.department 
from PURCHASEEVENT pur, ITEM ite where pur.itemID = ite.itemID and ite.itemSize = 'LARGE') group by department)
natural join
(select department, count(*) as XL from (select pur.purchaseID, pur.employeeID, pur.itemID, pur.purchaseDate, ite.itemSize, ite.department 
from PURCHASEEVENT pur, ITEM ite where pur.itemID = ite.itemID and ite.itemSize = 'XL') group by department)
natural join
(select department, count(*) as XXL from (select pur.purchaseID, pur.employeeID, pur.itemID, pur.purchaseDate, ite.itemSize, ite.department 
from PURCHASEEVENT pur, ITEM ite where pur.itemID = ite.itemID and ite.itemSize = 'XXL') group by department)");
		
		oci_execute($statement);
		echo "<div class=\"tableContainer\">";
		echo "<table>";
		echo  "<div class=\"rowHeader\">
		   <tr>
			    <th>Department</th>
			    <th>Small %</th>
			    <th>Medium %</th> 
			    <th>Large %</th> 
			    <th>XL %</th>
			    <th>XXL %</th>
		  </tr> </div>";
		while (($row = oci_fetch_array($statement, OCI_BOTH)) != false)
		{
		        echo "<div class=\"row\"> <tr> <td>";
		        echo $row[0]." </td><td> ".$row[1]." </td><td> ".$row[2]." </td><td> ".$row[3]." </td><td> ".$row[4]." </td><td> ".$row[5]." </td></tr></div>";
		}
		echo "</table> </div>";
		oci_free_statement($statement); // dont change
		oci_close($connection); // dont change
    }
    elseif(isset($_GET["query"]) && $_GET["query"] == 7)
    {
        $connection = oci_connect($username = 'kjessup', // username - dont change
                $password = 'd4taBas3r', // password - dont change
                $connection_string = '//oracle.cise.ufl.edu/orcl'); // database URL - dont change
                $statement =  oci_parse($connection, "select c1 as itemno, c2 as empno, c3 as purno, c4 as resno, c1+c2+c3+c4 as tot from (select count(*) as c1 from ITEM),(select count(*) as c2 from EMPLOYEE),(select count(*) as c3 from PURCHASEEVENT),(select count(*) as c4 from RESTOCKEVENT)");

                oci_execute($statement);
                echo "<div class=\"tableContainer\">";
                echo "<table>";
                echo  "<div class=\"rowHeader\">
                   <tr>
                            <th>Item </th>
                            <th>Employee </th>
			    <th>PurchaseEvent </th>
			    <th>RestockEvent </th>
			    <th>Total Tuple Count </th>
                  </tr> </div>";
                while (($row = oci_fetch_array($statement, OCI_BOTH)) != false)
                {
                        echo "<div class=\"row\"> <tr> <td>";
                        echo $row[0]." </td><td> ".$row[1]." </td><td>  ".$row[2]." </td><td> ".$row[3]." </td><td> ".$row[4]." </td></tr></div>";
                }
                echo "</table> </div>";
                oci_free_statement($statement); // dont change
                oci_close($connection); // dont change
    } 
?>
</body>
</html>
