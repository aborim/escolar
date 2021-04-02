<?php
//inclui a conexao com banco de dados e fun&ccedil; &otilde;es
include('conexao.php');
include('functions.php');
//modulo de cadastro de alunos, arquivo inserido ao menu restrito atrav &eacute;s de include
if(isset($_POST['buscar'])==1){


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
    aluno.ra,
    classe.nome as classe
FROM 
    aluno,
    usuarios,
    classe,
    matriculas
where
    
    matriculas.id_classe = classe.id AND
    matriculas.id_aluno = aluno.id AND
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
    <div class="form_comp" style="width:100%;">
    <div class="titulo">Pesquisar Aluno</div>

<table class="formulario" style="width:100%;">
<tbody >
    <tr><td colspan="101"></td></tr>

    <tr >
      <th><label for="nome">Nome: </label></th>
      <td><input type="text" value="" id="nomeAluno" name="nomeAluno" title="Nome do Aluno" maxlength="60"  style="border: solid 1px #ccc;"/>
        <br />
        <span class="legenda_bloco">Nome  do aluno.</span></td>
      <th><label for="rm">RM: </label></th>
      <td colspan="3"><input type="text" value="" id="rm" name="rm" title="RM do Aluno" maxlength="60" style="border: solid 1px #ccc;" />
        <br />
        <span class="legenda_bloco">RM do aluno.</span></td>
    </tr>
    <tr>
        <th colspan="6">Filtros Adicionais.</th>
    </tr>
    <tr >
        <?php 
        $buscaCurso = $con->prepare("select id, nome from curso order by nome asc");
        $buscaCurso->execute();
        $dadosCurso = $buscaCurso->fetchAll(PDO::FETCH_ASSOC);
        ?>
      <th><label for="nome">Curso:</label></th>
      <td><select name="curso" onchange="carregarTurmas(this.value)">
          <option value="">selecione um curso</option>
          <option value="0">Todos</option>
          <?php foreach($dadosCurso as $curso){?>
            <option value="<?=$curso['id']?>"><?=$curso['nome']?></option>
          <?php }?>
      </select>
        <br />
        </td>
      <th><label for="turma">Turma: </label></th>
      <td><select name="turma" id="turma" onchange="carregarClasses(this.value)">
          
          </select>
        <br />
        </td>
        <th><label for="turma">Classe: </label></th>
      <td><select name="classe" id="classe" >
          
          </select>
        <br />
        </td>
    </tr>
    <tr>
        <th colspan="6">Seleção de período.</th>
    </tr>
    <tr>
      <td colspan="6"><label>Data de processamento dos boletos: </label></td>
    </tr>
    <tr>
      <th>De: </th>
      <td colspan="2"><input type="text" name="dtInicio" id="dtInicio" value="" style="border: solid 1px #ccc;"/></td>
      <th>Até: </th>
      <td colspan="2"><input type="text" name="dtFim" id="dtFinal" value="" style="border: solid 1px #ccc;"/></td>
    </tr>

    <tr>
    <td colspan="6" align="right"><label for="nome">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
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
            <th width='35%'>Nome do aluno</th>
            <th >RM</th>
            <th >Matriculado</th>
            
            <th width='15%'>A&ccedil;&otilde;es</th>
            
            
            </tr>
            ";

            foreach(@$resultado as $dado){
                echo "<table class=\"mostrapesquisa\" width=100%><tr>";
                echo "<tr><td >"."<img src='".$dado['imagem']."' style='width: 70px'>"."</td>
                        <td style='width: 273px'><a href='menu_restrito.php?op=ver_aluno&idAluno=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['nome']."</a></td>
                        <td style='width: 100px'><a href='menu_restrito.php?op=ver_aluno&idAluno=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['rm']."</a></td>
                        <td style='width: 240px'><a href='menu_restrito.php?op=ver_aluno&idAluno=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['classe']."</a></td>
                        
                <td ><a href='menu_restrito.php?op=lista_boleto&idAluno=".$dado['id']."'><i class='fa fa-money' id='boleto' style='width: 10'></i>Imprimir Boletos</a>
                
                    
                   
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
   <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="/escolar/css/datepick.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   <script type="text/javascript">
    function carregarTurmas(id) {
        var msgErro = "O serviço está temporariamente indisponível. Tente novamente em alguns segundos";
        var turmas = $("select#turma");
        turmas.html("<option value='0'>Todas</option>");
        console.log(id);
        if (id > 0) {
            
            
            $.post("ws/lista_turma.php", "idCurso="+id, function( data ) {
            if(data!=""){
                console.log(data);
                
            }else{

            }
        });
    
        }
    }


    /*function carregarClasses(id) {
        var msgErro = "O serviço está temporariamente indisponível. Tente novamente em alguns segundos";
        var turmas = $("select#classe");
        turmas.html("<option value='0'>Todas</option>");

        if (id > 0) {
            $("td#iconeCarregando").show();
            $.ajax({
                type: "GET",
                url: path + "app/util/servicos/classe.php?idTurma=" + id + "&path=" + path,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                timeout: 5000,
                async: false,
                success: function(msg) {
                    for (var i = 0; i < msg.length; i++) {
                        turmas.append("<option value='" + msg[i].id + "'>" + msg[i].nome + "</option>");
                    }

                    $("td#iconeCarregando").hide();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("td#iconeCarregando").hide();
                    alert(msgErro);
                }
            });
        }
    }

    $('#selAll').click(function() {
        if ($(this).is(':checked')) {
            $('.alunos').each(function() {
                $(this).attr('checked', 'checked');
            });
        }
        else {
            $('.alunos').each(function() {
                $(this).removeAttr('checked');
            });
        }
    });


    $('#btGera').click(function(e) {
        var elemento = document.getElementById('frmGeraImprime');
        elemento.action = "gerar.php";
        elemento.target = "_self";

        if ($('#frmGeraImprime').valid()) {
            elemento.submit();
        }
    });
    
    $('#btGeraSlip').click(function(e){
        var elemento = document.getElementById('frmGeraImprime');
        elemento.action = "gerar.php?slip=1?dsc=" + document.getElementById('dsc').value;
        elemento.target = "_self";

        if ($('#frmGeraImprime').valid()) {
            elemento.submit();
        }
    });
*/
    
    $(document).ready(function() {
        $('#dtInicio, #dtFinal').datepicker();

        $('#frmGeraImprime').validate({
            rules: {
                conta: {required: true},
                dtInicio: {required: true},
                dtFinal: {required: true}
            },
            messages: {
                conta: {required: ""},
                dtInicio: {required: " Selecione uma data inicial"},
                dtFinal: {required: " Selecione uma data final"}
            }
        });
    });

</script>