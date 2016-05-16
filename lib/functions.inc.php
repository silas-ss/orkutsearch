<?php
require_once("../lib/model/comunidade.inc.php");
require_once("../lib/model/comunidadedao.inc.php");

function getLinksPaginaInicial($url){
	$html = file_get_contents($url);
	$array_links = array();

	//Create a new DOM document
	$dom = new DOMDocument;

	//Parse the HTML. The @ is used to suppress any parsing errors
	//that will be thrown if the $html string isn't valid XHTML.
	@$dom->loadHTML($html);

	$nodes = getElementByClass($dom, 'indexLetters');
	

	//Iterate over the extracted links and display their URLs
	foreach ($nodes as $link){
		$arr = array(
			'letra' => $link->nodeValue,
			'url' => $link->getAttribute('href')
		);
	    
	    array_push($array_links, $arr);
	}

	return $array_links;
}

function getUrlPaginacaoDaListaDeComunidades($urlPrimeiraPagina, $dominio){
	$array_links = array($urlPrimeiraPagina);

	$urlProximaPagina = $urlPrimeiraPagina;
	
	while($urlProximaPagina != ""){
		$html = file_get_contents($urlProximaPagina);			
		$dom = new DOMDocument;

		@$dom->loadHTML($html);

		$nodes = getElementByClass($dom, 'paginationSeparator');
		$node = $nodes[2];
				
		if($node->tagName == "a"){
			$urlProximaPagina = $dominio . $node->getAttribute('href');
			array_push($array_links, $urlProximaPagina);
		}else{
			$urlProximaPagina = "";
		}		
	}

	return $array_links;	
}

function getElementByClass($dom, $classname){
	$finder = new DomXPath($dom);		
	$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

	return $nodes;
}

function getListDeComunidades($url, $letra){
	$html = file_get_contents($url);
	$array_elements = array();
	
	$dom = new DOMDocument;
	
	@$dom->loadHTML($html);

	$nodes = getElementByClass($dom, 'listCommunityContainerSeparator');
	$comunidadedao = new ComunidadeDAO();
		
	foreach ($nodes as $node){		
	    $arr_nodes = $node->childNodes;	    

	    $comunidade = new Comunidade();
	    $comunidade->set('nome', $node->nodeValue);
	    $comunidade->set('letra', $letra);
	    
    	$sub_nodes = $arr_nodes[0]->childNodes;

    	if(!empty($sub_nodes[0])){
    		$comunidade->set('url', $sub_nodes[0]->getAttribute('href'));
    	}

    	if(!empty($sub_nodes[0]->childNodes)){
    		$sub = $sub_nodes[0]->childNodes;
    		if(!empty($sub[0])){
    			$comunidade->set('imagem', $sub[0]->getAttribute('src'));
    		}
    	}

    	$comunidade->set('descricao', getDescricaoComunidade($comunidade->get('url')));


    	$rows = $comunidadedao->inserir($comunidade);

    	print_r($comunidade);
    	if($rows > 0){
    		echo "\033[32m Inserido com sucesso!!!\033[0m \n";
    	}else{
    		echo "\033[31m Ja existe!!!\033[0m \n";
    	}
    	

    	array_push($array_elements, $comunidade);
    	
	}

	return $array_elements;
}

function getDescricaoComunidade($url){
	$html = file_get_contents($url);
	$array_elements = array();
	$descricao = "";
	
	$dom = new DOMDocument;
	
	@$dom->loadHTML($html);

	$nodes = getElementByClass($dom, 'communityProfileSection');

	if(is_a($nodes[2], DOMNode)){
		$descricao = DOMinnerHTML($nodes[2]);
	}

	return $descricao;
}

function DOMinnerHTML(DOMNode $element){ 
    $innerHTML = ""; 
    $children  = $element->childNodes;

    foreach ($children as $child) 
    { 
        $innerHTML .= $element->ownerDocument->saveHTML($child);
    }

    return $innerHTML; 
}