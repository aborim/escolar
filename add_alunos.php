<?php

//inclui a conexao com banco de dados e fun&ccedil; &otilde;es
include('conexao.php');
include('functions.php');

#pega as vari&aacute;veis enviadas pelo formul&aacute;rio
$nomeAluno                  = $_POST['nomeAluno'];
$emailAluno                 = $_POST['emailAluno'];
$ra                         = $_POST['ra'];
$dtNascimento               = substr($_POST['dtNascimento'],6,4)."-".substr($_POST['dtNascimento'],3,2)."-".substr($_POST['dtNascimento'],0,2);
$sexo                       = $_POST['sexo'];
$naturalidade               = $_POST['naturalidade'];
$nacionalidade              = $_POST['nacionalidade'];
$etnia                      = $_POST['etnia'];
$estadoCivil                = $_POST['estadoCivil'];
$telefone                   = $_POST['telefone'];
$celular                    = $_POST['celular'];
$rg                         = $_POST['rg'];
$expedidoEm                 = substr($_POST['expedidoEm'],6,4)."-".substr($_POST['expedidoEm'],3,2)."-".substr($_POST['expedidoEm'],0,2);
$orgaoEmissor               = $_POST['orgaoEmissor'];
$cpf                        = $_POST['cpfAluno'];
$certidaoNascimento         = $_POST['certidaoNascimento'];
$livro                      = $_POST['livro'];
$folha                      = $_POST['folha'];
$alunoNF                    = isset($_POST['alunoNF']) ? "1":"0";  
$alunoBolsista              = isset($_POST['alunoBolsista']) ? "1":"0";  
$valorBolsa                 = $_POST['valorBolsa'];
$motivoBolsa                = $_POST['motivoBolsa'];

//pega os dados do endere&ccedil;o pra gravar antes e obter o id, caso n &atilde;o tenha sido cadastrado primeiro
$cep            = $_POST['txt_cep'];
$endereco       = $_POST['txt_endereco'];
$num            = $_POST['txt_num'];
$complemento    = $_POST['txt_comp'];
$bairro         = $_POST['txt_bairro'];
$cidade         = $_POST['txt_cidade'];
$estado         = $_POST['txt_estado'];



