<?php
//dados de acesso
$dbhost 	    = 'localhost';
$dbusuario    = 'apoio19c_webescolar';
$dbsenha      = 'Dj6j;4O)uJvv';
$db           = 'apoio19c_webescolar';

/*

  //-----------------------------
 
  $host 	  = 'localhost';
  $usuario    = 'apoio19c_escolar';
  $senha      = 'escolar19!(';
  $db         = 'apoio19c_escolar'; 
  
  //dados de acesso
$dbhost 	    = 'localhost';
$dbusuario    = 'webtecno_escolar';
$dbsenha      = 'AiP?lAf#oUwp';
$db           = 'webtecno_escolar';
  
  */
  // Conex&atilde;o com servidor MySQL
 # $banco = array($host, $usuario, $senha, $db);
 # $con = mysqli_connect($banco[0], $banco[1], $banco[2], $banco[3]) or trigger_error(mysql_error());
  
  
  $var='mysql:host='.$dbhost.';dbname='.$db;
  $con=new PDO($var,$dbusuario,$dbsenha);
  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);