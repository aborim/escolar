<?php
//error_reporting(0);
//inclui a conexao com banco de dados e fun&ccedil;&otilde;es
include('conexao.php');
include('functions.php');

#busca as informações da classe
$buscaAlunos = $con->prepare("SELECT 
classe_aluno.idClasse,classe.nome as classe,classe.id_plano,classe.idTurma,aluno.id,aluno.nome
FROM 
aluno,classe_aluno,classe
where 
classe_aluno.idClasse=:idClasse and
classe_aluno.idClasse=classe.id and
aluno.id = classe_aluno.idAluno");
$buscaAlunos->execute(array(':idClasse'=>$idClasse));
$res = $buscaAlunos->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="titulo_interna">
    <i class="fa fa-users" aria-hidden="true"></i>Acadêmico
</div>
<div class="content_form">
    <?php
    $titulo = "Gerar Matriculas para Classe";
    ?>
    <div class="titulo"> <?= $titulo?> </div>
    

    <div class="form_comp">
    

<table class="formulario">
<tbody>
    <tr><td colspan="99"></td></tr>          
    <tr>
    <th><label for="nome">Nome.</label></th>
        <td>
            <?php echo $res[0]['classe'];?>
            <br>
            <span class="legenda_bloco">Nome do Classe.</span>
        </td>
    </tr>
    <tr>
      <th>
        <label for="id_plano">Plano de pagamento.</label></th>
      <td>
    
        <?php $buscaPlano = $con ->prepare("SELECT id, plano from planos where id = :id_plano");
            $buscaPlano->bindValue(':id_plano', $res[0]['id_plano']);
            $buscaPlano->execute();
            $resPlano = $buscaPlano->fetchAll(PDO::FETCH_ASSOC);
    echo($resPlano[0]['plano']);
  
    ?></select>
        </td>
    </tr>
    
    <!--turma-->
    <tr>
      <th>
        <label for="idTurma">Turma.</label></th>
      <td>
        
        <?php $buscarTurma=$con->prepare("SELECT * FROM turma where id=:idTurma");
          $buscarTurma->execute(array(':idTurma'=> $res[0]['idTurma']));
          $resultadoTurma = $buscarTurma->fetchAll(PDO::FETCH_ASSOC);
          foreach($resultadoTurma as $dado2){
          echo $dado2['nome'];
            
        }?></select>
        </td>
    </tr>
    <tr >
    <th><label for="professor[]">Professores das Disciplinas.</label></th>
      <td>
      <div id="contentDisciplinas">  
      
      <?php
          #busca as disciplinas
          $buscaProfessor = $con->prepare("SELECT
          classe_disciplina_professor.id , 
          classe_disciplina_professor.idClasse as idClasse,
            disciplina.id as idDisciplina,
            disciplina.nome as nomeDisciplina,
            professor.id as idProf,
            professor.nome as profNome,
            classe_disciplina_professor.cargaHoraria as CH,
            classe.situacao,classe.anoVigente,classe.periodoLetivo,classe.periodo
        FROM
            classe_disciplina_professor,
            classe,
            disciplina,
            professor,
            usuarios
        where 
          professor.idUsuario = usuarios.id AND
            usuarios.filial = :filial AND
            usuarios.ativo = 1 and 
            classe_disciplina_professor.idClasse=:idClasse and 
            disciplina.id=classe_disciplina_professor.idDisciplina and 
            professor.id=classe_disciplina_professor.idProfessor and 
            classe.id = classe_disciplina_professor.idClasse");

          
          $buscaProfessor->execute(array(
            ':filial'=>$_SESSION['Unidade'],
            ':idClasse'=>$idClasse
          ));
          $resultadoDisc = $buscaProfessor->fetchAll(PDO::FETCH_ASSOC); 
         
          ?>
          
          
          <table borde="1px" id="exibe_dados" width="100%" >
          <th >Nome</th>
          <th >Professor</th>
          <th >Carga Horaria</th>
          <?php
          foreach($resultadoDisc as $dadoDiscProf){
       
            
            echo "<tr>
                    <td style='font-size: 10px;'>".$dadoDiscProf['nomeDisciplina']."&nbsp; </td>
                    <td style='font-size: 10px;'>".$dadoDiscProf['profNome']."</td>
                    <td class='tituloDado2' style='font-size: 10px;'>".$dadoDiscProf['CH']." </td>
                  </tr>";
        

            
          } 
      ?>
        <th colspan="3"><label for="id_Professor"></th>
        <td>
    </table>
    </div>
  </td>
    </tr>
    <!--professor-->
    
    <tr>
    <th> <label for="alunos[]">Selecione os Alunos:</th></label>
    <td>
   
    <!--alunos-->
    </td><tr>
      <?php
      $buscarAlunos=$con->prepare("SELECT 
      classe_aluno.numeroChamada, 
      classe_aluno.dtMatricula,
        classe_aluno.idClasse, 
        classe_aluno.idAluno as idAluno, 
        aluno.rm, 
        aluno.nome as nome, 
        classe_aluno.situacao,
        responsavel.id as idResponsavel
    FROM 
      classe_aluno, 
        aluno,
        responsavel,
        aluno_responsavel
    where 
      classe_aluno.idClasse = :idClasse and 
        aluno.id=classe_aluno.idAluno AND
        aluno.id = aluno_responsavel.idAluno AND
        responsavel.id = aluno_responsavel.idResponsavel AND
        aluno_responsavel.financeiro =1");
      $buscarAlunos->bindValue(':idClasse', $idClasse);
      $buscarAlunos->execute();
      $resultadoAlunos = $buscarAlunos->fetchAll(PDO::FETCH_ASSOC);
     
      echo "<tr><th> Alunos vinculados:</th>";
      echo "<td>";
                  echo "<table border='1px' id='alunos[]' width='700' name='alunos'> ";
                  echo "<tr><th>Chamada &nbsp;</th>";
                  echo "<th>RM &nbsp;</th>";
                  echo "<th>Nome &nbsp;&nbsp;</th>";
                  echo "<th>Situacao</th>";
                  echo "<th>Ação</th></tr>";

      foreach($resultadoAlunos  as $dadosAlunos){?>
                  <tr id="aluno:<?=$dadosAlunos['idAluno']?>:<?=$idClasse?>">
                  <td align="center" style="font-size: 10px;"><?=$dadosAlunos['numeroChamada']?></td>
                  <td align="center" style="font-size: 10px;"><?=$dadosAlunos['rm']?></td>
                  <td style="font-size: 10px;"><?=$dadosAlunos['nome']?>&nbsp;</td>
                  <?php 
                  echo "<!--";
                  var_dump($dadosAlunos);
                  echo "-->";
                  ?>
                  <td style="font-size: 10px;">           
                    <?php 
                    switch ($dadosAlunos['situacao']) {
                      case '0':
                        $situacao = "Ativo";
                        break;
                      case '1':
                        $situacao = "Cancelado";
                        break;
                      case '2':
                        $situacao = "Transferido de Curso";
                        break;
                      case '3':
                        $situacao = "Transferido de Instituição";
                        break;
                      case '4':
                        $situacao = "Remanejado";
                        break;
                      case '5':
                        $situacao = "Desistencia";
                        break;
                      case '6':
                        $situacao = "Matricula Trancada";
                        break;
                      case '7':
                        $situacao = "Reclassificado";
                        break;
                                                        
                      default:
                        # code...
                        break;
                    }

                    echo $situacao;
                    ?>
                    </td>
                  <td style="font-size: 10px;">
                  <?php if($dadosAlunos['dtMatricula']==0){?>
                    <a href="menu_restrito.php?op=add_matriculas&idAluno=<?=$dadosAlunos['idAluno']?>&idRespFinan=<?=$dadosAlunos['idResponsavel']?>&idClasse=<?=$idClasse?>&idPlano=<?=$resPlano[0]['id']?>">Matricular</a>
                  <?php }else{?>
                    Aluno Matriculado em <?=formatoData($dadosAlunos['dtMatricula'])?>
                  <?php }?>
                </td>
                  </tr>
      <?php } ?></table></form></td></tr>
    
       <tr> <th><label for="anoVigente">Ano Vigente.<span class="obrigatorio"> *</span></label>
        </th>
        <td><?php 
        
        echo $resultadoDisc[0]['anoVigente'];?>
          <span class="legenda_bloco"><br />
          Ano em que estará ativo a Classe.</span>
          </td>
    </tr>
    <tr>
        <th><label for="periodo">Perí­odo.
          
        </label></th>
        <td>    
        <?php 
        
        switch ($resultadoDisc[0]['periodo']) {
          case '0':
            $periodo="Matutino";
            break;
          case '1':
            $periodo="Vespertino";
            break;
          case '2':
            $periodo="Notuno";
            break;
          default:
            # code...
            break;
        }
        echo $periodo;
        ?>    </td>
    </tr>
    <tr>
      <th><label form="periodoLetivo">Período Letivo.</label></th>
      <td>
        <?php echo $resultadoDisc[0]['periodoLetivo'];?>
         </td>
    </tr>
    <tr>
    <th><label for="situacao">Situação.</label></th>
        <td>
          <?php 
          switch ($resultadoDisc[0]['situacao']) {
            case '0':
              $situacaoClasse = "Provisória";
              break;
            case '1':
              $situacaoClasse = "Definida";
              break;
            case '2':
              $situacaoClasse = "Concluída";
              break;
            case '3':
              $situacaoClasse = "Desativada";
              break;
                  
            default:
              # code...
              break;
          }

          echo $situacaoClasse;
          ?>
        </td>
    </tr>    
</tbody></table>
</td>
</tr>    
</tbody></table>
    </div>
</div>
