<?php

//modulo de cadastro de diretor, arquivo inserido ao menu restrito atrav &eacute;s de include
if(isset($_POST['buscar'])==1){

//inclui a conexao com banco de dados e fun&ccedil; &otilde;es
include('conexao.php');
include('functions.php');
//armazena os dados das vari&aacute;veis do formul&aacute;rio
if(($_POST['nomeDiretor']!="")&&($_POST['cpf']=="")){
    $comp = "AND diretor.nome like :nomeDiretor";
}else if(($_POST['nomeDiretor']=="")&&($_POST['cpf']!="")){
    $comp = "AND diretor.cpf = :cpf";
}else{
    $comp = "AND diretor.nome like :nomeDiretor AND diretor.cpf = :cpf";
}

if(($_POST['nomeDiretor']!="")||($_POST['cpf']!="")){
    $buscar=$con->prepare("
    SELECT 
        diretor.id,
        diretor.nome,
        diretor.cpf,
        diretor.email
    FROM 
        diretor,usuarios 
    WHERE 
        diretor.idUsuario=usuarios.id and usuarios.filial= :filial and usuarios.ativo=1 ".$comp);
        #':search', '%' . $search . '%'
  $buscar->bindValue(":filial",$_SESSION['Unidade']);
  
  if(($_POST['nomeDiretor']!="")&&($_POST['cpf']=="")){
     $buscar->bindValue(":nomeDiretor",'%' . $_POST['nomeDiretor'] . '%');
  }
  
  if(($_POST['nomeDiretor']=="")&&($_POST['cpf']!="")){
    $buscar->bindValue(":cpf",$_POST['cpf']);
  }

  if(($_POST['nomeDiretor']!="")&&($_POST['cpf']!="")){
    $buscar->bindValue(":cpf",$_POST['cpf']);
    $buscar->bindValue(":nomeDiretor",'%' . $_POST['nomeDiretor'] . '%');
  }


  $buscar->execute();
  $resultado = $buscar->fetchAll(PDO::FETCH_ASSOC);
  if($buscar->rowCount()==0){$mensagem="Nenhum Diretor encontrado";}
}else{

$mensagem = "Nenhum dado selecionado";
}

}
?>

<div class="titulo_interna">
    <i class="fa fa-users" aria-hidden="true"></i>Pessoas
</div>
<div class="content_form">
    
    <?php
    if(!isset($status)){?>
<form method="post" action="#" enctype="multipart/form-data" id="frmAluno">
    <div class="form_comp">
    

<table class="formulario">
<div class="titulo">Pesquisar Diretor</div>
<tbody>
    <tr><td colspan="101"></td></tr>

    <tr>
      <th><label for="nome">Nome: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></th>
      <td><input class="form_campo" type="text" value="" id="nomeDiretor" name="nomeDiretor" title="Nome do Respons&aacute;vel" maxlength="60" />
        <br />
        <span class="legenda_bloco">Nome  do diretor.</span></td>
      <td>CPF: </td>
      <td><input class="form_campo" type="text" value="" id="cpf" name="cpf" title="CPF do Respons&aacute;vel" maxlength="60" />
        <br />
        <span class="legenda_bloco">CPF do diretor.</span></td>
    </tr>

    <tr>
    <td colspan="4" align="right"><label for="nome">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input type="hidden" name="buscar" value="1" />
      <span class="btn_pos">
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Pesquisar Diretor" />
      </span></td>
    </tr>
    
    
</tbody></table>
<?php
    if(@$mensagem==""){
        if(!empty($resultado)){
            echo "<table class=\"mostrapesquisa\" width=100%><tr>
            <th width='43%'>Nome</th>
            <th width='19%'>CPF</th>
            <th width='19%'>E-mail</th>
            <th width='19%'>A&ccedil;&otilde;es</th>        
            </tr>
            ";
            foreach(@$resultado as $dado){
                echo "<table class=\"mostrapesquisa\" width=100%><tr>";
                echo "<td width='48%'><a href='menu_restrito.php?op=ver_diretor&idDiretor=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['nome']."</a></td>
                        <td width='16%'><a href='menu_restrito.php?op=ver_diretor&idDiretor=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['cpf']."</a></td>
                        <td width='25%'><a href='menu_restrito.php?op=ver_diretor&idDiretor=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['email']."</a></td>
                        
                <td>
                <a href='?op=add_diretor&idDiretor=".$dado['id']."&fun=ed'><i class='fa fa-edit' id='edit'></i></a>
                    
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
        Aluno gravado com sucesso<br>
        <img src="images/alunos/<?php echo $novoNome;?>">
        Nome: <?= $nomeAluno?><br>
        RM:  <?= $rm?><br>
        RA: <?= $ra?><br>

      </div>

   <?php }?>