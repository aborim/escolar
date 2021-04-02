<?php

//modulo de cadastro de alunos, arquivo inserido ao menu restrito atrav &eacute;s de include
if(isset($_POST['buscar'])==1){

//inclui a conexao com banco de dados e fun&ccedil; &otilde;es
include('conexao.php');
include('functions.php');
//armazena os dados das vari&aacute;veis do formul&aacute;rio
if(($_POST['nomeResponsavel']!="")&&($_POST['cpf']=="")){
    $comp = "AND responsavel.nome like :nomeResponsavel";
}else if(($_POST['nomeResponsavel']=="")&&($_POST['cpf']!="")){
    $comp = "AND responsavel.cpf = :cpf";
}else{
    $comp = "AND responsavel.nome like :nomeResponsavel AND responsavel.cpf = :cpf";
}

if(($_POST['nomeResponsavel']!="")||($_POST['cpf']!="")){
    $buscar=$con->prepare("
    SELECT 
        responsavel.id,
        responsavel.nome,
        responsavel.cpf,
        responsavel.email,
        responsavel.grauParentesco 
    FROM 
        responsavel,usuarios 
    WHERE 
        responsavel.idUsuario=usuarios.id and usuarios.filial= :filial and usuarios.ativo=1 ".$comp);
        #':search', '%' . $search . '%'
  $buscar->bindValue(":filial",$_SESSION['Unidade']);
  
  if(($_POST['nomeResponsavel']!="")&&($_POST['cpf']=="")){
     $buscar->bindValue(":nomeResponsavel",'%' . $_POST['nomeResponsavel'] . '%');
  }
  
  if(($_POST['nomeResponsavel']=="")&&($_POST['cpf']!="")){
    $buscar->bindValue(":cpf",$_POST['cpf']);
  }

  if(($_POST['nomeResponsavel']!="")&&($_POST['cpf']!="")){
    $buscar->bindValue(":cpf",$_POST['cpf']);
    $buscar->bindValue(":nomeResponsavel",'%' . $_POST['nomeResponsavel'] . '%');
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
    
    <?php
    if(!isset($status)){?>
<form method="post" action="#" enctype="multipart/form-data" id="frmAluno">
    <div class="form_comp">
    

<table class="formulario">
<div class="titulo">Pesquisar Respons&aacute;vel</div>
<tbody>
    <tr><td colspan="101"></td></tr>

    <tr>
      <th><label for="nome">Nome: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></th>
      <td><input class="form_campo" type="text" value="" id="nomeResponsavel" name="nomeResponsavel" title="Nome do Respons&aacute;vel" maxlength="60" />
        <br />
        <span class="legenda_bloco">Nome  do respons&aacute;vel.</span></td>
      <td>CPF: </td>
      <td><input class="form_campo" type="text" value="" id="cpf" name="cpf" title="CPF do Respons&aacute;vel" maxlength="60" />
        <br />
        <span class="legenda_bloco">CPF do respons&aacute;vel.</span></td>
    </tr>

    <tr>
    <td colspan="4" align="right"><label for="nome">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input type="hidden" name="buscar" value="1" />
      <span class="btn_pos">
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Pesquisar Respons&aacute;vel" />
      </span></td>
    </tr>
    
    
</tbody></table>
<?php
    if(@$mensagem==""){
        if(!empty($resultado)){
            echo "<table class=\"mostrarpesquisa\" width=100%><tr>
            <th width='38%'>Nome da(o) Respons&aacute;vel (o)</th>
            <th width='8%'>CPF</th>
            <th width='23%'>E-mail</th>
            <th width='12%'>Grau de parentesco</th>
            <th >A&ccedil;&otilde;es</th>
            </tr>
            ";
            foreach(@$resultado as $dado){
                echo "<table class=\"mostrapesquisa\" width=100%><tr>";
                echo "<td style='width: 39%'><a href='menu_restrito.php?op=ver_responsavel&idResponsavel=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['nome']."</a></td>
                        <td style='width: 12%'><a href='menu_restrito.php?op=ver_responsavel&idResponsavel=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['cpf']."</a></td>
                        <td style='width: 23%'><a href='menu_restrito.php?op=ver_responsavel&idResponsavel=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['email']."</a></td>
                        <td style='width: 16%' ><a href='menu_restrito.php?op=ver_responsavel&idResponsavel=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['grauParentesco']."</a></td>
                <td >
                <a href='?op=add_responsaveis&idResponsavel=".$dado['id']."&fun=ed'><i class='fa fa-edit' id='edit'></i></a>
                    
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
        Respons&aacute;vel gravado com sucesso<br>
        <img src="images/alunos/<?php echo $novoNome;?>">
        Nome: <?= $nomeResponsavel?><br>
        CPF:  <?= $cpf?><br>
       

      </div>

   <?php }?>