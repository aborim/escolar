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
    $rgAluno	                = $_POST['rgAluno'];
    $cpfAluno	                = $_POST['cpfAluno'];
    $foto3x4	                = $_POST['foto3x4'];
    $atestadoMedico	          = $_POST['atestadoMedico'];
    $certidaoNascimento	      = $_POST['certidaoNascimento'];
    $cartaoVacina	            = $_POST['cartaoVacina'];
    $contrato	                = $_POST['contrato'];
    $cpfResponsavel	          = $_POST['cpfResponsavel'];
    $rgResponsavel	          = $_POST['rgResponsavel'];
    $comprovanteRenda         = $_POST['comprovanteRenda'];
    $comprovanteResidencia    = $_POST['comprovanteResidencia'];

    if($_POST['grava']==1){
        $grava_doc_adicionais=$con->prepare("insert into aluno_docadicionais(
                                  idAluno,
                                  rgAluno,	
                                  cpfAluno,	
                                  foto3x4,	
                                  atestadoMedico,	
                                  certidaoNascimento,	
                                  cartaoVacina,	
                                  contrato,	
                                  cpfResponsavel,	
                                  rgResponsavel,
                                  comprovanteRenda,
                                  comprovanteResidencia
                                  )values(
                                    :idAluno,
                                    :rgAluno,	
                                    :cpfAluno,	
                                    :foto3x4,		
                                    :atestadoMedico,	
                                    :certidaoNascimento,	
                                    :cartaoVacina,	
                                    :contrato,	
                                    :cpfResponsavel,	
                                    :rgResponsavel,	
                                    :comprovanteRenda,
                                    :comprovanteResidencia
        )");
        $grava_doc_adicionais->execute(array(
            ':idAluno'                  => $idAluno,
            ':rgAluno'	                => $rgAluno,
            ':cpfAluno'                 => $cpfAluno,
            ':foto3x4'                  => $foto3x4,
            ':atestadoMedico'           => $atestadoMedico,
            ':certidaoNascimento'       => $certidaoNascimento,
            ':cartaoVacina'             => $cartaoVacina,
            ':contrato'                 => $contrato,
            ':cpfResponsavel'           => $cpfResponsavel,
            ':rgResponsavel'            => $rgResponsavel,
            ':comprovanteRenda'         => $comprovanteRenda,
            ':comprovanteResidencia'    => $comprovanteResidencia
        ));
    }elseif($_POST['edita']==1){
        $altera_doc_adicionais=$con->prepare("update aluno_docadicionais set 
                            idAluno                  = :idAluno,
                            rgAluno	                 = :rgAluno,
                            cpfAluno                 = :cpfAluno,
                            foto3x4                  = :foto3x4,
                            atestadoMedico           = :atestadoMedico,
                            certidaoNascimento       = :certidaoNascimento,
                            cartaoVacina             = :cartaoVacina,
                            contrato                 = :contrato,
                            cpfResponsavel           = :cpfResponsavel,
                            rgResponsavel            = :rgResponsavel,
                            comprovanteRenda         = :comprovanteRenda,
                            comprovanteResidencia    = :comprovanteResidencia
                            where id=:id");
        $altera_doc_adicionais->execute(array(
                            ':id'                       => $_POST['idFicha'],  
                            ':idAluno'                  => $idAluno,
                            ':rgAluno'	                => $rgAluno,
                            ':cpfAluno'                 => $cpfAluno,
                            ':foto3x4'                  => $foto3x4,
                            ':atestadoMedico'           => $atestadoMedico,
                            ':certidaoNascimento'       => $certidaoNascimento,
                            ':cartaoVacina'             => $cartaoVacina,
                            ':contrato'                 => $contrato,
                            ':cpfResponsavel'           => $cpfResponsavel,
                            ':rgResponsavel'            => $rgResponsavel,
                            ':comprovanteRenda'         => $comprovanteRenda,
                            ':comprovanteResidencia'    => $comprovanteResidencia
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
        aluno.cpf,aluno.rg,
        aluno.dtNascimento,
        aluno.expedidoEm,aluno.orgaoEmissor,
        aluno.telefone,aluno.celular,
        aluno.nacionalidade,aluno.naturalidade,
        aluno.estadoCivil,aluno.certidaoNascimento,
        aluno.folha,aluno.livro,aluno.etnia,
        aluno.notaFiscal,aluno.bolsista,aluno.motivoBolsa,aluno.valorBolsa,
        usuarios.usuario
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
    //busca a ficha de documentos adicionais do aluno.
    $busca_adicionais=$con->prepare("SELECT * FROM aluno_docadicionais WHERE aluno_docadicionais.idAluno = :id");
            
    $busca_adicionais->bindvalue(":id", $idAluno);
    $busca_adicionais->execute();
    $resultado_ficha = $busca_adicionais->fetchAll(PDO::FETCH_ASSOC);
    //verifica se h&aacute; registro dos dados, se sim, apresenta o bot&atilde;o de editar, caso contrario, de adicionar a informa&ccedil;&atilde;o
    if (!empty($resultado_ficha[0])){
      $edicao = "sim";
    
    }else {
    $edicao = "nao"; 
    }

    ?>
    <form method="post" action="#" enctype="multipart/form-data" id="frmAluno">
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
    <th colspan="9" align="center">Documentos Adicionais</th>
    </tr>
  <tr>
    <tD colspan="9" align="center">
    <form method="post" action="#" enctype="multipart/form-data" id="frmAluno">   
  <table width="100%" cellpadding="0" cellspacing="0" class="ficha_med">
      <tr>
        <td><p>Identidade do aluno
          <br />
        </p></td>
        <td>
            <input type="radio" name="rgAluno" value="1" <?php if(@$resultado_ficha[0]['rgAluno']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="rgAluno" value="0" <?php if(@$resultado_ficha[0]['rgAluno']=="0"){echo "checked";}?>>N&atilde;o
        </td>
        </tr>
      <tr>
        <td>CPF do aluno</td>
        <td><input type="radio" name="cpfAluno" value="1" <?php if(@$resultado_ficha[0]['cpfAluno']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="cpfAluno" value="0" <?php if(@$resultado_ficha[0]['cpfAluno']=="0"){echo "checked";}?>>N&atilde;o
        </td>
      </tr>
      <tr>
        <td>Foto 3X4</td>
        <td><input type="radio" name="foto3x4" value="1" <?php if(@$resultado_ficha[0]['foto3x4']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="foto3x4" value="0" <?php if(@$resultado_ficha[0]['foto3x4']=="0"){echo "checked";}?>>N&atilde;o
        </td>
      <tr>
        <td>Atestado m&eacute;dico</td>
        <td><input type="radio" name="atestadoMedico" value="1" <?php if(@$resultado_ficha[0]['atestadoMedico']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="atestadoMedico" value="0" <?php if(@$resultado_ficha[0]['atestadoMedico']=="0"){echo "checked";}?>>N&atilde;o
        </td>
      </tr>
      <tr>
        <td>Certid&atilde;o de nascimento</td>
        <td><input type="radio" name="certidaoNascimento" value="1" <?php if(@$resultado_ficha[0]['certidaoNascimento']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="certidaoNascimento" value="0" <?php if(@$resultado_ficha[0]['certidaoNascimento']=="0"){echo "checked";}?>>N&atilde;o
        </td>
      </tr>
      
      <tr>
        <td>Cart&atilde;o de vacina&ccedil;&atilde;o </td>
        <td><input type="radio" name="cartaoVacina" value="1" <?php if(@$resultado_ficha[0]['cartaoVacina']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="cartaoVacina" value="0" <?php if(@$resultado_ficha[0]['cartaoVacina']=="0"){echo "checked";}?>>N&atilde;o
        </td>
      </tr>
      <tr>
        <td>Contrato</td>
        <td><input type="radio" name="contrato" value="1" <?php if(@$resultado_ficha[0]['contrato']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="contrato" value="0"<?php if(@$resultado_ficha[0]['contrato']=="0"){echo "checked";}?>>N&atilde;o
        </td>
      </tr>
      <tr>
        <td>CPF do respons&aacute;vel</td>
        <td><input type="radio" name="cpfResponsavel" value="1" <?php if(@$resultado_ficha[0]['cpfResponsavel']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="cpfResponsavel" value="0" <?php if(@$resultado_ficha[0]['cpfResponsavel']=="0"){echo "checked";}?>>N&atilde;o
        </td>
      </tr>
      <tr>
        <td>RG do respons&aacute;vel</td>
        <td><input type="radio" name="rgResponsavel" value="1" <?php if(@$resultado_ficha[0]['rgResponsavel']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="rgResponsavel" value="0" <?php if(@$resultado_ficha[0]['rgResponsavel']=="0"){echo "checked";}?>>N&atilde;o
        </td>
      </tr>
      <tr>
        <td>Comprovante de Renda &uacute;ltimos 3 meses</td>
        <td><input type="radio" name="comprovanteRenda" value="1" <?php if(@$resultado_ficha[0]['comprovanteRenda']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="comprovanteRenda" value="0" <?php if(@$resultado_ficha[0]['comprovanteRenda']=="0"){echo "checked";}?>>N&atilde;o
        </td>
      </tr>
      <tr>
        <td>Comprovante de Residencia</td>
        <td><input type="radio" name="comprovanteResidencia" value="1" <?php if(@$resultado_ficha[0]['comprovanteResidencia']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="comprovanteResidencia" value="0" <?php if(@$resultado_ficha[0]['comprovanteResidencia']=="0"){echo "checked";}?>>N&atilde;o
        </td>
      </tr>
    </table>
    </tr>
    </table>
    <div align="center">
    <?php if($edicao=="nao"){?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Adicionar Doc Adicionais"></div>
        <input type="hidden" name="grava" value="1">
        <input type="hidden" name="idAluno" value="<?= $idAluno;?>">
    <?php }elseif($edicao=="sim"){?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Editar Doc adicionais"></div>
        <input type="hidden" name="edita" value="1">
        <input type="hidden" name="idFicha" value="<?= $resultado_ficha[0]['id'];?>">
        
    <?php }?>
    </div>
    </form>
    <p>&nbsp;</p>
    
    <p>&nbsp;</p>
    <!-- fim da tabela demonstrativa-->

</div>
</div>



				
    
    
    