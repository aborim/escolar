<?php
	header('Content-Type: application/json');
    include('../conexao.php');
    include('../functions.php');

	$idAluno = $_REQUEST['idAluno'];
    $idClasse = $_REQUEST['idClasse'];
    $situacao = $_REQUEST['situacao'];
	
	
	// exclui o aluno da classe
	$alteraSituacao=$con->prepare("update classe_aluno set situacao=:situacao where idAluno =:idAluno and idClasse=:idClasse");
	$alteraSituacao->execute(array(
		':idAluno'=>$idAluno,
        ':idClasse'=>$idClasse,
        ':situacao'=>$situacao
	));
	
	$retorno = array("status"=>true,"message"=>"situacao alterada");
	echo json_encode($retorno);

	
	


	
?>