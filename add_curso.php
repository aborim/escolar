<?php
//error_reporting(0);
//inclui a conexao com banco de dados e fun&ccedil;&otilde;es
include('conexao.php');
include('functions.php');

#pega as vari&aacute;veis enviadas pelo formul&aacute;rio
$id_plano                 = $_POST['id_plano'];
$nome                     = $_POST['nome'];
$descricao                = $_POST['descricao'];
$grau                     = $_POST['grau'];
$descricaoPapeletas       = $_POST['descricaoPapeletas'];
$descricaoDeclaracoes     = $_POST['descricaoDeclaracoes'];
$cursoTecnico             = $_POST['cursoTecnico'];
$atoCriacao               = $_POST['atoCriacao'];
$tipoAvaliacao            = $_POST['tipoAvaliacao'];
$mediaFinalMinima         = $_POST['mediaFinalMinima'];
$presencaMinima           = $_POST['presencaMinima'];
$mediaRecuperacaoMinima   = $_POST['mediaRecuperacaoMinima'];
$notaMinimaReprovacao     = $_POST['notaMinimaReprovacao'];
$casasDecimais            = $_POST['casasDecimais'];
$tipoArredondamento       = $_POST['tipoArredondamento'];
$solicitarPreConselho     = $_POST['solicitarPreConselho'];
$solicitarConselho        = $_POST['solicitarConselho'];
$solicitarExame           = $_POST['solicitarExame'];
$formaCalculo             = $_POST['formaCalculo'];
$formaCalculoExame        = $_POST['formaCalculoExame'];
$periodosAvaliacao        = $_POST['periodosAvaliacao'];
$dtCriacao                = substr($_POST['dtCriacao'],6,4)."-".substr($_POST['dtCriacao'],3,2)."-".substr($_POST['dtCriacao'],0,2);
$nMtRec                   = $_POST['nMtRec'];
$nMtRep                   = $_POST['nMtRep'];
$dias_letivos             = $_POST['dias_letivos'];
$disciplinas              = $_POST['disciplinas'];


