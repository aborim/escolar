<?php
//error_reporting(0);
//inclui a conexao com banco de dados e fun&ccedil;&otilde;es
include('conexao.php');
include('functions.php');

#pega as vari&aacute;veis enviadas pelo formul&aacute;rio
$idTurma                  = $_POST['idTurma'];
$idFilial                 = $_SESSION['Unidade'];
$situacao                 = $_POST['situacao'];
$nome                     = $_POST['nome'];
$id_plano                 = $_POST['id_plano'];
$anoVigente               = $_POST['anoVigente'];
$dtCriacao                = substr($_POST['dtCriacao'],6,4)."-".substr($_POST['dtCriacao'],3,2)."-".substr($_POST['dtCriacao'],0,2);
$dtAtualizacao            = substr($_POST['dtAtualizacao'],6,4)."-".substr($_POST['dtAtualizacao'],3,2)."-".substr($_POST['dtAtualizacao'],0,2);
$periodo                  = $_POST['periodo'];
$periodoLetivo            = $_POST['periodoLetivo'];
$dadoAluno                = $_POST['alunos'];
$id_Professor             = $_POST['id_Professor'];
$professor                = $_POST['professor'];
$nomeAluno                = $_POST['nomeAluno'];
$situacaoAluno            = $_POST['situacaoAluno'];
$alunos                   = $_POST['alunos'];

    $busca_filial = $con->prepare("select id from filial where filial.id=:idFilial");
    $busca_filial->bindValue(':idFilial',$idFilial);
    $busca_filial->execute();
    $idFilial = $busca_filial->fetchAll(PDO::FETCH_ASSOC);
