<div class="titulo_interna">
    <i class="fa fa-users" aria-hidden="true"></i>Pessoas
</div>
<div class="content_aluno">
    <div class="titulo">Exibindo Informa&ccedil; &otilde;es do Diretor: <?= $nomeDiretor;?></div>
    <div id="DadosDiretor">
    <?php
    //inclui a conex�o com o banco de dados e suas fun��es
    include("conexao.php");
    include("functions.php");

    //busca as informa��es do aluno selecionado
    $buscar=$con->prepare("SELECT endereco.endereco,
		endereco.numero,
        endereco.complemento,
        endereco.bairro,
        endereco.cep,
        endereco.cidade,
        endereco.estado,
        diretor.nome,diretor.email,
        diretor.cpf,diretor.rg,
        diretor.telefone,diretor.celular,diretor.dtNascimento,diretor.dtCriacao,usuarios.usuario
FROM 
		diretor,endereco,usuarios 
WHERE 	
		diretor.idUsuario=usuarios.id and 
        diretor.idEndereco=endereco.id AND
        diretor.id=:idDiretor");
    $buscar->bindValue(":idDiretor",$idDiretor);
    $buscar->execute();
    
    if ($buscar->rowCount()!=1) {
        echo "Diretor n &atilde;o encontrado!";
    } else {
        // Salva os dados encontados na vari�vel $resultado
        $resultado = $buscar->fetchAll(PDO::FETCH_ASSOC);
        
    }
    ?>
    <!-- Tabela demonstrativa dos dados do aluno -->
    <table width="80%" cellspacing="0" cellpadding="0" class="dadosAluno">
    <th colspan="8" align="center">Dados Pessoais</th>
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
    <td><?= $resultado[0]['cpf'];?></td>
    <td class="tituloDado">RG:</td>
    <td><?= $resultado[0]['rg'];?></td>
    <td class="tituloDado">&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <th colspan="3" align="center">Localiza&ccedil;&atilde;o</th>
    <th colspan="5" align="center">Contato
    </tr>
  <tr>
    <td class="tituloDado">Endere&ccedil;o:</td>
    <td><?= $resultado[0]['endereco'];?></td>
    <td class="tituloDado">N&uacute;mero:</td>
    <td><?= $resultado[0]['numero'];?></td>
    <td class="tituloDado">Telefone:</td>
    <td><?= $resultado[0]['telefone'];?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </td>
    </tr>
  <tr>
    <td class="tituloDado">Bairro:</td>
    <td><?= $resultado[0]['bairro'];?></td>
    <td class="tituloDado">Cidade:</td>
    <td><?=$resultado[0]['cidade']; ?></td>
    <td class="tituloDado">Celular:</td>
    <td><?=$resultado[0]['celular'];?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  
  <tr>
    <td class="tituloDado">Complemento:</td>
    <td>
      <?= $resultado[0]['complemento'];?>
    <td class="tituloDado">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="tituloDado">&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    </tr>
  
  
</table>
<!-- fim da tabela demonstrativa-->

</div>
</div>



				
    
    
    