if(@$_GET['fun']=="ed"){
    
    $edicao = "sim";

    #busca  relacionamento aluno_responsavel, caso exista a query vai completa, caso contrario vai apenas a pesquisa geral do aluno
    $busca_vinc_resp = $con->prepare("select * from aluno_responsavel where idAluno=:idAluno");
    $busca_vinc_resp->bindValue(':idAluno',$idAluno);
    $busca_vinc_resp->execute();
    if($busca_vinc_resp->rowCount()>0){
            #aqui vem a busca dos dados do aluno pela variavel de entrada
    $busca_aluno = $con->prepare("select 
	aluno.id,
	aluno.idEndereco,
	aluno.idUsuario,
	aluno.nome,
  	aluno.email,
  	aluno.rg,
  	aluno.cpf,
  	aluno.rm,
  	aluno.ra,
  	aluno.dtNascimento,
  	aluno.certidaoNascimento,
  	aluno.livro,
  	aluno.folha,
  	aluno.sexo,
  	aluno.imagem,
  	aluno.orgaoEmissor,
  	aluno.expedidoEm,
  	aluno.etnia,
  	aluno.telefone,
  	aluno.celular,
  	aluno.nacionalidade,
  	aluno.naturalidade,
  	aluno.estadoCivil,
  	aluno.dtCriacao,
  	aluno.valorBolsa,
  	aluno.motivoBolsa,
  	aluno.notaFiscal,
  	aluno.bolsista,
	endereco.id as idEnd,
	endereco.endereco,
	endereco.numero,
	endereco.complemento,
	endereco.cep,
	endereco.bairro,
	endereco.cidade,
    endereco.estado,
    aluno_responsavel.id as VincID,
	aluno_responsavel.idAluno as VincAluno,
	aluno_responsavel.idResponsavel as VincResp,
	aluno_responsavel.financeiro as VincFinan,
	responsavel.id as RespID,
	responsavel.idEndereco as RespIdEnd,
	responsavel.idUsuario,
	responsavel.nome as nomeResp,
	responsavel.email as emailResp,
	responsavel.grauParentesco,
	responsavel.rg as rgResp,
	responsavel.expedidoEm as expedidoEmResp,
	responsavel.orgaoEmissor as orgaoEmissorResp,
	responsavel.nacionalidade nacionalidadeResp,
	responsavel.telefone as telResp,
	responsavel.celular as celResp,
	responsavel.telefoneCom,
	responsavel.profissao,
	responsavel.estadoCivil as estCivilResp,
	responsavel.cpf as cpfResp,
	responsavel.sexo as sexoResp,
	responsavel.dtNascimento as dtNascResp,
	responsavel.observacao,
	responsavel.dtCriacao 
    
    FROM 
    	aluno,endereco,aluno_responsavel,responsavel 
    WHERE 
    	aluno.id=:idAluno and 
        aluno.idEndereco = endereco.id and 
        aluno_responsavel.idAluno = aluno.id and 
        aluno_responsavel.idResponsavel=responsavel.id");
    }else{
        $busca_aluno = $con->prepare("select * from aluno,endereco where aluno.id=:idAluno and aluno.idEndereco = endereco.id");
    }

    $busca_aluno->bindValue(':idAluno',$idAluno);
    $busca_aluno->execute();
    $resultado = $busca_aluno->fetchAll(PDO::FETCH_ASSOC);
    //verifica se o aluno ja est&aacute; vinculado a um respons&aacute;vel
   
    if(@$_POST['edita']==1){
        #aqui vai a edi&ccedil;&atilde;o dos dados do aluno

        if(empty($_FILES['imagem']['name'])){
            $destino = $_POST['imagem'];
        }else{
            if ( isset( $_FILES[ 'imagem' ][ 'name' ] ) && $_FILES[ 'imagem' ][ 'error' ] == 0 ) {
                $arquivo_tmp = $_FILES[ 'imagem' ][ 'tmp_name' ];
                $nome = $_FILES[ 'imagem' ][ 'name' ];
                // Pega a extens�o
                $extensao = pathinfo ( $nome, PATHINFO_EXTENSION );
                // Converte a extens�o para min�sculo
                $extensao = strtolower ( $extensao );
                // Somente imagens, .jpg;.jpeg;.gif;.png
                // Aqui eu enfileiro as extens�es permitidas e separo por ';'
                // Isso serve apenas para eu poder pesquisar dentro desta String
                if ( strstr ( '.jpg;.jpeg;.gif;.png', $extensao ) ) {
                    // Cria um nome �nico para esta imagem
                    // Evita que duplique as imagens no servidor.
                    // Evita nomes com acentos, espa�os e caracteres n&atilde;o alfanum�ricos
                    $novoNome = uniqid ( time () ) . '.' . $extensao;
                    // Concatena a pasta com o nome
                    $destino = 'images/alunos/' . $novoNome;
                    // tenta mover o arquivo para o destino
                    if ( @move_uploaded_file ( $arquivo_tmp, $destino ) ) {            
                    }else{echo 'Erro ao salvar o arquivo. Aparentemente voc&ecirc; n&atilde;o tem permiss&atilde;o de escrita.<br />';}
                }else{echo 'Voc&ecirc; poder&aacute; enviar apenas arquivos "*.jpg;*.jpeg;*.gif;*.png"<br />';}
            }else{echo 'Voc&ecirc; n&atilde;o enviou nenhum arquivo!';}
        }

        $altera_aluno = $con->prepare("update aluno set
                                        nome = :nome, 
                                        email = :email,
                                        rg = :rg,
                                        cpf = :cpf,
                                        ra = :ra,
                                        dtNascimento = :dtNascimento,
                                        certidaoNascimento = :certidaoNascimento,
                                        livro = :livro,
                                        folha = :folha,
                                        sexo = :sexo,
                                        imagem = :imagem,
                                        orgaoEmissor = :orgaoEmissor,
                                        expedidoEm = :expedidoEm,
                                        etnia = :etnia,
                                        telefone = :telefone,
                                        celular = :celular,
                                        nacionalidade = :nacionalidade,
                                        naturalidade = :naturalidade,
                                        estadoCivil = :estadoCivil,
                                        valorBolsa = :valorBolsa,
                                        motivoBolsa = :motivoBolsa,
                                        notaFiscal = :notaFiscal,
                                        bolsista = :bolsista
                                        where id = :id     
                                        ");
        $altera_aluno->execute(array(
                            ':nome'                 => $nomeAluno, 
                            ':email'                => $emailAluno,       
                            ':rg'                   => $rg, 
                            ':cpf'                  => $cpf, 
                            ':ra'                   => $ra,  
                            ':dtNascimento'         => $dtNascimento, 
                            ':certidaoNascimento'   => $certidaoNascimento, 
                            ':livro'                => $livro, 
                            ':folha'                => $folha, 
                            ':sexo'                 => $sexo, 
                            ':imagem'               => $destino, 
                            ':orgaoEmissor'         => $orgaoEmissor, 
                            ':expedidoEm'           => $expedidoEm, 
                            ':etnia'                => $etnia, 
                            ':telefone'             => $telefone, 
                            ':celular'              => $celular, 
                            ':nacionalidade'        => $nacionalidade, 
                            ':naturalidade'         => $naturalidade, 
                            ':estadoCivil'          => $estadoCivil, 
                            ':valorBolsa'           => $valorBolsa, 
                            ':motivoBolsa'          => $motivoBolsa, 
                            ':notaFiscal'           => $alunoNF, 
                            ':bolsista'             => $alunoBolsista,
                            ':id'                   => $_POST['id']
        ));
        
        #ajusta o endere&ccedil;o
        $altera_endereco=$con->prepare("update endereco set 
                                        endereco    =:endereco, 
                                        numero      =:numero, 
                                        complemento =:complemento, 
                                        cep         =:cep, 
                                        bairro      =:bairro, 
                                        cidade      =:cidade, 
                                        estado      =:estado where id=:id");
        $altera_endereco->execute(array(
            ':endereco'     => $endereco,
            ':numero'       => $num,
            ':complemento'  => $complemento,
            ':cep'          => $cep,
            ':bairro'       => $bairro,
            ':cidade'       => $cidade,
            ':estado'       => $estado,
            ':id'           => $_POST['idEndereco']
        ));



        if(!empty($_POST['respId'])){
            //se o vinculo do respons�vel n�o existe ent�o cria um novo, se n�o s� atualiza
            $busca_vinculo = $con->prepare("select * from aluno_responsavel where idAluno=:idAluno and idResponsavel=:idResponsavel");
            $busca_vinculo->execute(array(
                ':idAluno'      =>$_POST['id'],
                ':idResponsavel'=>$_POST['respId']
            ));
            if($busca_vinculo->rowCount()==0){
                //grava o vinculo do responsavel caso exista
                if($_POST['respId']!=""){
                    $grava_vinculo_resp = $con->prepare("insert into aluno_responsavel (idAluno,idResponsavel,financeiro) values (:idAluno,:idResponsavel,:financeiro)");
                    $grava_vinculo_resp->bindValue(':idAluno',$_POST['id']);
                    $grava_vinculo_resp->bindValue('idResponsavel',$_POST['respId']);
                    $grava_vinculo_resp->bindValue('financeiro',$_POST['respFinan']);
                    $grava_vinculo_resp->execute();
                }
            }else{
                $altera_vinculo = $con->prepare("update aluno_responsavel set idResponsavel=:idResponsavel,financeiro=:financeiro where id=:VincID");
                $altera_vinculo->execute(array(
                    ':idResponsavel'    =>$_POST['respId'],
                    ':financeiro'       =>$_POST['respFinan'],
                    ':VincID'           =>$_POST['AluRespVinc']
                ));
            }
        }
        
        #echo "altera os dados do aluno aqui";
        $status = $altera_aluno->errorCode();
        if($status =="00000"){
            $dados_atualizados = "ok";
        }
        #echo $status;
    }


}else{
    $edicao ="nao";
}


//modulo de cadastro de alunos, arquivo inserido ao menu restrito atrav &eacute;s de include
if(isset($_POST['grava'])==1){


//armazena os dados das vari&aacute;veis do formul&aacute;rio

//grava a imagem do aluno
if ( isset( $_FILES[ 'imagem' ][ 'name' ] ) && $_FILES[ 'imagem' ][ 'error' ] == 0 ) {
    $arquivo_tmp = $_FILES[ 'imagem' ][ 'tmp_name' ];
    $nome = $_FILES[ 'imagem' ][ 'name' ];
    // Pega a extens�o
    $extensao = pathinfo ( $nome, PATHINFO_EXTENSION );
    // Converte a extens�o para min�sculo
    $extensao = strtolower ( $extensao );
    // Somente imagens, .jpg;.jpeg;.gif;.png
    // Aqui eu enfileiro as extens�es permitidas e separo por ';'
    // Isso serve apenas para eu poder pesquisar dentro desta String
    if ( strstr ( '.jpg;.jpeg;.gif;.png', $extensao ) ) {
        // Cria um nome �nico para esta imagem
        // Evita que duplique as imagens no servidor.
        // Evita nomes com acentos, espa�os e caracteres n&atilde;o alfanum�ricos
        $novoNome = uniqid ( time () ) . '.' . $extensao;
        // Concatena a pasta com o nome
        $destino = 'images/alunos/' . $novoNome;
        // tenta mover o arquivo para o destino
        if ( @move_uploaded_file ( $arquivo_tmp, $destino ) ) {            
        }else{echo 'Erro ao salvar o arquivo. Aparentemente voc&ecirc; n&atilde;o tem permiss&atilde;o de escrita.<br />';}
    }else{echo 'Voc&ecirc; poder&aacute; enviar apenas arquivos "*.jpg;*.jpeg;*.gif;*.png"<br />';}
}else{echo 'Voc&ecirc; n&atilde;o enviou nenhum arquivo!';}


//grava primeiro o endereco e o usuario, pega o id do endere&ccedil;o e do usuario pra montar o relacionamento correto
//(usuario =:login) AND (senha =:senha)
  $grava_endereco=$con->prepare("insert into endereco (endereco, numero, complemento, cep, bairro, cidade, estado) values (:endereco,:numero,:complemento,:cep,:bairro,:cidade,:estado)");
  $grava_endereco->execute(array(
    ':endereco'     => $endereco,
    ':numero'       => $num,
    ':complemento'  => $complemento,
    ':cep'          => $cep,
    ':bairro'       => $bairro,
    ':cidade'       => $cidade,
    ':estado'       => $estado
  ));
  //id do endereco gerado do endereco
  $LAST_ID = $con->lastInsertId();

 


  //grava os dados do aluno
  //junto ao banco de dados junto com a foto, usuario, endereco, 
  $grava_aluno=$con->prepare("insert into aluno (idEndereco, 
                                                nome, 
                                                email, 
                                                rg, 
                                                cpf, 
                                                ra, 
                                                dtNascimento, 
                                                certidaoNascimento, 
                                                livro, 
                                                folha, 
                                                sexo, 
                                                imagem, 
                                                orgaoEmissor, 
                                                expedidoEm, 
                                                etnia, 
                                                telefone, 
                                                celular, 
                                                nacionalidade, 
                                                naturalidade, 
                                                estadoCivil, 
                                                dtCriacao, 
                                                valorBolsa, 
                                                motivoBolsa, 
                                                notaFiscal, 
                                                bolsista) values (
                                                    :idEndereco, 
                                                    :nome, 
                                                    :email, 
                                                    :rg, 
                                                    :cpf, 
                                                    :ra, 
                                                    :dtNascimento, 
                                                    :certidaoNascimento, 
                                                    :livro, 
                                                    :folha, 
                                                    :sexo, 
                                                    :imagem, 
                                                    :orgaoEmissor, 
                                                    :expedidoEm, 
                                                    :etnia, 
                                                    :telefone, 
                                                    :celular, 
                                                    :nacionalidade, 
                                                    :naturalidade, 
                                                    :estadoCivil, 
                                                    :dtCriacao, 
                                                    :valorBolsa, 
                                                    :motivoBolsa, 
                                                    :notaFiscal, 
                                                    :bolsista 
                                                )");
  $grava_aluno->execute(array(
    ':idEndereco'           => $LAST_ID, 
    ':nome'                 => $nomeAluno, 
    ':email'                => $emailAluno,       
    ':rg'                   => $rg, 
    ':cpf'                  => $cpf, 
    ':ra'                   => $ra,  
    ':dtNascimento'         => $dtNascimento, 
    ':certidaoNascimento'   => $certidaoNascimento, 
    ':livro'                => $livro, 
    ':folha'                => $folha, 
    ':sexo'                 => $sexo, 
    ':imagem'               => $destino, 
    ':orgaoEmissor'         => $orgaoEmissor, 
    ':expedidoEm'           => $expedidoEm, 
    ':etnia'                => $etnia, 
    ':telefone'             => $telefone, 
    ':celular'              => $celular, 
    ':nacionalidade'        => $nacionalidade, 
    ':naturalidade'         => $naturalidade, 
    ':estadoCivil'          => $estadoCivil, 
    ':dtCriacao'            => date('Y-m-d H-i-s'), 
    ':valorBolsa'           => $valorBolsa, 
    ':motivoBolsa'          => $motivoBolsa, 
    ':notaFiscal'           => $alunoNF, 
    ':bolsista'             => $alunoBolsista
  ));
//grava o id do usuario pra gerar o rm
$LAST_ID_RM = $con->lastInsertId();
$rm = str_pad($LAST_ID_RM , 5 , '0' , STR_PAD_LEFT);
//atualiza o banco com o rm do aluno
$atualiza_rm = $con->prepare("update aluno set rm = :rm where id = :id");
$atualiza_rm->execute(array(
    ':rm' => $rm,
    ':id' => $LAST_ID_RM
));
//grava o vinculo do responsavel caso exista
if($_POST['respId']!=""){
    $grava_vinculo_resp = $con->prepare("insert into aluno_responsavel (idAluno,idResponsavel,financeiro) values (:idAluno,:idResponsavel,:financeiro)");
    $grava_vinculo_resp->bindValue(':idAluno',$LAST_ID_RM);
    $grava_vinculo_resp->bindValue('idResponsavel',$_POST['respId']);
    $grava_vinculo_resp->bindValue('financeiro',$_POST['respFinan']);
    $grava_vinculo_resp->execute();
}


//cadastra o usuario ja padronizado como aluno
$grava_usuario_aluno=$con->prepare("insert into usuarios (
                                                usuario,
                                                senha,
                                                nivel,
                                                ativo,
                                                filial,
                                                cadastro
                                            ) values (
                                                :usuario,
                                                :senha,
                                                :nivel,
                                                :ativo,
                                                :filial,
                                                :cadastro
                                                )");
//cria uma senha padrao com a data de nascimento do aluno
$senhaAluno = md5(soNumero($dtNascimento));
$grava_usuario_aluno->execute(array(
                        ':usuario'  => 'RM'.$rm,
                        ':senha'    => $senhaAluno,
                        ':nivel'    => '7',
                        ':ativo'    => '1',
                        ':filial'   =>$_SESSION['Unidade'],
                        ':cadastro' => date('Y-m-d H-i-s')
                        ));
//id do endereco gerado do usuario
$LAST_ID_USER = $con->lastInsertId();

//atualiza o cadastro do aluno inserindo o idUsuario
$atualiza_usuario = $con->prepare("update aluno set idUsuario = :idUsuario where id = :id");
$atualiza_usuario->execute(array(
    ':idUsuario'    => $LAST_ID_USER,
    ':id'           => $LAST_ID_RM
));

  $status = $grava_aluno->errorCode();
}
?>

<div class="titulo_interna">
    <i class="fa fa-users" aria-hidden="true"></i>Pessoas
</div>
<div class="content_form">
    <?php
    if(@$_GET['fun']=="ed"){$titulo = "Editar Aluno";}else{$titulo = "Adicionar Aluno";}
    ?>
    <div class="titulo"> <?= $titulo?> </div>
    <?php
    if(!isset($status)){?>
<form method="post" action="#" enctype="multipart/form-data" id="frmAluno">
    <div class="form_comp">
    

<table class="formulario">
<tbody>
    <tr><td colspan="99"></td></tr>
    <tr>
        <th>
        <label for="imagem">Imagem</label></th>
        <td class="logo">
        
            <input class="form_campo" type="hidden" value="" name="nomeImagemAntigo">
            <?php if(@$resultado[0]['imagem'] ==""){?>
                <img src="images/usuario.png" width="100" style="width: 100px;vertical-align: top;float: left;">
            <?php }else{?>
                <img src="<?=$resultado[0]['imagem']?>" style="width: 100px;vertical-align: top;float: left;">
                <input type="hidden" value="<?=$resultado[0]['imagem']?>" name="imagem">
            <?php }?>
            <input class="form_campo" type="file" id="imagem" name="imagem" title="Logotipo">
            <span class="legenda_bloco">
            <br>Tipos suportados: .JPG, .PNG, .GIF, .BMP;<br>
            Tamanho m&aacute;ximo 50kb;<br>
            Altura m&aacute;xima recomendada: 90px;
            </span>
        
            
        </td>
    </tr>

    <tr>
    <th><label for="nome">Nome</label><span class="obrigatorio"> obrigat &oacute;rio</span></th>
        <td>
            <input required class="form_campo" type="text" value="<?php if(@$resultado[0]['nome']!=""){echo $resultado[0]['nome'];}?>" id="nomeAluno" name="nomeAluno" title="Nome do Aluno" maxlength="60" required>
            <br><span class="legenda_bloco">Nome completo do aluno.</span>
        </td>
    </tr>

    <tr>
    <th><label for="nome">E-mail</label><span class="obrigatorio"> obrigat&oacute;rio</span></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['email']!=""){echo $resultado[0]['email'];}?>" id="emailAluno" name="emailAluno" title="Email do Aluno" maxlength="60" required>
            <br><span class="legenda_bloco">Email do aluno.</span>
        </td>
    </tr>

    <tr>	
    <th><label for="ra">RA</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['ra']!=""){echo $resultado[0]['ra'];}?>" id="ra" name="ra" title="RA" maxlength="15">
            <br><span class="legenda_bloco">Registro do aluno.</span>
        </td>
    </tr>


    <tr><th colspan="2" style="text-align:center;">Respons&aacute;veis</th></tr>
    <tr>
    <th><label for="cep">Respons&aacute;vel</label></th>
    <td>
        <table>
            <tbody> <?php 
                    if($resultado[0]['cpfResp']!=""){
                    ?>
                    <tr>
                        <td><span style="font-size: 12px;font-weight:bold;">Respons&aacute;veis vinculados.</span><br><span style="font-size: 12px;"><?=$resultado[0]['nomeResp']?> - <?=$resultado[0]['grauParentesco']?> - <?=$resultado[0]['cpfResp']?> - Respons&aacute;vel Financeiro: <?=$resultado[0]['VincFinan']==1?"SIM":"N&atilde;O"?></span><hr><input type="hidden" name="idRespAtual" value="<?=$resultado[0]['RespID']?>"></td>
                    </tr>
                    <?php }?>
                    
                    <tr><td>
                    <?php
                    echo "<!--";
                    var_dump($resultado);
                    echo "-->";
                    ?>
                    <label for="cpf">CPF do Respopns&aacute;vel:</label>
                    <!--<input class="form_campo" onchange="consultarResponsavel(this,'/menu_restrito.php?op=add_alunos')" type="text" value="<?php if(@$resultado[0]['ra']!=""){echo $resultado[0]['ra'];}?>" id="cpfResponsavel" name="cpfResponsavel" title="CPF do Respons&aacute;vel" maxlength="15"><br><span class="legenda_bloco">Digite o CPF do Respons&aacute;vel.</span></td></tr>-->
                    <input class="form_campo" type="text" value="<?php if(@$resultado[0]['cpfResp']!=""){echo $resultado[0]['cpfResp'];}?>" id="cpfResponsavel" name="cpfResponsavel" title="CPF do Respons&aacute;vel" maxlength="15"><br><span class="legenda_bloco">Digite o CPF do Respons&aacute;vel.</span>
                    <div id="ResResponsavel"><span class="lbl_file" id="obs_cnpj" style="visibility:hidden;">Responsavel n&atilde;o cadastrado.<br>Cadastrar respons&aacute;vel agora.</span></div>
                    <input type="hidden" name="AluRespVinc" value="<?=$resultado[0]['VincID']?>">
                </td></tr>
            </tbody>
        </table>
    </td>
    </tr>
    <tr><th colspan="2" style="text-align:center;">Informa&ccedil;&otilde;es</th></tr>
    <tr>
    <th><label for="cep">Endere&ccedil;o</label></th>
    <td>
        <table>
            <tbody><tr>
                
                <td><?php include('form_endereco.php');?></td>
            </tr>
        </tbody></table>
    </td>
    </tr>

    <tr>
        <th>
            <label for="dtNascimento">Data nascimento</label><span class="obrigatorio"> obrigat&oacute;rio</span>
        </th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['dtNascimento']!=""){echo formatoData($resultado[0]['dtNascimento']);}?>" id="dtNascimento" name="dtNascimento" title="Data de Nascimento" maxlength="10" placeholder="dd/mm/aaaa" required>	
        </td>
    </tr>

    <tr>
        <th><label for="sexo">Sexo</label><span class="obrigatorio"> obrigat&oacute;rio</span></th>
        <td>
            <select id="sexo" name="sexo" title="Sexo" class="form_campo">
                <option value="">Selecione...</option>
                <option value="1" <?php if(@$resultado[0]['sexo']=="1"){echo " selected";}?>>Feminino</option>
                <option value="2" <?php if(@$resultado[0]['sexo']=="2"){echo " selected";}?>>Masculino</option>
            </select>
        </td>
    </tr>

    <tr>	
        <th><label for="naturalidade">Naturalidade</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['naturalidade']!=""){echo $resultado[0]['naturalidade'];}?>" id="naturalidade" name="naturalidade" title="Naturalidade" maxlength="50">
        </td>
    </tr>

    <tr>	
        <th><label for="nacionalidade">Nacionalidade</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['nacionalidade']!=""){echo $resultado[0]['nacionalidade'];}?>" id="nacionalidade" name="nacionalidade" title="Nacionalidade" maxlength="50">
        </td>
    </tr>

    <tr>	
        <th><label for="etnia">Cor/ra&ccedil;a</label></th>
        <td>

        <select id="etnia" name="etnia" title="Cor/ra&ccedil;a" class="form_campo">
            <option value="">Selecione...</option>
            <option value="Caucasiana" <?php if(@$resultado[0]['etnia']=="Caucasiana"){echo " selected";}?>>Caucasiana</option>
            <option value="Negra" <?php if(@$resultado[0]['etnia']=="Negra"){echo " selected";}?>>Negra</option>
            <option value="Parda" <?php if(@$resultado[0]['etnia']=="Parda"){echo " selected";}?>>Parda</option>
            <option value="Amarela" <?php if(@$resultado[0]['etnia']=="Amarela"){echo " selected";}?>>Amarela</option>
            <option value="Ind &iacute;gena" <?php if(@$resultado[0]['etnia']=="Ind &iacute;gena"){echo " selected";}?>>Ind &iacute;gena</option>
            <option value="N&atilde;o declarada" <?php if(@$resultado[0]['etnia']=="N&atilde;o declarada"){echo " selected";}?>>N&atilde;o declarada</option>								
        </select>

        </td>
    </tr>

    <tr>	
        <th><label for="estadoCivil">Estado civil</label><span class="obrigatorio"> obrigat&oacute;rio</span></th>
        <td>
            <select id="estadoCivil" name="estadoCivil" title="Estado civil" class="form_campo">
                <option value="Solteiro(a)">Solteiro(a)</option>
                <option value="Casado(a)">Casado(a)</option>
                <option value="Desquitado(a)">Desquitado(a)</option>
                <option value="Divorcidado(a)">Divorcidado(a)</option>
            </select>
        </td>
    </tr>
    
    <tr>
        <th><label for="telefone">Telefone</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['telefone']!=""){echo $resultado[0]['telefone'];}?>" id="telefone" name="telefone" title="Telefone" maxlength="60">
        </td>
    </tr>

    <tr>
        <th><label for="celular">Celular</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['celular']!=""){echo $resultado[0]['celular'];}?>" id="celular" name="celular" title="celular" maxlength="20">
        </td>
    </tr>

    <tr>
        <th colspan="2" style="text-align:center;">Documentos</th>
    </tr>
    
    <tr>	
        <th><label for="rg">RG</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['rg']!=""){echo $resultado[0]['rg'];}?>" id="rg" name="rg" title="RG" maxlength="16">
            <br><span class="legenda_bloco">
             Sem pontos ou tra&ccedil;os.
            </span>
        </td>
    </tr>

    <tr>	
        <th><label for="expedidoEm">Expedido em</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['expedidoEm']!=""){echo formatoData($resultado[0]['expedidoEm']);}?>" id="expedidoEm" name="expedidoEm" title="Expedido em" maxlength="10">
            <br><span class="legenda_bloco">
             Data de expedi&ccedil;&atilde;o do RG do aluno no formato: dd/mm/aaaa
            </span>	
        </td>
    </tr>
 
    <tr>	
        <th><label for="orgaoEmissor">Org&atilde;o emissor</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['orgaoEmissor']!=""){echo $resultado[0]['orgaoEmissor'];}?>" id="orgaoEmissor" name="orgaoEmissor" title="Org�o emissor" maxlength="6" style="text-transform:uppercase">
            <br><span class="legenda_bloco">Org&atilde;o emissor do RG do aluno</span>
        </td>
    </tr>
 
    <tr>
        <th><label for="cpf">CPF</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['cpf']!=""){echo $resultado[0]['cpf'];}?>" id="cpfAluno" name="cpfAluno" title="CPF" maxlength="11" onkeypress="return soNumeros(event)" >
            <div id="ResAluno"><span class="lbl_file" id="obs_cpf" style="visibility:hidden;"> CPF Valido</div>
            <br><span class="legenda_bloco">
                Somente n &uacute;meros do CPF do aluno, sem pontos ou tra&ccedil;os.
            </span>	
        </td>
    </tr>
 
    <tr>
        <th><label for="certidaoNascimento">Certidao de nascimento</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['certidaoNascimento']!=""){echo $resultado[0]['certidaoNascimento'];}?>" id="certidaoNascimento" name="certidaoNascimento" title="Certidao de nascimento" maxlength="45">
            <br><span class="legenda_bloco">
                N&uacute;mero da certid&atilde;o de nascimento do aluno.
            </span>
        </td>
    </tr>

    <tr>
        <th><label for="livro">Livro</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['livro']!=""){echo $resultado[0]['livro'];}?>" id="livro" name="livro" title="Livro" maxlength="45">
            <br><span class="legenda_bloco">
                N&uacute;mero do livro referente a certid&atilde;o de nascimento.				
            </span>
        </td>
    </tr>

    <tr>
        <th><label for="folha">Folha</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['folha']!=""){echo $resultado[0]['folha'];}?>" id="folha" name="folha" title="Folha" maxlength="45">
            <br><span class="legenda_bloco">
                N&uacute;mero da folha referente a certid&atilde;o de nascimento.				
            </span>
        </td>
    </tr>

    <tr>
        <th><label for="bolsista">Nota Fiscal</label></th>
        <td>
            <input type="checkbox" name="alunoNF" value="1" id="alunoNF" <?php if(@$resultado[0]['notaFiscal']=="1"){echo "checked";}?>>
        </td>
    </tr>

    <tr>
        <th><label for="bolsista">Aluno bolsista</label></th>
        <td>
            <input type="checkbox" name="alunoBolsista" value="1" id="alunoBolsista" <?php if(@$resultado[0]['bolsista']=="1"){echo "checked";}?>>
        </td>
    </tr>
    
    <tr>
        <th><label for="valorBolsa">Valor do desconto</label> </th>
        <td>
            <input class="form_campo" name="valorBolsa" id="valorBolsa" maxlength="4" type="text" value="<?php if(@$resultado[0]['valorBolsa']!=""){echo $resultado[0]['valorBolsa'];}?>"><br>
            <span class="legenda_bloco">Valor expresso em porcentagem, 
            <br>- Utilize "." (ponto) como separador decimal;
            <br>- N&atilde;o utilize o s&iacute;mbolo "%".</span>
        </td>
    </tr>
    
    <tr>
        <th><label for="motivoBolsa">Motivo do desconto</label> </th>
        <td>
            <input class="form_campo" name="motivoBolsa" id="motivoBolsa" maxlength="500" type="text" value="<?php if(@$resultado[0]['motivoBolsa']!=""){echo $resultado[0]['motivoBolsa'];}?>">
        </td>
    </tr>
    
    
