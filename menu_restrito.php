<!DOCTYPE html>
<html>
<?php
  ini_set('display_errors',1);
  ini_set('display_startup_erros',1);
  error_reporting(E_ALL);
  // A sess&atilde;o precisa ser iniciada em cada p&aacute;gina diferente
  if (!isset($_SESSION)) session_start();
    
  // Verifica se n&atilde;o h&aacute; a vari&aacute;vel da sess&atilde;o que identifica o usu&aacute;rio
  if (!isset($_SESSION['UsuarioID'])) {
      // Destr&oacute;i a sess&atilde;o por seguran&ccedil;a
      session_destroy();
      $msg = base64_encode("Voc&ecirc; n&atilde;o tem permiss&atilde;o para acessar essa p&aacute;gina!");
      // Redireciona o visitante de volta pro login
      header("Location: index.php?msg=$msg"); exit;
  }
    

$path = explode($_SERVER['DOCUMENT_ROOT'], $_SERVER['SCRIPT_FILENAME']);
$path = explode('/', $path[1]);


?>
  <head>
  <meta http-equiv="Content-Type" content="text/html;">
        <!--===============================================================================================-->
        <link rel="stylesheet" href="css/reset.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" href="css/site_principal.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--===============================================================================================-->	
        <link rel="icon" type="image/png" href="images/favicon.png"/>
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
        <!--===============================================================================================-->	
        <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="css/util.css">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" href="fa/css/font-awesome.min.css">
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" ></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.js" integrity="sha256-TDtzz+WOGufaQuQzqpEnnxdJQW5xrU+pzjznwBtaWs4=" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <link rel="stylesheet" href="chosen.css">
        <title>WebTecno - Escolar</title>  
  </head>
  <body>
    <div id="topheader">
      <div class="headwrapper">
          <?php include("escola_info.php");?>
      </div>
    </div>
    <div id="contentwrapper">
      <div class="content">
        <!--===============================================================================================-->
        <!--Menu de Op&ccedil;&otilde;es do sistema, controlado pelo perfil do usu&aacute;rio pagina acessada de acordo com as variaveis passadas-->
        <!--===============================================================================================-->
            <?php 
            include('menu_opcoes.php');
            $url= $_SERVER['QUERY_STRING'];
            parse_str(html_entity_decode($url), $array);
            if(isset($array['idAluno'])){$idAluno = $array['idAluno']; $nomeAluno = base64_decode(@$array['nome']);}
            if(isset($array['rmAluno'])){$rmAluno = $array['rm']; $nomeAluno = base64_decode(@$array['nome']);}
            if(isset($array['idResponsavel'])){$idResponsavel = $array['idResponsavel']; $nomeResponsavel = base64_decode($array['nome']);}
            if(isset($array['idCoordenador'])){$idCoordenador = $array['idCoordenador']; $nomeCoordenador = base64_decode($array['nome']);}
            if(isset($array['idProfessor'])){$idProfessor = $array['idProfessor']; $nomeProfessor = base64_decode($array['nome']);}
            if(isset($array['idDiretor'])){$idDiretor = $array['idDiretor']; $nomeDiretor = base64_decode($array['nome']);}
            if(isset($array['idSecretaria'])){$idSecretaria = $array['idSecretaria']; $nomeSecretaria = base64_decode($array['nome']);}
            if(isset($array['idCurso'])){$idCurso = $array['idCurso']; $nomeCurso = base64_decode($array['nome']);}
            if(isset($array['idDisciplina'])){$idDisciplina = $array['idDisciplina']; $nomeDisciplina = base64_decode($array['nome']);}
            if(isset($array['idTurma'])){$idTurma = $array['idTurma']; $nomeTurma = base64_decode($array['nome']);}
            if(isset($array['idClasse'])){$idClasse = $array['idClasse']; $nomeClasse = base64_decode($array['nome']);}
            if(isset($array['idPlano'])){$idPlano = $array['idPlano']; $nomePlano = base64_decode($array['plano']);}
            if(isset($array['idRespFinan'])){$idRespFinan = $array['idRespFinan']; $nomePlano = base64_decode($array['plano']);}
            
            if(isset($array['op'])){include($array['op'].".php");}
            ?>
            <div class="clearfix"></div>
    <div id="footerwrapper">
      <div class="footer">
        <span>WebTecno&copy; - <?php echo date("Y");?> - Todos os direitos reservados</span>
      </div>
    </div>
        <!--===============================================================================================-->
      </div>
    </div>

    
    <script>
    /*  //Mascara CPF
      $(document).ready(function(){
        $('#txt_cnpj').mask('00.000.000/0000-00');
        $('#txt_telefone').mask('(00)0000-0000');
        $('#txt_ie').mask('000.000.000.000');
        $('#cpf').mask('000.000.000-00');
      });*/
    </script>
</body>
</html>