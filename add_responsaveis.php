<?php

//inclui a conexao com banco de dados e fun &atilde;� &atilde;�es
include('conexao.php');
include('functions.php');
#pega as variaveis enviadas pelo formulario
$nomeResponsavel            = $_POST['nomeResponsavel'];
$emailResponsavel           = $_POST['emailResponsavel'];
$grauParentesco             = $_POST['grauParentesco'];
$dtNascimento               = substr($_POST['dtNascimento'],6,4)."-".substr($_POST['dtNascimento'],3,2)."-".substr($_POST['dtNascimento'],0,2);
$sexo                       = $_POST['sexo'];
$nacionalidade              = $_POST['nacionalidade'];
$estadoCivil                = $_POST['estadoCivil'];
$telefone                   = $_POST['telefone'];
$telefoneCom                = $_POST['telefoneCom'];
$profissao                  = $_POST['profissao'];
$celular                    = $_POST['celular'];
$rg                         = $_POST['rg'];
$expedidoEm                 = substr($_POST['expedidoEm'],6,4)."-".substr($_POST['expedidoEm'],3,2)."-".substr($_POST['expedidoEm'],0,2);
$orgaoEmissor               = $_POST['orgaoEmissor'];
$cpf                        = tiraPonto($_POST['cpfResponsavel']);
$observacao                 = $_POST['observacao'];
$resultado_cpf              = $_POST['resultadocpf'];

//pega os dados do endere&ccedil;o pra gravar antes e obter o id, caso n&atilde;o tenha sido cadastrado primeiro
$cep            = $_POST['txt_cep'];
$endereco       = $_POST['txt_endereco'];
$num            = $_POST['txt_num'];
$complemento    = $_POST['txt_comp'];
$bairro         = $_POST['txt_bairro'];
$cidade         = $_POST['txt_cidade'];
$estado         = $_POST['txt_estado'];