if(@$_GET['fun']=="ed"){
  
    $edicao = "sim";
    #aqui vem a busca dos dados do classe pela variavel de entrada
    $busca_classe = $con->prepare("SELECT * from classe where id=:id");
    $busca_classe->bindValue(':id',$idClasse);
    $busca_classe->execute();
    $resultado = $busca_classe->fetchAll(PDO::FETCH_ASSOC);
   
    if(@$_POST['edita']==1){
 
        #aqui vai a edi&ccedil;&atilde;o dos dados do classe
        $altera_classe = $con->prepare("update classe set
                            idTurma                  = :idTurma,
                            situacao                 = :situacao,
                            nome                     = :nome,
                            id_plano                 = :id_plano,
                            anoVigente               = :anoVigente,
                            dtAtualizacao            = :dtAtualizacao,
                            periodo                  = :periodo,
                            periodoLetivo            = :periodoLetivo      
                            where id = :id     
                                 ");
        $altera_classe->execute(array(
                                    ':idTurma'                  => $idTurma,
                                    ':situacao'                 => $situacao,
                                    ':nome'                     => $nome,
                                    ':id_plano'                 => $id_plano,
                                    ':anoVigente'               => $anoVigente,
                                    ':dtAtualizacao'            => date('Y-m-d H-i-s'),
                                    ':periodo'                  => $periodo,
                                    ':periodoLetivo'            => $periodoLetivo,
                                    ':id'                       => $_POST['id']
        ));
                  
  foreach($_POST['professor'] as $dadoGrava){
  $dis_prof = explode(":",$dadoGrava);
    

      $grava_disciplina_curso = $con ->prepare("update classe_disciplina_professor set 
                                            idProfessor = :idProfessor,
                                            cargaHoraria = :cargaHoraria
                                            where id=:registro
            ");
            $grava_disciplina_curso->execute(array(
                                              ':idProfessor'   => $dis_prof[1],
                                              ':registro'      => $dis_prof[0],
                                              ':cargaHoraria'  => $_POST['CH'][$dis_prof[2]]
            ));
            echo "<!--";
    var_dump($dadoGrava, $dis_prof, $_POST['CH'],$grava_disciplina_curso);
    echo "-->";
      }
      if (!$grava_disciplina_curso) {
        echo "\nPDO::errorInfo():\n";
        print_r($grava_disciplina_curso->errorInfo());
        exit;
    }
        $status = $altera_classe->errorCode();
        if($status =="00000"){
            $dados_atualizados = "ok";
        }
    }


}else{
    $edicao ="nao";
}


//modulo de cadastro de alunos, arquivo inserido ao menu restrito atrav&eacute;s de include
if(isset($_POST['grava'])==1){

//armazena os dados das vari&aacute;veis do formul&aacute;rio
  //grava os dados do classe
  $grava_classe=$con->prepare("insert into classe (idTurma,
                                      idFilial,
                                      situacao,
                                      nome,
                                      id_plano,
                                      anoVigente,
                                      dtCriacao,
                                      periodo,
                                      periodoLetivo
                                      )values(
                                        :idTurma,
                                        :idFilial,
                                        :situacao,
                                        :nome,
                                        :id_plano,
                                        :anoVigente,
                                        :dtCriacao,
                                        :periodo,
                                        :periodoLetivo
                                                )");
  $grava_classe->execute(array(
                  ':idTurma'                  => $idTurma,
                  ':idFilial'                 => $_SESSION['Unidade'],
                  ':situacao'                 => $situacao,
                  ':nome'                     => $nome,
                  ':id_plano'                 => $id_plano,
                  ':anoVigente'               => $anoVigente,
                  ':dtCriacao'                => date('Y-m-d H-i-s'),
                  ':periodo'                  => $periodo,
                  ':periodoLetivo'            => $periodoLetivo
  ));
 
#grava os alunos e os professores disciplinas e cargas horarias
  
  $LAST_ID = $con->lastInsertId();

  foreach($_POST['professor'] as $dadoGrava){
  $dis_prof = explode(":",$dadoGrava);
  $grava_disciplina_curso = $con ->prepare("insert into classe_disciplina_professor ( 
                                          idClasse,
                                          idDisciplina,
                                          idProfessor,
                                          cargaHoraria
                                          )values(
                                            :idClasse,
                                            :idDisciplina,
                                            :idProfessor,
                                            :cargaHoraria)
          ");
          $grava_disciplina_curso->execute(array(
                                            ':idClasse'      => $LAST_ID,
                                            ':idDisciplina'  => $dis_prof[1],
                                            ':idProfessor'   => $dis_prof[0],
                                            ':cargaHoraria'  => $_POST['CH'][$dis_prof[1]]

          ));
    }
    $numChamada = 1;                
    foreach($_POST['alunos'] as $dadoGrava2){
    $grava_aluno_classe = $con ->prepare("insert into classe_aluno(
                                    idClasse,
                                    idAluno,
                                    numeroChamada
                                    )values(
                                        :idClasse,
                                        :idAluno,
                                        :numChamada
                                    )
    ");
    $grava_aluno_classe ->execute(array(
                                ':idClasse'     => $LAST_ID,
                                ':idAluno'      => $dadoGrava2,
                                ':numChamada'   => $numChamada
    ));
    $numChamada++;
    }
    $status = $grava_classe->errorCode();
}
?>

<div class="titulo_interna">
    <i class="fa fa-graduation-cap" aria-hidden="true"></i>Academico
</div>
<div class="content_form">
    <?php
    if(@$_GET['fun']=="ed"){$titulo = "Editar Classe";}else{$titulo = "Adicionar Classe";}
    ?>
    <div class="titulo"> <?= $titulo?> </div>
    <?php
    if(!isset($status)){?>
<form method="post" action="#" enctype="multipart/form-data" id="frmAluno">
    <div class="form_comp">
    

<table class="formulario">
<tbody>
    <tr><td colspan="99"></td></tr>          
    <tr>
    <th><label for="nome">Nome.<span class="obrigatorio"> *</span></label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['nome']!=""){echo $resultado[0]['nome'];}?>" id="nome" name="nome" title="Nome do Classe" maxlength="60" required>
            <br>
            <span class="legenda_bloco">Nome do Classe.</span>
        </td>
    </tr>
    <tr>
      <th>
        <label for="id_plano">Plano de pagamento.<span class="obrigatorio"> *</span></label></th>
      <td><select name="id_plano" id="id_plano" class="form_campo" required>
      <option value="">Selecione o plano</option>
        <?php $buscarPlano=$con->prepare("SELECT * FROM planos where 1");
          $buscarPlano->execute();
          $resultadoPlano = $buscarPlano->fetchAll(PDO::FETCH_ASSOC);
          foreach($resultadoPlano as $dado){
            if(@$resultado[0]['id_plano']==$dado['id']){
              $var2 = "selected";
            }else{
              $var2 = "";
            }
                    echo "<option value='".$dado['id']."' ".$var2.">".$dado['plano']."</option>";
        }?></select>
        </td>
    </tr>
    
    <!--turma-->
    <tr>
      <th>
        <label for="idTurma">Turma.<span class="obrigatorio"> *</span></label></th>
      <td><select name="idTurma" id="idTurma" class="form_campo" required onchange="mostracurso(this)">
        <option value="">Selecione a turma</option>
        <?php $buscarTurma=$con->prepare("SELECT * FROM turma where 1");
          $buscarTurma->execute();
          $resultadoTurma = $buscarTurma->fetchAll(PDO::FETCH_ASSOC);
          
          foreach($resultadoTurma as $dado2){
            
            if(@$resultado[0]['idTurma']==$dado2['id']){
                      $var = "selected";
                    }else{
                      $var = "";
                    }
                    echo"<option  value='".$dado2['id']."' ".$var.">".$dado2['nome']."</option>";
            
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
            classe_disciplina_professor.cargaHoraria as CH
        FROM
            classe_disciplina_professor,
            disciplina,
            professor,
            usuarios
        where 
          professor.idUsuario = usuarios.id AND
            usuarios.filial = :filial AND
            usuarios.ativo = 1 and 
            classe_disciplina_professor.idClasse=:idClasse and 
            disciplina.id=classe_disciplina_professor.idDisciplina and 
            professor.id=classe_disciplina_professor.idProfessor");
          
          $buscaProfessor->execute(array(
            ':filial'=>$_SESSION['Unidade'],
            ':idClasse'=>$idClasse
          ));
          $resultadoDisc = $buscaProfessor->fetchAll(PDO::FETCH_ASSOC); 
          
          ?>
          
          <?if(@$_GET['fun']=="ed"){?>
          <table borde="1px" id="exibe_dados" width="100%" >
          <th >Nome</th>
          <th >Professor</th>
          <th >Carga Horaria</th>
          <?php
          foreach($resultadoDisc as $dadoDiscProf){
            #busca todos os professores pra inserir em combobox pra seleção dentro da classe
            $buscaProfessor = $con->prepare("SELECT professor.id,professor.nome FROM professor,usuarios where professor.idUsuario = usuarios.id and usuarios.filial = :filial and usuarios.ativo=1");
            $buscaProfessor->bindValue(":filial",$_SESSION['Unidade']);
            $buscaProfessor->execute();
            $resultadoProf = $buscaProfessor->fetchAll(PDO::FETCH_ASSOC); 
            
            $mostraListraProfessor = "<select name='professor[]'>";
            foreach ($resultadoProf as $dadoProfessor) {
              if($dadoProfessor['id']==$dadoDiscProf['idProf']){$selecionado = " selected";}else{$selecionado = " ";}
              $mostraListraProfessor .= "<option value='".$dadoDiscProf['id'].":".$dadoProfessor['id'].":".$dadoDiscProf['idDisciplina']."'".$selecionado.">".$dadoProfessor['nome']."</option>";
            }
            $mostraListraProfessor .= "</select>";
            
            echo "<tr>
                    <td style='font-size: 10px;'>".$dadoDiscProf['nomeDisciplina']."&nbsp; </td>
                    <td style='font-size: 10px;'>".$mostraListraProfessor."</td>
                    <td class='tituloDado2' style='font-size: 10px;'>
                    <input type='text' value='".$dadoDiscProf['CH']."' maxlength='60' size='22%' name='CH[".$dadoDiscProf['idDisciplina']."]'> </td>
                  </tr>";
        

            
          } 
      ?>
        <th colspan="3"><label for="id_Professor"></th>
        <td>
    </table><?php }?>
    </div>
  </td>
    </tr>
    <!--professor-->
    <script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#chosen-select').chosen();
    });
    </script>
    <tr>
    <th> <label for="alunos[]">Selecione os Alunos:</th></label>
    <td>
    <select data-placeholder="Selecione os Alunos" multiple class="chosen-select" id="chosen-select" name="alunos[]" tabindex="12">
            <?php $buscarAluno=$con->prepare("SELECT id, nome FROM aluno where 1");
                $buscarAluno->execute();
                $resultadoAluno = $buscarAluno->fetchAll(PDO::FETCH_ASSOC); 
                //$nomeAluno = $resultadoAluno[0]['nome'];
          ?>
          <?php
          foreach($resultadoAluno as $dadoAluno){
                    echo "<option value='".$dadoAluno['id']."'>".$dadoAluno['nome']."</option>";
                    
          }?>
          </select>
    <!--alunos-->
    </td><tr>
    <!--Exibiï¿½ï¿½o dos alunos inseridos na classe-->
    <?php if(@$_GET['fun']=="ed"){

      if($_GET['order']=='s'){
        $buscarAlunos=$con->prepare("SELECT classe_aluno.numeroChamada, classe_aluno.idClasse, classe_aluno.idAluno as idAluno, aluno.rm, aluno.nome as nome, classe_aluno.situacao FROM classe_aluno, aluno where classe_aluno.idClasse = :idClasse and aluno.id=classe_aluno.idAluno order by nome asc");
        $buscarAlunos->bindValue(':idClasse', $idClasse);
        $buscarAlunos->execute();
        $resultadoAlunos = $buscarAlunos->fetchAll(PDO::FETCH_ASSOC);
        $novaChamada = 1;
       foreach ($resultadoAlunos as $key => $value) {
          #echo $value['idAluno'] . " - ".$novaChamada." - ".$value['nome']."<br>";
          $atualizaChamada = $con->prepare("update classe_aluno set numeroChamada = :novaChamada where idAluno = :idAluno");
          $atualizaChamada->execute(array(':novaChamada'=>$novaChamada,':idAluno'=>$value['idAluno']));
          $novaChamada++;
       }
       $buscarAlunos=$con->prepare("SELECT classe_aluno.numeroChamada, classe_aluno.idClasse, classe_aluno.idAluno as idAluno, aluno.rm, aluno.nome as nome, classe_aluno.situacao FROM classe_aluno, aluno where classe_aluno.idClasse = :idClasse and aluno.id=classe_aluno.idAluno order by nome asc");
        $buscarAlunos->bindValue(':idClasse', $idClasse);
        $buscarAlunos->execute();
        $resultadoAlunos = $buscarAlunos->fetchAll(PDO::FETCH_ASSOC);
      }else{
        $buscarAlunos=$con->prepare("SELECT classe_aluno.numeroChamada, classe_aluno.idClasse, classe_aluno.idAluno as idAluno, aluno.rm, aluno.nome as nome, classe_aluno.situacao FROM classe_aluno, aluno where classe_aluno.idClasse = :idClasse and aluno.id=classe_aluno.idAluno order by numeroChamada asc");
        $buscarAlunos->bindValue(':idClasse', $idClasse);
        $buscarAlunos->execute();
        $resultadoAlunos = $buscarAlunos->fetchAll(PDO::FETCH_ASSOC);
      }


      
        
        
      
      echo "<table class='formulario' id='subTabelaAlunos'><tr><th> Alunos vinculados:</th>";
      echo "<td>";
                  echo "<form method='post' action='#' enctype='multipart/form-data' id='frmAluno'><table border='1px' id='alunos[]' width='700' name='alunos'> ";
                  echo "<tr><th>Num</th>";
                  echo "<th>RM</th>";
                  echo "<th>Nome</th>";
                  echo "<th>Situação</th>";
                  echo "<th>Ação <br><span style='font-size:8px;'>(X Para remover o aluno)</span></th></tr>";
      foreach($resultadoAlunos  as $dadosAlunos){?>
                  <tr id="aluno:<?=$dadosAlunos['idAluno']?>:<?=$idClasse?>">
                  <td align="center" style="font-size: 10px;"><input type="text" name="chamada" data-id="<?php echo $dadosAlunos['numeroChamada'].":".$dadosAlunos['idAluno'].":".$idClasse;?>" value="<?=$dadosAlunos['numeroChamada']?> "maxlength="4" size="2" onchange="alteraChamada(this)"></td>
                  <td align="center" style="font-size: 10px;"><?=$dadosAlunos['rm']?></td>
                  <td style="font-size: 10px;"><?=$dadosAlunos['nome']?>&nbsp;</td>
                 
                  <td><select name='situacaoAluno' onchange="alteraSituacao(this)">
                            <option>Selecione...</option>
                            <option value='0:<?=$dadosAlunos['idAluno'].":".$idClasse?>'<?php if(@$dadosAlunos['situacao']==0){echo "selected";}?> required>Ativo</option>
                            <option value='1:<?=$dadosAlunos['idAluno'].":".$idClasse?>'<?php if(@$dadosAlunos['situacao']==1){echo "selected";}?> >Cancelado</option>
                            <option value='2:<?=$dadosAlunos['idAluno'].":".$idClasse?>'<?php if(@$dadosAlunos['situacao']==2){echo "selected";}?>>Transferido de Curso</option>
                            <option value='3:<?=$dadosAlunos['idAluno'].":".$idClasse?>'<?php if(@$dadosAlunos['situacao']==3){echo "selected";}?>>Transferido de Instituição</option>
                            <option value='4:<?=$dadosAlunos['idAluno'].":".$idClasse?>'<?php if(@$dadosAlunos['situacao']==4){echo "selected";}?>>Remanejado</option>
                            <option value='5:<?=$dadosAlunos['idAluno'].":".$idClasse?>'<?php if(@$dadosAlunos['situacao']==5){echo "selected";}?>>Desistencia</option>
                            <option value='6:<?=$dadosAlunos['idAluno'].":".$idClasse?>'<?php if(@$dadosAlunos['situacao']==6){echo "selected";}?>>Matricula Trancada</option>
                            <option value='7:<?=$dadosAlunos['idAluno'].":".$idClasse?>'<?php if(@$dadosAlunos['situacao']==7){echo "selected";}?>>Reclassificado</option>
                            </select></td> 
                            <td align="center"><button type="button" data-id="<?=$dadosAlunos['idAluno']?>:<?=$idClasse?>" class="excluir" ><img src='images/x.jpg' style="width: 25px;vertical-align: mid;"></button> 
                            
                           </td></tr>
      <?php } ?>
      </table>
      <table class="formulario">
        <?php if($resultado[0]['situacao']!="1"){?>
    <tr><td colspan="4"><a href="?op=add_classe&idClasse=<?=$idClasse?>&fun=ed&order=s" id="reordenaAlfabetico">Ordenar alunos</a></td></tr>
    <?php }?>
    </table></form></td></tr>
    <?php } ?>
       <tr> <th><label for="anoVigente">Ano Vigente.<span class="obrigatorio"> *</span></label>
        </th>
        <td><input class="form_campo" type="text" value="<?php if(@$resultado[0]['anoVigente']!=""){echo $resultado[0]['anoVigente'];}?>" id="anoVigente" name="anoVigente" title="anoVigente" maxlength="10" required>
          <span class="legenda_bloco"><br />
          Ano em que estará ativo a Classe.</span>
          </td>
    </tr>
    <tr>
        <th><label for="periodo">Perí­odo.<span class="obrigatorio"> *</span></label></th>
        <td><select name="periodo" id="periodo" class="form_campo" required>
            <option value="" >Selecione...</option>
            <option value="0"<?php if(@$resultado[0]['periodo']=="0"){echo "selected";}?> >Matutino</option>
            <option value="1"<?php if(@$resultado[0]['periodo']=="1"){echo "selected";}?> >Verpertino</option>
            <option value="2"<?php if(@$resultado[0]['periodo']=="2"){echo "selected";}?> >Intermediário</option>
            <option value="3"<?php if(@$resultado[0]['periodo']=="3"){echo "selected";}?> >Integral</option>
            </td>
    </tr>
    <tr>
      <th><label form="periodoLetivo">Período Letivo.</label></th>
      <td>
        <input class="form_campo" type="text" value="<?php if(@$resultado[0]['periodoLetivo']!=""){echo $resultado[0]['periodoLetivo'];}?>" id="periodoLetivo" name="periodoLetivo" title="" maxlength="20">
         </td>
    </tr>
    <tr>
    <th><label for="situacao">Situação.<span class="obrigatorio"> *</span></label></th>
        <td><select name="situacao" id="situacao" class="form_campo" required>
            <option value="">Selecione...</option>
            <option value="0"<?php if(@$resultado[0]['situacao']=="0"){echo "selected";}?> >Provisória</option>
            <option value="1"<?php if(@$resultado[0]['situacao']=="1"){echo "selected";}?> >Definida</option>
            <option value="2"<?php if(@$resultado[0]['situacao']=="2"){echo "selected";}?> >Concluída</option>
            <option value="3"<?php if(@$resultado[0]['situacao']=="3"){echo "selected";}?> >Desativada</option>

        </td>
    </tr>    
</tbody></table>
</td>
</tr>    
</tbody></table>

<script type="text/javascript">
$(function() {
	$("body").on("click", ".excluir", function(e) {
  	var conf = confirm("Tem certeza que deseja remover o aluno?");
    if(conf){
      var dados = $(this).data("id");
      var aluno = dados.split(':');
      var row = document.getElementById("aluno:"+dados);
      // iniciando Ajax
      $.ajax({
        url: "ws/cancela_aluno.php",
        type: 'POST',
        data: {idAluno: aluno[0],idClasse: aluno[1]},
        beforeSend: function( xhr ) {
          
        }
      })
      .done(function(data) {
        
        row.parentNode.removeChild(document.getElementById('aluno:'+dados));
        
      })
      .fail(function(e) {
        console.log(e)
      });     
     }
  });
    
});

function mostracurso(){
  var e = document.getElementById("idTurma");
  var strUser = e.options[e.selectedIndex].value;
  console.log(strUser);
  $.post("ws/disciplinas_turma.php", "idTurma="+strUser+"&filial=13&fun=<?=$_GET['fun']?>&idClasse=<?=$idClasse?>", function( data ) {
            document.getElementById('contentDisciplinas').innerHTML=data;
            console.log(data);
        });
     
}

function alteraSituacao(e){
  //var e = document.getElementById("situacaoAluno");
  var situacao = e.options[e.selectedIndex].value.split(':');

  console.log(situacao[0]+ " - "+situacao[1]+ " - "+situacao[2]);
  $.post("ws/altera_situacao.php", "idClasse="+situacao[2]+"&idAluno="+situacao[1]+"&situacao="+situacao[0]+"", function( data ) {

            console.log(data);
        });
}

function alteraChamada(e){
  //var e = document.getElementById("situacaoAluno");
  var dado = e.getAttribute('data-id');
  var chamada = $('input[data-id="' + dado + '"]').val();
  var dadosParaAlteracao = dado.split(":");
  console.log(dado + ' - ' +chamada + dadosParaAlteracao[0] + dadosParaAlteracao[1] + dadosParaAlteracao[2] );
  $.post("ws/altera_chamada.php", "idClasse="+dadosParaAlteracao[2]+"&idAluno="+dadosParaAlteracao[1]+"&chamada="+chamada+"", function( data ) {

            console.log(data);
        });
}
</script>

    </div>
    
        <div class="btn_pos">
    <?php if($edicao!="sim"){?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Adicionar Classe"></div>
        <input type="hidden" name="grava" value="1">
    <?php }else{?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Editar Classe"></div>
        <input type="hidden" name="edita" value="1">
        <input type="hidden" name="fun" value="ed">
        <input type="hidden" name="id" value="<?=$idClasse?>">
    <?php }?>

    
</form>
</div>


    <?php }else{
        if($dados_atualizados !="ok"){
        ?>

      <div class="form_comp">
        Classe gravado com sucesso<br>
        Nome: <?= $nome?><br>
        <br>

      </div>

   <?php }else{?>
    <div class="form_comp">
        Classe alterada com sucesso<br>
        
        Nome: <?= $nome?><br>
        
        <?


        foreach($alunos as $dadoGrava3){
          
        $grava_aluno_matricula = $con ->prepare("insert into classe_aluno(
                                                idClasse,
                                                idAluno,
                                                situacao
                                                )values(
                                                    :idClasse,
                                                    :idAluno,
                                                    :situacaoAluno
                                                )
            ");
            $grava_aluno_matricula ->execute(array(
                                        ':idClasse'     => $idClasse,
                                        ':idAluno'      => $dadoGrava3,
                                        ':situacaoAluno'=> $situacaoAluno
            ));
          }
          
        ?>

      </div>

<?php }
}?>
