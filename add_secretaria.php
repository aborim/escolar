<?php
 
//inclui a conexao com banco de dados e fun&ccedil; &otilde;es
include('conexao.php');
include('functions.php');

#pega as vari&aacute;veis enviadas pelo formul&aacute;rio
$nomeSecretaria           = $_POST['nome'];
$emailSecretaria          = $_POST['email'];
$rg                       = $_POST['rg'];
$cpf                      = $_POST['cpf'];
$dtNascimento             = substr($_POST['dtNascimento'],6,4)."-".substr($_POST['dtNascimento'],3,2)."-".substr($_POST['dtNascimento'],0,2);


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
    #aqui vem a busca dos dados do secretaria pela variavel de entrada
    $busca_secretaria = $con->prepare("select * from secretaria, endereco where secretaria.id=:idSecretaria and secretaria.idEndereco= endereco.id");
    $busca_secretaria->bindValue(':idSecretaria',$idSecretaria);
    $busca_secretaria->execute();
    $resultado = $busca_secretaria->fetchAll(PDO::FETCH_ASSOC);
   
   
    if(@$_POST['edita']==1){
        #aqui vai a edi&ccedil;&atilde;o dos dados da secretaria

        $altera_secretaria = $con->prepare("update secretaria set
                                    nome  = :nomeSecretaria,
                                    email = :emailSecretaria,
                                    rg  = :rg,
                                    cpf = :cpf,
                                    dtNascimento= :dtNascimento
                                    where id = :id     
                                 ");
        $altera_secretaria->execute(array(
                                    ':nomeSecretaria'       => $nomeSecretaria,
                                    ':emailSecretaria'      => $emailSecretaria,
                                    ':rg'                   => $rg,
                                    ':cpf'                  => $cpf,
                                    ':dtNascimento'         => $dtNascimento,
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
        
        
        $status = $altera_secretaria->errorCode();
        if($status =="00000"){
            $dados_atualizados = "ok";
        }
        #echo $status;
    }


}else{
    $edicao ="nao";
}


//modulo de cadastro de secretarias, arquivo inserido ao menu restrito atrav &eacute;s de include
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

  //grava os dados do secretaria
  //junto ao banco de dados junto com a foto, usuario, endereco, 
  $grava_secretaria=$con->prepare("insert into secretaria (
                                                
                                                idEndereco, 
                                                nome, 
                                                email,
                                                rg, 
                                                cpf,
                                                dtNascimento,
                                                dtCriacao
                                                )  values (
                                                
                                                :idEndereco, 
                                                :nomeSecretaria, 
                                                :emailSecretaria, 
                                                :rg, 
                                                :cpf, 
                                                :dtNascimento,
                                                :dtCriacao
                                                )");
  $grava_secretaria->execute(array(
    //':idUsuario'        => $LAST_ID_USER,
    ':idEndereco'       => $LAST_ID,  
    ':nomeSecretaria'   => $nomeSecretaria, 
    ':emailSecretaria'  => $emailSecretaria,
    ':rg'               => $rg, 
    ':cpf'              => $cpf, 
    ':dtCriacao'        => date('Y-m-d H-i-s'),
    ':dtNascimento'     => $dtNascimento
    ));
//id do endereco gerado do usuario
$LAST_ID_SEC = $con->lastInsertId();

$atualiza_cpf = $con->prepare("update secretaria set cpf = :cpf where id = :id");
$atualiza_cpf->execute(array(
    ':cpf' => $cpf,
    ':id' => $LAST_ID_SEC
));
//cadastra o usuario ja padronizado como secretaria
$grava_usuario_secretaria=$con->prepare("insert into usuarios (
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
//cria uma senha padrao com a data de nascimento do secretaria
$senhaSecretaria = md5(soNumero($dtNascimento));
$grava_usuario_secretaria->execute(array(
                        ':usuario'  => $cpf,
                        ':senha'    => $senhaSecretaria,
                        ':nivel'    => '5',
                        ':ativo'    => '1',
                        ':filial'   =>$_SESSION['Unidade'],
                        ':cadastro' => date('Y-m-d H-i-s')
                        ));
//id do endereco gerado do usuario
$LAST_ID_USER = $con->lastInsertId();

//atualiza o cadastro do secretaria inserindo o idUsuario
$atualiza_usuario = $con->prepare("update secretaria set idUsuario = :idUsuario where id = :id");
$atualiza_usuario->execute(array(
    ':idUsuario'    => $LAST_ID_USER,
    ':id'           => $LAST_ID_SEC
));

  $status = $grava_secretaria->errorCode();
}
?>

<div class="titulo_interna">
    <i class="fa fa-users" aria-hidden="true"></i>Pessoas
</div>
<div class="content_form">
    <?php
    if(@$_GET['fun']=="ed"){$titulo = "Editar Secretaria";}else{$titulo = "Adicionar Secretaria";}
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
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['nome']!=""){echo $resultado[0]['nome'];}?>" id="nomeSecretaria" name="nome" title="Nome do Secretaria (o)" maxlength="60">
            <br><span class="legenda_bloco">Nome completo da Secretaria(o).</span>
        </td>
    </tr>

    <tr>
    <th><label for="nome">E-mail</label><span class="obrigatorio"> *</span></th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['email']!=""){echo $resultado[0]['email'];}?>" id="emailSecretaria" name="email" title="Email do Secretaria(a)" maxlength="60">
            <br><span class="legenda_bloco">Email da Secretaria.</span>
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
            <label for="dtNascimento">Data nascimento</label><span class="obrigatorio"> *</span>
        </th>
        <td>
            <input class="form_campo" type="text" value="<?php if(@$resultado[0]['dtNascimento']!=""){echo formatoData($resultado[0]['dtNascimento']);}?>" id="dtNascimento" name="dtNascimento" title="Data de Nascimento" maxlength="10" placeholder="dd/mm/aaaa">	
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
                Somente n &uacute;meros do CPF do Secretaria (o), sem pontos ou tra&ccedil;os.
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
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Adicionar Secretaria (o)"></div>
        <input type="hidden" name="grava" value="1">
    <?php }else{?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Editar Secretaria (o)"></div>
        <input type="hidden" name="edita" value="1">
        <input type="hidden" name="fun" value="ed">
        <input type="hidden" name="idEndereco" value="<?=$resultado[0]['idEndereco']?>">
        <input type="hidden" name="id" value="<?=$idSecretaria?>">
    <?php }?>

    
</form>
</div>


    <?php }else{
        if($dados_atualizados !="ok"){
        ?>

      <div class="form_comp">
        Secretaria (o) gravado com sucesso<br>
        Nome: <?= $nomeSecretaria?><br>
        CPF:  <?= $cpf?><br>

      </div>

   <?php }else{?>
    <div class="form_comp">
        Secretaria (o) alterado com sucesso<br>
        
        Nome: <?= $nomeSecretaria?><br>
        

      </div>

<?php }
}?>