<?php

//modulo de cadastro de filial, arquivo inserido ao menu restrito atrav &eacute;s de include
if(isset($_POST['grava'])==1){

//inclui a conexao com banco de dados e fun&ccedil; &otilde;es
include('conexao.php');
include('functions.php');
//armazena os dados das vari&aacute;veis do formul&aacute;rio
$cnpj           = soNumero($_POST['txt_cnpj']);
$nomeFantasia   = $_POST['txt_nomeFantasia'];
$razaoSocial    = $_POST['txt_razaoSocial'];
$email          = $_POST['txt_email'];
$telefone       = $_POST['txt_telefone'];
$slogan         = $_POST['txt_slogan'];
$matriz         = isset($_POST['chk_matriz']) ? "1" : "0";
$ie             = soNumero($_POST['txt_ie']);
$numNF          = $_POST['txt_nfe'];
//pega os dados do endere&ccedil;o pra gravar antes e obter o id, caso n &atilde;o tenha sido cadastrado primeiro
$cep            = $_POST['txt_cep'];
$endereco       = $_POST['txt_endereco'];
$num            = $_POST['txt_num'];
$complemento    = $_POST['txt_comp'];
$bairro         = $_POST['txt_bairro'];
$cidade         = $_POST['txt_cidade'];
$estado         = $_POST['txt_estado'];
//grava primeiro o endereco, pega o id do endere&ccedil;o pra montar o relacionamento correto
//(usuario =:login) AND (senha =:senha)
  $grava_endereco=$con->prepare("insert into endereco (endereco, numero, complemento, cep, bairro, cidade, estado) values (:endereco,:numero,:complemento,:cep,:bairro,:cidade,:estado)");
  $grava_endereco->execute(array(
    ':endereco'     => $endereco,
    ':numero'       => $num,
    ':complemento'  => $complemento,
    ':cep'          => $cep,
    ':bairro'       => $bairro,
    ':cidade'       => $cidade,
    ':estado'       => $estado
  ));
  //id do endereco gerado
  $LAST_ID = $con->lastInsertId();
  //grava os dados da filial
  //grava a imagem do logo
  if ( isset( $_FILES[ 'logo' ][ 'name' ] ) && $_FILES[ 'logo' ][ 'error' ] == 0 ) {
 
    $arquivo_tmp = $_FILES[ 'logo' ][ 'tmp_name' ];
    $nome = $_FILES[ 'logo' ][ 'name' ];
 
    // Pega a extens&atilde;o
    $extensao = pathinfo ( $nome, PATHINFO_EXTENSION );
 
    // Converte a extens&atilde;o para min &uacute;sculo
    $extensao = strtolower ( $extensao );
 
    // Somente imagens, .jpg;.jpeg;.gif;.png
    // Aqui eu enfileiro as extens&otilde;es permitidas e separo por ';'
    // Isso serve apenas para eu poder pesquisar dentro desta String
    if ( strstr ( '.jpg;.jpeg;.gif;.png', $extensao ) ) {
        // Cria um nome &uacute;nico para esta imagem
        // Evita que duplique as imagens no servidor.
        // Evita nomes com acentos, espa&ccedil;os e caracteres n&atilde;o alfanum&eacute;ricos
        $novoNome = uniqid ( time () ) . '.' . $extensao;
 
        // Concatena a pasta com o nome
        $destino = 'images/logos/' . $novoNome;
 
        // tenta mover o arquivo para o destino
        if ( @move_uploaded_file ( $arquivo_tmp, $destino ) ) {            
        }else{echo 'Erro ao salvar o arquivo. Aparentemente voc&ecirc; n&atilde;o tem permiss&atilde;o de escrita.<br />';}
    }else{echo 'Voc&ecirc; poder&aacute; enviar apenas arquivos "*.jpg;*.jpeg;*.gif;*.png"<br />';}
}else{echo 'Voc&ecirc; n&atilde;o enviou nenhum arquivo!';}
    

//dados da filial ao banco de dados junto com o logo em arquivo
  $grava_filial=$con->prepare("insert into filial (id_endereco, cnpj, nomeFantasia, razaoSocial, email, telefone, logomarca, slogan, matriz, insc_muni_estadual, criacao, num_nfe) values (:id_endereco, :cnpj, :nomeFantasia, :razaoSocial, :email, :telefone, :logomarca, :slogan, :matriz, :insc_muni_estadual, :criacao, :num_nfe)");
  $grava_filial->execute(array(
    ':id_endereco'  => $LAST_ID,
    ':cnpj'         => $cnpj,
    ':nomeFantasia' => $nomeFantasia,
    ':razaoSocial'  => $razaoSocial,
    ':email'        => $email,
    ':telefone'     => $telefone,
    ':logomarca'    => $novoNome,
    ':slogan'       => $slogan,
    ':matriz'       => $matriz,
    ':insc_muni_estadual' => $ie,
    ':criacao'      => date('Y-m-d H:i:s'),
    ':num_nfe'      => $numNF
    )
  );

  
  $status = $grava_filial->errorCode();
}
?>
<div class="titulo_interna">
<i class="fa fa-cogs" aria-hidden="true"></i>Parametros
</div>
<div class="content_form">
    <div class="titulo">Adicionar Filial</div>
    <?php
    if(!isset($status)){?>
<form method="post" action="#" enctype="multipart/form-data">
    <div class="form_comp" id="filial">
        <div><label>CNPJ</label><input type="text" name="txt_cnpj" id="txt_cnpj" class="form_campo" placeholder="00.000.000/0000-00"><span class="lbl_file" id="obs_cnpj" style="visibility:hidden;">CNPJ j&aacute; utilizado.</span></div>
        <div><label>Nome Fantasia</label><input type="text" name="txt_nomeFantasia" class="form_campo"></div>
        <div><label>Raz&atilde;o Social</label><input type="text" name="txt_razaoSocial" class="form_campo"></div>
        <div><label>Telefone</label><input type="text" name="txt_telefone" id="txt_telefone" class="form_campo" placeholder="(00)0000-0000"></div>
        <div><label>E-mail</label><input type="text" name="txt_email" id="txt_email" class="form_campo"></div>
        <div><label>Logomarca</label><input type="file" name="logo" class="form_campo"><span class="lbl_file">Somente arquivos JPG, PNG ou GIF.</span></div>
        <div><label>Slogan</label><input type="text" name="txt_slogan" class="form_campo"></div>
        <div><label>Inscri&ccedil;&atilde;o Estadual</label><input type="text" name="txt_ie" id="txt_ie" class="form_campo" placeholder="000.000.000.000"></div>
        <div><label>N&uacute;mero inicial da Nota Fiscal</label><input type="text" name="txt_nfe" class="form_campo"></div>
    </div>
    <?php include('form_endereco.php');?>
        <div class="btn_pos"><input type="submit" name="btn_add_filial" class="form_buttom" value="Adicionar Filial"></div>
    <input type="hidden" name="grava" value="1">
</form>
</div>

<script type="text/javascript">
$('#txt_cnpj').blur(function() {
        $.post("ws/busca_filial.php", "cnpj="+$('#txt_cnpj').val(), function( data ) {
          console.log(data);
        if(data==1){
          $("#obs_cnpj").css("visibility", "visible");
          $("#obs_cnpj").css("color","red");
        }else{
          $("#obs_cnpj").css("visibility", "hidden");
        }
    });
});
</script>
    <?php }else{?>
      <div class="form_comp">
        Filial gravada com sucesso<br>
        <img src="images/logos/<?php echo $novoNome;?>">
      </div>

   <?php }?>