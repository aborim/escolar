<?php
header('Access-Control-Allow-Origin: *');

header('Content-Type: application/json');


include '../conexao.php';
include '../functions.php';

$buscaTurmas = $con->prepare("select id, nome from classe where idTurma=:idTurma");
$buscaTurmas->execute(array(':idTurma'=>$_REQUEST['idTurma']));
$resultado = $buscaTurmas->fetchAll(PDO::FETCH_ASSOC);
$suaarray = array_map('htmlentities',$resultado[0]);
$json = utf8_encode(html_entity_decode(json_encode($suaarray)));
echo $json;