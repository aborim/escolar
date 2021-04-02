<?php
//error_reporting(0);
//inclui a conexao com banco de dados e fun&ccedil;&otilde;es
include('conexao.php');
include('functions.php');

#pega as vari&aacute;veis enviadas pelo formul&aacute;rio
//$idFilial                 = $_POST['idFilial'];
$idCurso                  = $_POST['idCurso'];
$nome                     = $_POST['nome'];
$dp                       = $_POST['dp'];
$grau                     = $_POST['grau'];
$plano                    = $_POST['plano'];
$nivel                    = $_POST['nivel'];
$idFilial                 = $_SESSION['Unidade'];;
$disciplinas              = $_POST['disciplinas'];
$id_Disciplina            = $_POST['id_Disciplina'];

    /*$busca_filial = $con->prepare("select id from filial where filial.id=:idFilial");
    $busca_filial->bindValue(':idFilial',$idFilial);
    $busca_filial->execute();
    $idFilial = $busca_filial->fetchAll(PDO::FETCH_ASSOC);
*/
if(@$_GET['fun']=="ed"){
    
    $edicao = "sim";
    #aqui vem a busca dos dados do turma pela variavel de entrada
    $busca_turma = $con->prepare("select * from turma where turma.id=:id");
    $busca_turma->bindValue(':id',$idTurma);
    $busca_turma->execute();
    $resultado = $busca_turma->fetchAll(PDO::FETCH_ASSOC);
    
   
    if(@$_POST['edita']==1){
        #aqui vai a edi&ccedil;&atilde;o dos dados da turma
        $altera_turma = $con->prepare("update turma set
                            idFilial                 = :idFilial,
                            idCurso                  = :idCurso,
                            nome                     = :nome,
                            dp                       = :dp,
                            grau                     = :grau,
                            plano                    = :plano,
                            nivel                    = :nivel
                            where id = :id     
                                 ");
        $altera_turma->execute(array(
                                    ':idFilial'                 => $idFilial,
                                    ':idCurso'                  => $idCurso,
                                    ':nome'                     => $nome,
                                    ':dp'                       => $dp,
                                    ':grau'                     => $grau,
                                    ':plano'                    => $plano,
                                    ':nivel'                    => $nivel,
                                    ':id'                       => $_POST['id']
        ));
              
        #echo "altera os dados do aluno aqui";
        $status = $altera_turma->errorCode();
        if($status =="00000"){
            $dados_atualizados = "ok";
        }
    }


}else{
    $edicao ="nao";
}

//modulo de cadastro de alunos, arquivo inserido ao menu restrito atrav&eacute;s de include
if(isset($_POST['grava'])==1){
  $_POST['disciplinas'];
  $_POST['id_Disciplina'];
  
//armazena os dados das vari&aacute;veis do formul&aacute;rio
  //grava os dados do turma
  $grava_turma=$con->prepare("insert into turma (idFilial,
                                      idCurso,
                                      nome,
                                      dp,
                                      grau,
                                      plano,
                                      nivel
                                      )values(
                                        :idFilial,
                                        :idCurso,
                                        :nome,
                                        :dp,
                                        :grau,
                                        :plano,
                                        :nivel
                                                )");
  $grava_turma->execute(array(
                ':idFilial'                 => $idFilial,
                ':idCurso'                  => $idCurso,
                ':nome'                     => $nome,
                ':dp'                       => $dp,
                ':grau'                     => $grau,
                ':plano'                    => $plano,
                ':nivel'                    => $nivel                          
  ));
  $status = $grava_turma->errorCode();
}

?>

<div class="titulo_interna">
<i class="fa fa-graduation-cap" aria-hidden="true"></i>Acad&ecirc;mico
</div>
<div class="content_form">
    <?php
    if(@$_GET['fun']=="ed"){$titulo = "Editar Turma";}else{$titulo = "Adicionar Turma";}
    ?>
    <div class="titulo"> <?= $titulo?> </div>
    <?php
    if(!isset($status)){?>
<form method="post" action="#" enctype="multipart/form-data" id="frmAluno">
    <div class="form_comp">
    

<table class="formulario">
<tbody>
      <??>
    <tr><td colspan="99"></td></tr>

    <tr>
    <th><label for="nome">Nome.<span class="obrigatorio"> *</span></label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['nome']!=""){echo $resultado[0]['nome'];}?>" id="nome" name="nome" title="Nome da Turma" maxlength="60" required>
            <br>
            <span class="legenda_bloco">Nome da Turma.</span>
        </td>
    </tr>

    <tr>
    <th><label for="dp"> DP</label></th>
        <td>
            <input type="checkbox" value="1" id="dp" name="dp" title="DP" <?php if(@$resultado[0]['dp']=="1"){echo "checked";}?>>
            <br><span class="legenda_bloco">Selecione se a turma for DP</span>
        </td>
    </tr>
    
    <tr>
      <th>
        <label for="plano">Plano de pagamento.</label></th>
      <td><select name="plano" id="plano" class="form_campo" required>
        <option value="">Selecione o plano</option>
        <?$buscarPlano=$con->prepare("SELECT * FROM planos where 1");
          $buscarPlano->execute();
          $resultadoPlano = $buscarPlano->fetchAll(PDO::FETCH_ASSOC);
          foreach($resultadoPlano as $dado){
            if(@$resultado[0]['plano']==$dado['id']){$selecionado = "selected";}else{}
                    echo"<option value='".$dado['id']."' ".$selecionado.">".$dado['plano']."</option>";
            
        }
    ?>
      </select></td>
    </tr>
    <tr>
        <th><label for="grau">Grau.</label>
        </th>
        <td>
        
          <input type="radio" title="Curso Ber&ccedil;&aacute;rio" name="grau" id="grau7" value="1" <?if (@$resultado[0]['grau']==1){echo "checked";}?>/>
          <span class="legenda_bloco">Curso Ber&ccedil;&aacute;rio</span><br>
          <input type="radio" title="Ed. Infantil" name="grau" id="grau8" value="2" <?if (@$resultado[0]['grau']==2){echo "checked";}?> />
          <span class="legenda_bloco">Ed. Infantil</span><br>
        
       
      </td>
      </tr>

    <tr>
        <th><label for="nivel">Nivel.</label>
        </th>
        <td><select name="nivel" id="nivel" class="form_campo">
          <option value="0" <?if (@$resultado[0]['nivel']==0){echo "selected";}?>>Selecione:</option>
          <option value="1" <?if (@$resultado[0]['nivel']==1){echo "selected";}?>>1</option>
          <option value="2" <?if (@$resultado[0]['nivel']==2){echo "selected";}?>>2</option>
          <option value="3" <?if (@$resultado[0]['nivel']==3){echo "selected";}?>>3</option>
          <option value="4" <?if (@$resultado[0]['nivel']==4){echo "selected";}?>>4</option>
          <option value="5" <?if (@$resultado[0]['nivel']==5){echo "selected";}?>>5</option>
          <option value="6" <?if (@$resultado[0]['nivel']==6){echo "selected";}?>>6</option>
          <option value="7" <?if (@$resultado[0]['nivel']==7){echo "selected";}?>>7</option>
          <option value="8" <?if (@$resultado[0]['nivel']==8){echo "selected";}?>>8</option>
          <option value="9" <?if (@$resultado[0]['nivel']==9){echo "selected";}?>>9</option>
          <option value="10" <?if (@$resultado[0]['nivel']==10){echo "selected";}?>>10</option>
          <option value="11" <?if (@$resultado[0]['nivel']==11){echo "selected";}?>>11</option>
          <option value="12" <?if (@$resultado[0]['nivel']==12){echo "selected";}?>>12</option>
        </select></td>
    </tr>
    <tr>
      <th>
        <label for="idCurso">Curso.</label></th>
      <td><select name="idCurso" id="idCurso" class="form_campo" required onchange="mostracurso(this)">
        <option value="">Selecione o Curso</option>
        <?$buscarCurso=$con->prepare("SELECT * FROM curso");
          $buscarCurso->execute();
          $resultadoCurso = $buscarCurso->fetchAll(PDO::FETCH_ASSOC);
          foreach($resultadoCurso as $dadoCurso){
            if (@$resultado[0]['idCurso']==$dadoCurso['id']){$selecionado = "selected";}else{$selecionado="";}
                    echo"<option value='".$dadoCurso['id']."' ".$selecionado.">".$dadoCurso['nome']."</option>";
        
        }
    ?>
      </select></td>
    </tr>
   
    <tr>
      <th><label for="disciplinas">Disciplinas.</label></th>
      <td>
        <?php if(@$_GET['fun']!="ed"){?>
      <div id="contentDisciplinas"></div>|||
        <?php }else{?>
          <div id="contentDisciplinas">
      <table borde="1px" id="exibe_dados" width="100%">
      <?php 
      $buscar=$con->prepare("SELECT disciplina.id,disciplina.nome,turma_disciplina.cargaHoraria FROM disciplina,turma_disciplina where turma_disciplina.idTurma=:idTurma and disciplina.id=turma_disciplina.idDisciplina");
          $buscar->execute(array(':idTurma'=>$idTurma));
          $resultadoDisc = $buscar->fetchAll(PDO::FETCH_ASSOC);
          echo "<!--";
          var_dump($buscar);
          echo "-->";
          ?>
          <th >Nome</th>
          <th >&nbsp;&nbsp;&nbsp;Carga Horaria </th>
          <?
          foreach($resultadoDisc as $dado3){
                    echo "<tr><td>".$dado3['nome']."&nbsp;</td><td><input type='text' value='$dado3[cargaHoraria]' maxlength='60' size='12%' 
                    id='".$dado3['id']."' name='disciplinas[$dado3[id]]'></td></tr>";
                    ?>
         <th><label for="id_Disciplina"></th>
         <td>
         <?}foreach($resultadoDisc as $dadoId){
          echo "<input type='hidden' name='id_Disciplina[$id_Disciplina[id]]' value='".$dadoId['id']."'></td>";
        }
        ?>
    </table>
    </div>
    <?php }?></td>
    </tr>
       
</tbody></table>
</td>
</tr>    
</tbody></table>

    </div>
    
        <div class="btn_pos">
    <?php if($edicao!="sim"){?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Adicionar Curso"></div>
        <input type="hidden" name="grava" value="1">
        
    <?php }else{?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Editar Curso"></div>
        <input type="hidden" name="edita" value="1">
        <input type="hidden" name="fun" value="ed">
        <input type="hidden" name="id" value="<?=$idTurma?>">
    <?php }?>

    
</form>
</div>


    <?php }else{
        if($dados_atualizados !="ok"){
        ?>

      <div class="form_comp">
        Turma gravada com sucesso<br>
        Nome: <?= $nome?><br><?php
        $LAST_ID = $con->lastInsertId();
        $x = 0; ;  
          foreach($_POST['disciplinas'] as $dado2){
            /*
            print_r("<br>dado2 = ".$dado2."- id=");
            print_r($id_Disciplina[$x]);
            */
            $grava_disciplina_turma = $con ->prepare("insert into turma_disciplina ( 
                                                    idTurma,
                                                    idDisciplina,
                                                    cargaHoraria
                                                    )values(
                                                      :idTurma,
                                                      :idDisciplina,
                                                      :cargaHoraria)
                    ");
                    $grava_disciplina_turma->execute(array(
                                                      ':idTurma'      => $LAST_ID,
                                                      ':idDisciplina' => $id_Disciplina[$x],
                                                      ':cargaHoraria' => $dado2
                    ));
                    $x = $x + 1;}
        ?>

      </div>

   <?php }else{?>
    <div class="form_comp">
        Turma alterada com sucesso<br>
        
        Nome: <?= $nome?><br><?php
        #print_r($_POST['disciplinas']);
        
          foreach($_POST['disciplinas'] as $chave=>$dado2){
            $grava_disciplina_turma = $con ->prepare("update turma_disciplina set
                                                    cargaHoraria  = :cargaHoraria where  
                                                    idTurma       = :idTurma and
                                                    idDisciplina  = :idDisciplina");
                    $grava_disciplina_turma->execute(array(
                                                      ':idTurma'      => $idTurma,
                                                      ':idDisciplina' => $chave,
                                                      ':cargaHoraria' => $dado2));
                    
                  }
        ?>
        

      </div>

<?php }
}?>

<script>
function mostracurso(){
  var e = document.getElementById("idCurso");
  var strUser = e.options[e.selectedIndex].value;
  console.log(strUser);
  $.post("ws/disciplinas.php", "idCurso=" + strUser + "&idTurma=<?=$idTurma?>", function( data ) {
            console.log(data);
            document.getElementById("contentDisciplinas").innerHTML= data;
        });
}
</script>
