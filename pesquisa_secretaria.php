<?php


//modulo de cadastro de secretaria, arquivo inserido ao menu restrito atrav &eacute;s de include
if(isset($_POST['buscar'])==1){

//inclui a conexao com banco de dados e fun&ccedil; &otilde;es
include('conexao.php');
include('functions.php');

//armazena os dados das vari&aacute;veis do formul&aacute;rio
if(($_POST['nomeSecretaria']!="")&&($_POST['cpf']=="")){
    $comp = "AND secretaria.nome like :nomeSecretaria";
}else if(($_POST['nomeSecretaria']=="")&&($_POST['cpf']!="")){
    $comp = "AND secretaria.cpf = :cpf";
}else{
    $comp = "AND secretaria.nome like :nomeSecretaria AND secretaria.cpf = :cpf";
}

if(($_POST['nomeSecretaria']!="")||($_POST['cpf']!="")){
    $buscar=$con->prepare("
    SELECT 
        secretaria.id,
        secretaria.nome,
        secretaria.cpf,
        secretaria.rg
    FROM 
        secretaria,usuarios 
    WHERE 
        secretaria.idUsuario=usuarios.id and usuarios.filial= :filial and usuarios.ativo=1 ".$comp);
        #':search', '%' . $search . '%'
  $buscar->bindValue(":filial",$_SESSION['Unidade']);
  
  if(($_POST['nomeSecretaria']!="")&&($_POST['cpf']=="")){
     $buscar->bindValue(":nomeSecretaria",'%' . $_POST['nomeSecretaria'] . '%');
  }
  
  if(($_POST['nomeSecretaria']=="")&&($_POST['cpf']!="")){
    $buscar->bindValue(":cpf",$_POST['cpf']);
  }

  if(($_POST['nomeSecretaria']!="")&&($_POST['cpf']!="")){
    $buscar->bindValue(":cpf",$_POST['cpf']);
    $buscar->bindValue(":nomeSecretaria",'%' . $_POST['nomeSecretaria'] . '%');
  }


  $buscar->execute();
  $resultado = $buscar->fetchAll(PDO::FETCH_ASSOC);
  if($buscar->rowCount()==0){$mensagem="Nenhum respons&aacute;vel encontrado";}
}else{

$mensagem = "Nenhum dado selecionado";
}

}
?>

<div class="titulo_interna">
    <i class="fa fa-users" aria-hidden="true"></i>Pessoas
</div>
<div class="content_form">
   <form method="post" action="#" enctype="multipart/form-data" id="frmAluno">
    <div class="form_comp">
    

<table class="formulario" >
<tbody class="display">
    <tr><td colspan="101"></td></tr>
    <div class="titulo">Pesquisar Secretaria (o)</div>
    <?php
    if(!isset($status)){?>

    <tr>
      <th><label for="nome">Nome: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></th>
      <td><input class="form_campo" type="text" value="" id="nomeSecretaria" name="nomeSecretaria" title="Nome da Secretaria" maxlength="60" />
        <br />
        <span class="legenda_bloco">Nome  da secretaria (o).</span></td>
      <td>CPF: </td>
      <td><input class="form_campo" type="text" value="" id="cpf" name="cpf" title="CPF da Secretaria" maxlength="60" />
        <br />
        <span class="legenda_bloco">CPF da secretaria (o).</span></td>
    </tr>

    <tr>
    <td colspan="4" align="right"><label for="nome">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input type="hidden" name="buscar" value="1" />
      <span class="btn_pos">
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Pesquisar Secretaria" />
      </span></td>
    </tr>
    
    
</tbody></table>
<?php
    if(@$mensagem==""){
        if(!empty($resultado)){
            echo "<table class=\"mostrarpesquisa\" width=100%><tr>
            <th width= '43%'>Nome da Secretaria (o)</th>
            <th width= '19%'>CPF</th>
            <th width= '19%'>RG</th>
            <th width= '19%'>A&ccedil;&otilde;es</th>
            </tr>
            ";    

            foreach(@$resultado as $dado){
                echo "<table class=\"mostrapesquisa\" width=100%><tr>";
                echo "<td width= '48%'><a href='menu_restrito.php?op=ver_secretaria&idSecretaria=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['nome']."</a></td>
                        <td width= '20%'><a href='menu_restrito.php?op=ver_secretaria&idSecretaria=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['cpf']."</a></td>
                        <td width= '21%'><a href='menu_restrito.php?op=ver_secretaria&idSecretaria=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['rg']."</a></td>
                        
                        
                <td>
                <a href='?op=add_secretaria&idSecretaria=".$dado['id']."&fun=ed'><i class='fa fa-edit' id='edit'></i></a>
                    
                </td>";
                echo "</tr></table>";
            }
        }
    }else{
        echo @$mensagem;
    }

    ?>
</td>
</tr>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> 
    
</tbody></table>

    </div>
</form>
</div>


    <?php }else{?>
      <div class="form_comp">
        Secretaria gravado com sucesso<br>
        <img src="images/alunos/<?php echo $novoNome;?>">
        Nome: <?= $nomeSecretaria?><br>
        CPF:  <?= $cpf?><br>
        RG: <?= $rg?><br>

      </div>

   <?php }?>