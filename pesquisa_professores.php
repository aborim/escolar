<?php

//modulo de cadastro de professor, arquivo inserido ao menu restrito atrav &eacute;s de include
if(isset($_POST['buscar'])==1){

//inclui a conexao com banco de dados e fun&ccedil; &otilde;es
include('conexao.php');
include('functions.php');
//armazena os dados das vari&aacute;veis do formul&aacute;rio
if(($_POST['nomeProfessor']!="")&&($_POST['cpf']=="")){
    $comp = "AND professor.nome like :nomeProfessor";
}else if(($_POST['nomeProfessor']=="")&&($_POST['cpf']!="")){
    $comp = "AND professor.cpf = :cpf";
}else{
    $comp = "AND professor.nome like :nomeProfessor AND professor.cpf = :cpf";
}

if(($_POST['nomeProfessor']!="")||($_POST['cpf']!="")){
    $buscar=$con->prepare("
    SELECT 
        professor.id,
        professor.nome,
        professor.cpf,
        professor.email
    FROM 
        professor,usuarios 
    WHERE 
        professor.idUsuario=usuarios.id and usuarios.filial= :filial and usuarios.ativo=1 ".$comp);
        #':search', '%' . $search . '%'
  $buscar->bindValue(":filial",$_SESSION['Unidade']);
  
  if(($_POST['nomeProfessor']!="")&&($_POST['cpf']=="")){
     $buscar->bindValue(":nomeProfessor",'%' . $_POST['nomeProfessor'] . '%');
  }
  
  if(($_POST['nomeProfessor']=="")&&($_POST['cpf']!="")){
    $buscar->bindValue(":cpf",$_POST['cpf']);
  }

  if(($_POST['nomeProfessor']!="")&&($_POST['cpf']!="")){
    $buscar->bindValue(":cpf",$_POST['cpf']);
    $buscar->bindValue(":nomeProfessor",'%' . $_POST['nomeProfessor'] . '%');
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
<div class="titulo">Pesquisar Professor</div>
<tbody>
    <tr><td colspan="101"></td></tr>

    <tr>
      <th><label for="nome">Nome: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></th>
      <td><input class="form_campo" type="text" value="" id="nomeProfessor" name="nomeProfessor" title="Nome do Professor" maxlength="60" />
        <br />
        <span class="legenda_bloco">Nome  do professor.</span></td>
      <td>CPF: </td>
      <td><input class="form_campo" type="text" value="" id="cpf" name="cpf" title="CPF do Professor" maxlength="60" />
        <br />
        <span class="legenda_bloco">CPF do professor.</span></td>
    </tr>

    <tr>
    <td colspan="4" align="right"><label for="nome">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input type="hidden" name="buscar" value="1" />
      <span class="btn_pos">
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Pesquisar Professor" />
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
                echo "<td width='50%'><a href='menu_restrito.php?op=ver_professor&idProfessor=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['nome']."</a></td>
                        <td width='15%'><a href='menu_restrito.php?op=ver_professor&idProfessor=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['cpf']."</a></td>
                        <td width='25%'><a href='menu_restrito.php?op=ver_professor&idProfessor=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['email']."</a></td>
                        
                <td >
                <a href='?op=add_professores&idProfessor=".$dado['id']."&fun=ed'><i class='fa fa-edit' id='edit'></i></a>
                    
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
        Professor gravado com sucesso<br>
        <img src="images/alunos/<?php echo $novoNome;?>">
        Nome: <?= $nomeProfessor?><br>
        CPF: <?= $cpf?>

      </div>

   <?php }?>