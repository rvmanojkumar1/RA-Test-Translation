<?php
	
	include_once 'database.php';

	session_start();

	$company=$_SESSION['username'];

	$_SESSION["username"]=$company;
?>