//verifica se foi chamada a fun&atilde;�&atilde;�o editar ou incluir novo
if(@$_GET['fun']=="ed"){
    
    $edicao = "sim";
    #aqui vem a busca dos dados do aluno pela variavel de entrada
    $busca_responsavel = $con->prepare("SELECT * FROM responsavel,endereco where responsavel.id=:idResponsavel and responsavel.idEndereco = endereco.id");
    $busca_responsavel->bindValue(':idResponsavel',$idResponsavel);
    $busca_responsavel->execute();
    $resultado = $busca_responsavel->fetchAll(PDO::FETCH_ASSOC);
    //print_r($resultado);
    //echo $idResponsavel;
    
    if(@$_POST['edita']==1){
        #aqui vai a edi&ccedil;&atilde;o dos dados dos responsaveis
         //grava os dados do aluno 
    $altera_responsavel=$con->prepare("update responsavel set
                                               
                                                nome =:nome,
                                                email = :email, 
                                                grauParentesco = :grauParentesco,
                                                rg = :rg,
                                                cpf = :cpf, 
                                                dtNascimento = :dtNascimento,                                                  
                                                sexo = :sexo, 
                                                orgaoEmissor = :orgaoEmissor, 
                                                expedidoEm = :expedidoEm,  
                                                telefone = :telefone, 
                                                telefoneCom = :telefoneCom,
                                                celular = :celular,
                                                profissao = :profissao, 
                                                nacionalidade = :nacionalidade,  
                                                estadoCivil = :estadoCivil,
                                                observacao = :observacao,
                                                dtCriacao = :dtCriacao
                                                where id=:id
                                               ");
    $altera_responsavel->execute(array(
                        //':idEndereco'       => $LAST_ID, 
                        ':nome'             => $nomeResponsavel, 
                        ':email'            => $emailResponsavel, 
                        ':grauParentesco'   => $grauParentesco,
                        ':rg'               => $rg, 
                        ':cpf'              => $cpf,      
                        ':dtNascimento'     => $dtNascimento,                                                  
                        ':sexo'             => $sexo, 
                        ':orgaoEmissor'     => $orgaoEmissor, 
                        ':expedidoEm'       => $expedidoEm,  
                        ':telefone'         => $telefone, 
                        ':telefoneCom'      => $telefoneCom,
                        ':celular'          => $celular,
                        ':profissao'        => $profissao, 
                        ':nacionalidade'    => $nacionalidade,  
                        ':estadoCivil'      => $estadoCivil, 
                        ':observacao'       => $observacao, 
                        ':dtCriacao'        => date('Y-m-d H-i-s'),
                        'id'                => $_POST['id']
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

        
        $status = $altera_responsavel->errorCode();
        if($status =="00000"){
            $dados_atualizados = "ok";
        }
        #echo $status;
    }


}else{
    $edicao ="nao";
}


//modulo de cadastro de alunos, arquivo inserido ao menu restrito atrav�s de include
if(isset($_POST['grava'])==1){

/*if (mysql_num_rows($resultado_cpf[0])!=1) {
    echo "Já existe um responsável com esse cpf";
    "<input type='button' value='Voltar' onClick='history.go(-1)'>";
}*/
//armazena os dados das vari�veis do formul�rio

//grava primeiro o endereco e o usuario, pega o id do endere�o e do usuario pra montar o relacionamento correto
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
  $grava_responsavel=$con->prepare("insert into responsavel (idEndereco, 
                                                nome, 
                                                email, 
                                                grauParentesco,
                                                rg, 
                                                cpf, 
                                                dtNascimento,                                                  
                                                sexo, 
                                                orgaoEmissor, 
                                                expedidoEm,  
                                                telefone, 
                                                telefoneCom,
                                                celular,
                                                profissao, 
                                                nacionalidade,  
                                                estadoCivil, 
                                                observacao,
                                                dtCriacao
                                                ) values (
                                                    :idEndereco, 
                                                    :nome, 
                                                    :email, 
                                                    :grauParentesco,
                                                    :rg, 
                                                    :cpf, 
                                                    :dtNascimento,                                                  
                                                    :sexo, 
                                                    :orgaoEmissor, 
                                                    :expedidoEm,  
                                                    :telefone, 
                                                    :telefoneCom,
                                                    :celular,
                                                    :profissao, 
                                                    :nacionalidade,  
                                                    :estadoCivil, 
                                                    :observacao,
                                                    :dtCriacao 
                                                )");
  $grava_responsavel->execute(array(
    ':idEndereco'       => $LAST_ID, 
    ':nome'             => $nomeResponsavel, 
    ':email'            => $emailResponsavel, 
    ':grauParentesco'   => $grauParentesco,
    ':rg'               => $rg, 
    ':cpf'              => $cpf,      
    ':dtNascimento'     => $dtNascimento,                                                  
    ':sexo'             => $sexo, 
    ':orgaoEmissor'     => $orgaoEmissor, 
    ':expedidoEm'       => $expedidoEm,  
    ':telefone'         => $telefone, 
    ':telefoneCom'      => $telefoneCom,
    ':celular'          => $celular,
    ':profissao'        => $profissao, 
    ':nacionalidade'    => $nacionalidade,  
    ':estadoCivil'      => $estadoCivil, 
    ':observacao'       => $observacao, 
    ':dtCriacao'        => date('Y-m-d H-i-s')
  ));
//grava o id do usuario pra gerar o nome do usuario
$LAST_ID_RESP = $con->lastInsertId();

//atualiza o banco com o cpf do responsavel
$atualiza_cpf = $con->prepare("update responsavel set cpf = :cpf where id = :id");
$atualiza_cpf->execute(array(
    ':cpf' => $cpf,
    ':id' => $LAST_ID_RESP
));

//cadastra o usuario ja padronizado como responsavel
$grava_usuario_responsavel=$con->prepare("insert into usuarios (
                                                usuario,
                                                senha,
                                                nivel,
                                                ativo,
                                                cadastro,
                                                filial
                                            ) values (
                                                :usuario,
                                                :senha,
                                                :nivel,
                                                :ativo,
                                                :cadastro,
                                                :filial
                                                )");
//cria uma senha padrao com a data de nascimento do aluno
$senhaResp = md5(tiraPonto($cpf));
$grava_usuario_responsavel->execute(array(
                        ':usuario'  => $cpf,
                        ':senha'    => $senhaResp,
                        ':nivel'    => '8',
                        ':ativo'    => '1',
                        ':cadastro' => date('Y-m-d H-i-s'),
                        ':filial'   => $_SESSION['Unidade']
                        ));
//id do endereco gerado do usuario
$LAST_ID_USER = $con->lastInsertId();

//atualiza o banco com o rm do aluno
$atualiza_usuario = $con->prepare("update responsavel set idUsuario = :idusuario where id = :id");
$atualiza_usuario->execute(array(
    ':idusuario' => $LAST_ID_USER,
    ':id' => $LAST_ID_RESP
));

  $status = $grava_responsavel->errorCode();
  
} 
?>

<div class="titulo_interna">
    <i class="fa fa-users" aria-hidden="true"></i>Pessoas
</div>
<div class="content_form">
   <!--<div class="titulo">Adicionar Respons&atilde;�veis</div>-->
    <?php
    if(@$_GET['fun']=="ed"){$titulo = "Editar Respons&aacute;veis";}else{$titulo = "Adicionar Respons&aacute;veis";}
    ?>
    <div class="titulo"> <?= $titulo?> </div>
    <?php
    if(!isset($status)){?>
<form method="post" action="#" enctype="multipart/form-data" id="frmAluno" onsubmit="return verificarCPF()">
    <div class="form_comp">
    

<table class="formulario">
<tbody>
    <tr><td colspan="99"></td></tr>

    <tr>
    <th><label for="nome">Nome</label><span class="obrigatorio"> *</span></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['nome']!=""){echo $resultado[0]['nome'];}?>" id="nomeResponsavel" name="nomeResponsavel" title="Nome do Respons�vel" maxlength="60">
            <br><span class="legenda_bloco">Nome completo do respons&aacute;vel.</span>
        </td>
    </tr>

    <tr>
    <th><label for="nome">E-mail</label><span class="obrigatorio"> *</span></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['email']!=""){echo $resultado[0]['email'];}?>" id="emailResponsavel" name="emailResponsavel" title="Email do Aluno" maxlength="60">
            <br><span class="legenda_bloco">Email do respons&aacute;vel.</span>
        </td>
    </tr>
    <tr>
    <th><label for="nome">Grau de parentesco:</label><span class="obrigatorio"> *</span></th>
        <td>
        <select name="grauParentesco" id="grauParentesco" class="form_campo">
            <option value="">Selecione...</option>
            <option value="Mae" <?php if(@$resultado[0]['grauParentesco']=="Mae"){echo "selected";}?>>M&atilde;e</option>
            <option value="Pai" <?php if(@$resultado[0]['grauParentesco']=="Pai"){echo "selected";}?>>Pai</option>
            <option value="Terceiro" <?php if(@$resultado[0]['grauParentesco']=="Terceiro"){echo "selected";}?>>Terceiro</option>
        </select>
            
        </td>
    </tr>
    
    
    <tr><th colspan="2" style="text-align:center;">Informa&ccedil; &otilde;es</th></tr>
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
            <label for="dtNascimento">Data nascimento</label><span class="obrigatorio"> *</span>
        </th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['dtNascimento']!=""){echo formatoData($resultado[0]['dtNascimento']);}?>" id="dtNascimento" name="dtNascimento" title="Data de Nascimento" maxlength="10" placeholder="dd/mm/aaaa">	
        </td>
    </tr>

    <tr>
        <th><label for="sexo">Sexo</label><span class="obrigatorio"> *</span></th>
        <td>
            <select id="sexo" name="sexo" title="Sexo" class="form_campo">
                <option value="" <?php if(@$resultado[0]['sexo']==""){echo "selected";}?>>Selecione...</option>
                <option value="1" <?php if(@$resultado[0]['sexo']=="1"){echo "selected";}?>>Feminino</option>
                <option value="2" <?php if(@$resultado[0]['sexo']=="2"){echo "selected";}?>>Masculino</option>
            </select>
        </td>
    </tr>

  
    <tr>	
        <th><label for="nacionalidade">Nacionalidade</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['nacionalidade']!=""){echo $resultado[0]['nacionalidade'];}?>" id="nacionalidade" name="nacionalidade" title="Nacionalidade" maxlength="50">
        </td>
    </tr>


    <tr>	
        <th><label for="estadoCivil">Estado civil</label><span class="obrigatorio"> *</span></th>
        <td>
            <select id="estadoCivil" name="estadoCivil" title="Estado civil" class="form_campo">
                <option value="1" <?php if(@$resultado[0]['estadoCivil']=="1"){echo "selected";}?>>Solteiro(a)</option>
                <option value="2" <?php if(@$resultado[0]['estadoCivil']=="2"){echo "selected";}?>>Casado(a)</option>
                <option value="3" <?php if(@$resultado[0]['estadoCivil']=="3"){echo "selected";}?>>Desquitado(a)</option>
                <option value="4" <?php if(@$resultado[0]['estadoCivil']=="4"){echo "selected";}?>>Divorcidado(a)</option>
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
        <th><label for="celular">Telefone Comercial</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['telefoneCom']!=""){echo $resultado[0]['telefoneCom'];}?>" id="telefoneCom" name="telefoneCom" title="Telefone Comercial" maxlength="20">
        </td>
    </tr>

    <tr>
        <th><label for="celular">Profiss&atilde;o</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['profissao']!=""){echo $resultado[0]['profissao'];}?>" id="profissao" name="profissao" title="Profiss�o">
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
             Data de expedi&ccedil;&atilde;o do RG do respons&aacute;vel no formato: dd/mm/aaaa
            </span>	
        </td>
    </tr>
 
    <tr>	
        <th><label for="orgaoEmissor">Org&atilde;o emissor</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['orgaoEmissor']!=""){echo $resultado[0]['orgaoEmissor'];}?>" id="orgaoEmissor" name="orgaoEmissor" title="Orgão emissor" maxlength="6" style="text-transform:uppercase">
            <br><span class="legenda_bloco">Org&atilde;o emissor do RG do aluno</span>
        </td>
    </tr>
 
    <tr>
        <th><label for="cpfResponsavel">CPF</label></th>
        <td>
            <input class="form_campo" onchange="" type="text" value="<?php if(@$resultado[0]['cpf']!=""){echo $resultado[0]['cpf'];}?>" id="cpfResponsavel" name="cpfResponsavel" title="CPF" maxlength="11" onkeypress="return tiraPonto()" required>
            <div id="ResResponsavel"><span class="lbl_file" id="obs_cnpj" style="visibility:hidden;"> CPF Valido</div>
            <br><span class="legenda_bloco">
                Somente n &uacute;meros do CPF do respons&atilde;vel, sem pontos ou tra&ccedil;os.
            </span>	
        </td>
    </tr>
    <tr>
        <th><label for="observacao">Observa&ccedil;&otilde;es</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['observacao']!=""){echo $resultado[0]['observacao'];}?>" id="observacao" name="observacao" title="Observa��o" >
            
        </td>
    </tr>
 
   
    
