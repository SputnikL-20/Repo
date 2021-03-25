<?php	
	$host = 'localhost';
	$user = 'root';
	$pass = '';
	$db = 'testdb';
	$link = mysqli_connect($host, $user, $pass);
	mysqli_set_charset($link, 'utf8');
	if (!$link)
	{
		exit("ERROR");
	}
?>