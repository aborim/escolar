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

   //pega as variaveis enviadas pelo formulario
         
   $peso                   = $_POST['peso'];
   $tipoSanguineo          = $_POST['tipoSanguineo'];       
   $nomeMedico             = $_POST['nomeMedico'];
   $telMedico              = $_POST['telMedico'];
   $necessidadeEspecial    = $_POST['necessidadeEspecial'];
   $necessidadeQual        = $_POST['necessidadeQual'];
   $alergicoMedicamento    = $_POST['alergicoMedicamento'];
   $alergicoQual           = $_POST['alergicoQual'];
   $alergicoAlimento       = $_POST['alergicoAlimento'];
   $alergicoAlimentoQual   = $_POST['alergicoAlimentoQual'];
   $febre                  = $_POST['febre'];
   $febreMedicamento       = $_POST['febreMedicamento'];
   $febreDosagem           = $_POST['febreDosagem'];
   $doencaCongenita        = $_POST['doencaCongenita'];
   $doencaCongenitaQual    = $_POST['doencaCongenitaQual'];
   $doencaContagiosa       = $_POST['doencaContagiosa'];
   $doencaContagiosaOutras = $_POST['doencaContagiosaOutras'];
   $hemofilico             = $_POST['hemofilico'];
   $epileptico             = $_POST['epileptico'];
   $hipertenso             = $_POST['hipertenso'];
   $asmatico               = $_POST['asmatico'];
   $diabetico              = $_POST['diabetico'];
   $cardiaco               = $_POST['cardiaco'];
   $estaEmTratamento       = $_POST['estaEmTratamento'];
   $tratamentoQual         = $_POST['tratamentoQual'];
   $medicacaoEspecifica    = $_POST['medicacaoEspecifica'];
   $medicacaoEspecificaQual= $_POST['medicacaoEspecificaQual'];
   $planoSaude             = $_POST['planoSaude'];
   $planoSaudeQual         = $_POST['planoSaudeQual'];
   $planoSaudeCarterinha   = $_POST['planoSaudeCarterinha'];
   $emergencia             = $_POST['emergencia'];
   $emergenciaNome         = $_POST['emergenciaNome'];
   $emergenciaParentesco   = $_POST['emergenciaParentesco'];
   $emergenciaTelefone     = $_POST['emergenciaTelefone'];
   $emergenciaCelular      = $_POST['emergenciaCelular'];
   

   if($_POST['grava']==1){
         $grava_ficha_med=$con->prepare("
                     insert into aluno_fichamed (
                       idAluno,
                       peso,	
                       tipoSanguineo,
                       nomeMedico,
                       telMedico,
                       necessidadeEspecial,
                       necessidadeQual,
                       alergicoMedicamento,
                       alergicoQual,
                       alergicoAlimento,
                       alergicoAlimentoQual,
                       febre,
                       febreMedicamento,
                       febreDosagem,
                       doencaCongenita,
                       doencaCongenitaQual,
                       doencaContagiosa,
                       doencaContagiosaOutras,
                       hemofilico,
                       epileptico,
                       hipertenso,
                       asmatico,
                       diabetico,
                       cardiaco,
                       estaEmTratamento,
                       tratamentoQual,
                       medicacaoEspecifica,
                       medicacaoEspecificaQual,
                       planoSaude,
                       planoSaudeQual,
                       planoSaudeCarterinha,
                       emergencia,
                       emergenciaNome,
                       emergenciaParentesco,
                       emergenciaTelefone,
                       emergenciaCelular)
                       value(
                         :idAluno,
                         :peso,	
                         :tipoSanguineo,
                         :nomeMedico,
                         :telMedico,
                         :necessidadeEspecial,
                         :necessidadeQual,
                         :alergicoMedicamento,
                         :alergicoQual,
                         :alergicoAlimento,
                         :alergicoAlimentoQual,
                         :febre,
                         :febreMedicamento,
                         :febreDosagem,
                         :doencaCongenita,
                         :doencaCongenitaQual,
                         :doencaContagiosa,
                         :doencaContagiosaOutras,
                         :hemofilico,
                         :epileptico,
                         :hipertenso,
                         :asmatico,
                         :diabetico,
                         :cardiaco,
                         :estaEmTratamento,
                         :tratamentoQual,
                         :medicacaoEspecifica,
                         :medicacaoEspecificaQual,
                         :planoSaude,
                         :planoSaudeQual,
                         :planoSaudeCarterinha,
                         :emergencia,
                         :emergenciaNome,
                         :emergenciaParentesco,
                         :emergenciaTelefone,
                         :emergenciaCelular)

        " );
        $grava_ficha_med->execute(array(
               ':idAluno'                => $idAluno,
               ':peso'                   => $peso,
               ':tipoSanguineo'          => $tipoSanguineo,       
               ':nomeMedico'             => $nomeMedico,
               ':telMedico'              => $telMedico,
               ':necessidadeEspecial'    => $necessidadeEspecial,
               ':necessidadeQual'        => $necessidadeQual,
               ':alergicoMedicamento'    => $alergicoMedicamento,
               ':alergicoQual'           => $alergicoQual,
               ':alergicoAlimento'       => $alergicoAlimento,
               ':alergicoAlimentoQual'   => $alergicoAlimentoQual,
               ':febre'                  => $febre,
               ':febreMedicamento'       => $febreMedicamento,
               ':febreDosagem'           => $febreDosagem,
               ':doencaCongenita'        => $doencaCongenita,
               ':doencaCongenitaQual'    => $doencaCongenitaQual,
               ':doencaContagiosa'       => $doencaContagiosa,
               ':doencaContagiosaOutras' => $doencaContagiosaOutras,
               ':hemofilico'             => $hemofilico,
               ':epileptico'             => $epileptico,
               ':hipertenso'             => $hipertenso,
               ':asmatico'               => $asmatico,
               ':diabetico'              => $diabetico,
               ':cardiaco'               => $cardiaco,
               ':estaEmTratamento'       => $estaEmTratamento,
               ':tratamentoQual'         => $tratamentoQual,
               ':medicacaoEspecifica'    => $medicacaoEspecifica,
               ':medicacaoEspecificaQual'=> $medicacaoEspecificaQual,
               ':planoSaude'             => $planoSaude,
               ':planoSaudeQual'         => $planoSaudeQual,
               ':planoSaudeCarterinha'   => $planoSaudeCarterinha,
               ':emergencia'             => $emergencia,
               ':emergenciaNome'         => $emergenciaNome,
               ':emergenciaParentesco'   => $emergenciaParentesco,
               ':emergenciaTelefone'     => $emergenciaTelefone,
               ':emergenciaCelular'      => $emergenciaCelular
        ));
   
   }elseif($_POST['edita']==1){
     $altera_ficha_med=$con->prepare("update aluno_fichamed set
                                 idAluno                 = :idAluno,
                                 peso                    = :peso,
                                 tipoSanguineo           = :tipoSanguineo,
                                 nomeMedico              = :nomeMedico,
                                 telMedico               = :telMedico,
                                 necessidadeEspecial     = :necessidadeEspecial,
                                 necessidadeQual         = :necessidadeQual,
                                 alergicoMedicamento     = :alergicoMedicamento,
                                 alergicoQual            = :alergicoQual,
                                 alergicoAlimento        = :alergicoAlimento,
                                 alergicoAlimentoQual    = :alergicoAlimentoQual,
                                 febre                   = :febre,
                                 febreMedicamento        = :febreMedicamento,
                                 febreDosagem            = :febreDosagem,
                                 doencaCongenita         = :doencaCongenita,
                                 doencaCongenitaQual     = :doencaCongenitaQual,
                                 doencaContagiosa        = :doencaContagiosa,
                                 doencaContagiosaOutras  = :doencaContagiosaOutras,
                                 hemofilico              = :hemofilico,
                                 epileptico              = :epileptico,
                                 hipertenso              = :hipertenso,
                                 asmatico                = :asmatico,
                                 diabetico               = :diabetico,
                                 cardiaco                = :cardiaco,
                                 estaEmTratamento        = :estaEmTratamento,
                                 tratamentoQual          = :tratamentoQual,
                                 medicacaoEspecifica     = :medicacaoEspecifica,
                                 medicacaoEspecificaQual = :medicacaoEspecificaQual,
                                 planoSaude              = :planoSaude,
                                 planoSaudeQual          = :planoSaudeQual,
                                 planoSaudeCarterinha    = :planoSaudeCarterinha,
                                 emergencia              = :emergencia,
                                 emergenciaNome          = :emergenciaNome,
                                 emergenciaParentesco    = :emergenciaParentesco,
                                 emergenciaTelefone      = :emergenciaTelefone,
                                 emergenciaCelular       = :emergenciaCelular
                                 where id = :id
                                 ");
     $altera_ficha_med->execute(array(
       ':id'                     => $_POST['idFicha'],
       ':idAluno'                => $idAluno,
       ':peso'                   => $peso,
       ':tipoSanguineo'          => $tipoSanguineo,       
       ':nomeMedico'             => $nomeMedico,
       ':telMedico'              => $telMedico,
       ':necessidadeEspecial'    => $necessidadeEspecial,
       ':necessidadeQual'        => $necessidadeQual,
       ':alergicoMedicamento'    => $alergicoMedicamento,
       ':alergicoQual'           => $alergicoQual,
       ':alergicoAlimento'       => $alergicoAlimento,
       ':alergicoAlimentoQual'   => $alergicoAlimentoQual,
       ':febre'                  => $febre,
       ':febreMedicamento'       => $febreMedicamento,
       ':febreDosagem'           => $febreDosagem,
       ':doencaCongenita'        => $doencaCongenita,
       ':doencaCongenitaQual'    => $doencaCongenitaQual,
       ':doencaContagiosa'       => $doencaContagiosa,
       ':doencaContagiosaOutras' => $doencaContagiosaOutras,
       ':hemofilico'             => $hemofilico,
       ':epileptico'             => $epileptico,
       ':hipertenso'             => $hipertenso,
       ':asmatico'               => $asmatico,
       ':diabetico'              => $diabetico,
       ':cardiaco'               => $cardiaco,
       ':estaEmTratamento'       => $estaEmTratamento,
       ':tratamentoQual'         => $tratamentoQual,
       ':medicacaoEspecifica'    => $medicacaoEspecifica,
       ':medicacaoEspecificaQual'=> $medicacaoEspecificaQual,
       ':planoSaude'             => $planoSaude,
       ':planoSaudeQual'         => $planoSaudeQual,
       ':planoSaudeCarterinha'  => $planoSaudeCarterinha,
       ':emergencia'             => $emergencia,
       ':emergenciaNome'         => $emergenciaNome,
       ':emergenciaParentesco'   => $emergenciaParentesco,
       ':emergenciaTelefone'     => $emergenciaTelefone,
       ':emergenciaCelular'      => $emergenciaCelular
     ));
     $status = $altera_ficha_med->errorCode();
     if($status =="00000"){
         $dados_atualizados = "ok";
     }

   }










    //busca as informa&ccedil;&otilde;es do aluno selecionado
    $buscar=$con->prepare("SELECT endereco.endereco,
		endereco.numero,
        endereco.complemento,
        endereco.bairro,
        endereco.cep,
        endereco.cidade,
        endereco.estado,
        aluno.id,
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
#busca a ficha m &eacute;dica do aluno.
$busca_ficha=$con->prepare("SELECT * FROM aluno_fichamed WHERE aluno_fichamed.idAluno = :id");
        
$busca_ficha->bindvalue(":id", $idAluno);
$busca_ficha->execute();
$resultado_ficha = $busca_ficha->fetchAll(PDO::FETCH_ASSOC);
#ja verifica a existencia, se n&atilde;o existir mostra pra gravar caso contrario, pra editar
if (!empty($resultado_ficha[0])){
  $edicao = "sim";
 
}else {
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
   
  <form method="post" action="#" enctype="multipart/form-data" id="frmAluno">  
  <div class="form_comp">
  <table width="100%" cellpadding="0" cellspacing="0" class="ficha_med" id="exibe_dados">
      
      <tr>
        <td>Peso atual do aluno:</td>
        <td><input type="text" name="peso" value ="<?php if(@$resultado_ficha[0]['peso']!=""){echo $resultado_ficha[0]['peso'];}?>" title="Qual o peso do aluno?" maxlenght="20" size="30"></td>
        <td>Tipo Sangu&iacute;neo:</td>
        <td colspan="6">
              <select name="tipoSanguineo" id="select">
                <option value="a+" <?php if(@$resultado_ficha[0]['tipoSanguineo']=="a+"){echo " selected";}?>>A+</option>
                <option value="a-" <?php if(@$resultado_ficha[0]['tipoSanguineo']=="a-"){echo " selected";}?>>A-</option>
                <option value="b+" <?php if(@$resultado_ficha[0]['tipoSanguineo']=="b+"){echo " selected";}?>>B+</option>
                <option value="b-" <?php if(@$resultado_ficha[0]['tipoSanguineo']=="b-"){echo " selected";}?>>B-</option>
                <option value="o+" <?php if(@$resultado_ficha[0]['tipoSanguineo']=="o+"){echo " selected";}?>>O+</option>
                <option value="o-" <?php if(@$resultado_ficha[0]['tipoSanguineo']=="o-"){echo " selected";}?>>O-</option>
                <option value="ab+" <?php if(@$resultado_ficha[0]['tipoSanguineo']=="ab+"){echo " selected";}?>>AB+</option>
                <option value="ab-" <?php if(@$resultado_ficha[0]['tipoSanguineo']=="ab-"){echo " selected";}?>>AB-</option>

              </select>
        </td>
        </tr>
      <tr>
        <td>Nome do  m&eacute;dico do aluno:</td>
        <td><input type="text" name="nomeMedico" value ="<?php if(@$resultado_ficha[0]['nomeMedico']!=""){echo $resultado_ficha[0]['nomeMedico'];}?>" title="Qual Medico do Aluno?" maxlenght="60" size="30"></td>
        <td>Telefone  para contato:</td>
        <td><input type="text" name="telMedico" value ="<?php if(@$resultado_ficha[0]['telMedico']!=""){echo $resultado_ficha[0]['telMedico'];}?>" title="Telefone do Medico para contato" maxlenght="20" size="30"></td>
        </tr>
      <tr>
        <td>Aluno(a) &eacute;  portador de necessidade especial?</td>
        <td><input type="radio" name="necessidadeEspecial"  value="1" <?php if(@$resultado_ficha[0]['necessidadeEspecial']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="necessidadeEspecial"  value="0" <?php if(@$resultado_ficha[0]['necessidadeEspecial']=="0"){echo "checked";}?>>N&atilde;o
        </td>
        <td >Qual?</td>
        <td><input type="text" name="necessidadeQual" value ="<?php if(@$resultado_ficha[0]['necessidadeQual']!=""){echo $resultado_ficha[0]['necessidadeQual'];}?>" title="Qual a Necessidade especial do Aluno?" maxlenght="40" size="30"></td>
      </tr>
      <tr>
        <td>Aluno/a &eacute;  al&eacute;rgico a algum tipo de medicamento? </td>
        <td>
            <input type="radio" name="alergicoMedicamento" value="1" <?php if(@$resultado_ficha[0]['alergicoMedicamento']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="alergicoMedicamento" value="0" <?php if(@$resultado_ficha[0]['alergicoMedicamento']=="0"){echo "checked";}?>>N&atilde;o
        </td>
        <td>Qual?</td>
        <td><input type="text" name="alergicoQual" value ="<?php if(@$resultado_ficha[0]['alergicoQual']!=""){echo $resultado_ficha[0]['alergicoQual'];}?>" title="Qual, ou quais os medicamentos o aluno &eacute; al&eacute;rgico?" maxlenght="100" size="30"></td>
      </tr>
      <tr>
        <td >Aluno/a &eacute;  al&eacute;rgico a algum tipo de alimento ou material? </td>
        <td>
            <input type="radio" name="alergicoAlimento" value="1" <?php if(@$resultado_ficha[0]['alergicoAlimento']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="alergicoAlimento" value="0" <?php if(@$resultado_ficha[0]['alergicoAlimento']=="0"){echo "checked";}?>>N&atilde;o
        </td>
        <td >Quais</td>
        <td><input type="text" name="alergicoAlimentoQual" value ="<?php if(@$resultado_ficha[0]['alergicoAlimentoQual']!=""){echo $resultado_ficha[0]['alergicoAlimentoQual'];}?>" title="Qual, ou quais os alimentos ou materiais o aluno &eacute; al&eacute;rgico?" maxlenght="100" size="30"></td>
      </tr>
      <tr>
        <td>Em caso  de febre alta, n&atilde;o sendo localizados os pais ou respons&aacute;veis, qual medicamento  poder&aacute; ser utilizado?</td>
        <td>
            <input type="radio" name="febre" value="1" <?php if(@$resultado_ficha[0]['febre']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="febre" value="0" <?php if(@$resultado_ficha[0]['febre']=="0"){echo "checked";}?>>N&atilde;o
        </td></tr><tr>
        <td>Medicamento:</td>
        <td><input type="text" name="febreMedicamento" value ="<?php if(@$resultado_ficha[0]['febreMedicamento']!=""){echo $resultado_ficha[0]['febreMedicamento'];}?>" title="Qual medicamento a ser aplicado neste caso?" maxlenght="50" size="30"></td>
        <td>Dosagem:</td>
        <td><input type="text" name="febreDosagem" value ="<?php if(@$resultado_ficha[0]['febreDosagem']!=""){echo $resultado_ficha[0]['febreDosagem'];}?>" title="Qual a dosagem?" maxlenght="50" size="30"></td>
      </tr>
      <tr>
        <td >Aluno/a  tem alguma doen&ccedil;a cong&ecirc;nita?</td>
        <td>
            <input type="radio" name="doencaCongenita" value="1" <?php if(@$resultado_ficha[0]['doencaCongenita']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="doencaCongenita" value="0" <?php if(@$resultado_ficha[0]['doencaCongenita']=="0"){echo "checked";}?>>N&atilde;o
        </td>
        <td >Qual?</td>
        <td><input type="text" name="doencaCongenitaQual" value ="<?php if(@$resultado_ficha[0]['doencaCongenitaQual']!=""){echo $resultado_ficha[0]['doencaCongenitaQual'];}?>" title="Qual doen&ccedil;a?" maxlenght="100" size="30"></td>
      </tr>
      <tr>
        <td>Quais as  doen&ccedil;as contagiosas j&aacute; contra&iacute;das na inf&acirc;ncia?</td>
        <td colspan="8">
            
            <select name="doencaContagiosa" id="select">
                <option value="nenhuma" <?php if(@$resultado_ficha[0]['doencaContagiosa']=="nenhuma"){echo " selected";}?>>Nenhuma</option> 
                <option value="caxumba" <?php if(@$resultado_ficha[0]['doencaContagiosa']=="caxumba"){echo " selected";}?>>Caxumba</option> 
                <option value="sarampo" <?php if(@$resultado_ficha[0]['doencaContagiosa']=="sarampo"){echo " selected";}?>>Sarampo</option> 
                <option value="rubeola" <?php if(@$resultado_ficha[0]['doencaContagiosa']=="rubeola"){echo " selected";}?>>Rub&eacute;ola</option> 
                <option value="catapora" <?php if(@$resultado_ficha[0]['doencaContagiosa']=="catapora"){echo " selected";}?>>Catapora</option> 
                <option value="coqueluxe" <?php if(@$resultado_ficha[0]['doencaContagiosa']=="coqueluxe"){echo " selected";}?>>Coqueluxe</option> 
            </select>
        </td>
        </tr>
      <tr>
        <td >Outras?</td>
        <td><input type="text" name="doencaContagiosaOutras" value ="<?php if(@$resultado_ficha[0]['doencaContagiosaOutras']!=""){echo $resultado_ficha[0]['doencaContagiosaOutras'];}?>" title="Quais outras doen&ccedil;as o aluno possui?" maxlenght="100" size="30"></td>
        </tr>
      <tr>
        <td>Aluno(a) &eacute;  hemof&iacute;lico(a)?</td>
        
        <td><input type="radio" name="hemofilico" value="1" <?php if(@$resultado_ficha[0]['hemofilico']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="hemofilico" value="0" <?php if(@$resultado_ficha[0]['hemofilico']=="0"){echo "checked";}?>>N&atilde;o
            <input type="radio" name="hemofilico" value="2" <?php if(@$resultado_ficha[0]['hemofilico']=="2"){echo "checked";}?>>Em tratamento
        </td>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td>Aluno(a)  &eacute; epil&eacute;tico(a)?</td>
        <td><input type="radio" name="epileptico" value="1" <?php if(@$resultado_ficha[0]['epileptico']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="epileptico" value="0" <?php if(@$resultado_ficha[0]['epileptico']=="0"){echo "checked";}?>>N&atilde;o
            <input type="radio" name="epileptico" value="2" <?php if(@$resultado_ficha[0]['epileptico']=="2"){echo "checked";}?>>Em tratamento</td>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td>Aluno(a)  &eacute; hipertenso(a)?</td>
        <td><input type="radio" name="hipertenso" value="1" <?php if(@$resultado_ficha[0]['hipertenso']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="hipertenso" value="0" <?php if(@$resultado_ficha[0]['hipertenso']=="0"){echo "checked";}?>>N&atilde;o
            <input type="radio" name="hipertenso" value="2" <?php if(@$resultado_ficha[0]['hipertenso']=="2"){echo "checked";}?>>Em tratamento</td>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td>Aluno(a) &eacute; asm&aacute;tico(a)?</td>
        <td><input type="radio" name="asmatico" value="1" <?php if(@$resultado_ficha[0]['asmatico']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="asmatico" value="0" <?php if(@$resultado_ficha[0]['asmatico']=="0"){echo "checked";}?>>N&atilde;o
            <input type="radio" name="asmatico" value="2" <?php if(@$resultado_ficha[0]['asmatico']=="2"){echo "checked";}?>>Em tratamento</td>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td>Aluno(a)  &eacute; diab&eacute;tico(a)?</td>
        <td><input type="radio" name="diabetico" value="1"<?php if(@$resultado_ficha[0]['diabetico']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="diabetico" value="0"<?php if(@$resultado_ficha[0]['diabetico']=="0"){echo "checked";}?>>N&atilde;o
            <input type="radio" name="diabetico" value="2"<?php if(@$resultado_ficha[0]['diabetico']=="2"){echo "checked";}?>>Usa insulina </td><td colspan="2">
            <input type="radio" name="diabetico" value="3"<?php if(@$resultado_ficha[0]['diabetico']=="3"){echo "checked";}?>>Usa Outro medicamento</td>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td>Aluno(a) &eacute; card&iacute;aco(a)?</td>
        <td><input type="radio" name="cardiaco" value="1" <?php if(@$resultado_ficha[0]['cardiaco']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="cardiaco" value="0" <?php if(@$resultado_ficha[0]['cardiaco']=="0"){echo "checked";}?>>N&atilde;o
            <input type="radio" name="cardiaco" value="2" <?php if(@$resultado_ficha[0]['cardiaco']=="2"){echo "checked";}?>>Em tratamento</td>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td >Est&aacute;  fazendo algum outro tipo de tratamento?</td>
        <td><input type="radio" name="estaEmTratamento" value="1" <?php if(@$resultado_ficha[0]['estaEmTratamento']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="estaEmTratamento" value="0" <?php if(@$resultado_ficha[0]['estaEmTratamento']=="0"){echo "checked";}?>>N&atilde;o
          </td>
        
        <td >Qual?</td>
        <td><input type="text" name="tratamentoQual" value ="<?php if(@$resultado_ficha[0]['tratamentoQual']!=""){echo $resultado_ficha[0]['tratamentoQual'];}?>" title="Qual Tratamento?" maxlenght="100" size="30"></td>
      </tr>
      <tr>
        <td >Aluno(a)  est&aacute; ingerindo medica&ccedil;&atilde;o espec&iacute;fica?</td>
        <td><input type="radio" name="medicacaoEspecifica" value="1" <?php if(@$resultado_ficha[0]['medicacaoEspecifica']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="medicacaoEspecifica" value="0" <?php if(@$resultado_ficha[0]['medicacaoEspecifica']=="0"){echo "checked";}?>>N&atilde;o
        </td>
        
        <td >Qual?</td>
        <td><input type="text" name="medicacaoEspecificaQual" value ="<?php if(@$resultado_ficha[0]['medicacaoEspecificaQual']!=""){echo $resultado_ficha[0]['medicacaoEspecificaQual'];}?>" title="Qual Medica&ccedil;&atilde;o?" maxlenght="100" size="30"></td>
      </tr>
      <tr>
        <td colspan="9" align="center">Emerg&ecirc;ncia</td>
      </tr>
      <tr>
        <td >Aluno(a)  possui algum plano de sa&uacute;de?</td>
        <td><input type="radio" name="planoSaude" value="1" <?php if(@$resultado_ficha[0]['planoSaude']=="1"){echo "checked";}?>>Sim
            <input type="radio" name="planoSaude" value="0" <?php if(@$resultado_ficha[0]['planoSaude']=="0"){echo "checked";}?>>N&atilde;o</td>
            
        </tr>
      <tr>
        <td>Qual?</td>
        <td><input type="text" name="planoSaudeQual" value ="<?php if(@$resultado_ficha[0]['planoSaudeQual']!=""){echo $resultado_ficha[0]['planoSaudeQual'];}?>" title="Qual plano de sa &uacute;de do Aluno?" maxlenght="50" size="30"></td>
        <td>N&uacute;mero da Carterinha:</td>
        <td ><input type="text" name="planoSaudeCarterinha" value ="<?php if(@$resultado_ficha[0]['planoSaudeCarterinha']!=""){echo $resultado_ficha[0]['planoSaudeCarterinha'];}?>" title="Quais numero da carteirinha do Plano de Sa&uacute;de?" maxlenght="30" size="30"></td>
      </tr>
      <tr>
        <td>Em caso  de emerg&ecirc;ncia, quem dever&aacute; ser avisado primeiro?</td>
        <td>
            <input type="radio" name="emergencia" value="1" <?php if(@$resultado_ficha[0]['emergencia']=="1"){echo "checked";}?>> Pai
            <input type="radio" name="emergencia" value="0" <?php if(@$resultado_ficha[0]['emergencia']=="0"){echo "checked";}?>> M&atilde;e
        </td>
        <td colspan="7">&nbsp;</td>
        </tr>
      <tr>
        <td colspan="9">N&atilde;o  conseguindo a comunica&ccedil;&atilde;o, informe uma outra pessoa:</td>
        </tr>
      <tr>
        <td>Nome:</td>
        <td><input type="text" name="emergenciaNome" value ="<?php if(@$resultado_ficha[0]['emergenciaNome']!=""){echo $resultado_ficha[0]['emergenciaNome'];}?>" title="Nome da pessoa para avisar caso nao consiga contato com os pais" maxlenght="60" size="30"></td>
        <td>Parentesco:</td>
        <td><input type="text" name="emergenciaParentesco" value ="<?php if(@$resultado_ficha[0]['emergenciaParentesco']!=""){echo $resultado_ficha[0]['emergenciaParentesco'];}?>" title="Qual o grau de parentesco com o aluno" maxlenght="30" size="30"></td>
      </tr>
      <tr>
        <td>Telefone:</td>
        <td><input type="text" name="emergenciaTelefone" value ="<?php if(@$resultado_ficha[0]['emergenciaTelefone']!=""){echo $resultado_ficha[0]['emergenciaTelefone'];}?>" title="Telefone da do terceiro para caso de emerg&ecirc;ncia" maxlenght="20" size="30"></td>
        <td>Celular:</td>
        <td ><input type="text" name="emergenciaCelular" value ="<?php if(@$resultado_ficha[0]['emergenciaCelular']!=""){echo $resultado_ficha[0]['emergenciaCelular'];}?>" title="Celular da do terceiro para caso de emerg&ecirc;ncia" maxlenght="20" size="30"></td>
      </tr>
    </table>
    
    </th>
  </tr>
  
    </table>
    </div>
    <div align="center">
    <?php if($edicao=="nao"){?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Adicionar Info Medica"></div>
        <input type="hidden" name="grava" value="1">
        <input type="hidden" name="idAluno" value="<?= $idAluno;?>">
    <?php }elseif($edicao=="sim"){?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Editar Info Medica"></div>
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



				
    
    
    