<div class="titulo_interna">
    <i class="fa fa-users" aria-hidden="true"></i>Pessoas
</div>
<div class="content_secretaria">
    <div class="titulo">Exibindo Informa&ccedil; &otilde;es da Secretaria  (o): <?= $nomeSecretaria;?></div>
    <div id="DadosProfessor">
    <?php
    //inclui a conex�o com o banco de dados e suas fun��es
    include("conexao.php");
    include("functions.php");

    //busca as informa��es do aluno selecionado
    $buscar=$con->prepare("select endereco.endereco,
		endereco.numero,
        endereco.complemento,
        endereco.bairro,
        endereco.cep,
        endereco.cidade,
        endereco.estado,
        secretaria.id,
        secretaria.nome,secretaria.email,
        secretaria.cpf,secretaria.rg,
        secretaria.dtNascimento,
        secretaria.dtCriacao,
        usuarios.usuario
FROM 
		secretaria,endereco,usuarios 
WHERE 	
		secretaria.idUsuario = usuarios.id and 
        secretaria.idEndereco = endereco.id and
        secretaria.id=:idSecretaria");
    $buscar->bindValue(":idSecretaria",$idSecretaria);
    $buscar->execute();
    
    if ($buscar->rowCount()!=1) {
        echo "Secretaria (o) n &atilde;o encontrado!";
    } else {
        // Salva os dados encontados na vari�vel $resultado
        $resultado = $buscar->fetchAll(PDO::FETCH_ASSOC);
        
    }
    ?>
    <!-- Tabela demonstrativa dos dados do aluno -->
    <table width="80%" cellspacing="0" cellpadding="0" class="dadosAluno">
  <tr>
  <th colspan="8" align="center">Dados Cadastrais</th> 
    <?php
    //inclui o mini menu de op��es dos alunos
    //include("menu_.php");
    ?> </td>
    </tr>
  <tr>
    
    <td class="tituloDado">Nome:</td>
    <td><?= $resultado[0]['nome'];?></td>
    <td class="tituloDado">E-mail:</td>
    <td><?= $resultado[0]['email'];?></td>
    <td class="tituloDado">Data de Nascimento:</td>
    <td ><?= formatoData($resultado[0]['dtNascimento']);?></td>
    </tr>
  <tr>
    <td class="tituloDado">CPF:</td>
    <td ><?= $resultado[0]['cpf'];?></td>
    <td class="tituloDado">RG</td>
    <td ><?= $resultado[0]['rg'];?></td>
    <td class="tituloDado">&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
  <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr>

  <tr>
    <th colspan="8" align="center">Localiza&ccedil;&atilde;o</th>
    <!--<th colspan="4" align="center">Dados Cadastrais</th>-->
    </tr>
  <tr>
    <td class="tituloDado" >Endere&ccedil;o:</td>
    <td><?= $resultado[0]['endereco'];?></td>
    <td class="tituloDado" >N&uacute;mero:</td>
    <td><?= $resultado[0]['numero'];?></td>
    <td class="tituloDado" >Complemento:</td>
    <td><?= $resultado[0]['complemento'];?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td class="tituloDado" >CEP:</td>
    <td><?= $resultado[0]['cep'];?></td>
    <td class="tituloDado" >Bairro:</td>
    <td><?= $resultado[0]['bairro'];?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    
    </tr>

    
    <tr>
    <td class="tituloDado">Cidade:</td>
    <td><?= $resultado[0]['cidade'];?></td>
    <td class="tituloDado">Estado:</td>
    <td><?= $resultado[0]['estado'];?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    
    </tr>
  
 
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    </tr>
  <!--<tr>
    <th colspan="8" align="center">Dados Adicionais</th>
    </tr>
  
  <tr>
    <td class="tituloDado">Data Cria&ccedil;&atilde;o:</td>
    <td colspan="7"><?= $resultado[0]['dtCriacao'];?></td>
    </tr>-->
  
</table>
<!-- fim da tabela demonstrativa-->

</div>
</div>



				
    
    
    