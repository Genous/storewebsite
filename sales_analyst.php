#!/usr/local/bin/php

<html>
<head>
<title>Sales Analyst</title>
<link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>

<?php
        session_start();
// do queries 
        if(!isset($_SESSION["user_name"]) || !isset($_SESSION["user_psw"]))
        {
                header('Location: login.php') ;
                exit();
        }

	echo "<button class=\"logout\" onclick=\"location.href = 'logout.php';\" id=\"myButton\" class=\"float-left submit-button\" >Logout</button>
	</h1> </div>";

        echo "<button class=\"option\" onclick=\"location.href = 'sales_analyst.php?query=1';\" id=\"quer1\" class=\"float-left submit-button\" >Query 1</button>
        </h1> </div>";

        echo "<button class=\"option\" onclick=\"location.href = 'sales_analyst.php?query=2';\" id=\"quer2\" class=\"float-left submit-button\" >Query 2</button>
        </h1> </div>";

	if(isset($_GET["query"]) && $_GET["query"] == 1)
	{
        $connection = oci_connect($username = 'kjessup', // username - dont change
                                      $password = 'd4taBas3r', // password - dont change
                                      $connection_string = '//oracle.cise.ufl.edu/orcl'); // database URL - dont change

	$statement = oci_parse($connection, 'select r1.itemID, r1.rescnt - p1.purcnt as stock from (select itemID, count(*) as purcnt from PURCHASEEVENT group by itemID) p1, (select itemID, sum(amountrestocked) + 100 as rescnt from RESTOCKEVENT group by ItemID) r1 where r1.itemID = p1.itemID order by itemID');
	oci_execute($statement);	
	

	echo "<div class=\"tableContainer\">";
	echo "<table style=\"width:70%\">";
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

        if(isset($_GET["query"]) && $_GET["query"] == 2)
        {
        $connection = oci_connect($username = 'kjessup', // username - dont change
                                      $password = 'd4taBas3r', // password - dont change
                                      $connection_string = '//oracle.cise.ufl.edu/orcl'); // database URL - dont change

        $statement =  oci_parse($connection, "select itemID, purcnt / (to_date('02-DEC-2016') - to_date('24-NOV-2016')) as rate from (select itemID, count(*) as purcnt from PURCHASEEVENT where (purchaseDate between to_date('24-NOV-2016') and to_date('02-DEC-2016')) group by itemID order by itemID)");
        oci_execute($statement);


        echo "<div class=\"tableContainer\">";
        echo "<table style=\"width:70%\">";
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

?>
</body>
</html>