if(@$_GET['fun']=="ed"){
    
    $edicao = "sim";
    #aqui vem a busca dos dados do curso pela variavel de entrada
    $busca_curso = $con->prepare("select * from curso where id=:id");
    $busca_curso->bindValue(':id', $idCurso);
    $busca_curso->execute();
    $resultado = $busca_curso->fetchAll(PDO::FETCH_ASSOC);
    
   
    if(@$_POST['edita']==1){
        #aqui vai a edi&ccedil;&atilde;o dos dados do curso
        $altera_curso = $con->prepare("update curso set
                            id_plano                 = :id_plano,
                            nome                     = :nome,
                            descricao                = :descricao,
                            grau                     = :grau,
                            descricaoPapeletas       = :descricaoPapeletas,
                            descricaoDeclaracoes     = :descricaoDeclaracoes,
                            tipoAvaliacao            = :tipoAvaliacao,
                            mediaFinalMinima         = :mediaFinalMinima,
                            presencaMinima           = :presencaMinima,
                            mediaRecuperacaoMinima   = :mediaRecuperacaoMinima,
                            notaMinimaReprovacao     = :notaMinimaReprovacao,
                            casasDecimais            = :casasDecimais,
                            tipoArredondamento       = :tipoArredondamento,
                            solicitarPreConselho     = :solicitarPreConselho,
                            solicitarConselho        = :solicitarConselho,
                            solicitarExame           = :solicitarExame,
                            formaCalculo             = :formaCalculo,
                            formaCalculoExame        = :formaCalculoExame,
                            periodosAvaliacao        = :periodosAvaliacao,
                            nMtRec                   = :nMtRec,
                            nMtRep                   = :nMtRep,
                            dias_letivos             = :dias_letivos
                            where id = :id     
                                 ");
        $altera_curso->execute(array(
                                    ':id_plano'                 => $id_plano,
                                    ':nome'                     => $nome,
                                    ':descricao'                => $descricao,
                                    ':grau'                     => $grau,
                                    ':descricaoPapeletas'       => $descricaoPapeletas,
                                    ':descricaoDeclaracoes'     => $descricaoDeclaracoes,
                                    ':tipoAvaliacao'            => $tipoAvaliacao,
                                    ':mediaFinalMinima'         => $mediaFinalMinima,
                                    ':presencaMinima'           => $presencaMinima,
                                    ':mediaRecuperacaoMinima'   => $mediaRecuperacaoMinima,
                                    ':notaMinimaReprovacao'     => $notaMinimaReprovacao,
                                    ':casasDecimais'            => $casasDecimais,
                                    ':tipoArredondamento'       => $tipoArredondamento,
                                    ':solicitarPreConselho'     => $solicitarPreConselho,
                                    ':solicitarConselho'        => $solicitarConselho,
                                    ':solicitarExame'           => $solicitarExame,
                                    ':formaCalculo'             => $formaCalculo,
                                    ':formaCalculoExame'        => $formaCalculoExame,
                                    ':periodosAvaliacao'        => $periodosAvaliacao,
                                    ':nMtRec'                   => $nMtRec,
                                    ':nMtRep'                   => $nMtRep,
                                    ':dias_letivos'             => $dias_letivos,
                                    ':id'                       => $_POST['id']
        ));
              
        #echo "altera os dados do aluno aqui";
        $status = $altera_curso->errorCode();
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
  //grava os dados do curso
  $grava_curso=$con->prepare("insert into curso (id_plano,
                                      nome,
                                      descricao,
                                      grau,
                                      descricaoPapeletas,
                                      descricaoDeclaracoes,
                                      tipoAvaliacao,
                                      mediaFinalMinima,
                                      presencaMinima,
                                      mediaRecuperacaoMinima,
                                      notaMinimaReprovacao,
                                      casasDecimais,
                                      tipoArredondamento,
                                      solicitarPreConselho,
                                      solicitarConselho,
                                      solicitarExame,
                                      formaCalculo,
                                      formaCalculoExame,
                                      periodosAvaliacao,
                                      dtCriacao,
                                      nMtRec,
                                      nMtRep,
                                      dias_letivos
                                      )values(
                                        :id_plano,
                                        :nome,
                                        :descricao,
                                        :grau,
                                        :descricaoPapeletas,
                                        :descricaoDeclaracoes,
                                        :tipoAvaliacao,
                                        :mediaFinalMinima,
                                        :presencaMinima,
                                        :mediaRecuperacaoMinima,
                                        :notaMinimaReprovacao,
                                        :casasDecimais,
                                        :tipoArredondamento,
                                        :solicitarPreConselho,
                                        :solicitarConselho,
                                        :solicitarExame,
                                        :formaCalculo,
                                        :formaCalculoExame,
                                        :periodosAvaliacao,
                                        :dtCriacao,
                                        :nMtRec,
                                        :nMtRep,
                                        :dias_letivos
                                                )");
  $grava_curso->execute(array(
                        ':id_plano'                 => $id_plano,
                        ':nome'                     => $nome,
                        ':descricao'                => $descricao,
                        ':grau'                     => $grau,
                        ':descricaoPapeletas'       => $descricaoPapeletas,
                        ':descricaoDeclaracoes'     => $descricaoDeclaracoes,
                        ':tipoAvaliacao'            => $tipoAvaliacao,
                        ':mediaFinalMinima'         => $mediaFinalMinima,
                        ':presencaMinima'           => $presencaMinima,
                        ':mediaRecuperacaoMinima'   => $mediaRecuperacaoMinima,
                        ':notaMinimaReprovacao'     => $notaMinimaReprovacao,
                        ':casasDecimais'            => $casasDecimais,
                        ':tipoArredondamento'       => $tipoArredondamento,
                        ':solicitarPreConselho'     => $solicitarPreConselho,
                        ':solicitarConselho'        => $solicitarConselho,
                        ':solicitarExame'           => $solicitarExame,
                        ':formaCalculo'             => $formaCalculo,
                        ':formaCalculoExame'        => $formaCalculoExame,
                        ':periodosAvaliacao'        => $periodosAvaliacao,
                        ':dtCriacao'                => date('Y-m-d H-i-s'),
                        ':nMtRec'                   => $nMtRec,
                        ':nMtRep'                   => $nMtRep,
                        ':dias_letivos'             => $dias_letivos
                                    
  ));

  
  $status = $grava_curso->errorCode();
}
?>

<div class="titulo_interna">
<i class="fa fa-graduation-cap" aria-hidden="true"></i>Acad&ecirc;mico
</div>
<div class="content_form">
    <?php
    if(@$_GET['fun']=="ed"){$titulo = "Editar Curso";}else{$titulo = "Adicionar Curso";}
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
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['nome']!=""){echo $resultado[0]['nome'];}?>" id="nome" name="nome" title="Nome do Curso" maxlength="60" required>
            <br>
            <span class="legenda_bloco">Nome do Curso.</span>
        </td>
    </tr>

    <tr>
    <th><label for="descricao">Descri&ccedil;&atilde;o do curso.<span class="obrigatorio"> *</span></label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['descricao']!=""){echo $resultado[0]['descricao'];}?>" id="descricao" name="descricao" title="Email do Curso(a)" maxlength="60" required>
            <br><span class="legenda_bloco">Descri&ccedil;&atilde;o do Curso.</span>
        </td>
    </tr>
    
    <tr>
      <th>
        <label for="id_plano">Plano de pagamento.<span class="obrigatorio"> *</span></label></th>
      <td><select name="id_plano" id="id_plano" class="form_campo" required>
        <option value="">Selecione o plano</option>
        <?$buscarPlano=$con->prepare("SELECT * FROM planos where 1");
          $buscarPlano->execute();
          $resultadoPlano = $buscarPlano->fetchAll(PDO::FETCH_ASSOC);
          foreach($resultadoPlano as $dado){
            if(@$resultado[0]['id_plano']==$dado['id']){$selecionado = "selected";}else{$selecionado="";}
                    echo"<option value='".$dado['id']."' ".$selecionado.">".$dado['plano']."</option>";
            
        }
    ?>
      </select><a href="?op=add_plano" style="font-size: 20px;">+</a></td>
    </tr>

    <tr>
        <th><label for="grau">Grau.<span class="obrigatorio"> *</span></label>
        </th>
        <td>
          <input type="radio" title="Curso Ber&ccedil;&aacute;rio" name="grau" id="grau7" value="1" <?if (@$resultado[0]['grau']==1){echo "checked";}?>/>
          <span class="legenda_bloco">Curso Ber&ccedil;&aacute;rio</span><br>
          <input type="radio" title="Ed. Infantil" name="grau" id="grau8" value="2" <?if (@$resultado[0]['grau']==2){echo "checked";}?> />
          <span class="legenda_bloco">Ed. Infantil</span><br>
          
    </tr>
   
    <tr>
        <th><label for="dias_letivos">Dias letivos.</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['dias_letivos']!=""){echo $resultado[0]['dias_letivos'];}?>" id="dias_letivos" name="dias_letivos" title="dias letivos" maxlength="10">
          <span class="legenda_bloco"><br />
          Quantidade de dias letivos do curso.</span></td>
    </tr>

    <tr>
      <th><label form="nMtRep">Mat&eacute;rias para reprova&ccedil;&atilde;o.</label></th>
      <td>
        <input class="form_campo" type="text" value="<?php if(@$resultado[0]['nMtRep']!=""){echo $resultado[0]['nMtRep'];}?>" id="nMtRep" name="nMtRep" title="" maxlength="20">
        <br />
        <span class="legenda_bloco">Quantidade de mat&eacute;rias necess&aacute;rias para reprova&ccedil;&atilde;o autom&aacute;tica.</span>        </td>
    </tr>
    <tr>
      <th><label form="nMtRec">Mat&eacute;rias para recupera&ccedil;&atilde;o.</label></th>
      <td>
        <input class="form_campo" type="text" value="<?php if(@$resultado[0]['nMtRec']!=""){echo $resultado[0]['nMtRec'];}?>" id="nMtRec" name="nMtRec" title="" maxlength="20">
        <br />
        <span class="legenda_bloco">Quantidade de mat&eacute;rias necess&aacute;rias para recupera&ccedil;&atilde;o</span>        </td>
    </tr>

    <tr>
      <th><label for="disciplinas">Disciplinas.</label></th>
      <td><div class="passarItensSelects">
        <div class="select">
          <span class="legenda bloco">Disciplinas selecionadas</span>
          <select name="disciplinasSelect[]" multiple="multiple" class="form_campo" id="disciplinasSelect" title="Disciplinas selecionadas">
          <?php
          #mostra as disciplinas cadastradas no curso
          $buscaDisciplinas = $con->prepare("SELECT disciplina.id,disciplina.nome FROM disciplina,curso_disciplina WHERE idCurso=:idCurso and disciplina.id = curso_disciplina.idDisciplina");
          $buscaDisciplinas->execute(array(':idCurso'=> $idCurso));
          $resultadoDisciplina = $buscaDisciplinas->fetchAll(PDO::FETCH_ASSOC);
          
            
            foreach ($resultadoDisciplina as $disciplina) {
              echo "<option value='".$disciplina['id']."'>".$disciplina['nome']."</option>";
            }
          
          ?>
          </select>														
          </div>
        <div class="controles">
          <!--<input type="button" title="Incluir todas as disciplinas" value="<<" onclick="passarItensSelects('disciplinas', 'disciplinasSelect', 'disciplinasSelect', 'hidden_disciplinas', true);">-->
          <input type="button" title="Incluir disciplinas selecionadas" value="<" onclick="passarItensSelects('disciplinas', 'disciplinasSelect', 'disciplinasSelect', 'hidden_disciplinas', false);">
          <input type="button" title="Remover disciplinas selecionadas" value=">" onclick="passarItensSelects('disciplinasSelect', 'disciplinas', 'disciplinasSelect', 'hidden_disciplinas', false);">
          <!--<input type="button" title="Remover todas as disciplinas" value=">>" onclick="passarItensSelects('disciplinasSelect', 'disciplinas', 'disciplinasSelect', 'hidden_disciplinas', true);">-->
          </div>
        <div class="select">
          <span class="legenda bloco" style="white-space:nowrap">Disciplinas n&atilde;o selecionadas</span>
          <select name="disciplinas" multiple="multiple" class="form_campo" id="disciplinas" title="Lista de disciplinas">
          <?php 
          $buscar=$con->prepare("SELECT id,nome FROM disciplina where 1");
          $buscar->execute();
          $resultadoDisc = $buscar->fetchAll(PDO::FETCH_ASSOC); 
          function udiffCompare($a, $b)
        {
            return $a['id'] - $b['id'];
        }

        $arrdiff = array_udiff($resultadoDisc,$resultadoDisciplina, 'udiffCompare');
        
          ?>
          
          <?php
          foreach($arrdiff as $dado){
                    echo"<option value='".$dado['id']."'>".$dado['nome']."</option>";
        }
    ?>
            </select>
          </div>
        <span class="legenda bloco">Escolha as disciplinas para o curso, para selecionar duas ou mais disciplinas utilize as teclas Ctrl para seleção independente ou Shift para seleção sequencial.</span>
        <div class="clearFloat"></div>
        </div><input type="hidden" id="hidden_disciplinas" name="hidden_disciplinas" value=""></td>
    </tr>
    <tr>	
        <th><label for="descricaoPapeletas">Descri&ccedil;&atilde;o para papeletas.</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['descricaoPapeletas']!=""){echo $resultado[0]['descricaoPapeletas'];}?>" id="descricaoPapeletas" name="descricaoPapeletas" title="RG" maxlength="16">
            <br><span class="legenda_bloco">
             O que ser&aacute; apresentado nas papeletas.
            </span>
        </td>
    </tr>
    <tr>	
        <th><label for="descricaoDeclaracoes">Descri&ccedil;&atilde;o para declara&ccedil;&otilde;es.</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['descricaoDeclaracoes']!=""){echo $resultado[0]['descricaoDeclaracoes'];}?>" id="descricaoDeclaracoes" name="descricaoDeclaracoes" title="Descri&ccedil;&atilde;o das Declara&ccedil;&otilde;es" maxlength="16" style="text-transform:uppercase">
            <br><span class="legenda_bloco">O que ser&aacute; apresentado nas declara&ccedil;&otilde;es.</span>
        </td>
    </tr>

    <tr>	
        <th><label for="tipoAvaliacao">Tipo de avalia&ccedil;&atilde;o.</label></th>
        <td>
            <select name="tipoAvaliacao" class="form_campo">
            
              <option value="1" <?php if(@$resultado[0]['tipoAvaliacao']=="1"){echo " selected";}?>>Conceito</option>
              <option value="2" <?php if(@$resultado[0]['tipoAvaliacao']=="2"){echo " selected";}?>>Nota</option>
            </select>
            
            <br><span class="legenda_bloco">
             Qual ser&aacute; o tipo de avalia&ccedil;&atilde;o para esse curso, se &eacute; conceitual ou por nota.
            </span>	
        </td>
    </tr>
 
    <tr>	
        <th><label for="mediaFinalMinima">M&eacute;dia final m&iacute;nima.</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['mediaFinalMinima']!=""){echo $resultado[0]['mediaFinalMinima'];}?>" id="mediaFinalMinima" name="mediaFinalMinima" title="mediaFinalMinima" maxlength="16" style="text-transform:uppercase">
            <br><span class="legenda_bloco">Valor da m&eacute;dia m&iacute;nima final</span>
        </td>
    </tr>

    <tr>	
        <th><label for="presencaMinima">Presen&ccedil;a m&iacute;nima obrigat&oacute;ria.</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['presencaMinima']!=""){echo $resultado[0]['presencaMinima'];}?>" id="presencaMinima" name="presencaMinima" title="presencaMinima" maxlength="10" style="text-transform:uppercase">
            <br><span class="legenda_bloco">Faltas m&iacute;nimas para esse curso.</span>
        </td>
    </tr>
    <tr>	
        <th><label for="mediaRecuperacaoMinima">M&eacute;dia recupera&ccedil;&atilde;o m&iacute;nima.</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['mediaRecuperacaoMinima']!=""){echo $resultado[0]['mediaRecuperacaoMinima'];}?>" id="mediaRecuperacaoMinima" name="mediaRecuperacaoMinima" title="Data da Admiss&atilde;o" maxlength="12" style="text-transform:uppercase">
            <br><span class="legenda_bloco">Valor da m&eacute;dia m&iacute;nima para recupera&ccedil;&atilde;o.</span>
        </td>
    </tr>
    
 
    <tr>
      <th><label for="notaMinimaReprovacao">Nota m&iacute;nima para reprova&ccedil;&atilde;o.</label></th>
      <td><input class="form_campo" type="text" value="<?php if(@$resultado[0]['notaMinimaReprovacao']!=""){echo $resultado[0]['notaMinimaReprovacao'];}?>" id="notaMinimaReprovacao" name="notaMinimaReprovacao" title="notaMinimaReprovacao" maxlength="12" style="text-transform:uppercase" /></td>
    </tr>
    <tr>
      <th><label for="casasDecimais">Casas decimais para exibi&ccedil;&atilde;o.</label></th>
      <td><input class="form_campo" type="text" value="<?php if(@$resultado[0]['casasDecimais']!=""){echo $resultado[0]['casasDecimais'];}?>" id="casasDecimais" name="casasDecimais" title="casasDecimais" maxlength="12" style="text-transform:uppercase" /></td>
    </tr>
    <!--<tr>
      <th><label for="tipoArredondamento">Arredondamento.</label></th>
      <td><select name="tipoArredondamento" id="tipoArredondamento" class="form_campo">
        <option value="">Selecione o plano</option>
      </select></td>
    </tr>-->
    <tr>
      <th><label for="periodosAvaliacao">N&uacute;mero de per&iacute;odos de avalia&ccedil;&atilde;o.</label></th>
      <td><input class="form_campo" type="text" value="<?php if(@$resultado[0]['periodosAvaliacao']!=""){echo $resultado[0]['periodosAvaliacao'];}?>" id="periodosAvaliacao" name="periodosAvaliacao" title="periodosAvaliacao" maxlength="12" style="text-transform:uppercase" /></td>
    </tr>
    <tr>
      <th><label for="solicitarPreConselho">Utilizar Pr&eacute;-conselho</label></th>
      <td><input type="checkbox" title="Utilizar Pr&eacute;-conselho" name="solicitarPreConselho" id="solicitarPreConselho" value="1" <?php if(@$resultado[0]['solicitarPreConselho']=="1"){echo "checked";}?>/></td>
    </tr>
    <tr>
      <th><label for="solicitarExame">Utilizar Exame</label></th>
      <td><input type="checkbox" title="Utilizar Exame" name="solicitarExame" id="solicitarExame" value="1"  <?php if(@$resultado[0]['solicitarExame']=="1"){echo "checked";}?>/></td>
    </tr>
    <tr>
      <th><label for="solicitarConselho">Utilizar Conselho</label></th>
      <td><input type="checkbox" title="Utilizar conselho" name="solicitarConselho" id="solicitarConselho" value="1"  <?php if(@$resultado[0]['solicitarConselho']=="1"){echo "checked";}?>/></td>
    </tr>
    <tr>
      <th><label for="formaCalculo">F&oacute;rmula c&aacute;lculo.</label></th>
      <td><input class="form_campo" type="text" value="<?php if(@$resultado[0]['formaCalculo']!=""){echo $resultado[0]['formaCalculo'];}?>" id="formaCalculo" name="formaCalculo" title="Forma de Calulo." maxlength="12" style="text-transform:uppercase" />
        <br /><span class="legenda_bloco">
        Formulas de c&aacute;lculo da m&eacute;dia final antes do exame.<br />
        Utilize as vari&aacute;veis: MA (M&eacute;dia Aritm&eacute;tica), SP (Soma dos Per&iacute;odos), PC (Pr&eacute;-Conselho), C (Conselho) ou P1 [2, 3, ...] (M&eacute;dia dos per&iacute;odos)</span></td>
    </tr>
    <tr>
      <th><label for="formaCalculoExame">F&oacute;rmula de c&aacute;lculo com exame.</label></th>
      <td><input class="form_campo" type="text" value="<?php if(@$resultado[0]['formaCalculoExame']!=""){echo $resultado[0]['formaCalculoExame'];}?>" id="formaCalculoExame" name="formaCalculoExame" title="Formula de Calculo do Exame" maxlength="12" style="text-transform:uppercase" />
        <br /><span class="legenda_bloco">
        Formulas de c&aacute;lculo da m&eacute;dia final ap&oacute;s o exame.<br />
        Utilize as vari&aacute;veis: MA (M&eacute;dia Aritm&eacute;tica), SP (Soma dos Per&iacute;odos), EX (Exame), PC (Pr&eacute;-Conselho), C (Conselho) ou P1 [2, 3, ...] (M&eacute;dia dos per&iacute;odos)</span></td>
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
        <input type="hidden" name="id" value="<?= $idCurso;?>">
    <?php }?>

    
