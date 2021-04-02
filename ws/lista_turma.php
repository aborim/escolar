<?php
header('Access-Control-Allow-Origin: *');

header('Content-Type: application/json');


include '../conexao.php';
include '../functions.php';

$buscaTurmas = $con->prepare("select id, nome from turma where idCurso=:idCurso");
$buscaTurmas->execute(array(':idCurso'=>$_REQUEST['idCurso']));
$resultado = $buscaTurmas->fetchAll(PDO::FETCH_ASSOC);
$suaarray = array_map('htmlentities',$resultado[0]);
$json = utf8_encode(html_entity_decode(json_encode($suaarray)));
echo $json;