#!/usr/local/bin/php

<?php
	session_start();
	
	$_SESSION["user_name"] = $_POST["user_name"];
	$_SESSION["user_psw"] = $_POST["user_psw"];
	
	header('Location: welcome.php') ;
	exit();
?>