</form>
</div>


    <?php }else{
        if($dados_atualizados !="ok"){
        ?>

      <div class="form_comp">
        Curso gravado com sucesso<br>
        Nome: <?= $nome ;
        $id;
          $LAST_ID = $con->lastInsertId();
          foreach($_POST['disciplinasSelect'] as $dado2){
            
            $grava_disciplina_curso = $con ->prepare("insert into curso_disciplina ( 
                                                    idCurso,
                                                    idDisciplina
                                                    )values(
                                                      :idCurso,
                                                      :idDisciplina)
                    ");
                    $grava_disciplina_curso->execute(array(
                                                      ':idCurso'      => $LAST_ID,
                                                      ':idDisciplina' => $dado2['disciplinasSelect']
                    ));
                    }
        ?><br>
        

      </div>

   <?php }else{?>
    <div class="form_comp">
        Curso alterado com sucesso<br>
        
        Nome: <?php echo $nome;

      
        $deleta_disciplina = $con ->prepare("delete from curso_disciplina where 
        idCurso = :idCurso");
        $deleta_disciplina->execute(array(
                  ':idCurso'      => $idCurso));

        foreach($_POST['disciplinasSelect'] as $dado2){
          
          $grava_disciplina_curso = $con ->prepare("insert into curso_disciplina ( 
                                                    idCurso,
                                                    idDisciplina
                                                    )values(
                                                      :idCurso,
                                                      :idDisciplina)
                    ");
                    $grava_disciplina_curso->execute(array(
                                                      ':idCurso'      => $idCurso,
                                                      ':idDisciplina' => $dado2['disciplinasSelect']
                    ));
                    }
        }

        
        ?><br>
        

      </div>

<?php } ?>