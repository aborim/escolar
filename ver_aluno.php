<div class="titulo_interna">
    <i class="fa fa-users" aria-hidden="true"></i>Pessoas
</div>
<div class="content_aluno">
    <div class="titulo">Exibindo informa&ccedil;&otilde;es do aluno: <?= $nomeAluno;?></div>
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
        aluno.cpf,aluno.rg,
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
    $buscaClasse = $con->prepare("SELECT idAluno, idClasse from classe_aluno where idAluno = :idAluno");
    $buscaClasse->bindValue(":idAluno", $idAluno);
    $buscaClasse->execute();
    $resultadoClasse = $buscaClasse->fetchAll(PDO::FETCH_ASSOC);
    $id_classe = $resultadoClasse[0]['idClasse'];
    $buscaDadosClasse = $con->prepare("SELECT id, anoVigente, nome, periodo from classe where id = :id_classe");
    $buscaDadosClasse->bindValue(":id_classe",$id_classe);
    $buscaDadosClasse->execute();
    $resultadoDadosClasse = $buscaDadosClasse->fetchAll(PDO::FETCH_ASSOC);
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
    <td rowspan="4"><img src="<?php echo $resultado[0]['imagem']==""?"images/usuario.png":$resultado[0]['imagem'];?>" class="imagemAluno" /></td>
    <td class="tituloDado">Nome:</td>
    <td><?= $resultado[0]['nome'];?></td>
    <td class="tituloDado">CPF:</td>
    <td><?= $resultado[0]['cpf'];?></td>
    <td class="tituloDado">RG:</td>
    <td><?= $resultado[0]['rg'];?></td>
    </tr>
  <tr>
    <td class="tituloDado">RA:</td>
    <td><?= $resultado[0]['ra'];?></td>
    <td class="tituloDado">RM:</td>
    <td><?= $resultado[0]['rm'];?></td>
    <td class="tituloDado">Org&atilde;o Emissor:</td>
    <td ><?= $resultado[0]['orgaoEmissor'];?></td>
    </tr>
  <tr>
    <td class="tituloDado">Sexo:</td>
    <td><?= $resultado[0]['sexo']==1?"Feminino":"Masculino";?></td>
    <td class="tituloDado">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="tituloDado">Expedido Em:</td>
    <td ><?= formatoData($resultado[0]['expedidoEm']);?></td>
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
    <th colspan="3" align="center">Localiza&ccedil;&atilde;o</th>
    <th colspan="6" align="center">Dados Cadastrais</th>
  </tr>
  <tr>
    <td class="tituloDado">Endere&ccedil;o:</td>
    <td><?= $resultado[0]['endereco'];?></td>
    <td class="tituloDado">N &uacute;mero:</td>
    <td><?= $resultado[0]['numero'];?></td>
    <td>&nbsp;</td>
    <td class="tituloDado">Telefone:</td>
    <td><?= $resultado[0]['telefone'];?></td>
    <td class="tituloDado">Celular:</td>
    <td><?= $resultado[0]['celular'];?></td>
  </tr>
  <tr>
    <td class="tituloDado">Complemento:</td>
    <td><?= $resultado[0]['complemento'];?></td>
    <td class="tituloDado">Bairro:</td>
    <td><?= $resultado[0]['bairro'];?></td>
    <td>&nbsp;</td>
    <td class="tituloDado">Nacionalidade:</td>
    <td><?= $resultado[0]['nacionalidade'];?></td>
    <td class="tituloDado">Naturalidade:</td>
    <td><?= $resultado[0]['naturalidade'];?></td>
    
  </tr>
  <tr>
    <td class="tituloDado">CEP:</td>
    <td><?= $resultado[0]['cep'];?></td>
    <td class="tituloDado">Cidade:</td>
    <td><?= $resultado[0]['cidade'];?></td>
    <td>&nbsp;</td>
    <td class="tituloDado">Estado Civil:</td>
    <td><?= $resultado[0]['estadoCivil'];?></td>
    <td class="tituloDado">Etnia:</td>
    <td><?= $resultado[0]['etnia'];?></td>
  </tr>
  <tr>
    <td class="tituloDado">Estado</td>
    <td><?= $resultado[0]['estado'];?></td>
    <td class="tituloDado"></td>
    <td></td>
    <td>&nbsp;</td>
    <td class="tituloDado">Certid&atilde;o de Nascimento:</td>
    <td><?= $resultado[0]['certidaoNascimento'];?></td>
    <td class="tituloDado">Folha:</td>
    <td><?= $resultado[0]['folha'];?></td>
    
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="tituloDado">Livro:</td>
    <td><?= $resultado[0]['livro'];?></td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th colspan="9" align="center">Dados Matr&iacute;cula</th>
    </tr>
  <tr>
    <td class="tituloDado">Ano Letivo</td>
    <td><?= isset($resultadoDadosClasse[0]['anoVigente'])?$resultadoDadosClasse[0]['anoVigente']:"Aluno n&atilde;o Matriculado"?></td>
    <td class="tituloDado">Classe</td>
    <td><?= isset($resultadoDadosClasse[0]['nome'])?$resultadoDadosClasse[0]['nome']:"Aluno n&atilde;o Matriculado"?></td>
    <td>&nbsp;</td>
    <td class="tituloDado">Per&iacute;odo</td>
    <td><? if(isset($resultadoDadosClasse[0]['periodo'])){
          switch($resultadoDadosClasse[0]['periodo']){
              case '0':
                $periodo = "Matutino";
              break;
              case '1':
                $periodo = "Vespertino";
                break;
              case '2':
                $periodo = "Noturno";
                break;
              
              default:
                #code ...
                break;
              }
              echo($periodo);
            }else{
              echo"Aluno n&atilde;o Matriculado";
            }
              ?></td>
    <td></td>
    <td><??></td>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    
  </tr>
  <tr>
    <th colspan="9" align="center">Dados Adicionais</th>
    </tr>
  <tr>
    <td class="tituloDado">Aluno recebe NF:</td>
    <td><?= $resultado[0]['notaFiscal']==1?"Sim":"N&atilde;o";?></td>
    <td class="tituloDado">Aluno Bolsista:</td>
    <td><?= $resultado[0]['bolsista']==1?"Sim":"N&atilde;o";?></td>
    <td>&nbsp;</td>
    <td class="tituloDado">Bolsa:</td>
    <td><?= $resultado[0]['valorBolsa'];?></td>
    <td class="tituloDado">Motivo da Bolsa:</td>
    <td><?= $resultado[0]['motivoBolsa'];?></td>
    
    
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th colspan="9" align="center">Responsáveis</th>
    </tr>
    
  
  <?php 
    #busca os responsaveis ja vinculados ao aluno
    $buscaVinculo = $con->prepare("select DISTINCT(responsavel.id) as idResp, responsavel.nome, responsavel.grauParentesco, aluno_responsavel.financeiro, aluno.id from aluno, responsavel, aluno_responsavel where aluno_responsavel.idAluno=:idAluno and aluno_responsavel.idResponsavel=responsavel.id AND aluno.id = aluno_responsavel.idAluno");
    $buscaVinculo->bindValue(":idAluno",$idAluno);
    $buscaVinculo->execute();
    $resultadoVinculo = $buscaVinculo->fetchAll(PDO::FETCH_ASSOC);
 
		//verifica se a query de busca do usu&aacute;rio retornou algum resultado
	if ($buscaVinculo->rowCount()>0) {
    $responsavel = $buscaVinculo->fetchAll(PDO::FETCH_ASSOC);
    #print_r($responsavel);
    $resultadoResponsavel = $responsavel[0];
    
    foreach ($resultadoVinculo as $dado ) {
      ?>
    <tr>
    <td class='tituloDado'>Nome:</td><td colspan="2"><a href='menu_restrito.php?op=ver_responsavel&idResponsavel=<?=$dado['idResp']?>&nome=<?=base64_encode($dado['nome'])?>'><?=$dado['nome']?></a></td>
    
    <td class='tituloDado'>Grau de Parentesco:</td><td colspan="2"><?=$dado['grauParentesco']?></td>
  
    <td class='tituloDado'>Responsável Financeiro:</td>
    <td colspan="2"><?=$dado['financeiro']==1?'Sim':'Não'?></td>
    </tr>
   <?php
    }
	} else{?>
		<tr><td colspan='9'> Nenhum responsável vinculado.</td></tr>
    <?php }
    ?>   
    
  
    </table>
<!-- fim da tabela demonstrativa-->

</div>
</div>



				
    
    
    