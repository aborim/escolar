<?php
    //inclui a conexï¿½o com o banco de dados e suas funï¿½ï¿½es
    include("conexao.php");
    include("functions.php");

    //busca as informaï¿½ï¿½es do aluno selecionado
    $buscar=$con->prepare("select * from turma where id=:idTurma");
    $buscar->bindValue(':idTurma',$idTurma);
    $buscar->execute();
    
    if ($buscar->rowCount()==0) {
        echo "Turma n&atilde;o encontrado!";
    } else {
        // Salva os dados encontados na variï¿½vel $resultado
        $resultado = $buscar->fetchAll(PDO::FETCH_ASSOC);
        
    }
    ?>
<div class="titulo_interna">
<i class="fa fa-graduation-cap" aria-hidden="true"></i>Acad&ecirc;mico
</div>
<div class="content_aluno">
    <div class="titulo">Exibindo Informa&ccedil;&otilde;es da Turma: <?= $resultado[0]['nome'];?></div>
    <div id="DadosTurma">
    
    <!-- Tabela demonstrativa dos dados do aluno -->
    <table width="80%" cellspacing="0" cellpadding="0" class="dadosAluno">
    <th colspan="8" align="center">Dados da Turma</th>
  <tr>
    
    <td class="tituloDado">Nome:</td>
    <td><?= $resultado[0]['nome'];?></td>
    <td class="tituloDado">DP:</td>
    <td><? if($resultado[0]['dp']==0){echo "Não";}else{echo "Sim";};?></td>
    <td class="tituloDado">Plano de Pagamento</td>
    <td ><?= $resultado[0]['plano'];?></td>
    </tr>
  <tr>
    <td class="tituloDado">Grau:</td>
    <td><?= $resultado[0]['grau'];?></td>
    <td class="tituloDado">Nivel</td>
    <td><?= $resultado[0]['nivel'];?></td>
    <td class="tituloDado" rowspan="6">CH Disciplinas:</td>
    <td rowspan="6">
      <?php
      $buscar=$con->prepare("SELECT disciplina.id,disciplina.nome,turma_disciplina.cargaHoraria FROM disciplina,turma_disciplina where turma_disciplina.idTurma=:idTurma and disciplina.id=turma_disciplina.idDisciplina");
      $buscar->execute(array(':idTurma'=>$idTurma));
      $resultadoDisc = $buscar->fetchAll(PDO::FETCH_ASSOC);
      foreach($resultadoDisc as $dado3){
        echo $dado3['nome']."&nbsp; $dado3[cargaHoraria] horas<br>";
      }
        ?> 
      
    </td>
  </tr>
  
</table>
<!-- fim da tabela demonstrativa-->

</div>
</div>