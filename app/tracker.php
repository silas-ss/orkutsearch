<?php
	require_once("../lib/conexao.inc.php");

	$url = \filter_input(INPUT_GET, 'url');

	if($url != ""){
		$conexao = getConnection();

		$strQuery = "UPDATE comunidades SET com_quantidadecliques = com_quantidadecliques + 1 WHERE com_url = :com_url";
		$update = $conexao->prepare($strQuery);
		$update->bindParam(":com_url", $url, PDO::PARAM_STR);
		$update->execute();

		header("Location: " . $url);
	}
?>