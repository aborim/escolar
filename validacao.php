<?php
    
  // Verifica se houve POST e se o usu&aacute;rio ou a senha &eacute;(s &atilde;o) vazio(s)
  if (!empty($_POST) AND (empty($_POST['usuario']) OR empty($_POST['senha']))) {
      header("Location: index.php"); exit;
  }

  //inclui a conexao com o banco de dados
  require_once('conexao.php');

  //recebe os dadosdo formulario padronizado no mysql
  $usuario  = $_POST['usuario'];
  $senha    = $_POST['senha'];
  $ano      = $_POST['ano'];

  // Valida&ccedil;&atilde;o do usu&aacute;rio/senha digitado

#$buscar=$con->prepare("SELECT id, nivel FROM usuarios WHERE (usuario =:login) AND (senha =:senha) AND (ativo = 1) LIMIT 1");
  $buscar=$con->prepare("
  SELECT usuarios.id as id,
  usuarios.usuario as usuario,
  usuarios.nivel as nivel,
  filial.id as unidade,
  filial.nomeFantasia as nomeFantasia, 
  filial.slogan as slogan,
  filial.logomarca as logo,
  filial.cnpj,
  endereco.*
FROM 
  usuarios,filial,endereco 
where 
usuarios.usuario=:login and usuarios.senha = :senha and filial.matriz=1 and usuarios.ativo=1 and filial.id_endereco = endereco.id");
  $buscar->bindValue(":login",$usuario);
  $buscar->bindValue(":senha",md5($senha));
  $buscar->execute();
    
    //verifica se a query de busca do usu&aacute;rio retornou algum resultado
  if ($buscar->rowCount()!=1) {
      // Mensagem de erro quando os dados s&atilde;o inv&aacute;lidos e/ou o usu&aacute;rio n&atilde;o foi encontrado
      $msg = base64_encode("Usu&aacute;rio ou senha incorretos!");
      // Redireciona o visitante de volta pro login
      header("Location: index.php?msg=$msg"); exit;
  } else {
      // Salva os dados encontados na vari&aacute;vel $resultado
      $resultado = $buscar->fetchAll(PDO::FETCH_ASSOC);
      // Se a sess&atilde;o n&atilde;o existir, inicia uma
      if (!isset($_SESSION)) session_start();
    

      // Salva os dados encontrados na sess&atilde;o
      $_SESSION['UsuarioID']        = $resultado[0]['id'];
      $_SESSION['UsuarioNome']      = $resultado[0]['usuario'];
      $_SESSION['UsuarioNivel']     = $resultado[0]['nivel'];
      $_SESSION['UsuarioAno']       = $ano;
      $_SESSION['Unidade']          = $resultado[0]['unidade'];
      $_SESSION['UnidadeFantasia']  = $resultado[0]['nomeFantasia'];
      $_SESSION['UnidadeSlogan']    = $resultado[0]['slogan'];
      $_SESSION['UnidadeLogo']      = $resultado[0]['logo'];
      $_SESSION['UnidadeCNPJ']      = $resultado[0]['cnpj'];
      $_SESSION['UnidadeEndereco']  = $resultado[0]['endereco'].", ".$resultado[0]['numero']." - ".$resultado[0]['cep']." - ".$resultado[0]['bairro']." - ".$resultado[0]['cidade']." - ".$resultado[0]['estado'];
      // Redireciona o visitante
      header("Location: menu_restrito.php"); exit;
    }