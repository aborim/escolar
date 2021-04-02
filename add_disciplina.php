<?php
//error_reporting(0);
//inclui a conexao com banco de dados e fun&ccedil;&otilde;es
include('conexao.php');
include('functions.php');

#pega as vari&aacute;veis enviadas pelo formul&aacute;rio
$codigo                   = $_POST['codigo'];
$nome                     = $_POST['nome'];
$descricaoPapeletas       = $_POST['descricaoPapeletas'];
$descricaoDeclaracao      = $_POST['descricaoDeclaracao'];
$dtCriacao                = substr($_POST['dtCriacao'],6,4)."-".substr($_POST['dtCriacao'],3,2)."-".substr($_POST['dtCriacao'],0,2);
$identificacaoHistorico   = $_POST['identificacaoHistorico'];


if(@$_GET['fun']=="ed"){
    
    $edicao = "sim";
    #aqui vem a busca dos dados do disciplina pela variavel de entrada
    $busca_disciplina = $con->prepare("select * from disciplina where disciplina.id=:id");
    $busca_disciplina->bindValue(':id',$idDisciplina);
    $busca_disciplina->execute();
    $resultado = $busca_disciplina->fetchAll(PDO::FETCH_ASSOC);
    
   
    if(@$_POST['edita']==1){
        #aqui vai a edi&ccedil;&atilde;o dos dados do disciplina
        $altera_disciplina = $con->prepare("update disciplina set
                            codigo                   = :codigo,
                            nome                     = :nome,
                            descricaoPapeletas       = :descricaoPapeletas,
                            descricaoDeclaracao     = :descricaoDeclaracao,
                            identificacaoHistorico   = :identificacaoHistorico
                            where id = :id     
                                 ");
        $altera_disciplina->execute(array(
                                    ':codigo'                   => $codigo,
                                    ':nome'                     => $nome,
                                    ':descricaoPapeletas'       => $descricaoPapeletas,
                                    ':descricaoDeclaracao'      => $descricaoDeclaracao,
                                    ':identificacaoHistorico'   => $identificacaoHistorico,
                                    ':id'                       => $_POST['idDisciplina']
        ));
              
        #echo "altera os dados do aluno aqui";
        $status = $altera_disciplina->errorCode();
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
  //grava os dados do disciplina
  $grava_disciplina=$con->prepare("insert into disciplina (codigo,
                                      nome,
                                      descricaoPapeletas,
                                      descricaoDeclaracao,
                                      dtCriacao,
                                      identificacaoHistorico
                                      )values(
                                        :codigo,
                                        :nome,
                                        :descricaoPapeletas,
                                        :descricaoDeclaracao,
                                        :dtCriacao,
                                        :identificacaoHistorico
                                                )");
  $grava_disciplina->execute(array(
                        ':codigo'                   => $codigo,
                        ':nome'                     => $nome,
                        ':descricaoPapeletas'       => $descricaoPapeletas,
                        ':descricaoDeclaracao'     => $descricaoDeclaracao,
                        ':dtCriacao'                => date('Y-m-d H-i-s'),
                        ':identificacaoHistorico'   => $identificacaoHistorico
                                    
  ));
  $status = $grava_disciplina->errorCode();
}
?>

<div class="titulo_interna">
    <i class="fa fa-users" aria-hidden="true"></i>Academico
</div>
<div class="content_form">
    <?php
    if(@$_GET['fun']=="ed"){$titulo = "Editar Disciplina";}else{$titulo = "Adicionar Disciplina";}
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
    <th><label for="codigo">Código da disciplina.<span class="obrigatorio"> *</span></label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['codigo']!=""){echo $resultado[0]['codigo'];}?>" id="codigo" name="codigo" title="Código da disciplina" maxlength="10" required>
            <br><span class="legenda_bloco">Código para identificação da  Disciplina.</span>
        </td>
    </tr>   
    <tr>
    <th><label for="nome">Nome.<span class="obrigatorio"> *</span></label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['nome']!=""){echo $resultado[0]['nome'];}?>" id="nome" name="nome" title="Nome do Disciplina" maxlength="60" required>
            
        </td>
    </tr>    
    <tr>
      <th><label for="descricaoDeclaracao">Descrição das Declarações.<span class="obrigatorio"> *</span></label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['descricaoDeclaracao']!=""){echo $resultado[0]['descricaoDeclaracao'];}?>" id="descricaoDeclaracao" name="descricaoDeclaracao" title="Descrição das Declarações da Disciplina" maxlength="60" required>
      </td>
    </tr>

    <tr>
      <th><label for="descricaoPapeletas">Descrição das Papeletas.<span class="obrigatorio"> *</span></label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['descricaoPapeletas']!=""){echo $resultado[0]['descricaoPapeletas'];}?>" id="descricaoPapeletas" name="descricaoPapeletas" title="Descrição das Papeletas da Disciplina" maxlength="60" required>
      </td>
    </tr>
   
    <tr>
        <th><label for="identificacaoHistorico">Identificação dos históricos.</label></th>
        <td>
            <select name="identificacaoHistorico" id="identificacaoHistorico" class="form_campo">
              <option value="0"<?php if(utf8_decode(@$resultado[0]['identificacaoHistorico']=="0")){echo "selected";}?> required> Núcleo comum</option>
              <option value="1"<?php if(utf8_decode(@$resultado[0]['identificacaoHistorico']=="1")){echo "selected";}?>> Núcleo diversificado</option>
              <br>
            <span class="legenda_bloco">Selecione a identificação dos históricos.</span>
          </td>
    </tr>
   
</tbody></table>
</td>
</tr>    
</tbody></table>

    </div>
    
        <div class="btn_pos">
    <?php if($edicao!="sim"){?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Adicionar Disciplina"></div>
        <input type="hidden" name="grava" value="1">
    <?php }else{?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Editar Disciplina"></div>
        <input type="hidden" name="edita" value="1">
        <input type="hidden" name="fun" value="ed">
        <input type="hidden" name="id" value="<?=$id?>">
    <?php }?>

    
</form>
</div>


    <?php }else{
        if($dados_atualizados !="ok"){
        ?>

      <div class="form_comp">
        Disciplina gravado com sucesso<br>
        Nome: <?= $nome?><br>
        

      </div>

   <?php }else{?>
    <div class="form_comp">
        Disciplina alterado com sucesso<br>
        
        Nome: <?= $nome?><br>
        

      </div>

<?php }
}?>