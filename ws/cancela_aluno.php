<?php
	header('Content-Type: application/json');
    include('../conexao.php');
    include('../functions.php');

	$idAluno = $_REQUEST['idAluno'];
	$idClasse = $_REQUEST['idClasse'];
	
	
	// exclui o aluno da classe
	$cancelaAluno=$con->prepare("DELETE from classe_aluno where idAluno =:idAluno and idClasse=:idClasse");
	$cancelaAluno->execute(array(
		':idAluno'=>$idAluno,
		':idClasse'=>$idClasse
	));
	
	$retorno = array("status"=>true,"message"=>"aluno removido da classe");
	echo json_encode($retorno);

	
	


	
?>