</tbody></table>
</td>
</tr>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" crossorigin="anonymous"></script>
<script type="text/javascript">

    $(document).ready(function() {
        function limpa_formulario_cep() {
            // Limpa valores do formul�rio de cep.
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
                        //CEP pesquisado n�o foi encontrado.
                        limpa_formulario_cep();
                        alert("CEP n&atilde;o encontrado.");
                    }
                });
            }else {
                //cep � inv�lido.
                limpa_formulario_cep();
                alert("Formato de CEP inv&aacute;lido.");
            }
        } else {
            //cep sem valor, limpa formul�rio.
            limpa_formulario_cep();
        }
        });
    });

    <?php if(@$_GET['fun']=="ed"){
        echo "var opcao = 'ed';";
    }else{
        echo "var opcao = 'add';";
    }    
    ?>

if(opcao == 'add'){

    $('#cpfResponsavel').blur(function() {
        var cpf = $("#cpfResponsavel").val();
            $.post("ws/responsavel.php", "cpf="+$('#cpfResponsavel').val(), function( data ) {
            if(data!=""){
                $("#obs_cnpj").css("visibility", "visible");
                $("#obs_cnpj").css("color","red");
                $("#obs_cnpj").html(data[0].cpf +" - CPF ja cadastrado ");     
                alert("CPF Duplicado, verifique por favor \n O botao de envio ficara indisponivel ate que seja inserido um CPF valido");       
                setTimeout(() => {
                    $("#cpfResponsavel").val("");
                }, 1500);
                document.getElementById('btn_add_aluno').classList.remove("form_buttom");
                document.getElementById('btn_add_aluno').classList.add("form_buttom_grey"); 
                document.getElementById('btn_add_aluno').value = "Verifique o CPF";
            }else{
            if(cpf==""){
                $("#obs_cnpj").css("visibility", "visible");
                $("#obs_cnpj").css("color","red");
                $("#obs_cnpj").html("Ops! Você esqueceu de digitar o CPF");
                document.getElementById('btn_add_aluno').classList.remove("form_buttom");
                document.getElementById('btn_add_aluno').classList.add("form_buttom_grey");
                document.getElementById('btn_add_aluno').value = "Verifique o CPF";         
                }else{
                $("#obs_cnpj").css("visibility", "visible");
                $("#obs_cnpj").css("color","blue");
                $("#obs_cnpj").html("CPF v&aacute;lido");
                $("#btn_add_aluno").removeAttr("disabled");
                document.getElementById('btn_add_aluno').classList.add("form_buttom");
                document.getElementById('btn_add_aluno').value = "Adicionar Responsavel";
                } 
            }
        });
    });

}
    
   



