<?php
//inclui a conexao com banco de dados e fun&ccedil; &otilde;es
include('conexao.php');
include('functions.php');
#quando for necess�rio substituir o ano vigente escrito aqui pelo que vem no login
$anoVigente = 2020;


if($_POST['qp']==1){


  foreach ($_POST['valor'] as $key => $value) {
    
    
    if($_POST['pago'][$key]==1){
      $alteraParcelas = $con->prepare("update pagamentos set valor_pago = :valParc, data_pagamento = :venc, pg=:pago, local_baixa=:local where id=:idParc");
      $alteraParcelas->execute(array(':valParc'=>formatoMoedaBD($_POST['valor'][$key]),':venc'=>formatoDataBD($_POST['venc'][$key]),':idParc'=>$key,':pago'=>1,':local'=>1));
    }else{
      $alteraParcelas = $con->prepare("update pagamentos set valor_pago = :valParc, data_pagamento = :venc where id=:idParc");
      $alteraParcelas->execute(array(':valParc'=>formatoMoedaBD($_POST['valor'][$key]),':venc'=>formatoDataBD($_POST['venc'][$key]),':idParc'=>$key));
    }
    
     
  }
  $status ='ok';
 
}else{
  #busca o nome da classe que o aluno est� matriculado
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
    <div class="titulo">Quita&ccedil;&atilde;o de Parcelas</div>

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
            <span class="legenda_bloco">Classe que o aluno est&aacute; matriculado.</span></td>
    </tr>
    <tr >
      <td colspan="2">
        <table class="formulario" style="width: 700px;">
        <tr>
              <th>N&uacute;mero da Parcela</th>
              <th>Valor da Parcela</th>
              <th>Vencimento</th>
              <th>Valor Pago</th>
              <th>Data do Pagamento</th>
              <th>Quitado</th>
          </tr>
          <?php for($i=0;$i<sizeof($resultadoMatriculas);$i++){?>
            <tr>
                <td style="font-size: 11px;background: #C0C0C0;">Parcela: <?=$resultadoMatriculas[$i]['num_parc']."/".sizeof($resultadoMatriculas)?></td>    
                <td style="font-size: 11px;background: #C0C0C0;"><?=formatoMoeda($resultadoMatriculas[$i]['valor_parc'])?></td>
                <td style="font-size: 11px;background: #C0C0C0;"><?=formatoData($resultadoMatriculas[$i]['venc'])?></td>
                <td style="font-size: 11px;background: #C0C0C0;text-align: center;"><input type="text" name="valor[<?=$resultadoMatriculas[$i]['id']?>]" id="valor[<?=$resultadoMatriculas[$i]['id']?>]" value="<?=formatoMoeda($resultadoMatriculas[$i]['valor_pago'])?>" size="10"></td>
                <!--<td style="font-size: 11px;background: #C0C0C0;text-align: center;"><input type="text" name="valor[<?=$resultadoMatriculas[$i]['id']?>]" id="valor[<?=$resultadoMatriculas[$i]['id']?>]" value="<?=formatoMoeda($resultadoMatriculas[$i]['valor_parc'])?>" size="10"></td>-->
                <td style="font-size: 11px;background: #C0C0C0;text-align: center;"><input type="text" name="venc[<?=$resultadoMatriculas[$i]['id']?>]" id="venc[<?=$resultadoMatriculas[$i]['id']?>]" value="<?if($resultadoMatriculas[$i]['data_pagamento']!=0){echo formatoData($resultadoMatriculas[$i]['data_pagamento']);}?>" class="date-format" size="11" onkeypress="formatoData(this)"><br><span class="error" style="display: none;font-size: 9px;color: red;font-weight: bold;">Data inv&aacute;lida, formato permitido &eacute; dd/mm/yyyy .</span></td>
                <td style="font-size: 11px;background: #C0C0C0;text-align: center;"><input type="checkbox" name="pago[<?=$resultadoMatriculas[$i]['id']?>]" value="1" onclick="verificaData(this)" <?if($resultadoMatriculas[$i]['pg']==1){echo("checked"); } ?>></td>
          </tr>
          <?php }?>
        </table>
      </td>
      
    </tr>
    <tr>
    <td colspan="4" align="right"><label for="nome">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input type="hidden" name="qp" value="1" />
      <span class="btn_pos">
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Salvar Parcelas" />
      </span></td>
    </tr>
    
    
    
</tbody></table>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> 
<script type="text/javascript">
    var isShift = false;
    var seperator = "/";
    function formatoData (e) {
        //Only allow Numeric Keys.
        e.onkeydown = function (e) {
            return IsNumeric(this, e.keyCode);
        };

        //Validate Date as User types.
        e.onkeyup = function (e) {
            ValidateDateFormat(this, e.keyCode);
        };
        
    };
 
    function IsNumeric(input, keyCode) {
        if (keyCode == 16) {
            isShift = true;
        }
        //Allow only Numeric Keys.
        if (((keyCode >= 48 && keyCode <= 57) || keyCode == 8 || keyCode <= 37 || keyCode <= 39 || (keyCode >= 96 && keyCode <= 105)) && isShift == false) {
            if ((input.value.length == 2 || input.value.length == 5) && keyCode != 8) {
                input.value += seperator;
            }
 
            return true;
        }
        else {
            return false;
        }
    };
 
    function ValidateDateFormat(input, keyCode) {
        var dateString = input.value;
        if (keyCode == 16) {
            isShift = false;
        }
        var regex = /(((0|1)[0-9]|2[0-9]|3[0-1])\/(0[1-9]|1[0-2])\/((19|20)\d\d))$/;
 
        //Check whether valid dd/MM/yyyy Date Format.
        if (regex.test(dateString) || dateString.length == 0) {
            ShowHideError(input, "none");
        } else {
            ShowHideError(input, "block");
        }
    };
 
    function ShowHideError(textbox, display) {
        var row = textbox.parentNode.parentNode;
        var errorMsg = row.getElementsByTagName("span")[0];
        if (errorMsg != null) {
            errorMsg.style.display = display;
        }
    };

    function verificaData(e){
      
      if (e.checked) {
        var n = e.name.indexOf("[");
        var campoCheck = e.name.substr(n);
        //alert(e.name.substr(-4));
        
        if(document.getElementById("venc"+campoCheck).value ==""){
          alert("Por favor preencha a data do pagamento.");
          document.getElementById("venc"+campoCheck).focus();
        }
         
      } 
    }
</script>    
</tbody></table>

    </div>
</form>
</div>


    <?php }else{?>
      <div class="form_comp">
        Parcelas quitadas com sucesso!<br>
        

      </div>

   <?php }?>
