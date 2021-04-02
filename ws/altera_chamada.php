<?php
	header('Content-Type: application/json');
    include('../conexao.php');
    include('../functions.php');

	$idAluno = $_REQUEST['idAluno'];
    $idClasse = $_REQUEST['idClasse'];
    $chamada = $_REQUEST['chamada'];
	
	
	// altera a chamada do aluno da classe
	$alteraSituacao=$con->prepare("update classe_aluno set numeroChamada=:chamada where idAluno =:idAluno and idClasse=:idClasse");
	$alteraSituacao->execute(array(
		':idAluno'=>$idAluno,
        ':idClasse'=>$idClasse,
        ':chamada'=>$chamada
	));
	
    if($alteraSituacao){
        echo $idAluno, " <- Aluno " ,$idClasse, "<- Classe", $chamada , "<- Chamada";
        $retorno = array("status"=>true,"message"=>"chamada alterada");
        echo json_encode($retorno);
    }
?>