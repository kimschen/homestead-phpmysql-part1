<?php

	// Define a variable that store database connection using PDO (PHP Data Object) class
	$pdo = new PDO('mysql:host=homestead;dbname=ijdb;charset=utf8',
				   'ijdbuser', 'mypassword');

	// Set the PDO attribute that controls the error mode to the mode that throws exceptions
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
