<div class="titulo_interna">
    <i class="fa fa-users" aria-hidden="true"></i>Pessoas
</div>
<div class="content_professor">
    
    <div id="DadosProfessor">
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
        professor.nome,professor.email,
        professor.cpf,professor.rg,
        professor.ctps,professor.pis,
        professor.ctpsDtExpedicao,
        professor.dtAdmissao,
        professor.telefone,professor.celular,professor.dtNascimento,
        professor.observacao, professor.dtCriacao,usuarios.usuario
FROM 
		professor,endereco,usuarios 
WHERE 	
		professor.idUsuario=usuarios.id and 
        professor.idEndereco=endereco.id AND
        professor.id=:idProfessor");
    $buscar->bindValue(":idProfessor",$idProfessor);
    $buscar->execute();
    
    if ($buscar->rowCount()!=1) {
        echo "Professor n &atilde;o encontrado!";
    } else {
        // Salva os dados encontados na vari�vel $resultado
        $resultado = $buscar->fetchAll(PDO::FETCH_ASSOC);
        
    }
    ?>
    <!-- Tabela demonstrativa dos dados do aluno -->
    <table width="80%" cellspacing="0" cellpadding="0" class="dadosAluno">
    <div class="titulo">Exibindo Informa&ccedil;&otilde;es do Professor: <?= $nomeProfessor;?></div>
  <tr>
    <td colspan="8">   
    <?php
    //inclui o mini menu de op��es dos alunos
    //include("menu_professores.php");
    ?> </td>
    
    </tr>

    <th colspan="8" align="center"> Dados Pessoais</th>
  <tr>
    
    <td class="tituloDado">Nome:</td>
    <td><?= $resultado[0]['nome'];?></td>
    <td class="tituloDado">E-mail:</td>
    <td><?= $resultado[0]['email'];?></td>
    <td class="tituloDado">Data de Nascimento:</td>
    <td><?= formatoData($resultado[0]['dtNascimento']);?></td>
    </tr>
  <tr>
    <td class="tituloDado">CPF:</td>
    <td><?= $resultado[0]['cpf'];?></td>
    <td class="tituloDado">RG:</td>
    <td><?= $resultado[0]['rg'];?></td>
    <td class="tituloDado">Data de Admiss&atilde;o</td>
    <td ><?= formatoData($resultado[0]['dtAdmissao']);?></td>
  </tr>
  <tr>
    <td class="tituloDado">CTPS:</td>
    <td ><?= $resultado[0]['ctps'];?></td>
    <td class="tituloDado">Data de Expedi&ccedil;&atilde;o da CTPS:</td>
    <td><?= formatoData($resultado[0]['ctpsDtExpedicao']);?></td>
    <td class="tituloDado">PIS:</td>
    <td><?= $resultado[0]['pis'];?></td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    
    </tr>
  <tr>
  >
    <td class="tituloDado">Cidade:</td>
    <td><?= $resultado[0]['cidade'];?></td>
    <td class="tituloDado">Bairro:</td>
    <td><?=$resultado[0]['bairro']; ?></td>
    <td class="tituloDado">Celular:</td>
    <td><?=$resultado[0]['celular'];?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  
  <tr>
  <td class="tituloDado">Complemento:</td>
    <td><?= $resultado[0]['complemento'];?></td>
    <td class="tituloDado">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="tituloDado">&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
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



				
    
    
    