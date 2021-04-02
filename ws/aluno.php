<?php
	header('Content-Type: application/json');
    include('../conexao.php');
    include('../functions.php');

	$cpf = $_REQUEST['cpf'];
	
	
	// busca as informa&ccedil;ões para saber se o cnpj ja foi utilizado
	$buscarAluno=$con->prepare("SELECT id,nome,rm,cpf FROM aluno WHERE (cpf =:cpf) LIMIT 1");
	$buscarAluno->bindValue(":cpf",soNumero($cpf));
	$buscarAluno->execute();
		
		//verifica se a query de busca do usu&aacute;rio retornou algum resultado
	if ($buscarAluno->rowCount()>0) {
		$resultadoAluno = $buscarAluno->fetchAll(PDO::FETCH_ASSOC);
		print_r(json_encode($resultadoAluno));	
	} else{
		echo "0";
	}
	
	


	
?>