<?php
	function getConnection(){
		$pdo = new PDO('mysql:host=localhost;dbname=db_buscardororkut', 'root','toor');
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

		return $pdo;
	}
