<div class="titulo_interna">
    <i class="fa fa-users" aria-hidden="true"></i>Pessoas
</div>
<div class="content_responsavel">
    <div class="titulo">Exibindo Informa&ccedil; &otilde;es do Responsavel: <?= $nomeResponsavel;?></div>
    <div id="DadosResponsavel">
    <?php
    //inclui a conexï¿½o com o banco de dados e suas funï¿½ï¿½es
    include("conexao.php");
    include("functions.php");

    //busca as informaï¿½ï¿½es do aluno selecionado
    $buscar=$con->prepare("SELECT endereco.endereco,
		endereco.numero,
        endereco.complemento,
        endereco.bairro,
        endereco.cep,
        endereco.cidade,
        endereco.estado,
        responsavel.nome,responsavel.email,responsavel.grauParentesco,
        responsavel.sexo,
        responsavel.cpf,responsavel.rg,
        responsavel.expedidoEm,responsavel.orgaoEmissor,
        responsavel.telefone,responsavel.celular,responsavel.telefoneCom,
        responsavel.nacionalidade,
        responsavel.estadoCivil,responsavel.dtNascimento,
        responsavel.profissao,
        responsavel.observacao, responsavel.dtCriacao,usuarios.usuario
FROM 
		responsavel,endereco,usuarios 
WHERE 	
		responsavel.idUsuario=usuarios.id and 
        responsavel.idEndereco=endereco.id AND
        responsavel.id=:idResponsavel");
    $buscar->bindValue(":idResponsavel",$idResponsavel);
    $buscar->execute();
    
    if ($buscar->rowCount()!=1) {
        echo "Responsavel n &atilde;£o encontrado!";
    } else {
        // Salva os dados encontados na variï¿½vel $resultado
        $resultado = $buscar->fetchAll(PDO::FETCH_ASSOC);
        
    }
    ?>
    <!-- Tabela demonstrativa dos dados do aluno -->
    <table width="80%" cellspacing="0" cellpadding="0" class="dadosAluno">
  <tr>
    <td colspan="8">   
    <?php
    //inclui o mini menu de opï¿½ï¿½es dos alunos
    include("menu_responsavel.php");
    ?> </td>
    </tr>
    <th colspan="8" align="center">Dados Pessoais</th>
  <tr>
    
    <td class="tituloDado">Nome:</td>
    <td><?= $resultado[0]['nome'];?></td>
    <td class="tituloDado">E-mail:</td>
    <td><?= $resultado[0]['email'];?></td>
    <td class="tituloDado">Parentesco:</td>
    <td ><?= $resultado[0]['grauParentesco'];?></td>
    </tr>
  <tr>
    <td class="tituloDado">CPF:</td>
    <td><?= $resultado[0]['cpf'];?></td>
    <td class="tituloDado">Sexo:</td>
    <td><?= $resultado[0]['sexo']==1?"Feminino":"Masculino";?></td>
    <td class="tituloDado">&nbsp;</td>
    <td>&nbsp;</td>
    
    </tr>
  <tr>
    <td class="tituloDado">RG:</td>
    <td ><?= $resultado[0]['rg'];?></td>
    <td class="tituloDado">Org&atilde;o Emissor:</td>
    <td ><?= $resultado[0]['orgaoEmissor'];?></td>
    <td class="tituloDado">Expedido Em:</td>
    <td ><?= formatoData($resultado[0]['expedidoEm']);?></td>
    </tr>
  <tr>
    <td class="tituloDado">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="tituloDado">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="tituloDado">&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <th colspan="3" align="center">Localiza&ccedil;&atilde;o</th>
    <th colspan="5" align="center">Contato</th>
    </tr>
  <tr>
    <td class="tituloDado">Endere&ccedil;o:</td>
    <td><?= $resultado[0]['endereco'];?></td>
    <td class="tituloDado">N&uacute;mero:</td>
    <td><?= $resultado[0]['numero'];?></td>
    <td class="tituloDado">Telefone:</td>
    <td><?= $resultado[0]['telefone'];?></td>
    <td class="tituloDado">Celular:</td>
    <td ><?= $resultado[0]['celular'];?></td>
    </tr>
  <tr>
    <td class="tituloDado">Complemento:</td>
    <td><?= $resultado[0]['complemento'];?></td>
    <td class="tituloDado">Bairro:</td>
    <td><?= $resultado[0]['bairro'];?></td>
    <td class="tituloDado">Telefone Comercial:</td>
    <td ><?= $resultado[0]['telefoneCom'];?></td>
    <td class="tituloDado">Data de Nascimento:</td>
    <td><?= formatoData($resultado[0]['dtNascimento']);?></td>
    
    </tr>
  <tr>
    <td class="tituloDado">CEP:</td>
    <td><?= $resultado[0]['cep'];?></td>
    <td class="tituloDado">Cidade:</td>
    <td><?= $resultado[0]['cidade'];?></td>
    <td class="tituloDado">Nacionalidade:</td>
    <td ><?= $resultado[0]['nacionalidade'];?></td>
    <td class="tituloDado">Estado Civil:</td>
    <td><?= $resultado[0]['estadoCivil'];?></td>
    </tr>
  <tr>
    <td class="tituloDado">Estado:</td>
    <td><?= $resultado[0]['estado'];?></td>
    <td class="tituloDado">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="tituloDado">Profiss&atilde;o:</td>
    <td ><?= $resultado[0]['profissao'];?></td>
    <td class="tituloDado"> </td>
    <td></td>
    </tr>

  <tr>
    <td class="tituloDado">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="tituloDado">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="tituloDado">&nbsp;</td>
    <td >&nbsp;</td>
    <td class="tituloDado">&nbsp;</td>
    <td >&nbsp;</td>
    </tr>
  <tr>
    <th colspan="8" align="center">Dados Adicionais</th>
    </tr>
  <tr>
    <td class="tituloDado">Observa&ccedil;&atilde;o:</td>
    <td colspan="7"><?= $resultado[0]['observacao'];?></td>
    </tr>
 
  
</table>
<!-- fim da tabela demonstrativa-->

</div>
</div>



				
    
    
    