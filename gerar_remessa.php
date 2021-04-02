<?php
//error_reporting(0);
//inclui a conexao com banco de dados e fun&ccedil;&otilde;es
include('conexao.php');
include('functions.php');

if($_POST['geraRemessa']==1){
  #echo $_POST['dtInicio'], " ", $_POST['dtFim'];
  #echo $_SESSION['UnidadeFantasia'];
  $hoje = date("Y-m-d");
  $dtInicio = formatoDataBD($_REQUEST['dtInicio']);
  $dtFinal = formatoDataBD($_REQUEST['dtFim']);

  
 if(( $dtInicio == $dtFinal ) && ( $dtInicio == $hoje ) && ( $dtFinal == $hoje )){
  $compQuery = " and boletos.dtProcessamento='".$hoje."'";
    
 }elseif(( $dtInicio == $dtFinal ) && ( $dtInicio != $hoje ) && ( $dtFinal != $hoje )) {
  $compQuery = " and boletos.dtProcessamento='".$dtInicio."'";
    
 }else{
  $compQuery = " and 
  boletos.dtProcessamento >= '".$dtInicio."' and 
  boletos.dtProcessamento <= '".$dtFinal."'";
 }

 #echo $dtInicio, ' ',$dtFinal, ' ', $compQuery;
    $pagamentos = array();
    #busca todos os boletos com data de processamento igual ao solicitado
    $buscaBoleto = $con->prepare("select 
    matriculas.rm,
    responsavel.*, 
    endereco.*, 
    pagamentos.num_parc, 
    pagamentos.valor_parc, 
    pagamentos.venc, 
    boletos.nosso_numero, 
    boletos.dv,
    boletos.dtProcessamento, 
    boletos.remessa,
    boletos.identificador
from 
	responsavel, 
    aluno_responsavel, 
    aluno, 
    endereco,
    pagamentos,
    matriculas,
    boletos 
where 
	aluno_responsavel.idAluno = aluno.id and 
    aluno_responsavel.idResponsavel = responsavel.id and 
    aluno_responsavel.financeiro=1 and 
    responsavel.idEndereco = endereco.id AND 
    pagamentos.id_matricula = matriculas.id and 
    boletos.id_pagamento = pagamentos.id AND 
    matriculas.id_aluno = aluno.id ".$compQuery);
    $buscaBoleto->execute();
    $pos =0;
    $result = $buscaBoleto->fetchAll(PDO::FETCH_ASSOC);
    
    #print_r($result);
    $nSequencialArquivo="";
    foreach ($result as $value) {
      
      
        if(($value['remessa']=="")||($value['remessa']==0)){
          $nSequencialArquivo = mb_str_pad($nSequencialArquivo, 7, '0', STR_PAD_LEFT); //organiza o sequencial ativo pelo formato indicado
            //caso exista algum arquivo gera o próximo nome para inserir no banco
            while (file_exists('financeiro/remessa/geradas/' . $nSequencialArquivo . '.txt')) {
                $nSequencialArquivo = ((int) $nSequencialArquivo) + 1;
                $nSequencialArquivo = mb_str_pad($nSequencialArquivo, 7, '0', STR_PAD_LEFT);
            }
           
          #dados pagamento
          $pagamentos[$pos]['rm']               = $value['rm'];
          $pagamentos[$pos]['id']               = $value['id'];
          $pagamentos[$pos]['num_parc']         = $value['num_parc'];
          $pagamentos[$pos]['valor_parc']       = $value['valor_parc'];
          $pagamentos[$pos]['venc']             = $value['venc'];
          $pagamentos[$pos]['nossoNumero']      = $value['nosso_numero'].$value['dv'];
          $pagamentos[$pos]['identificador']    = $value['identificador'];
          $pagamentos[$pos]['dtProcessamento']  = $value['dtProcessamento'];
          
          #dados Pagante
          $pagamentos[$pos]['cpf']        = $value['cpf'];
          $pagamentos[$pos]['nome']       = $value['nome'];
          $pagamentos[$pos]['logradouro'] = $value['endereco']." ".$value['numero']." ".$value['complemento'];
          $pagamentos[$pos]['bairro']     = $value['bairro'];
          $pagamentos[$pos]['cep']        = $value['cep'];
          $pagamentos[$pos]['cidade']     = $value['cidade'];
          $pagamentos[$pos]['estado']     = $value['estado'];
      
          $pos++;
          $comRemessa = 1;
          }else{
            $semRemessa = 1;
          }
      }
  }

#print_r($pagamentos);
  

?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="css/datepick.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#dtInicio" ).datepicker({dateFormat: 'dd/mm/yy'});
    $( "#dtFim" ).datepicker({dateFormat: 'dd/mm/yy'});
  } );
  </script>
<div class="titulo_interna">
    <i class="fa fa-users" aria-hidden="true"></i>Academico
</div>
<div class="content_form">
    <?php
    $titulo = "Gerar Remessa";
    ?>
    <div class="titulo"> <?= $titulo?> </div>
    <?php
    if($_POST['geraRemessa']!=1){?>

<form name="frmRemessa" id="frmRemessa" method="post" action="">
  <table width="100%">
    <tr>
      <td colspan="2"><label>Data de processamento dos boletos: </label></td>
    </tr>
    <tr>
      <td>De: </td>
      <td><input type="text" name="dtInicio" id="dtInicio" required value="" style="border: solid 1px #ccc;"/></td>
    </tr>
    <tr>
      <td>Até: </td>
      <td><input type="text" name="dtFim" id="dtFim" value="" required style="border: solid 1px #ccc;"/></td>
    </tr>
    <tr><td colspan="2"><input type="hidden" value="1" name="geraRemessa">
                    <input type="submit" value="Gerar"></td></tr>
    
  </table>
            </form>
</div>


    <?php }else{
     # geraRemessa(strtoupper("Escola Futuros Genios"),$pagamentos,'5567782000169');
      $remessa = geraRemessa(strtoupper("Escola Futuros Genios"),$pagamentos,'5567782000169');
      
      #após gerar a remessa grava nos boletos o nome da remessa gerada.
      
      $atualiza_boletos = $con->prepare("update boletos set remessa = :remessa where remessa =0");
      $atualiza_boletos->execute(array(':remessa'=>soNumero($remessa)));

      if($atualiza_boletos->rowCount()!=0){
        echo "<a href='".$remessa."' target='_blank'>Baixar arquivo de remessa</a>";
      }else{
        echo "Não há boletos gerados para essa remessa";
        #var_dump($atualiza_boletos->errorInfo());

      }
      

}?>

