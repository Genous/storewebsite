#!/usr/local/bin/php

<?php
$connection = oci_connect($username = 'kjessup', // username - dont change
                          $password = 'd4taBas3r', // password - dont change
                          $connection_string = '//oracle.cise.ufl.edu/orcl'); // database URL - dont change
$x = 5;
$statement = oci_parse($connection, 'SELECT * FROM EMPLOYEE WHERE EMPLOYEEID = :x'); // SQL query
oci_bind_by_name($statement, ":x", $x);
oci_execute($statement); // execute the query

echo "Welcome, ";
while (($row = oci_fetch_array($statement, OCI_BOTH)) != false) // iterate through all the available tuples produced by the query, set it equal to row, an array where row[i] is the ith column of the current tuple
{
        echo $row[1]. " ".$row[2]."<br>"; // print out the full tuple, add newline
}
//
// VERY important to close Oracle Database Connections and free statements!
//
oci_free_statement($statement); // dont change
oci_close($connection); // dont change
