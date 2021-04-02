<div class="logo"><a href="menu_restrito.php"><img src="images/img-01.png"></a></div>

    <div class="info_escola">
    <div class="logo_escola"><a href="menu_restrito.php"><img src="images/logos/<?php echo $_SESSION['UnidadeLogo']; ?>" width="70"></a></div>
    <div class="titulo_escola"><?php echo $_SESSION['UnidadeFantasia']; ?></div>
    <div class="extra_escola"><?php echo $_SESSION['UnidadeSlogan']; ?></div>

</div>
<div class="info_escola">
    <div>Ol&aacute;, <?php echo $_SESSION['UsuarioNome']; ?>!<br>
    <span class="user_data">Ano Letivo: <?php echo $_SESSION['UsuarioAno']; ?><br>
    <span class="user_data">Alterar Informa&ccedil;&otilde;es | <a href="sair.php">Sair!</a></span></div>
</div>