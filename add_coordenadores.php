<?php
 
//inclui a conexao com banco de dados e fun&ccedil;&otilde;es
include('conexao.php');
include('functions.php');

#pega as vari&aacute;veis enviadas pelo formul&aacute;rio
$nomeCoordenador            = $_POST['nomeCoordenador'];
$emailCoordenador           = $_POST['emailCoordenador'];
$rg                         = $_POST['rg'];
$cpf                        = $_POST['cpf'];
$ctps                       = $_POST['ctps'];
$pis                        = $_POST['pis'];
$ctpsDtExpedicao            = substr($_POST['expedidoEm'],6,4)."-".substr($_POST['expedidoEm'],3,2)."-".substr($_POST['expedidoEm'],0,2);
$ultimoExame                = substr($_POST['ultimoExame'],6,4)."-".substr($_POST['ultimoExame'],3,2)."-".substr($_POST['ultimoExame'],0,2);
$dtAdmissao                 = substr($_POST['dtAdmissao'],6,4)."-".substr($_POST['dtAdmissao'],3,2)."-".substr($_POST['dtAdmissao'],0,2);
$dtNascimento               = substr($_POST['dtNascimento'],6,4)."-".substr($_POST['dtNascimento'],3,2)."-".substr($_POST['dtNascimento'],0,2);
$estadoCivil                = $_POST['estadoCivil'];
$sexo                       = $_POST['sexo'];
$telefone                   = $_POST['telefone'];
$celular                    = $_POST['celular'];
$observacao                 = $_POST['observacao'];


//pega os dados do endere&ccedil;o pra gravar antes e obter o id, caso n&atilde;o tenha sido cadastrado primeiro
$cep            = $_POST['txt_cep'];
$endereco       = $_POST['txt_endereco'];
$num            = $_POST['txt_num'];
$complemento    = $_POST['txt_comp'];
$bairro         = $_POST['txt_bairro'];
$cidade         = $_POST['txt_cidade'];
$estado         = $_POST['txt_estado'];



