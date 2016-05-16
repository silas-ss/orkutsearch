<?php
class Comunidade{
	private $nome;
	private $url;
	private $descricao;
	private $imagem;
	private $letra;

	public function set($atributo, $valor){
		$this->$atributo = $valor;
	}

	public function get($atributo){
		return $this->$atributo;
	}
}