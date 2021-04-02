
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

    //guarda as variaveis do formulario
    $meioTransporte	        = $_POST['meioTransporte'];
    $sairSozinho	          = $_POST['sairSozinho'];
    $autorizada1	          = $_POST['autorizada1'];
    $rgAutorizada1	        = $_POST['rgAutorizada1'];
    $autorizada2	          = $_POST['autorizada2'];
    $rgAutorizada2	        = $_POST['rgAutorizada2'];
    $autorizada3            =	$_POST['autorizada3'];
    $rgAutorizada3	        = $_POST['rgAutorizada3'];
    $autorizada4	          = $_POST['autorizada4'];    
    $rgAutorizada4	        = $_POST['rgAutorizada4'];
    $impedimentoEdFisica	  = $_POST['impedimentoEdFisica'];
    $qualImpedimento        = $_POST['qualImpedimento'];
    $escolaAnterior         = $_POST['escolaAnterior'];
    $cidade	                = $_POST['cidade'];
    $estado	                = $_POST['estado'];
    $serie	                = $_POST['serie'];
    $curso	                = $_POST['curso'];
    $ano                    = $_POST['ano'];

    if($_POST['grava']==1){
              $grava_info_gerais=$con->prepare("insert into aluno_infogerais(
                          idAluno,
                          meioTransporte,
                          sairSozinho,
                          autorizada1,
                          rgAutorizada1,
                          autorizada2,
                          rgAutorizada2,
                          autorizada3,
                          rgAutorizada3,
                          autorizada4,
                          rgAutorizada4,
                          impedimentoEdFisica,
                          qualImpedimento,
                          escolaAnterior,
                          cidade,
                          estado,
                          serie,
                          curso,
                          ano
                          )value(
                            :idAluno,
                            :meioTransporte,
                            :sairSozinho,
                            :autorizada1,
                            :rgAutorizada1,
                            :autorizada2,
                            :rgAutorizada2,
                            :autorizada3,
                            :rgAutorizada3,
                            :autorizada4,
                            :rgAutorizada4,
                            :impedimentoEdFisica,
                            :qualImpedimento,
                            :escolaAnterior,
                            :cidade,
                            :estado,
                            :serie,
                            :curso,
                            :ano
                          )");
            $grava_info_gerais->execute(array(
              ':idAluno'                => $idAluno,
              ':meioTransporte'         => $meioTransporte,
              ':sairSozinho'	          => $sairSozinho,
              ':autorizada1'	          => $autorizada1,
              ':rgAutorizada1'	        => $rgAutorizada1,
              ':autorizada2'	          => $autorizada2,
              ':rgAutorizada2'	        => $rgAutorizada2,
              ':autorizada3'            => $autorizada3,
              ':rgAutorizada3'	        => $rgAutorizada3,
              ':autorizada4'	          => $autorizada4,    
              ':rgAutorizada4'	        => $rgAutorizada4,
              ':impedimentoEdFisica'	  => $impedimentoEdFisica,
              ':qualImpedimento'        => $qualImpedimento,
              ':escolaAnterior'         => $escolaAnterior,
              ':cidade'	                => $cidade,
              ':estado'	                => $estado,
              ':serie'	                => $serie,
              ':curso'	                => $curso,
              ':ano'                    => $ano
            ));
    }elseif($_POST['edita']==1){
            $altera_infor_gerais=$con->prepare("update aluno_infogerais set 
                    idAluno                = :idAluno,
                    meioTransporte	       = :meioTransporte,
                    sairSozinho	           = :sairSozinho,
                    autorizada1	           = :autorizada1,
                    rgAutorizada1	         = :rgAutorizada1,
                    autorizada2	           = :autorizada2,
                    rgAutorizada2	         = :rgAutorizada2,
                    autorizada3            =	:autorizada3,
                    rgAutorizada3	         = :rgAutorizada3,
                    autorizada4	           = :autorizada4,    
                    rgAutorizada4	         = :rgAutorizada4,
                    impedimentoEdFisica	   = :impedimentoEdFisica,
                    qualImpedimento        = :qualImpedimento,
                    escolaAnterior         = :escolaAnterior,
                    cidade	               = :cidade,
                    estado	               = :estado,
                    serie	                 = :serie,
                    curso	                 = :curso,
                    ano                    = :ano
                    where id = :id
            ");
            $altera_infor_gerais->execute(array(
              ':id'                     => $_POST['idFicha'],
              ':idAluno'                => $idAluno,
              ':meioTransporte'         => $meioTransporte,
              ':sairSozinho'	          => $sairSozinho,
              ':autorizada1'	          => $autorizada1,
              ':rgAutorizada1'	        => $rgAutorizada1,
              ':autorizada2'	          => $autorizada2,
              ':rgAutorizada2'	        => $rgAutorizada2,
              ':autorizada3'            => $autorizada3,
              ':rgAutorizada3'	        => $rgAutorizada3,
              ':autorizada4'	          => $autorizada4,    
              ':rgAutorizada4'	        => $rgAutorizada4,
              ':impedimentoEdFisica'	  => $impedimentoEdFisica,
              ':qualImpedimento'        => $qualImpedimento,
              ':escolaAnterior'         => $escolaAnterior,
              ':cidade'	                => $cidade,
              ':estado'	                => $estado,
              ':serie'	                => $serie,
              ':curso'	                => $curso,
              ':ano'                    => $ano
            ));
    }


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
    //busca as informa&ccedil;&otilde;es gerais do aluno
    $busca_infogerais=$con->prepare("SELECT * from aluno_infogerais where aluno_infogerais.idAluno = :id");
    $busca_infogerais->bindValue(":id", $idAluno);
    $busca_infogerais->execute();
    $resultado_infogerais = $busca_infogerais->fetchAll(PDO::FETCH_ASSOC);
    //ja verifica a existencia, se n&atilde;o existir mostra pra gravar caso contrario, pra editar
    if(!empty($resultado_infogerais[0])){
      $edicao = "sim";
    }else{
      $edicao = "nao";
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
    <td><?= formatoData($resultado[0]['dtNascimento']); ?></td>
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
    <th colspan="9" align="center">Informa&ccedil;&otilde;es Gerais do Aluno(a)</th>
    </tr>
  <tr>
    <tD colspan="9" align="center">
    <form method="post" action="#" enctype="multipart/form-data" id="frmAluno">  
  
  <table width="100%" cellpadding="0" cellspacing="0" class="ficha_med" id="exibe_dados">
      <tr>
        <td><p> Aluno(a)  vem para a escola de que forma?</p></td>
        <td colspan="3"><input type="radio" name="meioTransporte" value="0" <?php if(@$resultado_infogerais[0]['meioTransporte']=="0"){echo "checked";}?>>A p &eacute; <br />
                        <input type="radio" name="meioTransporte" value="1" <?php if(@$resultado_infogerais[0]['meioTransporte']=="1"){echo "checked";}?>> Ve &iacute;culo da Fam &iacute;lia<br />
                        <input type="radio" name="meioTransporte" value="2" <?php if(@$resultado_infogerais[0]['meioTransporte']=="2"){echo "checked";}?>>Transporte Escolar<br />
                        <input type="radio" name="meioTransporte" value="3" <?php if(@$resultado_infogerais[0]['meioTransporte']=="3"){echo "checked";}?>>Transporte P &uacute;blico</td>
        </tr>
      <tr>
        <td><p>Aluno(a)  est&aacute; autorizado(a) a deixar a escola sozinho(a)?<br />
          <span class="legendaIG">Em caso  negativo, al&eacute;m dos respons&aacute;veis, as &uacute;nicas pessoas autorizadas a retir&aacute;-lo/a da  escola s&atilde;o:</span></p></td>
        <td><input type="radio" name="sairSozinho" value="1" <?php if(@$resultado_infogerais[0]['sairSozinho']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="sairSozinho" value="0" <?php if(@$resultado_infogerais[0]['sairSozinho']=="0"){echo "checked";}?>>N&atilde;o</td>
        
      </tr>
      <tr>
        <td >Nome: <input type="text" name="autorizada1" value ="<?php if(@$resultado_infogerais[0]['autorizada1']!=""){echo $resultado_infogerais[0]['autorizada1'];}?>" title="Nome da pessoa Autorizada" maxlenght="60" size="90"> </td>
        <td>RG: <input type="text" name="rgAutorizada1" value="<?php if(@$resultado_infogerais[0]['rgAutorizada1']!=""){echo $resultado_infogerais[0]['rgAutorizada1'];}?>"title="RG da pessoa Autorizada" maxlenght="12" size="30"></td>
        
      </tr>
      <tr>
      <td >Nome: <input type="text" name="autorizada2" value ="<?php if(@$resultado_infogerais[0]['autorizada2']!=""){echo $resultado_infogerais[0]['autorizada2'];}?>" title="Nome da pessoa Autorizada" maxlenght="60" size="90"> </td>
        <td>RG: <input type="text" name="rgAutorizada2" value="<?php if(@$resultado_infogerais[0]['rgAutorizada2']!=""){echo $resultado_infogerais[0]['rgAutorizada2'];}?>"title="RG da pessoa Autorizada" maxlenght="12" size="30"></td>
        
      </tr>
      <tr>
      <td >Nome: <input type="text" name="autorizada3" value ="<?php if(@$resultado_infogerais[0]['autorizada3']!=""){echo $resultado_infogerais[0]['autorizada3'];}?>" title="Nome da pessoa Autorizada" maxlenght="60" size="90"> </td>
        <td>RG: <input type="text" name="rgAutorizada3" value="<?php if(@$resultado_infogerais[0]['rgAutorizada3']!=""){echo $resultado_infogerais[0]['rgAutorizada3'];}?>"title="RG da pessoa Autorizada" maxlenght="12" size="30"></td>
        
      </tr>
      <tr>
      <td >Nome: <input type="text" name="autorizada4" value ="<?php if(@$resultado_infogerais[0]['autorizada4']!=""){echo $resultado_infogerais[0]['autorizada4'];}?>" title="Nome da pessoa Autorizada" maxlenght="60" size="90"> </td>
        <td>RG: <input type="text" name="rgAutorizada4" value="<?php if(@$resultado_infogerais[0]['rgAutorizada4']!=""){echo $resultado_infogerais[0]['rgAutorizada4'];}?>"title="RG da pessoa Autorizada" maxlenght="12" size="30"></td>
        
      </tr>
      <tr>
        <td>Aluno(a)  possui algum impedimento permanente para a pr&aacute;tica de Educa&ccedil;&atilde;o F&iacute;sica? </td>
        <td><input type="radio" name="impedimentoEdFisica" value="1" <?php if(@$resultado_infogerais[0]['impedimentoEdFisica']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="impedimentoEdFisica" value="0" <?php if(@$resultado_infogerais[0]['impedimentoEdFisica']=="0"){echo "checked";}?>>N&atilde;o</td>
        
      </tr>
      <tr>
        <td colspan="4"><p><br />
          Qual? <input type="text" name="qualImpedimento" value ="<?php if(@$resultado_infogerais[0]['qualImpedimento']!=""){echo $resultado_infogerais[0]['qualImpedimento'];}?>" title="Qual o impedimento para a pr&aacute;tica de Educa&ccedil;&atilde;o F&iacute;sica?" maxlenght="150" size="90"></p></td>
        </tr>
      <tr>
        <td><p>Escola  anterior: </p></td>
        <td><input class="teste" type="text" name="escolaAnterior" value ="<?php if(@$resultado_infogerais[0]['escolaAnterior']!=""){echo $resultado_infogerais[0]['escolaAnterior'];}?>" title="Escola Anterior" maxlenght="60" size="30"></td>
        
      </tr>
      <tr>
        <td>Cidade:<br /></td>
        <td><input class="teste" type="text" name="cidade" value ="<?php if(@$resultado_infogerais[0]['cidade']!=""){echo $resultado_infogerais[0]['cidade'];}?>" title="Cidade" maxlenght="60" size="30"></td>
        
      </tr>
      <tr>
        <td>Estado: <br /></td>
        <td><input class="teste" type="text" name="estado" value ="<?php if(@$resultado_infogerais[0]['estado']!=""){echo $resultado_infogerais[0]['estado'];}?>" title="Estado" maxlenght="60" size="30"></td>
        
      </tr>
      <tr>
        <td>S&eacute;rie: <br /></td>
        <td><input class="teste" type="text" name="serie" value ="<?php if(@$resultado_infogerais[0]['serie']!=""){echo $resultado_infogerais[0]['serie'];}?>" title="S&eacute;rie" maxlenght="60" size="30"></td>
        
      </tr>
      <tr>
        <td>Curso:   <br /></td>
        <td><input class="teste" type="text" name="curso" value ="<?php if(@$resultado_infogerais[0]['curso']!=""){echo $resultado_infogerais[0]['curso'];}?>" title="Curso" maxlenght="60" size="30"></td>
        
      </tr>
      <tr>
        <td>Ano: </td>
        <td><input class="teste" type="text" name="ano" value ="<?php if(@$resultado_infogerais[0]['ano']!=""){echo $resultado_infogerais[0]['ano'];}?>" title="Ano" maxlenght="60" size="30"></td>
        
      </tr>
    </table>
    
    </tr>
    </table>
    <div align="center">
    <?php if($edicao=="nao"){?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Adicionar Info Gerais"></div>
        <input type="hidden" name="grava" value="1">
        <input type="hidden" name="idAluno" value="<?= $idAluno;?>">
    <?php }elseif($edicao=="sim"){?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Editar Info Gerais"></div>
        <input type="hidden" name="edita" value="1">
        <input type="hidden" name="idFicha" value="<?= $resultado_infogerais[0]['id'];?>">
        
    <?php }?>
    </div>
    </form>
    <p>&nbsp;</p>
    
    <p>&nbsp;</p>
    <!-- fim da tabela demonstrativa-->

</div>
</div>



				
    
    
    