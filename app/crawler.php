<?php
	require_once("../lib/functions.inc.php");

	//Buscar os links das comunidades orkut.google.com
	$domain = "http://orkut.google.com/";
	$seekUrl = $domain;

	$links_pagina_inicial = getLinksPaginaInicial($seekUrl);

	foreach ($links_pagina_inicial as $item) {
		$letra = $item['letra'];
		$urlListaComunidades = $domain . $item['url'];

		$lista_paginacao_comunidades = getUrlPaginacaoDaListaDeComunidades($urlListaComunidades, $domain);
		
		
		foreach ($lista_paginacao_comunidades as $paginaComunidae) {
			$lista_comunidades = getListDeComunidades($paginaComunidae, $letra);			
		}
	}
?>