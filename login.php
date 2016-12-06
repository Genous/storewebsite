#!/usr/local/bin/php

<html>

<head>
<title> A-Apperal </title>
<link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>

<!-- you can use forms to pass information between webpages -->
<form action="temp.php" method="post"> <!-- The form goes to this page after you hit submit -->
  <div class="imgcontainer">
	<img src="img_avatar2.png" alt="Avatar" class="avatar">
  </div>

  <div class="container"> <!-- put stuff in div's to control their style; loop in the style.css file and see syntax for container-->
	<label><b>Username</b></label>
	<input type="text" placeholder="Enter Username" name="user_name" required>

	<label><b>Password</b></label>
	<input type="password" placeholder="Enter Password" name="user_psw" required>

	<button class="login" type="submit">Login</button>
	<?php
		if($_GET["login"] == "failed")
		{
			echo "<font color=\"red\"> Wrong username or password... </font>";
		}
	?>
  </div>

</form>

</body>

</html>
