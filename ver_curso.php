<?php
    //inclui a conexï¿½o com o banco de dados e suas funï¿½ï¿½es
    include("conexao.php");
    include("functions.php");

    //busca as informaï¿½ï¿½es do aluno selecionado
    $buscar=$con->prepare("select id,nome,descricao,id_plano,grau,dias_letivos,descricaoPapeletas,descricaoDeclaracoes,
                          tipoAvaliacao,mediaFinalMinima,presencaMinima,mediaRecuperacaoMinima,notaMinimaReprovacao,
                          tipoArredondamento,periodosAvaliacao,solicitarPreConselho,formaCalculo,solicitarExame,
                          formaCalculoExame,solicitarConselho
                          from curso where id=:id");
    $buscar->bindValue(':id',$idCurso);
    $buscar->execute();
  
    if ($buscar->rowCount()==0) {
        echo "Curso n&atilde;o encontrado!";
    } else {
        // Salva os dados encontados na variï¿½vel $resultado
        $resultado = $buscar->fetchAll(PDO::FETCH_ASSOC);
        
    }
    ?>
<div class="titulo_interna">
<i class="fa fa-graduation-cap" aria-hidden="true"></i>Acad&ecirc;mico
</div>
<div class="content_aluno">
    <div class="titulo">Exibindo Informa&ccedil;&otilde;es do Curso: <?= $resultado[0]['nome'];?></div>
    <div id="DadosCurso">
    
    <!-- Tabela demonstrativa dos dados do aluno -->
    <table width="80%" cellspacing="0" cellpadding="0" class="dadosAluno">
    <th colspan="6" align="center">Dados do Curso</th>
  <tr>
    
    <td class="tituloDadoCurso">Nome:</td>
    <td><?= $resultado[0]['nome'];?></td>
    <td class="tituloDadoCurso">Descri&ccedil;&atilde;o:</td>
    <td><?= $resultado[0]['descricao'];?></td>
    <td class="tituloDadoCurso">Plano de Pagamento</td>
    <td ><?= $resultado[0]['id_plano'];?></td>
    </tr>
  <tr>
    <td class="tituloDadoCurso">Grau:</td>
    <td><?= $resultado[0]['grau'];?></td>
    <td class="tituloDadoCurso">Dias Letivos:</td>
    <td><?= $resultado[0]['dias_letivos'];?></td>
    <td class="tituloDadoCurso" rowspan="6">Disciplinas:</td>
    <td rowspan="6">
<?php 
#mostra as disciplinas cadastradas no curso
$buscaDisciplinas = $con->prepare("SELECT disciplina.nome FROM disciplina,curso_disciplina WHERE idCurso=:idCurso and disciplina.id = curso_disciplina.idDisciplina");
$buscaDisciplinas->execute(array(':idCurso'=> $idCurso));
$resultadoDisciplina = $buscaDisciplinas->fetchAll(PDO::FETCH_ASSOC);
if($buscaDisciplinas->rowCount()>0){
  
  foreach ($resultadoDisciplina as $disciplina) {
    echo $disciplina['nome']."<br>";
  }
}else{
  echo "disciplinas não encontradas";
}

?>

    </td>
  </tr>
  <tr>
    <td class="tituloDadoCurso">Descri&ccedil;&atilde;o para papeletas:</td>
    <td><?= $resultado[0]['descricaoPapeletas'];?></td>
    <td class="tituloDadoCurso">Descri&ccedil;&atilde;o para Declara&ccedil;&otilde;es:</td>
    <td><?= $resultado[0]['descricaoDeclaracoes'];?></td>
    </tr>
  <tr>
    <td class="tituloDadoCurso">Tipo de Avalia&ccedil;&atilde;o</td>
    <td><?= $resultado[0]['tipoAvaliacao'];?></td>
    <td class="tituloDadoCurso">Media Final Minima:</td>
    <td><?=$resultado[0]['mediaFinalMinima']; ?></td>
    </tr>
  
  <tr>
    <td class="tituloDadoCurso">Presen&ccedil;a M&iacute;nima Obrigat&oacute;ria:</td>
    <td><?= $resultado[0]['presencaMinima'];?></td>
    <td class="tituloDadoCurso">M&eacute;dia Recupera&ccedil;&atilde;o M&iacute;nima</td>
    <td><?= $resultado[0]['mediaRecuperacaoMinima'];?></td>
    </tr>
  <tr>
    <td class="tituloDadoCurso">Nota M&iacute;nima para Reprova&ccedil;&atilde;o:</td>
    <td><?= $resultado[0]['notaMinimaReprovacao'];?></td>
    <td class="tituloDadoCurso">Plano de Arredondamento</td>
    <td><?= $resultado[0]['tipoArredondamento'];?></td>
    </tr>
  <tr>
    <td class="tituloDadoCurso">N&uacute;mero de Periodos para Avalia&ccedil;&atilde;o:</td>
    <td><?= $resultado[0]['periodosAvaliacao'];?></td>
    <td class="tituloDadoCurso">Utiliza Pr&eacute;-Conselho</td>
    <td><? if(@$resultado[0]['solicitarPreConselho']=="0"){echo "N&atilde;o";}else{echo "sim";}?></td>
    </tr>
  <tr>
    <td class="tituloDadoCurso">Forma de C&aacute;lculo:</td>
    <td><?= $resultado[0]['formaCalculo'];?></td>
    <td class="tituloDadoCurso">Utiliza Exame</td>
    <td><? if(@$resultado[0]['solicitarExame']=="0"){echo "N&atilde;o";}else{echo "sim";}?></td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  </tr>
  <tr>
    <td class="tituloDadoCurso">Forma de C&aacute;lculo com Exame:</td>
    <td><?= $resultado[0]['formaCalculoExame'];?></td>
    <td class="tituloDadoCurso">Utiliza Conselho</td>
    <td><? if(@$resultado[0]['solicitarConselho']=="0"){echo "N&atilde;o";}else{echo "sim";}?></td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
</table>
<!-- fim da tabela demonstrativa-->

</div>
</div>



				
    
    
    