</tbody></table>
</td>
</tr>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script type="text/javascript">

    $(document).ready(function() {
        function limpa_formulario_cep() {
            // Limpa valores do formul&aacute;rio de cep.
            $("#rua").val("");
            $("#bairro").val("");
            $("#cidade").val("");
            $("#uf").val("");
            $("#ibge").val("");
        }
        //Quando o campo cep perde o foco.
        $("#cep").blur(function() {
        //Nova vari�vel "cep" somente com d�gitos.
        var cep = $(this).val().replace(/\D/g, '');
        //Verifica se campo cep possui valor informado.
        if (cep != "") {
            //Express�o regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;
            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                $("#rua").val("...");
                $("#bairro").val("...");
                $("#cidade").val("...");
                $("#uf").val("...");
                $("#ibge").val("...");

                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#rua").val(dados.logradouro);
                        $("#bairro").val(dados.bairro);
                        $("#municipio").val(dados.localidade);
                        $("#uf").val(dados.uf);
                        $("#ibge").val(dados.ibge);
                    }else {
                        //CEP pesquisado n&atilde;o foi encontrado.
                        limpa_formulario_cep();
                        alert("CEP n&atilde;o encontrado.");
                    }
                });
            }else {
                //cep &eacute; inv&aacute;lido.
                limpa_formulario_cep();
                alert("Formato de CEP inv&aacute;lido.");
            }
        } else {
            //cep sem valor, limpa formul&aacute;rio.
            limpa_formulario_cep();
        }
        });
    });


    $('#cpfResponsavel').blur(function() {
            $.post("ws/responsavel.php", "cpf="+$('#cpfResponsavel').val(), function( data ) {
            if(data!=""){
                $("#obs_cnpj").css("visibility", "visible");
                $("#obs_cnpj").css("color","blue");
                $("#obs_cnpj").html(data[0].nome +" - "+ data[0].grauParentesco +" - "+ data[0].cpf +" <input type=checkbox name=respFinan value=1> Financeiro<input type=hidden name=respId value="+ data[0].id +">"); 
            }else{
                $("#obs_cnpj").css("visibility", "visible");
                $("#obs_cnpj").css("color","red");
                $("#obs_cnpj").html("Respons&aacute;vel n&atilde;o localizado<br>Cadastrar novo respons&aacute;vel");
            }
        });
    });
    /*
    $('#cpfAluno').blur(function() {
        var cpf = $("#cpfAluno").val();
            $.post("ws/aluno.php", "cpf="+$('#cpfAluno').val(), function( data ) {
            if(data!=""){
                $("#obs_cpf").css("visibility", "visible");
                $("#obs_cpf").css("color","red");
                $("#obs_cpf").html(data[0].cpf +" - CPF ja cadastrado ");     
                alert("CPF Duplicado, verifique por favor \n O botao de envio ficara indisponivel ate que seja inserido um CPF valido");       
                setTimeout(() => {
                    $("#cpfAluno").val("");
                }, 1500);
                document.getElementById('btn_add_aluno').classList.remove("form_buttom");
                document.getElementById('btn_add_aluno').classList.add("form_buttom_grey"); 
                document.getElementById('btn_add_aluno').value = "Verifique o CPF";
            }else{
            if(cpf==""){
                $("#obs_cpf").css("visibility", "visible");
                $("#obs_cpf").css("color","red");
                $("#obs_cpf").html("Ops! Voc&ecirc; esqueceu de digitar o CPF");
                document.getElementById('btn_add_aluno').classList.remove("form_buttom");
                document.getElementById('btn_add_aluno').classList.add("form_buttom_grey");
                document.getElementById('btn_add_aluno').value = "Verifique o CPF";         
                }else{
                $("#obs_cpf").css("visibility", "visible");
                $("#obs_cpf").css("color","blue");
                $("#obs_cpf").html("CPF v&aacute;lido");
                $("#btn_add_aluno").removeAttr("disabled");
                document.getElementById('btn_add_aluno').classList.remove("form_buttom_grey");
                document.getElementById('btn_add_aluno').classList.add("form_buttom");
                document.getElementById('btn_add_aluno').value = "Adicionar Aluno";
                } 
            }
        });
    });
    */
    </script>
    
    
