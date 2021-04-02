<?php
//inclui a conexao com banco de dados e fun&ccedil; &otilde;es
include('conexao.php');
include('functions.php');


if($_POST['grava']==1){
    $idClasse = $_POST['idClasse'];
#primeiro gera a matricula depois efetua a gravação das parcelas
$grava_matricula = $con->prepare("insert into matriculas (id_aluno,id_classe,id_resp,id_plano,rm,valor,dia_venc,val_matricula,n_parc,dataMatricula)
                                VALUES (:idAluno,:idClasse,:idResp,:idPlano,:rm,:valor,:dia_venc,:val_matricula,:n_pac,:data_matricula)");
    
$grava_matricula->execute(array(
    ':idAluno'          => $idAluno,
    ':idClasse'         => $idClasse,
    ':idResp'           => $_POST['idPlano'],
    ':idPlano'          => $_POST['idResp'],
    ':rm'               => $_POST['rm'],
    ':valor'            => $_POST['anuidade'],
    ':dia_venc'         => $_POST['dia_venc'],
    ':val_matricula'    => $_POST['val_mat'],
    ':n_pac'            => $_POST['n_parc'],
    ':data_matricula'   => date("Y-m-d")
));      

$idMatricula = $con->lastInsertId();

    #grava os pagamentos matricula
    $pos=1;
    $grava_pgtos = $con->prepare("insert into pagamentos (id_matricula,tipo,num_parc,valor_parc,venc) VALUES (
        :id_matricula,:tipo,:num_parc,:valor_parc,:venc)");
    $grava_pgtos->execute(array(
        ':id_matricula' =>$idMatricula,
        ':tipo'         =>1,
        ':num_parc'     =>$pos,
        ':valor_parc'   =>$_POST['val_mat'],
        ':venc'         =>formatoDataBD($_POST['venc_mat'])
    ));
    $pos++;
    
    for($i=0;$i<count($_POST['parc']);$i++){
        
        #grava os pagamentos parcela
        $grava_pgtos_parc = $con->prepare("insert into pagamentos (id_matricula,tipo,num_parc,valor_parc,venc) VALUES (
            :id_matricula,:tipo,:num_parc,:valor_parc,:venc)");
        $grava_pgtos_parc->execute(array(
            ':id_matricula' =>$idMatricula,
            ':tipo'         =>2,
            ':num_parc'     =>$pos,
            ':valor_parc'   =>$_POST['parc'][$i],
            ':venc'         =>formatoDataBD($_POST['parc'][$i+1])
        ));

        $i++;
        $pos++;
    }

    $status = $grava_pgtos->errorCode();
    if($status =="00000"){
        #atualizando a tabela classe aluno com a data da matrícula
        $atualizaClasseAluno = $con->prepare("update classe_aluno set dtMatricula=:dtMatricula where idAluno=:idAluno and idClasse=:idClasse");
        $atualizaClasseAluno->execute(array(
            'dtMatricula'   => date("Y-m-d"),
            ':idAluno'      => $idAluno,
            ':idClasse'     => $idClasse
        ));
        $matriculado='ok';
        
    }

}else{
    #busca as informações dos alunos, responsável e plano
    $buscaAlunoeResponsavel = $con->prepare("SELECT 
    classe_aluno.numeroChamada, 
    classe_aluno.idClasse, 
    classe_aluno.idAluno as idAluno, 
    classe.nome as nomeClasse,
    aluno.rm, 
    aluno.nome as nome, 
    classe_aluno.situacao,
    responsavel.id as idResponsavel, 
    responsavel.nome as nomeResponsavel,
    planos.id as idPlano,
    planos.plano, 
    planos.valor,
    planos.val_matricula,
    planos.n_parc,
    planos.val_parc,
    planos.dia_venc
    FROM 
    classe_aluno, 
    aluno,
    responsavel,
    aluno_responsavel,
    planos,classe
    where 
    classe_aluno.idClasse = :idClasse and 
    aluno.id=classe_aluno.idAluno AND
    aluno.id = aluno_responsavel.idAluno AND
    responsavel.id = aluno_responsavel.idResponsavel AND
    aluno_responsavel.financeiro =1 AND 
    aluno.id = :idAluno AND
    classe.id_plano = planos.id");

    $buscaAlunoeResponsavel->execute(array(
        ':idClasse' => $idClasse,
        ':idAluno'  => $idAluno
    ));
    $dadosMatricula = $buscaAlunoeResponsavel->fetchAll(PDO::FETCH_ASSOC);

    #buscar o nome do plano de acordo com o idPlano
    $buscaPlano = $con->prepare("select plano from planos where id = :idPlano");
    $buscaPlano->execute(array(':idPlano'=>$idPlano));
    $dadosPlano = $buscaPlano->fetchAll(PDO::FETCH_ASSOC);
    #buscar o nome da classe de acordo com o idClasse
    $buscaClasse = $con->prepare("select nome from classe where id=:idClasse");
    $buscaClasse->execute(array(':idClasse'=>$idClasse));
    $dadosClasse = $buscaClasse->fetchAll(PDO::FETCH_ASSOC);
    
}  
?>

<div class="titulo_interna">
    <i class="fa fa-users" aria-hidden="true"></i>Acadêmico
</div>
<div class="content_form">
    <div class="titulo">Matricular Aluno </div>
    
    <div>
    <?php if($matriculado!='ok'){?>    
    <form method="post" action="#">
        <table width="100%" cellpadding="0" cellspacing="0" class="formulario">
            <tr>
                <th>Nome do aluno:</th><td style="font-size: 11px;"><?=$dadosMatricula[0]['nome']?></td>
            </tr>
            <tr>
                <th>RM:</th><td style="font-size: 11px;"><?=$dadosMatricula[0]['rm']?></td>
            </tr>
            <tr>
                <th>Nome do Responsável Financeiro:</th><td style="font-size: 11px;"><?=$dadosMatricula[0]['nomeResponsavel']?></td>
            </tr>
            <tr>
                <th>Nome do Plano Escolhido:</th><td style="font-size: 11px;"><?=$dadosPlano[0]['plano']?></td>
            </tr>
            <tr>
                <th>Nome da Classe:</th><td style="font-size: 11px;"><?=$dadosClasse[0]['nome']?></td>
            </tr>
            <tr>
              <th colspan="3">Anuidade</td>
          </tr>
            <tr>
              <th>Valor da Anuidade:</th>
              <td style="font-size: 11px;">R$ <input type="text" name="anuidade" value="<?=$dadosMatricula[0]['valor']?>"><span class="legenda_bloco">Para inserir as casas decimais utilize ponto (.)</span></td>           
              <td>            </td>
          </tr>
            <tr>
              <th colspan="3">Matr&iacute;cula</th>
              
          </tr>
          <tr>
              <th>Valor da Matrícula</th>
              <td style="font-size: 11px;">R$ <input type="text" name="val_mat" value="<?=$dadosMatricula[0]['val_matricula']?>"><span class="legenda_bloco">Para inserir as casas decimais utilize ponto (.)</span></td>
              <td>            </td>
          </tr>
          <tr>
              <th>Vencimento da matricula</th>
              <td style="font-size: 11px;"><input type="text" name="venc_mat" value="<?=$dadosMatricula[0]['dia_venc']?>">
              <br>
            <span class="legenda_bloco">Inserir a data no formato DD/MM/AAAA.</span></td>
              <td>            </td>
          </tr>
            <tr>
              <th colspan="3">Presta&ccedil;&otilde;es</th>
              
          </tr>
          <tr>
              <th>Número da Parcela</th>
              <th>Valor da Parcela</th>
              <th>Vencimento</th>
          </tr>
          <?php for($i=1;$i<=$dadosMatricula[0]['n_parc'];$i++){?>
            <tr>
                <td style="font-size: 11px;background: #C0C0C0;">Parcela: <?=$i."/".$dadosMatricula[0]['n_parc']?></td>    
                <td style="font-size: 11px;background: #C0C0C0;">R$ <input type="text" name="parc[]" value="<?=$dadosMatricula[0]['val_parc']?>"><span class="legenda_bloco">Para inserir as casas decimais utilize ponto (.)</span></td>
                <td style="font-size: 11px;background: #C0C0C0;"><input type="text" name="parc[]" value="<?=$dadosMatricula[0]['dia_venc']?>/<?=$i?>/2020"></td>
          </tr>
          <?php }?>
        </table>
        <div class="btn_pos">
    
    
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Efetivar Matrícula"></div>
        <input type="hidden" name="grava" value="1">
        <input type="hidden" name="idAluno" value="<?=$idAluno?>">
        <input type="hidden" name="idClasse" value="<?=$idClasse?>">
        <input type="hidden" name="idResp" value="<?=$dadosMatricula[0]['idResponsavel']?>">
        <input type="hidden" name="idPlano" value="<?=$dadosMatricula[0]['idPlano']?>">
        <input type="hidden" name="n_parc" value="<?=$dadosMatricula[0]['n_parc']?>">
        
        <input type="hidden" name="dia_venc" value="<?=$dadosMatricula[0]['dia_venc']?>">
        <input type="hidden" name="rm" value="<?=$dadosMatricula[0]['rm']?>">

        
    

    
</form>
          <?php }else{?>
          Aluno Matriculado com Sucesso.
<br><br>
          <a href="menu_restrito.php?op=gera_mat_classe&idClasse=<?php echo $idClasse;?>">Retornar para a classe.</a>
          <?php }?>
</div>
    </div>
</div>
