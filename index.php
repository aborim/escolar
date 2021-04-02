<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title>Webtecno - Escolar</title>
	<meta charset="UTF-8">
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
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic">
					<img src="images/img-01.png" alt="IMG">
				</div>

                <form class="login100-form validate-form" method="post" action="validacao.php">
					<span class="login100-form-title">
						Acesso ao sistema Escolar
					</span>

					<div class="wrap-input100 validate-input" data-validate = "O campo do usu&aacute;rio n&atilde;o deve estar vazio!">
						<input class="input100" type="text" name="usuario" placeholder="Usu&aacute;rio" required>
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "A senha &eacute; obrigat&oacute;ria!">
						<input class="input100" type="password" name="senha" placeholder="Senha" required>
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "O Ano &eacute; obrigat&oacute;ria!">
						<select name="ano" class="input100">
							<option value="" disabled selected>Selecione o Ano</option>
<option value="2021">2021</option>							
<option value="2020">2020</option>
							
						</select>
						<!--<input class="input100" type="password" name="senha" placeholder="Senha">-->
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-calendar" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Entrar
						</button>
					</div>

					<div class="text-center p-t-12">
						<span class="txt1">
							Esqueceu 
						</span>
						<a class="txt2" href="#">
							Usu&aacute;rio / Senha?
						</a>
					</div>

					<div class="text-center p-t-136">
                        <?php if(@$_REQUEST['msg']!=''){?>
                            <div class="mensagem_erro"><?php echo base64_decode($_REQUEST['msg']);?></div>
                        <?php }?>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->


<script src="js/main.js"></script>

</body>
</html>