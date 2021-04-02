<div class="titulo_interna">
    <i class="fa fa-graduation-cap" aria-hidden="true"></i>Acad&ecirc;mico
</div>
<div class="content_aluno">
    <div class="titulo">Exibindo Informa&ccedil;&otilde;es do Classe: <?= $nomeClasse;?></div>
    <div id="DadosClasse">
    <?php
    //inclui a conex�o com o banco de dados e suas fun��es
    include("conexao.php");
    include("functions.php");

    //busca as informa��es do aluno selecionado
    $buscar=$con->prepare("select * from classe where classe.id=:idClasse");
    $buscar->bindValue(":idClasse",$idClasse);
    $buscar->execute();
    
    if ($buscar->rowCount()!=1) {
        echo "Classe n&atilde;o encontrada!";
    } else {
        // Salva os dados encontados na vari�vel $resultado
        $resultado = $buscar->fetchAll(PDO::FETCH_ASSOC);
        
    }
    ?>
    <!-- Tabela demonstrativa dos dados do aluno -->
    <table width="80%" cellspacing="0" cellpadding="0" class="dadosAluno">
    <th colspan="8" align="center">Dados do Classe</th>
  <tr>
    
    <td class="tituloDado">Nome:</td>
    <td><?= $resultado[0]['nome'];?></td>
    <td class="tituloDado">Turma:</td>
    <td><? $buscaTurma = $con ->prepare("SELECT id, nome from turma where id = :idTurma");
            $buscaTurma->bindValue(':idTurma', $resultado[0]['idTurma']);
            $buscaTurma->execute();
            $resTurma = $buscaTurma->fetchAll(PDO::FETCH_ASSOC);
    echo($resTurma[0]['nome']);?></td>
    <td class="tituloDado">Plano de Pagamento</td>
    <td ><?$buscaPlano = $con ->prepare("SELECT id, plano from planos where id = :id_plano");
            $buscaPlano->bindValue(':id_plano', $resultado[0]['id_plano']);
            $buscaPlano->execute();
            $resPlano = $buscaPlano->fetchAll(PDO::FETCH_ASSOC);
    echo($resPlano[0]['plano']);?></td>
    </tr>
  <tr>
    <td class="tituloDado">Ano Vigente:</td>
    <td><?= $resultado[0]['anoVigente'];?></td>
    <td class="tituloDado">Periodo Letivo:</td>
    <td><?= $resultado[0]['periodoLetivo'];?></td>
    <td class="tituloDado">Periodo:</td>
    <td ><? 
    
    switch ($resultado[0]['periodo']) {
      case '0':
        $periodoLetivo = "Matutino";
        break;
      case '1':
        $periodoLetivo = "Vespertino";
        break;
      case '2':
        $periodoLetivo = "Noturno";
        break;
      
      default:
        # code...
        break;
    }
      echo($periodoLetivo);
        ?></td>
  </tr>
  <tr>
    <td class="tituloDado">Situa&ccedil;&atilde;o </td>
    <td><? 
    switch ($resultado[0]['situacao']) {
      case '0':
          $situacao = "Provis&oacute;ria";
          break;
      case '1':
          $situacao = "Definida";
          break;
      case '2':
          $situacao = "Conclu&iacute;da";
          break;
      case '3':
          $situacao = "Desativada";
          break;
                      
      default:
          # code...
          break;
  }
    
        echo($situacao);?></td>
    <td class="tituloDado"></td>
    <td><??></td>
    <td class="tituloDado">&nbsp;</td>
    <td>&nbsp;</td>
    
    </tr>
  <form method="post" action="#" enctype="multipart/form-data" id="frmAluno">
  <tr>
    <td class="tituloDado">Alunos:</td>
    <td colspan="5"><table border="0" cellpadding="0" cellspacing = "0" width="100%">
        <tr>
            <th>N&#176; Chamada</th>
            <th>RM</th>
            <th>Nome</th>
            <th>Matr&iacute;cula</th>
        </tr>
     
    <? $i=0;
            
            $buscaAluno = $con ->prepare("SELECT aluno.nome, aluno.rm, classe_aluno.dtMatricula, classe_aluno.numeroChamada, classe_aluno.idAluno, classe_aluno.idClasse 
            from aluno, classe_aluno 
            where aluno.id = classe_aluno.idAluno and idClasse = :idClasse  order by aluno.nome");
            $buscaAluno->bindValue(':idClasse', $idClasse);
            $buscaAluno->execute();
            $resAluno = $buscaAluno->fetchAll(PDO::FETCH_ASSOC);
            
              foreach($resAluno as $dado){
               
                if($dado['dtMatricula']==0){
                  $matricula= "Aluno nao matriculado!";
                  }else{
                  $matricula = "Aluno matriculado em ".formatoData($dado['dtMatricula']);
                  }?>
                  <tr>
                    <td align="center"><?=$dado['numeroChamada'] ?></td>
                    <td><?=$dado['rm'] ?></td>
                    <td><?=$dado['nome'] ?></td>
                    <td><?=$matricula ?></td>
                  </tr>
                <? 
            } 
            ?> </table></td>
            <tr>
            <td class="tituloDado"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <!--<td align="right" cowspan="5">
            <input type="submit" class="form_buttom" id="ordem" value="Ordem Alfabetica" name="ordem">
            <input type="hidden" name="ordena" id="ordena" value="1"></td>--></tr>
  </tr></form>
    <tr><td class="tituloDado">Professores:</td>
    <td colspan="5"><? 
            $buscaProf = $con ->prepare("SELECT 
              disciplina.nome as nomeDisciplina, 
              professor.nome as profNome, 
              classe_disciplina_professor.cargaHoraria as CH 
            FROM 
              classe_disciplina_professor, 
              disciplina, 
              professor, 
              usuarios 
            where 
              professor.idUsuario = usuarios.id AND 
              usuarios.filial = :idFilial AND 
              usuarios.ativo = 1 and 
              classe_disciplina_professor.idClasse=:idClasse and 
              disciplina.id=classe_disciplina_professor.idDisciplina and 
              professor.id=classe_disciplina_professor.idProfessor");
            
            $buscaProf->execute(array(':idClasse'=> $idClasse, ':idFilial'=>$_SESSION['Unidade']));
            $resProf = $buscaProf->fetchAll(PDO::FETCH_ASSOC);
            
            ?><table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <th>Disciplina</th>
                <th>Professor</th>
                <th>Carga Hor&aacute;ria</th>
              </tr>
<?php
foreach($resProf as $dProf){
  echo "<tr><td> ".$dProf['nomeDisciplina']."</td><td> ".$dProf['profNome']."</td><td> ".$dProf['CH']."</td></tr>";
  
}
?>
            </table></td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
    </tr>
  <tr>
    <td></td>
    <td><??></td>
    <td></td>
    <td><??></td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
  </tr>

</table>
<!-- fim da tabela demonstrativa-->

</div>
</div>



				
    
    
    