</tbody></table>

    </div>
    
        <div class="btn_pos">
    <?php if($edicao!="sim"){?>
        <input disabled="disabeld" type="submit" name="btn_add_aluno" id="btn_add_aluno" class="form_buttom" value="Adicionar Aluno"></div>
        <input type="hidden" name="grava" value="1">
    <?php }else{?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Editar Aluno"></div>
        <input type="hidden" name="edita" value="1">
        <input type="hidden" name="fun" value="ed">
        <input type="hidden" name="idEndereco" value="<?=$resultado[0]['idEndereco']?>">
        <input type="hidden" name="id" value="<?=$idAluno?>">
        <input type="hidden" name="imagem" value="<?=$resultado[0]['imagem']?>">
    <?php }?>

    
</form>
</div>


    <?php }else{
        if($dados_atualizados !="ok"){
        ?>

      <div class="form_comp">
        Aluno gravado com sucesso<br>
        <img src="images/alunos/<?php echo $novoNome;?>">
        Nome: <?= $nomeAluno?><br>
        RM:  <?= $rm?><br>
        RA: <?= $ra?><br>
        <?php echo "<a href='menu_restrito.php?op=ver_aluno&idAluno=".$LAST_ID_RM."&nome=".base64_encode($nomeAluno)."'>Acessar informa&ccedil;&otilde;es do aluno/edi&ccedil;&atilde;o, informa&ccedil;&otilde;es de ficha m&eacute;dica e adicionais.</a>";?>
            
      </div>

   <?php }else{?>
    <div class="form_comp">
        Aluno alterado com sucesso<br>
        
        Nome: <?= $nomeAluno?><br>
        <?php echo "<a href='menu_restrito.php?op=ver_aluno&idAluno=".$idAluno."&nome=".base64_encode($nomeAluno)."'>Acessar informa&ccedil;&otilde;es do aluno/edi&ccedil;&atilde;o, informa&ccedil;&otilde;es de ficha m&eacute;dica e adicionais.</a>";?>

      </div>

<?php }
}?>