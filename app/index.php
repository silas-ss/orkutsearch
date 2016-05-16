<!DOCTYPE html>
<html>
<head>
	<title>orkutSearch</title>	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid">
	<div class="row" style="border-top:0px;">
		<div class="col-md-offset-1 col-md-10">
			<h3 style="border-top:0px;margin-top:0;"><a href="." title="orkutSearch"><img src="../assets/images/logo240x46.png" /></a></h3>
		</div>
	</div>
	<div class="row">
		<div class="col-md-offset-1 col-md-10">
<?php
	require_once("../lib/model/comunidadedao.inc.php");
	
	$procurar = \filter_input(INPUT_GET, 'procurar');
	$buscaCompleta = (\filter_input(INPUT_GET, 'full') == "on");
?>
 <form class="form-inline" role="form" action=".">
  	<div class="form-group">
    	<label for="procurar">Buscar por:</label>
    	<input type="text" class="form-control" name="procurar" size="50" value="<?php echo $procurar; ?>" />
  	</div>  	
  	<div class="checkbox">
    	<label><input type="checkbox" name="full" <?php if($buscaCompleta){ echo "checked"; } ?> /> Busca completa</label>
  	</div>
  	<button type="submit" class="btn btn-info">Buscar</button>
</form><br/>

<?php
	if($procurar != ""){
		$comunidadedao = new ComunidadeDAO();		

		$lista_comunidades = $comunidadedao->fullSearch($procurar, $buscaCompleta);

		if(count($lista_comunidades)){
?>
<p class="text-muted">Foram encontradas <?php echo count($lista_comunidades); ?> comunidades</p>
<ul class="media-list">		
<?php		
		foreach ($lista_comunidades as $comunidade) {			
			$link = "tracker.php?url=" . $comunidade->get('url');
			$nome = $comunidade->get('nome');
			$imagem = $comunidade->get('imagem');
			$descricao = strip_tags($comunidade->get('descricao'));
			$index = 0;
			foreach (split(" ", $procurar) as $p) {
				if(strlen($p) >= 3){
					$i = strpos($descricao, $p);
					if($i > $index){
						$index = $i;
					}
					
					$descricao = preg_replace("/\w*?$p\w*/i", "<b>$0</b>", $descricao);
				}
				
			}			
			
?>

    <li class="media">
        <a class="pull-left" target="_blank" href="<?php echo $link; ?>">
          <img class="media-object img-rounded"   src="<?php echo $imagem; ?>" width="50" height="50" />
        </a>
        <div class="media-body">
            <h4 class="media-heading"><a target="_blank" href="<?php echo $link; ?>"><?php echo $nome; ?></a></h4>
          	<p><?php echo $descricao; ?></p>
        </div>
    </li>

<?php
		}
?>
</ul>
<?php
	} else {
?>
<center><div class="alert alert-info">Não resultado na busca por <strong><?php echo $procurar; ?></strong></div></center>
<?php
	}
}	
?>	

		</div>
	</div>

	<div class="row">
		<div class="col-md-offset-1 col-md-10">
			<center style="margin:1%" class="text-muted">&copy; orkutSearch. Todos os direitos reservados.</center>
		</div>
	</div>
</div>
</body>
</html>