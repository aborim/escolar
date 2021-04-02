<div class="titulo_interna">
    <i class="fa fa-users" aria-hidden="true"></i>Pessoas
</div>
<div class="content_aluno">
    <div class="titulo">Exibindo informa&ccedil; &otilde;es do aluno: <?= $nomeAluno;?></div>
    <div id="DadosAluno">
    <?php
    //inclui a conex &atilde;o com o banco de dados e suas fun&ccedil;&otilde;es
    include("conexao.php");
    include("functions.php");

    //busca as informa&ccedil;&otilde;es do aluno selecionado
    $buscar=$con->prepare("SELECT endereco.endereco,
		endereco.numero,
        endereco.complemento,
        endereco.bairro,
        endereco.cep,
        endereco.cidade,
        endereco.estado,
        aluno.nome,aluno.ra,aluno.rm,
        aluno.sexo,
        aluno.imagem,
        aluno.cpf,aluno.rg, aluno.dtNascimento,
        aluno.expedidoEm,aluno.orgaoEmissor,
        aluno.telefone,aluno.celular,
        aluno.nacionalidade,aluno.naturalidade,
        aluno.estadoCivil,aluno.certidaoNascimento,
        aluno.folha,aluno.livro,aluno.etnia,
        aluno.notaFiscal,aluno.bolsista,aluno.motivoBolsa,aluno.valorBolsa,usuarios.usuario
FROM 
		aluno,endereco,usuarios 
WHERE 	
		aluno.idUsuario=usuarios.id and 
        aluno.idEndereco=endereco.id AND
        aluno.id=:idAluno");
    $buscar->bindValue(":idAluno",$idAluno);
    $buscar->execute();
    
    if ($buscar->rowCount()!=1) {
        echo "Aluno n&atilde;o encontrado!";
    } else {
        // Salva os dados encontados na vari&aacute;vel $resultado
        $resultado = $buscar->fetchAll(PDO::FETCH_ASSOC);
        
    }
    ?>
    <!-- Tabela demonstrativa dos dados do aluno -->
    <table width="80%" cellspacing="0" cellpadding="0" class="dadosAluno">
  <tr>
    <td colspan="9">   
    <?php
    //inclui o mini menu de op&ccedil;&otilde;es dos alunos
    include("menu_alunos.php");
    ?> </td>
    </tr>
  <tr>
    <td rowspan="4"><img src="<?= $resultado[0]['imagem'];?>" class="imagemAluno" /></td>
    <td class="tituloDado">Nome:</td>
    <td><?= $resultado[0]['nome'];?></td>
    <td class="tituloDado">RA:</td>
    <td><?= $resultado[0]['ra'];?></td>
    <td class="tituloDado">RM:</td>
    <td colspan="3"><?= $resultado[0]['rm'];?></td>
    </tr>
  <tr>
    <td class="tituloDado">Data de Nascimento:</td>
    <td><?= formatoData($resultado[0]['dtNascimento']);?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    </tr>
  <tr>
    <th colspan="9" align="center">Ficha M&eacute;dica</th>
    </tr>
  <tr>
    <tD colspan="9" align="center">
    
  <table width="100%" cellpadding="0" cellspacing="0" class="ficha_med" id="exibe_dados">
      <tr>
        <td>Peso atual do aluno:</td>
        <td><input type="text" name="" value ="<?php #if(@$resultado[0]['']!=""){echo $resultado[0][''];}else{"Qual?"}?>58.500 KG" title="Qual o do aluno?" maxlenght="20" size="30"></td>
        <td>Tipo Sangu&iacute;neo:</td>
        <td colspan="6">
              <select name="tipoSanguineo" id="select">
                <option value="a+">A+</option>
                <option value="a-">A-</option>
                <option value="b+">B+</option>
                <option value="b-">B-</option>
                <option value="o+">O+</option>
                <option value="o-">O-</option>
                <option value="ab+">AB+</option>
                <option value="ab-">AB-</option>

              </select>
        </td>
        </tr>
      <tr>
        <td>Nome do  m&eacute;dico do aluno:</td>
        <td><input type="text" name="" value ="<?php #if(@$resultado[0]['']!=""){echo $resultado[0][''];}else{"Qual?"}?>Joaquim Filho" title="Qual Medico do Aluno?" maxlenght="60" size="30"></td>
        <td>Telefone  para contato:</td>
        <td><input type="text" name="" value ="<?php #if(@$resultado[0]['']!=""){echo $resultado[0][''];}else{"Qual?"}?>66996970937" title="Telefone do Medico para contato" maxlenght="20" size="30"></td>
        </tr>
      <tr>
        <td>Aluno(a) &eacute;  portador de necessidade especial?</td>
        <td><input type="radio" name="necessidadeEspecial" value="sim">Sim
            <input type="radio" name="necessidadeEspecial" value="nao">N&atilde;o
        </td>
        </tr>
      <tr>
        <td >Qual?</td>
        <td><input type="text" name="" value ="<?php #if(@$resultado[0]['']!=""){echo $resultado[0][''];}else{"Qual?"}?>" title="Qual a Necessidade especial do Aluno?" maxlenght="40" size="30"></td>
      </tr>
      <tr>
        <td>Aluno/a &eacute;  al&eacute;rgico a algum tipo de medicamento? </td>
        <td>
            <input type="radio" name="alergicoMedicamento" value="sim">Sim
            <input type="radio" name="alergicoMedicamento" value="nao">N&atilde;o
        </td>
        </tr>
      <tr>
        <td>Qual?</td>
        <td><input type="text" name="" value ="<?php #if(@$resultado[0]['']!=""){echo $resultado[0][''];}else{"Qual?"}?>" title="Qual, ou quais os medicamentos o aluno &eacute; al &eacute;rgico?" maxlenght="100" size="30"></td>
      </tr>
      <tr>
        <td >Aluno/a &eacute;  al&eacute;rgico a algum tipo de alimento ou material? </td>
        <td>
            <input type="radio" name="alergicoAlimento" value="sim">Sim
            <input type="radio" name="alergicoAlimento" value="nao">N&atilde;o
        </td>
        </tr>
      <tr>
        <td >Quais</td>
        <td><input type="text" name="" value ="<?php #if(@$resultado[0]['']!=""){echo $resultado[0][''];}else{"Qual?"}?>" title="Qual, ou quais os alimentos ou materiais o aluno &eacute; al&eacute;rgico?" maxlenght="100" size="30"></td>
      </tr>
      <tr>
        <td>Em caso  de febre alta, n&atilde;o sendo localizados os pais ou respons&aacute;veis, qual medicamento  poder&aacute; ser utilizado?</td>
        <td>
            <input type="radio" name="medicamentoUtilizado" value="sim">Sim
            <input type="radio" name="medicamentoUtilizado" value="nao">N&atilde;o
        </td>
        </tr>
      <tr>
        <td>Medicamento:</td>
        <td><input type="text" name="" value ="<?php #if(@$resultado[0]['']!=""){echo $resultado[0][''];}else{"Qual?"}?>" title="Qual medicamento a ser aplicado neste caso?" maxlenght="50" size="30"></td>
        <td>Dosagem:</td>
        <td><input type="text" name="" value ="<?php #if(@$resultado[0]['']!=""){echo $resultado[0][''];}else{"Qual?"}?>" title="Qual a dosagem?" maxlenght="50" size="30"></td>
      </tr>
      <tr>
        <td >Aluno/a  tem alguma doen&ccedil;a cong&ecirc;nita?</td>
        <td>
            <input type="radio" name="congenita" value="sim">Sim
            <input type="radio" name="congenita" value="nao">N&atilde;o
        </td>
        </tr>
      <tr>
        <td >Qual?</td>
        <td><input type="text" name="" value ="<?php #if(@$resultado[0]['']!=""){echo $resultado[0][''];}else{"Qual?"}?>" title="Qual doen&ccedil;a?" maxlenght="100" size="30"></td>
      </tr>
      <tr>
        <td>Quais as  doen&ccedil;as contagiosas j&aacute; contra&iacute;das na inf&acirc;ncia?</td>
        <td colspan="8">
            <select name="doencaContagiosa" id="select">
                <option value="caxumba">Caxumba</option> 
                <option value="sarampo">Sarampo</option> 
                <option value="rubeola">Rub&eacute;ola</option> 
                <option value="catapora">Catapora</option> 
                <option value="coqueluxe">Coqueluxe</option> 
            </select>
        </td>
        </tr>
      <tr>
        <td >Outras?</td>
        <td><input type="text" name="" value ="<?php #if(@$resultado[0]['']!=""){echo $resultado[0][''];}else{"Qual?"}?>" title="Quais outras doen&ccedil;as o aluno possui?" maxlenght="100" size="30"></td>
        </tr>
      <tr>
        <td>Aluno(a) &eacute;  hemof&iacute;lico(a)?</td>
        
        <td><input type="radio" name="hemofolico" value="sim">Sim
            <input type="radio" name="hemofolico" value="nao">N&atilde;o
            <input type="radio" name="hemofolico" value="emTratamento">Em tratamento
        </td>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td>Aluno(a)  &eacute; epil&eacute;tico(a)?</td>
        <td><input type="radio" name="epiletico" value="sim">Sim
            <input type="radio" name="epiletico" value="nao">N&atilde;o
            <input type="radio" name="epiletico" value="emTratamento">Em tratamento</td>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td>Aluno(a)  &eacute; hipertenso(a)?</td>
        <td><input type="radio" name="hipertenso" value="sim">Sim
            <input type="radio" name="hipertenso" value="nao">N&atilde;o
            <input type="radio" name="hipertenso" value="emTratamento">Em tratamento</td>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td>Aluno(a) &eacute; asm&aacute;tico(a)?</td>
        <td><input type="radio" name="asmatico" value="sim">Sim
            <input type="radio" name="asmatico" value="nao">N&atilde;o
            <input type="radio" name="asmatico" value="emTratamento">Em tratamento</td>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td>Aluno(a)  &eacute; diab&eacute;tico(a)?</td>
        <td><input type="radio" name="diabetico" value="sim">Sim
            <input type="radio" name="diabetico" value="nao">N&atilde;o
            <input type="radio" name="diabetico" value="emTratamento">Usa insulina <br />
            <input type="radio" name="diabetico" value="outromedicamento">Usa Outro medicamento</td>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td>Aluno(a) &eacute; card&iacute;aco(a)?</td>
        <td><input type="radio" name="cardiaco" value="sim">Sim
            <input type="radio" name="cardiaco" value="nao">N&atilde;o
            <input type="radio" name="cardiaco" value="emTratamento">Em tratamento</td>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td >Est&aacute;  fazendo algum outro tipo de tratamento?</td>
        <td><input type="radio" name="congenita" value="sim">Sim
            <input type="radio" name="congenita" value="nao">N&atilde;o
          </td>
        </tr>
      <tr>
        <td colspan="9">Qual?</td>
      </tr>
      <tr>
        <td >Aluno(a)  est&aacute; ingerindo medica&ccedil;&atilde;o espec&iacute;fica?</td>
        <td><input type="radio" name="congenita" value="sim">Sim
            <input type="radio" name="congenita" value="nao">N&atilde;o
        </td>
        </tr>
      <tr>
        <td colspan="9">Qual?</td>
      </tr>
      <tr>
        <td colspan="9" align="center">Emerg&ecirc;ncia</td>
      </tr>
      <tr>
        <td >Aluno(a)  possui algum plano de sa&uacute;de?</td>
        <td><input type="radio" name="congenita" value="sim">Sim
            <input type="radio" name="congenita" value="nao">N&atilde;o</td>
            
        </tr>
      <tr>
        <td>Qual?</td>
        <td><input type="text" name="" value ="<?php #if(@$resultado[0]['']!=""){echo $resultado[0][''];}else{"Qual?"}?>" title="Qual plano de sa &uacute;de do Aluno?" maxlenght="50" size="30"></td>
        <td>N&uacute;mero da Carteirinha:</td>
        <td ><input type="text" name="" value ="<?php #if(@$resultado[0]['']!=""){echo $resultado[0][''];}else{"Qual?"}?>" title="Quais numero da carteirinha do Plano de Sa&uacute;de?" maxlenght="30" size="30"></td>
      </tr>
      <tr>
        <td>Em caso  de emerg&ecirc;ncia, quem dever&aacute; ser avisado primeiro?</td>
        <td>
            <input type="radio" name="avisarEmergencia" value="pai"> Pai
            <input type="radio" name="avisarEmergencia" value="mae"> M&atilde;e
        </td>
        <td colspan="7">&nbsp;</td>
        </tr>
      <tr>
        <td colspan="9">N&atilde;o  conseguindo a comunica&ccedil;&atilde;o, informe uma outra pessoa:</td>
        </tr>
      <tr>
        <td>Nome:</td>
        <td><input type="text" name="" value ="<?php #if(@$resultado[0]['']!=""){echo $resultado[0][''];}else{"Qual?"}?>" title="Nome da pessoa para avisar caso nao consiga contato com os pais" maxlenght="60" size="30"></td>
        <td>Parentesco:</td>
        <td ><input type="text" name="" value ="<?php #if(@$resultado[0]['']!=""){echo $resultado[0][''];}else{"Qual?"}?>" title="Qual o grau de parentesco com o aluno" maxlenght="30" size="30"></td>
      </tr>
      <tr>
        <td>Telefone:</td>
        <td><input type="text" name="" value ="<?php #if(@$resultado[0]['']!=""){echo $resultado[0][''];}else{"Qual?"}?>" title="Telefone da do terceiro para caso de emerg&ecirc;ncia" maxlenght="20" size="30"></td>
        <td>Celular:</td>
        <td ><input type="text" name="" value ="<?php #if(@$resultado[0]['']!=""){echo $resultado[0][''];}else{"Qual?"}?>" title="Celular da do terceiro para caso de emerg&ecirc;ncia" maxlenght="20" size="30"></td>
      </tr>
    </table>
    
    </th>
  </tr>
    </table>
    <p>&nbsp;</p>
    
    <p>&nbsp;</p>
    <!-- fim da tabela demonstrativa-->

</div>
</div>



				
    
    
    