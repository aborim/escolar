<?php
	header('Content-Type: application/json');
    include('../conexao.php');
    include('../functions.php');

	$cpf = $_REQUEST['cpf'];
	
	
	// busca as informa&ccedil;ões para saber se o cnpj ja foi utilizado
	$buscarResponsavel=$con->prepare("SELECT id,nome,grauParentesco,cpf FROM responsavel WHERE (cpf =:cpf) LIMIT 1");
	$buscarResponsavel->bindValue(":cpf",soNumero($cpf));
	$buscarResponsavel->execute();
		
		//verifica se a query de busca do usu&aacute;rio retornou algum resultado
	if ($buscarResponsavel->rowCount()>0) {
		$resultadoResponsavel = $buscarResponsavel->fetchAll(PDO::FETCH_ASSOC);
		print_r(json_encode($resultadoResponsavel));	
	} else{
		echo "0";
	}
	
	


	
?>