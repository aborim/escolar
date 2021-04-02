<?php
include('../conexao.php');
include('../functions.php');
header ('Content-type: text/html; charset=UTF-8');

if($_POST['fun']!='ed'){

    $saida = '<table borde="1px" id="exibe_dados" width="100%" >';
    #busca as disciplinas
    $buscar=$con->prepare("SELECT disciplina.id, disciplina.codigo, disciplina.nome, turma_disciplina.cargaHoraria FROM disciplina,turma_disciplina where turma_disciplina.idTurma=:idTurma and disciplina.id=turma_disciplina.idDisciplina");
    $buscar->bindvalue(':idTurma',$_POST['idTurma']);
    $buscar->execute();
    $resultadoDisc = $buscar->fetchAll(PDO::FETCH_ASSOC); 

    $saida .="    
        <th >Nome</th>
        <th >Professor</th>
        <th >Carga Horaria</th>
        ";
        
        foreach($resultadoDisc as $disciplina){
        #busca todos os professores pra inserir em combobox pra seleção dentro da classe
        $buscaProfessor = $con->prepare("SELECT professor.id,professor.nome FROM professor,usuarios where professor.idUsuario = usuarios.id and usuarios.filial = :filial and usuarios.ativo=1");
        $buscaProfessor->bindValue(":filial",$_POST['filial']);
        $buscaProfessor->execute();
        $resultadoProf = $buscaProfessor->fetchAll(PDO::FETCH_ASSOC); 
        $mostraListraProfessor = "<select name='professor[]'>";
        foreach ($resultadoProf as $dadoProfessor) {
            $mostraListraProfessor .= "<option value='".$dadoProfessor['id'].":".$disciplina['id']."'>".$dadoProfessor['nome']."</option>";
        }
        $mostraListraProfessor .= "</select>";


        $saida .= "<tr>
                <td style='font-size: 10px;'>".utf8_encode($disciplina['nome'])."&nbsp; </td>
                <td style='font-size: 10px;'>".utf8_encode($mostraListraProfessor)."</td>
                <td class='tituloDado2' style='font-size: 10px;'>
                <input type='text' value='' maxlength='60' size='22%' name='CH[".$disciplina['id']."]'> </td>
                </tr>"; 
        } 
    $saida .='
    <th colspan="3"><label for="id_Professor"></th>
    <td>
    </table>';
    /* ****************************************************************************************
    Após toda saída montada retorna para a página com o resultado*/


}else{
    $saida = '<table borde="1px" id="exibe_dados" width="100%" >';
    #busca as disciplinas
    $buscaProfessor = $con->prepare("SELECT 
    classe_disciplina_professor.idClasse as idClasse,
      disciplina.id as idDisciplina,
      disciplina.nome as nomeDisciplina,
      professor.id as idProf,
      professor.nome as profNome,
      classe_disciplina_professor.cargaHoraria as CH
      
  FROM
      classe_disciplina_professor,
      disciplina,
      professor,
      usuarios,
      turma_disciplina
  where 
    professor.idUsuario = usuarios.id AND
      usuarios.filial = :filial AND
      usuarios.ativo = 1 and 
      classe_disciplina_professor.idClasse=:idClasse and 
      disciplina.id=classe_disciplina_professor.idDisciplina and 
      professor.id=classe_disciplina_professor.idProfessor and
      turma_disciplina.idTurma=:idTurma and disciplina.id=turma_disciplina.idDisciplina");
    
    $buscaProfessor->execute(array(
      ':filial'=>$_POST['filial'],
      ':idClasse'=>$_POST['idClasse'],
      ':idTurma'=>$_POST['idTurma']
    ));
    $resultadoDisc = $buscaProfessor->fetchAll(PDO::FETCH_ASSOC); 
    
    $saida.='
    <table borde="1px" id="exibe_dados" width="100%" >
    <th >Nome</th>
    <th >Professor</th>
    <th >Carga Horaria</th>';
    
    foreach($resultadoDisc as $dadoDiscProf){
      #busca todos os professores pra inserir em combobox pra seleção dentro da classe
      $buscaProfessor = $con->prepare("SELECT professor.id,professor.nome FROM professor,usuarios where professor.idUsuario = usuarios.id and usuarios.filial = :filial and usuarios.ativo=1");
      $buscaProfessor->bindValue(":filial",$_POST['filial']);
      $buscaProfessor->execute();
      $resultadoProf = $buscaProfessor->fetchAll(PDO::FETCH_ASSOC); 
      
      $mostraListraProfessor = "<select name='professor[]'>";
      foreach ($resultadoProf as $dadoProfessor) {
        if($dadoProfessor['id']==$dadoDiscProf['idProf']){$selecionado = " selected";}else{$selecionado = " ";}
        $mostraListraProfessor .= "<option value='".$dadoProfessor['id'].":".$dadoDiscProf['idDisciplina']."'".$selecionado.">".$dadoProfessor['nome']."</option>";
      }
      $mostraListraProfessor .= "</select>";
      
      $saida .= "<tr>
              <td style='font-size: 10px;'>".utf8_encode($dadoDiscProf['nomeDisciplina'])."&nbsp; </td>
              <td style='font-size: 10px;'>".utf8_encode($mostraListraProfessor)."</td>
              <td class='tituloDado2' style='font-size: 10px;'>
              <input type='text' value='".$dadoDiscProf['CH']."' maxlength='60' size='22%' name='CH[".$dadoDiscProf['idDisciplina']."]'> </td>
            </tr>";
        } 
    $saida .='<th colspan="3"><label for="id_Professor"></th>
            <td>
            </table>'; 
    
    }
    
    echo $saida;
