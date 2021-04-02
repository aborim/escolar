<!DOCTYPE html>
<html lang="pt-BR">
<?php
    
  // A sess &atilde;o precisa ser iniciada em cada p&aacute;gina diferente
  if (!isset($_SESSION)) session_start();
    
  // Verifica se n&atilde;o h&aacute; a vari&aacute;vel da sess&atilde;o que identifica o usu&aacute;rio
  if (!isset($_SESSION['UsuarioID'])) {
      // Destr &oacute;i a sess&atilde;o por seguran&ccedil;a
      session_destroy();
      $msg = base64_encode("Voc&ecirc; n&atilde;o tem permiss&atilde;o para acessar essa p&aacute;gina!");
      // Redireciona o visitante de volta pro login
      header("Location: index.php?msg=$msg"); exit;
  }
    
  ?>
  <head>
        <meta charset="iso-8859-1">
        <!--===============================================================================================-->
        <link rel="stylesheet" href="css/reset.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" href="css/site_principal.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" href="vendor/fontawesome/css/fontawesome.min.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" href="vendor/fontawesome/css/fontawesome.min.css">
        <!--===============================================================================================-->
        <script src="https://kit.fontawesome.com/cc5254b95c.js"></script>
        <script src="vendor/fontawesome/js/fontawesome.min.js"></script>
        <title>WebTecno - Escolar</title>  
  </head>
  <body>
    <div id="topheader">
      <div class="headwrapper">
          <div class="logo"><img src="images/img-01.png"></div>
          <?php include("user_info.php");?>
      </div>
    </div>
    <div id="contentwrapper">
      <div class="content">
        <!--===============================================================================================-->
        <!--Menu de Op&ccedil; &otilde;es do sistema, controlado pelo perfil do usu&aacute;rio===============================-->
        <!--===============================================================================================-->
            <?php include('menu_opcoes.php');?>
        <!--===============================================================================================-->
      </div>
    </div>
      <div class="clearfix"></div>
    <div id="footerwrapper">
      <div class="footer">
        <span>WebTecno&copy; - <?php echo date("Y");?> - Todos os direitos reservados</span>
      </div>
    </div>
</body>
</html>