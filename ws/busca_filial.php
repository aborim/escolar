<?php
include('../conexao.php');
include('../functions.php');

// busca as informa&ccedil; &otilde;es para saber se o cnpj ja foi utilizado
$buscar=$con->prepare("SELECT cnpj FROM filial WHERE (cnpj =:cnpj) LIMIT 1");
$buscar->bindValue(":cnpj",soNumero($_POST['cnpj']));

$buscar->execute();
  
  //verifica se a query de busca do usu&aacute;rio retornou algum resultado
if ($buscar->rowCount()!=1) {
    // Mensagem de erro quando os dados s &atilde;o inv&aacute;lidos e/ou o usu&aacute;rio n &atilde;o foi encontrado
    echo "0"; exit;
}else{
    echo "1"; exit;
} 