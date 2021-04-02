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


if($_GET['remove']==1){
  $vinculaResp = $con->prepare("delete from `aluno_responsavel` where idAluno= :idAluno and idresponsavel = :idResponsavel");
  $vinculaResp->bindValue(':idAluno',$idAluno);
  $vinculaResp->bindValue(':idResponsavel',$_REQUEST['idResp']);
  
  $vinculaResp->execute();
}

if($_GET['vincula']==1){
  #echo "vincula aluno $idAluno ao respons&aacute;vel ".$_REQUEST['idResp']." como financeiro igual a ".$_REQUEST['financ'];

  $vinculaResp = $con->prepare("INSERT INTO `aluno_responsavel` (`idAluno`, `idResponsavel`, `financeiro`) VALUES (:idAluno, :idResponsavel, :financeiro)");
  $vinculaResp->bindValue(':idAluno',$idAluno);
  $vinculaResp->bindValue(':idResponsavel',$_REQUEST['idResp']);
  $vinculaResp->bindValue(':financeiro',$_REQUEST['RespFinanceiro']);
  $vinculaResp->execute();
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    </tr>
    
    <?php
    #busca os responsaveis ja vinculados ao aluno
    $buscaVinculo = $con->prepare("select DISTINCT(responsavel.id) as idResp, responsavel.nome, responsavel.grauParentesco, aluno_responsavel.financeiro, aluno.id from aluno, responsavel, aluno_responsavel where aluno_responsavel.idAluno=:idAluno and aluno_responsavel.idResponsavel=responsavel.id AND aluno.id = aluno_responsavel.idAluno");
    $buscaVinculo->bindValue(":idAluno",$idAluno);
    $buscaVinculo->execute();
    $resultadoVinculo = $buscaVinculo->fetchAll(PDO::FETCH_ASSOC);
    if($buscaVinculo->rowCount()>0){
    ?>
    <tr>
      <th colspan="9" align="center">Respons&aacute;veis vinculados</th>
    </tr>
    <tr>
      <td colspan="9">
        <table width="100%">
        <tr style="background: #ccc; font-weight: bold;">
          <td>Nome do Respons&aacute;vel</td>
          <td>Grau de Parentesco</td>
          <td>Financeiro</td>
          <td>A&ccedil;&otilde;es</td>
        </tr>
        <?php 
        
        foreach ($resultadoVinculo as $dado ) {?>
          <tr>
          <td><?=$dado['nome']?></td>
          <td><?=$dado['grauParentesco']?></td>
          <td><?php echo $dado['financeiro']==1 ? "Sim":"Não";?></td>
          <td><a href="menu_restrito.php?op=vincular_responsavel&idAluno=<?=$idAluno?>&nome=<?= base64_encode($resultado[$i]['nome'])?>&idResp=<?=$dado['idResp']?>&remove=1">Remover</a></td>
        </tr>
        <?php }?>
        
        <?php ?>
      </table>

    
        <?php }?>
      </td>
    </tr>
  <tr>
    <th colspan="9" align="center">Vincular Responsável</th>
  
    
    <tr>
      <form method="POST" action="">
        <td colspan="4"><input type="text" class="form_campo" name="cpf">
        <input type="hidden" name="busca" value="1">
</td>
<td>
  <input type="submit" value="Buscar" class="form_buttom">
</td>
      </form>
    </tr>
  
    <?php if($_POST['busca']==1){
      // busca as informa&ccedil;&otilde;es para saber se o cnpj ja foi utilizado
      $buscarResponsavel=$con->prepare("SELECT id,nome,grauParentesco,cpf FROM responsavel WHERE cpf =:cpf ");
      $buscarResponsavel->bindValue(":cpf",tiraPonto($_POST['cpf']));
      $buscarResponsavel->execute();
      if($buscarResponsavel->rowCount()>0){
        $resultadoResponsavel = $buscarResponsavel->fetchAll(PDO::FETCH_ASSOC);
      
         
      
      ?>
      <tr style="background: #ccc; font-weight: bold;">
      <td>Respons&aacute;vel</td>
      <td>Grau de Parentesco</td>
      <td>CPF</td>
      <td>Financeiro</td>
      <td>A&ccedil;&atilde;o</td>
    </tr>
    <tr style="background: #e0e0e0;">
      <td><?=$resultadoResponsavel[0]['nome']?></td>
      <td><?=$resultadoResponsavel[0]['grauParentesco']?></td>
      <td><?=$resultadoResponsavel[0]['cpf']?></td>
      <td><input type="checkbox" name="RespFinanceiro" value="1" id="finanCheck" onclick="checkFinanceiro()"></td>
      <td><p id="financ"><a href="menu_restrito.php?op=vincular_responsavel&idAluno=<?=$idAluno?>&nome=<?= base64_encode($resultado[0]['nome'])?>&idResp=<?=$resultadoResponsavel[0]['id']?>&vincula=1" id="linkVinculo">vincular</a></p></td></tr>
    <script>
    function checkFinanceiro(){
      if(document.getElementById('finanCheck').checked){
      document.getElementById("linkVinculo").setAttribute("href","menu_restrito.php?op=vincular_responsavel&idAluno=<?=$idAluno?>&nome=<?= base64_encode($resultado[0]['nome'])?>&idResp=<?=$resultadoResponsavel[0]['id']?>&vincula=1&RespFinanceiro=1");
      }else{
        document.getElementById("linkVinculo").setAttribute("href","menu_restrito.php?op=vincular_responsavel&idAluno=<?=$idAluno?>&nome=<?= base64_encode($resultado[0]['nome'])?>&idResp=<?=$resultadoResponsavel[0]['id']?>&vincula=1");
      }
    }
    </script>
   
    <?php }else{?>
      <tr style="background: #ccc; font-weight: bold;">
      <td rowspan="40">Respons&aacute;vel não localizado</td>
      
    </tr>
    
  
      <?php }}?>
  </tr>
</table>
<!-- fim da tabela demonstrativa-->

</div>
</div>