if(@$_GET['fun']=="ed"){
    
    $edicao = "sim";
    #aqui vem a busca dos dados do coordenador pela variavel de entrada
    $busca_Coordenador = $con->prepare("select * from coordenador,endereco where coordenador.id=:idCoordenador and coordenador.idEndereco= endereco.id");
    $busca_Coordenador->bindValue(':idCoordenador',$idCoordenador);
    $busca_Coordenador->execute();
    $resultado = $busca_Coordenador->fetchAll(PDO::FETCH_ASSOC);
   
   
    if(@$_POST['edita']==1){
        #aqui vai a edi&ccedil;&atilde;o dos dados do coordenador

        $altera_coordenador = $con->prepare("update coordenador set
                                    nome  = :nomeCoordenador,
                                    email  = :emailCoordenador,
                                    rg  = :rg,
                                    cpf = :cpf,
                                    ctps = :ctps,
                                    ctpsDtExpedicao =:ctpsDtExpedicao,
                                    pis = :pis,
                                    dtultimoExame = :ultimoExame,
                                    dtAdmissao = :dtAdmissao,
                                    dtNascimento= :dtNascimento,
                                    estadoCivil  = :estadoCivil,
                                    sexo  = :sexo,
                                    telefone  = :telefone,
                                    celular = :celular,
                                    observacao = :observacao
                                    where id = :id     
                                 ");
        $altera_coordenador->execute(array(
                                    ':nomeCoordenador'      => $nomeCoordenador,
                                    ':emailCoordenador'     => $emailCoordenador,
                                    ':rg'                   => $rg,
                                    ':cpf'                  => $cpf,
                                    ':ctps'                 => $ctps,
                                    ':ctpsDtExpedicao'      => $ctpsDtExpedicao,
                                    ':pis'                  => $pis,
                                    ':ultimoExame'          => $ultimoExame,  
                                    ':dtAdmissao'           => $dtAdmissao,
                                    ':dtNascimento'         => $dtNascimento,
                                    ':estadoCivil'          => $estadoCivil,
                                    ':sexo'                 => $sexo,
                                    ':telefone'             => $telefone,
                                    ':celular'              => $celular,
                                    ':observacao'           => $observacao,
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
        
        #echo "altera os dados do coordenador aqui";
        $status = $altera_coordenador->errorCode();
        if($status =="00000"){
            $dados_atualizados = "ok";
        }
        #echo $status;
    }


}else{
    $edicao ="nao";
}


//modulo de cadastro de coordenadors, arquivo inserido ao menu restrito atrav&eacute;s de include
if(isset($_POST['grava'])==1){


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

  //grava os dados do coordenador
  //junto ao banco de dados junto com a foto, usuario, endereco, 
  $grava_coordenador=$con->prepare("insert into coordenador (
                                                idEndereco, 
                                                nome, 
                                                email, 
                                                rg, 
                                                cpf, 
                                                ctps,
                                                ctpsDtExpedicao,
                                                pis,
                                                dtUltimoExame,
                                                dtNascimento, 
                                                dtAdmissao,
                                                sexo,
                                                telefone, 
                                                celular,
                                                estadoCivil, 
                                                dtCriacao,
                                                observacao) values (
                                                :idEndereco, 
                                                :nome, 
                                                :email, 
                                                :rg, 
                                                :cpf, 
                                                :ctps,
                                                :ctpsDtExpedicao,
                                                :pis,
                                                :dtUltimoExame,
                                                :dtNascimento, 
                                                :dtAdmissao,
                                                :sexo,
                                                :telefone, 
                                                :celular,
                                                :estadoCivil, 
                                                :dtCriacao,
                                                :observacao)");
  $grava_coordenador->execute(array(
    ':idEndereco'       => $LAST_ID,  
    ':nome'             => $nomeCoordenador, 
    ':email'            => $emailCoordenador,
    ':rg'               => $rg, 
    ':cpf'              => $cpf, 
    ':ctps'             => $ctps,
    ':ctpsDtExpedicao'  => $ctpsDtExpedicao,
    ':pis'              => $pis,
    ':dtUltimoExame'    => $ultimoExame,
    ':dtNascimento'     => $dtNascimento, 
    ':dtAdmissao'       => $dtAdmissao,
    ':sexo'             => $sexo,
    ':telefone'         => $telefone, 
    ':celular'          => $celular,
    ':estadoCivil'      => $estadoCivil, 
    ':dtCriacao'        => date('Y-m-d H-i-s'),
    ':observacao'       => $observacao
    ));
//id do endereco gerado do usuario
$LAST_ID_COORD = $con->lastInsertId();
//cadastra o usuario ja padronizado como coordenador
$grava_usuario_coordenador=$con->prepare("insert into usuarios (
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
//cria uma senha padrao com a data de nascimento do coordenador
$senhaCoordenador = md5(soNumero($cpf));
$grava_usuario_coordenador->execute(array(
                        ':usuario'  => $cpf,
                        ':senha'    => $senhaCoordenador,
                        ':nivel'    => '5',
                        ':ativo'    => '1',
                        ':filial'   =>$_SESSION['Unidade'],
                        ':cadastro' => date('Y-m-d H-i-s')
                        ));
//id do endereco gerado do usuario
$LAST_ID_USER = $con->lastInsertId();

//atualiza o cadastro do coordenador inserindo o idUsuario
$atualiza_usuario = $con->prepare("update coordenador set idUsuario = :idUsuario where id = :id");
$atualiza_usuario->execute(array(
    ':idUsuario'    => $LAST_ID_USER,
    ':id'           => $LAST_ID_COORD
));

  $status = $grava_coordenador->errorCode();
}
?>

<div class="titulo_interna">
    <i class="fa fa-users" aria-hidden="true"></i>Pessoas
</div>
<div class="content_form">
    <?php
    if(@$_GET['fun']=="ed"){$titulo = "Editar Coordenador";}else{$titulo = "Adicionar Coordenador";}
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
    <th><label for="nome">Nome</label><span class="obrigatorio"> *</span></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['nome']!=""){echo $resultado[0]['nome'];}?>" id="nomeCoordenador" name="nomeCoordenador" title="Nome do Coordenador" maxlength="60">
            <br><span class="legenda_bloco">Nome completo do Coordenador(a).</span>
        </td>
    </tr>

    <tr>
    <th><label for="nome">E-mail</label><span class="obrigatorio"> *</span></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['email']!=""){echo $resultado[0]['email'];}?>" id="emailCoordenador" name="emailCoordenador" title="Email do Coordenador(a)" maxlength="60">
            <br><span class="legenda_bloco">Email do Coordenador(a).</span>
        </td>
    </tr>
    <!--<tr>
    <th><label for="nome">Cargo Ocupado:</label><span class="obrigatorio"> *</span></th>
        <td>
        <select name="cargo" id="cargo" class="form_campo">
            <option value="">Selecione...</option>
            <option value="Professor" <?php //if(@$resultado[0]['cargo']=="Professor"){echo "selected";}?>>Professor</option>
            <option value="Coordenador" <?php // if(@$resultado[0]['cargo']=="Coordenador"){echo "selected";}?>>Coordenador</option>
            <option value="Diretor" <?php //if(@$resultado[0]['cargo']=="Diretor"){echo "selected";}?>>Diretor</option>
            <option value="Secretario" <?php //if(@$resultado[0]['cargo']=="Secretario"){echo "selected";}?>>Secret&aacute;rio</option>
        </select>
            
        </td>
    </tr>-->
    
    
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
        <th><label for="celular">Celular</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['celular']!=""){echo $resultado[0]['celular'];}?>" id="celular" name="celular" title="celular" maxlength="20">
        </td>
    </tr>

    <tr>
        <th colspan="2" style="text-align:center;">Documentos</th>
    </tr>
    
    <tr>
        <th><label for="cpf">CPF</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['cpf']!=""){echo $resultado[0]['cpf'];}?>" id="cpf" name="cpf" title="CPF" maxlength="11" onkeypress="return soNumeros(event)">
            <br><span class="legenda_bloco">
                Somente n&uacute;meros do CPF do Coordenador, sem pontos ou tra&ccedil;os.
            </span>	
        </td>
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
        <th><label for="ctps">Carteira de Trabalho</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['ctps']!=""){echo $resultado[0]['ctps'];}?>" id="ctps" name="ctps" title="Numero da Carteira de Trabalho" maxlength="16" style="text-transform:uppercase">
            <br><span class="legenda_bloco">N&uacute;mero da carteira de trabalho do Coordenador</span>
        </td>
    </tr>

    <tr>	
        <th><label for="expedidoEm">Expedido em</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['ctpsDtExpedicao']!=""){echo formatoData($resultado[0]['ctpsDtExpedicao']);}?>" id="expedidoEm" name="expedidoEm" title="Expedido em" maxlength="10">
            <br><span class="legenda_bloco">
             Data de expedi&ccedil;&atilde;o da Carteira de Trabalho do Coordenador no formato: dd/mm/aaaa
            </span>	
        </td>
    </tr>
 
    <tr>	
        <th><label for="pis">PIS</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['pis']!=""){echo $resultado[0]['pis'];}?>" id="pis" name="pis" title="PIS" maxlength="16" style="text-transform:uppercase">
            <br><span class="legenda_bloco">PIS do Coordenador</span>
        </td>
    </tr>

    <tr>	
        <th><label for="ultimoExame">Ultimo Exame</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['dtUltimoExame']!=""){echo formatoData($resultado[0]['dtUltimoExame']);}?>" id="ultimoExame" name="ultimoExame" title="Data do ultimo Exame" maxlength="10" style="text-transform:uppercase">
            <br><span class="legenda_bloco">Data do &uacute;ltimo exame medico</span>
        </td>
    </tr>
    <tr>	
        <th><label for="dtAdmissao">Data da Admiss&atilde;o</label></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['dtAdmissao']!=""){echo formatoData($resultado[0]['dtAdmissao']);}?>" id="dtAdmissao" name="dtAdmissao" title="Data da Admiss&atilde;o" style="text-transform:uppercase">
            <br><span class="legenda_bloco">Data da Admiss&atilde;o do Coordenador</span>
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

    </script>
    
    
</tbody></table>

    </div>
    
        <div class="btn_pos">
    <?php if($edicao!="sim"){?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Adicionar Coordenador"></div>
        <input type="hidden" name="grava" value="1">
    <?php }else{?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Editar Coordenador"></div>
        <input type="hidden" name="edita" value="1">
        <input type="hidden" name="fun" value="ed">
        <input type="hidden" name="idEndereco" value="<?=$resultado[0]['idEndereco']?>">
        <input type="hidden" name="id" value="<?=$idCoordenador?>">
    <?php }?>

    
</form>
</div>


    <?php }else{
        if($dados_atualizados !="ok"){
        ?>

      <div class="form_comp">
        Coordenador gravado com sucesso<br>
        Nome: <?= $nomeCoordenador?><br>
        CPF:  <?= $cpf?><br>

      </div>

   <?php }else{?>
    <div class="form_comp">
        Coordenador alterado com sucesso<br>
        
        Nome: <?= $nomeCoordenador?><br>
        

      </div>

<?php }
}?>