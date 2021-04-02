<?php
//inclui a conexao com banco de dados e fun&ccedil; &otilde;es
include('conexao.php');
include('functions.php');
#quando for necessário substituir o ano vigente escrito aqui pelo que vem no login
$anoVigente = 2020;


if($_POST['ep']==1){

  foreach ($_POST['val_parc'] as $key => $value) {
    $alteraParcelas = $con->prepare("update pagamentos set valor_parc = :valParc, venc = :venc where id=:idParc");
    $alteraParcelas->execute(array(':valParc'=>formatoMoedaBD($_POST['val_parc'][$key]),':venc'=>formatoDataBD($_POST['venc_parc'][$key]),':idParc'=>$key));
    
  }
  $status ='ok';
  
}else{
  #busca o nome da classe que o aluno está matriculado
  $buscaMatriculas = $con->prepare("SELECT 
  aluno.nome as nomeAluno,
  aluno.rm as RM,
  classe.nome as classe,
  planos.plano,
  pagamentos.*
  FROM 
  classe,
  matriculas,
  aluno,
  planos,
  pagamentos
  where 
  aluno.id=matriculas.id_aluno and 
  classe.id_plano = planos.id and 
  matriculas.id_classe = classe.id and 
  matriculas.id_aluno=:idAluno and 
  classe.anoVigente=:anoVigente AND
  matriculas.id = pagamentos.id_matricula
  ");
  $buscaMatriculas->execute(array(':idAluno'=>$idAluno,':anoVigente'=>$anoVigente));
  $resultadoMatriculas = $buscaMatriculas->fetchAll(PDO::FETCH_ASSOC);
  #print_r($resultadoMatriculas);

}




?>

<div class="titulo_interna">
    <i class="fa fa-money" aria-hidden="true"></i>Financeiro
</div>
<div class="content_form">
<?php
    if(!isset($status)){?>
<form method="post" action="#" enctype="multipart/form-data" id="frmAluno">
    <div class="form_comp">
    <div class="titulo">Edição de Parcelas</div>

<table class="formulario" width="100%">
<tbody >
    <tr><td colspan="101"></td></tr>

    <tr >
      <th><label for="nome">Nome: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></th>
      <td><?=$resultadoMatriculas[0]['nomeAluno']?>
            <br />
            <span class="legenda_bloco">Nome  do aluno.</span></td>
    </tr>
    <tr >
      <th><label for="nome">RM: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></th>
      <td><?=$resultadoMatriculas[0]['RM']?>
            <br />
            <span class="legenda_bloco">RM do aluno.</span></td>
    </tr>
    <tr >
      <th><label for="nome">Classe: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></th>
      <td><?=$resultadoMatriculas[0]['classe']?>
            <br />
            <span class="legenda_bloco">Classe que o aluno está matriculado.</span></td>
    </tr>
    <tr >
      <td colspan="2">
        <table class="formulario">
        <tr>
              <th>Número da Parcela</th>
              <th>Valor da Parcela</th>
              <th>Vencimento</th>
          </tr>
          <?php for($i=0;$i<sizeof($resultadoMatriculas);$i++){?>
            <tr>
                <td style="font-size: 11px;background: #C0C0C0;">Parcela: <?=$resultadoMatriculas[$i]['num_parc']."/".sizeof($resultadoMatriculas)?></td>    
                <td style="font-size: 11px;background: #C0C0C0;"><input type="text" name="val_parc[<?=$resultadoMatriculas[$i]['id']?>]" value="<?=formatoMoeda($resultadoMatriculas[$i]['valor_parc'])?>"><br><span class="legenda_bloco">Para inserir as casas decimais utilize ponto (.)</span></td>
                <td style="font-size: 11px;background: #C0C0C0;"><input type="text" name="venc_parc[<?=$resultadoMatriculas[$i]['id']?>]" value="<?=formatoData($resultadoMatriculas[$i]['venc'])?>"></td>
          </tr>
          <?php }?>
        </table>
      </td>
      
    </tr>
    <tr>
    <td colspan="4" align="right"><label for="nome">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input type="hidden" name="ep" value="1" />
      <span class="btn_pos">
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Editar Parcelas" />
      </span></td>
    </tr>
    
    
    
</tbody></table>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> 
    
</tbody></table>

    </div>
</form>
</div>


    <?php }else{?>
      <div class="form_comp">
        Parcelas alteradas com sucesso!<br>
        

      </div>

   <?php }?>
