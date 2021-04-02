<?php

//modulo de cadastro de alunos, arquivo inserido ao menu restrito atrav &eacute;s de include
if(isset($_POST['buscar'])==1){

//inclui a conexao com banco de dados e fun&ccedil; &otilde;es
include('conexao.php');
include('functions.php');
//armazena os dados das vari&aacute;veis do formul&aacute;rio
if(($_POST['nomeAluno']!="")&&($_POST['rm']=="")){
    $comp = "AND aluno.nome like :nomeAluno";
}else if(($_POST['nomeAluno']=="")&&($_POST['rm']!="")){
    $comp = "AND aluno.rm = :rm";
}else{
    $comp = "AND aluno.nome like :nomeAluno AND aluno.rm = :rm";
}

if(($_POST['nomeAluno']!="")||($_POST['rm']!="")){
    $buscar=$con->prepare("
SELECT aluno.id,
        aluno.imagem,
        aluno.nome,
        aluno.rm,
        aluno.ra 
    FROM 
        aluno,
        usuarios 
    where   
        aluno.idUsuario = usuarios.id and 
        usuarios.filial = :filial and usuarios.ativo=1 ".$comp);
        #':search', '%' . $search . '%'
  $buscar->bindValue(":filial",$_SESSION['Unidade']);
  
  if(($_POST['nomeAluno']!="")&&($_POST['rm']=="")){
     $buscar->bindValue(":nomeAluno",'%' . $_POST['nomeAluno'] . '%');
  }
  
  if(($_POST['nomeAluno']=="")&&($_POST['rm']!="")){
    $buscar->bindValue(":rm",$_POST['rm']);
  }

  if(($_POST['nomeAluno']!="")&&($_POST['rm']!="")){
    $buscar->bindValue(":rm",$_POST['rm']);
    $buscar->bindValue(":nomeAluno",'%' . $_POST['nomeAluno'] . '%');
  }


  $buscar->execute();
  $resultado = $buscar->fetchAll(PDO::FETCH_ASSOC);
  if($buscar->rowCount()==0){$mensagem="Nenhum aluno encontrado";}
}else{

$mensagem = "nenhum dado selecionado";
}

}
?>

<div class="titulo_interna">
    <i class="fa fa-money" aria-hidden="true"></i>Financeiro
</div>
<div class="content_form">
    
    <?php
    if(!isset($status)){?>
<form method="post" action="#" enctype="multipart/form-data" id="frmAluno">
    <div class="form_comp">
    <div class="titulo">Pesquisar Aluno - Listagem de boletos gerados</div>

<table class="formulario">
<tbody >
    <tr><td colspan="101"></td></tr>

    <tr >
      <th><label for="nome">Nome: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></th>
      <td><input class="form_campo" type="text" value="" id="nomeAluno" name="nomeAluno" title="Nome do Aluno" maxlength="60" />
        <br />
        <span class="legenda_bloco">Nome  do aluno.</span></td>
      <td>RM: </td>
      <td><input class="form_campo" type="text" value="" id="rm" name="rm" title="RM do Aluno" maxlength="60" />
        <br />
        <span class="legenda_bloco">RM do aluno.</span></td>
    </tr>

    <tr>
    <td colspan="4" align="right"><label for="nome">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input type="hidden" name="buscar" value="1" />
      <span class="btn_pos">
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Pesquisar Aluno" />
      </span></td>
    </tr>
    
    
</tbody></table>
<?php
    if(@$mensagem==""){
        if(!empty($resultado)){
            echo "<table class=\"mostrapesquisa\" width=100%><tr>
            <th ></th>
            <th width='55%'>Nome do aluno</th>
            <th width='12%'>RA</th>
            <th width='12%'>RM</th>
            
            <th width='15%'>A&ccedil;&otilde;es</th>
            
            
            </tr>
            ";

            foreach(@$resultado as $dado){
                
                echo "<tr><td >"."<img src='".$dado['imagem']."' style='width: 70px'>"."</td>
                        <td style='width: 432px'><a href='menu_restrito.php?op=ver_aluno&idAluno=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['nome']."</a></td>
                        <td style='width: 100px'><a href='menu_restrito.php?op=ver_aluno&idAluno=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['ra']."</a></td>
                        <td style='width: 100px'><a href='menu_restrito.php?op=ver_aluno&idAluno=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['rm']."</a></td>
                        
                <td ><a href='?op=add_responsaveis&idResponsavel=".$dado['id']."&fun=ed'>
                    <a href='?op=lista_boleto&idAluno=".$dado['id']."'><i class='fa fa-money' id='edit'style='width: 10'></i></a>
                    
                   
                </td></tr>";
                
            }
            echo "</table>";
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