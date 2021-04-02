<?php
//error_reporting(0);
//inclui a conexao com banco de dados e fun&ccedil;&otilde;es
include('conexao.php');
include('functions.php');

#pega as vari&aacute;veis enviadas pelo formul&aacute;rio
$plano                    = $_POST['plano'];
$valor                    = $_POST['valor'];
$val_matricula            = $_POST['val_matricula'];
$n_parc                   = $_POST['n_parc'];
$val_parc                 = $_POST['val_parc'];
$dia_venc                 = $_POST['dia_venc'];

if(@$_GET['fun']=="ed"){
    
    $edicao = "sim";
    #aqui vem a busca dos dados do plano pela variavel de entrada
    $busca_plano = $con->prepare("select * from planos where planos.id=:id");
    $busca_plano->bindValue(':id',$idPlano);
    $busca_plano->execute();
    $resultado = $busca_plano->fetchAll(PDO::FETCH_ASSOC);
    
   
    if(@$_POST['edita']==1){
        #aqui vai a edi&ccedil;&atilde;o dos dados do plano
        $altera_plano = $con->prepare("update planos set
                            plano                    = :plano,
                            valor                    = :valor,
                            val_matricula            = :val_matricula,
                            n_parc                   = :n_parc,
                            val_parc                 = :val_parc,
                            dia_venc                 = :dia_venc
                            where id = :id     
                                 ");
        $altera_plano->execute(array(
                                    ':plano'                    => $plano,
                                    ':valor'                    => $valor,
                                    ':val_matricula'            => $val_matricula,
                                    ':n_parc'                   => $n_parc,
                                    ':val_parc'                 => $val_parc,
                                    ':dia_venc'                 => $dia_venc,
                                    ':id'                       => $_POST['id']
        ));
        #echo "altera os dados do aluno aqui";
        $status = $altera_plano->errorCode();
        if($status =="00000"){
            $dados_atualizados = "ok";
        }
    }


}else{
    $edicao ="nao";
}


//modulo de cadastro de plano, arquivo inserido ao menu restrito atrav&eacute;s de include
if(isset($_POST['grava'])==1){
//armazena os dados das vari&aacute;veis do formul&aacute;rio
  //grava os dados do plano
  $grava_plano=$con->prepare("insert into planos (plano,
                                      valor,
                                      val_matricula,
                                      n_parc,
                                      val_parc,
                                      dia_venc
                                      )values(
                                        :plano,
                                        :valor,
                                        :val_matricula,
                                        :n_parc,
                                        :val_parc,
                                        :dia_venc
                                                )");
  $grava_plano->execute(array(
                    ':plano'                    => $plano,
                    ':valor'                    => $valor,
                    ':val_matricula'            => $val_matricula,
                    ':n_parc'                   => $n_parc,
                    ':val_parc'                 => $val_parc,
                    ':dia_venc'                 => $dia_venc
  ));
  $status = $grava_plano->errorCode();
}
?>

<div class="titulo_interna">
    <i class="fa fa-users" aria-hidden="true"></i>Parâmetros
</div>
<div class="content_form">
    <?php
    if(@$_GET['fun']=="ed"){$titulo = "Editar Plano";}else{$titulo = "Adicionar Plano";}
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
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['plano']!=""){echo $resultado[0]['plano'];}?>" id="plano" name="plano" title="Nome do Plano" maxlength="60" required>
        </td>
    </tr>

    <tr>
    <th><label for="descricao">Valor da Anuidade.<span class="obrigatorio"> *</span></label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['valor']!=""){echo $resultado[0]['valor'];}?>" id="descricao" name="valor" title="Valor do Plano" maxlength="60" required>
        </td>
    </tr>
   
    <tr>
        <th><label for="dias_letivos">Valor da Matrícula.</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['val_matricula']!=""){echo $resultado[0]['val_matricula'];}?>" id="val_matricula" name="val_matricula" title="Valor da matrícula" maxlength="10">
    </tr>

    <tr>
      <th><label form="n_parc">Número de Parcelas.</label></th>
      <td>
        <input class="form_campo" type="text" value="<?php if(@$resultado[0]['n_parc']!=""){echo $resultado[0]['n_parc'];}?>" id="n_parc" name="n_parc" title="" maxlength="20">
      </td>
    </tr>
    <tr>
      <th><label form="val_parc">Valor das Parcelas.</label></th>
      <td>
        <input class="form_campo" type="text" value="<?php if(@$resultado[0]['val_parc']!=""){echo $resultado[0]['val_parc'];}?>" id="val_parc" name="val_parc" title="" maxlength="20">
      </td>
    </tr>
    <tr>	
        <th><label for="dia_venc">Dia do Vencimento</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['dia_venc']!=""){echo $resultado[0]['dia_venc'];}?>" id="dia_venc" name="dia_venc" title="Dia do Vencimento da parcela" maxlength="16">
        
        </td>
    </tr>
    
</tbody></table>
</td>
</tr>    
</tbody></table>

    </div>
    
        <div class="btn_pos">
    <?php if($edicao!="sim"){?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Adicionar Plano"></div>
        <input type="hidden" name="grava" value="1">
       
    <?php }else{?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Editar Plano"></div>
        <input type="hidden" name="edita" value="1">
        <input type="hidden" name="fun" value="ed">
        <input type="hidden" name="id" value="<?=$idPlano?>">
    <?php }?>

    
</form>
</div>


    <?php }else{
        if($dados_atualizados !="ok"){
        ?>

      <div class="form_comp">
        Plano gravado com sucesso<br>
        Nome: <?= $plano;
          
        ?><br>
        

      </div>

   <?php }else{?>
    <div class="form_comp">
        Plano alterado com sucesso<br>
        
        Nome: <?= $plano?><br>
        

      </div>

<?php }
}?>