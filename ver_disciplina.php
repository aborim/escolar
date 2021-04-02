<div class="titulo_interna">
    <i class="fa fa-users" aria-hidden="true"></i>Pessoas
</div>
<div class="content_aluno">
    <div class="titulo">Exibindo Informa&ccedil;&otilde;es do Curso: <?= $nomeCurso;?></div>
    <div id="DadosCurso">
    <?php
    //inclui a conex�o com o banco de dados e suas fun��es
    include("conexao.php");
    include("functions.php");

    //busca as informa��es do aluno selecionado
    $buscar=$con->prepare("select * from curso where curso.id=:idCurso");
    $buscar->bindValue(":idCurso",$idCurso);
    $buscar->execute();
    
    if ($buscar->rowCount()!=0) {
        echo "Diretor n&atilde;o encontrado!";
    } else {
        // Salva os dados encontados na vari�vel $resultado
        $resultado = $buscar->fetchAll(PDO::FETCH_ASSOC);
        
    }
    ?>
    <!-- Tabela demonstrativa dos dados do aluno -->
    <table width="80%" cellspacing="0" cellpadding="0" class="dadosAluno">
    <th colspan="8" align="center">Dados do Curso</th>
  <tr>
    
    <td class="tituloDado">Nome:</td>
    <td><?= $resultado[0]['nome'];?></td>
    <td class="tituloDado">Descri&ccedil;&atilde;o:</td>
    <td><?= $resultado[0]['descricao'];?></td>
    <td class="tituloDado">Plano de Pagamento</td>
    <td ><?= $resultado[0]['id_plano'];?></td>
    </tr>
  <tr>
    <td class="tituloDado">Grau:</td>
    <td><?= $resultado[0]['grau'];?></td>
    <td class="tituloDado">Dias Letivos:</td>
    <td><?= $resultado[0]['dias_letivos'];?></td>
    <td class="tituloDado" rowspan="6">Disciplinas:</td>
    <td rowspan="6">Aqui vai as disciplinas</td>
  </tr>
  <tr>
    <td class="tituloDado">Descri&ccedil;&atilde;o para papeletas:</td>
    <td><?= $resultado[0]['descricaoPapeletas'];?></td>
    <td class="tituloDado">Descri&ccedil;&atilde;o para Declara&ccedil;&otilde;es:</td>
    <td><?= $resultado[0]['descricaoDeclaracoes'];?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </td>
    </tr>
  <tr>
    <td class="tituloDado">Tipo de Avalia&ccedil;&atilde;o</td>
    <td><?= $resultado[0]['tipoAvaliacao'];?></td>
    <td class="tituloDado">Media Final Minima:</td>
    <td><?=$resultado[0]['mediaFinalMinima']; ?></td>
    <td ></td>
    <td></td>
    </tr>
  
  <tr>
    <td class="tituloDado">Presen&ccedil;a M&iacute;nima Obrigat&oacute;ria:</td>
    <td><?= $resultado[0]['presencaMinima'];?></td>
    <td class="tituloDado">M&eacute;dia Recupera&ccedil;&atilde;o M&iacute;nima</td>
    <td><?= $resultado[0]['mediaRecuperacaoMinima'];?></td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
    </tr>
  <tr>
    <td class="tituloDado">Nota M&iacute;nima para Reprova&ccedil;&atilde;o:</td>
    <td><?= $resultado[0]['notaMinimaReprovacao'];?></td>
    <td class="tituloDado">Plano de Arredondamento</td>
    <td><?= $resultado[0]['tipoArredondamento'];?></td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td class="tituloDado">N&uacute;mero de Periodos para Avalia&ccedil;&atilde;o:</td>
    <td><?= $resultado[0]['periodosAvaliacao'];?></td>
    <td class="tituloDado">Utiliza Pr&eacute;-Conselho</td>
    <td><?= if(@$resultado[0]['solicitarPreConselho']=="0"){echo "N&atilde;o";}else{echo "sim";}?></td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td class="tituloDado">Forma de C&aacute;lculo:</td>
    <td><?= $resultado[0]['formaCalculo'];?></td>
    <td class="tituloDado">Utiliza Exame</td>
    <td><?= if(@$resultado[0]['solicitarExame']=="0"){echo "N&atilde;o";}else{echo "sim";}?></td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  </tr>
  <tr>
    <td class="tituloDado">Forma de C&aacute;lculo com Exame:</td>
    <td><?= $resultado[0]['formaCalculoExame'];?></td>
    <td class="tituloDado">Utiliza Conselho</td>
    <td><?= if(@$resultado[0]['solicitarconselho']=="0"){echo "N&atilde;o";}else{echo "sim";}?></td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
</table>
<!-- fim da tabela demonstrativa-->

</div>
</div>



				
    
    
    