</script>
 
    
</tbody></table>

    </div>
    
    <div class="btn_pos">
    <?php if($edicao!="sim"){?>
        <input disabled="disabled" type="submit" name="btn_add_aluno" id="btn_add_aluno" class="form_buttom" value="Adicionar Respons&aacute;vel" onclick="return validaSubmit(this)"></div>
        <input type="hidden" name="grava" value="1">
        <? ?>
    <?php }else{?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Editar Respons&aacute;vel"></div>
        <input type="hidden" name="edita" value="1">
        <input type="hidden" name="fun" value="ed">
        <input type="hidden" name="idEndereco" value="<?=$resultado[0]['idEndereco']?>">
        <input type="hidden" name="id" value="<?=$idResponsavel?>">

    <?php }?>

</form>

</div>


    <?php }else{?>
       <?php if($dados_atualizados !="ok"){
            ?>
            <div class="form_comp">
            Respons&aacute;vel gravado com sucesso<br>
            Nome: <?= $nomeResponsavel?><br>
            Usu&aacute;rio:  <?= $cpf?><br>
            Senha: Favor indicar o uso do CPF inicialmente para a senha do sistema<br>
          <? 
        ?>
       <?php }elseif($dados_atualizados =="ok"){?>
        Respons&aacute;vel alterado com sucesso <br>
        Nome: <?= $nomeResponsavel?><br>
        
       <?php  
       } ?>
      </div>

   <?php  }?>