<?php
include('../conexao.php');
include('../functions.php');
#header ('Content-type: text/html; charset=UTF-8');
#busca as disciplinas para preenchimento da tela de adição de novos cursos.
$busca_disciplina = $con->prepare("SELECT 
disciplina.id,
disciplina.nome,
turma_disciplina.cargaHoraria 
FROM 
disciplina,
curso_disciplina,
turma_disciplina,
turma 
where 
curso_disciplina.idCurso=:idCurso and 
disciplina.id=curso_disciplina.idDisciplina and
turma.id = turma_disciplina.idTurma AND
disciplina.id = turma_disciplina.idDisciplina and 
turma.id = :idTurma");
$busca_disciplina->execute(array(':idCurso' => $_REQUEST['idCurso'],':idTurma'=>$_REQUEST['idTurma']));
$resultadoDisc = $busca_disciplina->fetchAll(PDO::FETCH_ASSOC);

if($busca_disciplina->rowCount()!=0){
    $saida = '<table borde="1px" id="exibe_dados" width="100%">
    <th >Nome</th>
    <th >&nbsp;&nbsp;&nbsp;Carga Horaria </th>';
        foreach($resultadoDisc as $dado3){
                $saida.= "<tr><td>".utf8_encode($dado3['nome'])."&nbsp;</td><td><input type='text' value='".$dado3["cargaHoraria"]."' maxlength='60' size='12%' 
                id='".$dado3['id']."' name='disciplinas[".$dado3["id"]."]'></td></tr><th><label for='id_Disciplina'></th><td>";}
        foreach($resultadoDisc as $dadoId){
            $saida.= "<input type='hidden' name='id_Disciplina[".$id_Disciplina['id']."]' value='".$dadoId['id']."'></td>";
        }
        
    $saida.="</table>";
}else{
    $saida = utf8_encode("Não há disciplinas cadastradas para esse curso!");
   
}


echo $saida;