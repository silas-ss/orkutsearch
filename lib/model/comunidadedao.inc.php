<?php
require_once("../lib/model/comunidade.inc.php");
require_once("../lib/conexao.inc.php");

class ComunidadeDAO{
	private $conexao;

	public function __construct(){
		$this->conexao = getConnection();
	}

	public function inserir($comunidade){
		$strQuery = "SELECT * FROM comunidades WHERE com_url = :com_url";
		$select = $this->conexao->prepare($strQuery);
		$url = $comunidade->get('url');
		$select->bindParam(":com_url", $url, PDO::PARAM_STR);
		$select->execute();

		$rows_affecteds = 0;		

		if(count($select->fetchAll()) == 0){
			$strQuery = "INSERT INTO comunidades VALUES(
							:com_url,
							:com_nome, 
							:com_descricao, 
							:com_imagem, 
							:com_letra,
							:com_quantidadecliques)";
			$insert = $this->conexao->prepare($strQuery);
			$insert->bindParam(":com_url", $comunidade->get('url'), PDO::PARAM_STR);
			$insert->bindParam(":com_nome", $comunidade->get('nome'), PDO::PARAM_STR);
			$insert->bindParam(":com_descricao", $comunidade->get('descricao'), PDO::PARAM_STR);
			$insert->bindParam(":com_imagem", $comunidade->get('imagem'), PDO::PARAM_STR);
			$insert->bindParam(":com_letra", $comunidade->get('letra'), PDO::PARAM_STR);
			$quantidade = 0;
			$insert->bindParam(":com_letra", $quantidade, PDO::PARAM_INT);
			$insert->execute();

			$rows_affecteds = $insert->rowCount();
		}

		return $rows_affecteds;
	}

	public function fullSearch($string_busca, $full = TRUE){
		$arr_comunidades = array();

		$strQuery = "SELECT * FROM comunidades WHERE MATCH(com_nome) AGAINST(:procurar IN NATURAL LANGUAGE MODE) ORDER BY com_quantidadecliques DESC";
		if($full){
			$strQuery = "SELECT * FROM comunidades WHERE MATCH(com_nome, com_descricao) AGAINST(:procurar IN NATURAL LANGUAGE MODE) ORDER BY com_quantidadecliques DESC";
		}
		
		$select = $this->conexao->prepare($strQuery);
		$select->bindParam(":procurar", $string_busca, PDO::PARAM_STR);
		$select->execute();

		$linhas = $select->fetchAll();
		foreach ($linhas as $linha) {
			$comunidade = new Comunidade();
			$comunidade->set('url', $linha['com_url']);
			$comunidade->set('nome', $linha['com_nome']);
			$comunidade->set('descricao', $linha['com_descricao']);
			$comunidade->set('imagem', $linha['com_imagem']);
			$comunidade->set('letra', $linha['com_letra']);

			array_push($arr_comunidades, $comunidade);
		}

		return $arr_comunidades;
	}
}