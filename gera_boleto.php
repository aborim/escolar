<?php
//inclui a conexao com banco de dados e fun&ccedil; &otilde;es
include('conexao.php');
include('functions.php');

if(isset($_REQUEST['boletos'])==1){
    $hoje = date("Y-m-d");
    $dtInicio = formatoDataBD($_REQUEST['dtInicio']);
    $dtFinal = formatoDataBD($_REQUEST['dtFinal']);

    
   if(( $dtInicio == $dtFinal ) && ( $dtInicio == $hoje ) && ( $dtFinal == $hoje )){
    $compQuery = " and dtProcessamento='".$hoje."'";
    $compVenc = " AND pagamentos.venc like '".substr($hoje,0,7)."%'";
      
   }elseif(( $dtInicio == $dtFinal ) && ( $dtInicio != $hoje ) && ( $dtFinal != $hoje )) {
    $compQuery = " and dtProcessamento='".$dtInicio."'";
    $compVenc = " AND pagamentos.venc like '".substr($dtInicio,0,7)."%'";
      
   }else{
    $compQuery = " and dtProcessamento between '".$dtInicio."' and '".$dtFinal."'";
    #$compVenc = " AND pagamentos.venc >= '".substr($dtInicio,0,7)."%' and pagamentos.venc <= '".substr($dtFinal,0,7)."%'";
    $compVenc = " AND pagamentos.venc >= '".$dtInicio."' and pagamentos.venc <= '".$dtFinal."'";
   }

   #busca o ultimo boleto inserido para verificar qual será o p´roximo "nosso numero" válido
   $busca_ultimo_boleto = $con->prepare("SELECT nosso_numero FROM boletos ORDER BY id DESC limit 1");
   $busca_ultimo_boleto->execute();
   $result_ultimo_boleto = $busca_ultimo_boleto->fetchAll(PDO::FETCH_ASSOC);
   #se o numero for menor que 400 assume o valor de 400+1 se for maior assumo o numero+1
   if($result_ultimo_boleto[0]['nosso_numero']<400){
        $nossoNumeroBoleto = 400;
   }else{
        $nossoNumeroBoleto = $result_ultimo_boleto[0]['nosso_numero']+1;
   }



  
    foreach ($_REQUEST['aluno'] as $idAluno) {
        
        #busca todos os pagamentos não inseridos na tabela boletos.
        $buscaPagamentos = $con->prepare("
        SELECT 
            aluno.id as idAluno,
            matriculas.rm, 
            pagamentos.*,
            classe.id as idClasse,
            classe.nome as nomeClasse,
            classe.anoVigente,
            classe_aluno.situacao
        FROM 
            aluno,matriculas,pagamentos,classe,classe_aluno 
        where 
            pagamentos.pg=0 and 
            matriculas.id= pagamentos.id_matricula and 
            aluno.rm=matriculas.rm and 
            aluno.id=:idAluno and
            pagamentos.tipo=:tipo and classe.id=classe_aluno.idClasse and classe_aluno.idAluno=aluno.id". $compVenc);
        $buscaPagamentos->execute(array(':idAluno'=>$idAluno,':tipo'=>$_REQUEST['tipo']));
        $result = $buscaPagamentos->fetchAll(PDO::FETCH_ASSOC);
        $pos =0;
        
        var_dump($result);
        exit;
        foreach ($result as $value) {
            
            
            $buscaBoleto = $con->prepare("select * from boletos where id_pagamento =:idPgto ");
            $buscaBoleto->execute(array(':idPgto'=>$value['id']));

            if($buscaBoleto->rowCount()!=0){
                $mensagem="Boletos já gerados";
                $cont_gerar_boletos = 0;
            }else{
                $cont_gerar_boletos = 1;
            }

            if(($hoje<$value['venc'])&&($cont_gerar_boletos==1)){
                #grava a geração dos boletos
                $nossoNumero =  $value['idAluno'].$value['num_parc'].substr($value['venc'],2,2);
                $insereBoleto = $con->prepare("insert into boletos (id_pagamento,nosso_numero,dv,dtProcessamento,impresso,identificador) values (:idPgto,:nosso_numero,:dv,:dtProcessamento,:impresso,:identificador)");
                $insereBoleto->execute(
                array(
                    ':idPgto'           =>$value['id'],
                    ':nosso_numero'     =>mb_str_pad($nossoNumeroBoleto, 7, '0', STR_PAD_LEFT),
                    ':dv'               =>geraDVboleto($nossoNumeroBoleto),
                    ':dtProcessamento'  =>date("Y-m-d"),
                    ':impresso'         =>0,
                    ':identificador'    =>$nossoNumero.$value['id']
                ));
                $status = "ok";
                $nossoNumeroBoleto += 1;
                echo "boleto ".$value['id']." gerado<br>";
            }else{
                $naoBoleto = 1;
            }   
        }

        if(@$naoBoleto ==1){
            $mensagem="Boletos não gerados, data de processamento maior que data de vencimento";
        }
        
    }
}


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
    $buscar=$con->prepare("SELECT 
                            aluno.id, 
                            aluno.imagem, 
                            aluno.nome, 
                            aluno.rm, 
                            aluno.ra, 
                            classe.nome as classe, 
                            classe.id as idClasse, 
                            classe_aluno.situacao 
                        FROM 
                            aluno, 
                            usuarios, 
                            classe, 
                            matriculas, 
                            classe_aluno 
                        where 
                            matriculas.id_classe = classe.id AND 
                            matriculas.id_aluno = aluno.id AND 
                            aluno.idUsuario = usuarios.id AND
                            aluno.id = classe_aluno.idAluno AND 
                            usuarios.filial = :filial AND 
                            usuarios.ativo=1 ".$comp);
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
  
<form method="post" action="#" enctype="multipart/form-data" id="frmAluno" >
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
    <!--<tr>
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
      <td><select name="turma" id="turma" onchange="carregarClasse(this.value)">
          
          </select>
        <br />
        </td>
        <th><label for="turma">Classe: </label></th>
      <td><select name="classe" id="classe" >
          
          </select>
        <br />
        </td>
    </tr>-->   
</tbody></table>

<?php
    if(@$mensagem==""){
        if(!empty($resultado)){
            echo "<table class=\"mostrapesquisa\" width=100%><tr>
            <th ></th>
            <th width='35%'>Nome do aluno</th>
            <th >RM</th>
            <th >Matriculado</th>
            
            <th width='15%'></th>
            
            
            </tr>
            ";

            foreach(@$resultado as $dado){
                if($dado['situacao'] == 5){
                    $checkdisabled = " disabled";
                    $strickedOP = "<s>";
                    $strickedCL = "</s> Desistente";
                }else{
                    $checkdisabled = " ";
                    $strickedOP = "";
                    $strickedCL = "";
                }

                echo "<tr>";
                echo "<tr><td ><input type='checkbox' name='aluno[]' value='".$dado['id']."' ".$checkdisabled."></td>
                        <td style='width: 273px'><a href='menu_restrito.php?op=ver_aluno&idAluno=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['nome']."</a></td>
                        <td style='width: 100px'><a href='menu_restrito.php?op=ver_aluno&idAluno=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['rm']."</a></td>
                        <!--<td style='width: 240px'><a href='menu_restrito.php?op=ver_aluno&idAluno=".$dado['idClasse']."&nome=".base64_encode($dado['nome'])."'>".$dado['classe']."</a></td>-->
                        <td style='width: 240px;font-size: 12px;'>$strickedOP".$dado['classe']."$strickedCL</td>
                        <td ></td>
                    </tr>
                    <tr>
        ";
                
            }
            echo "
            <th colspan='6'>Seleção de período.</th>
    </tr>
    <tr>
      <td colspan='4'><label>Data de processamento dos boletos: </label></td>
      <td colspan='2' style=\"
      font-size: 10px;
      width: 210px;
  \">Tipo:<select name=\"tipo\">
      <option value=2>Mensalidade</option>
      <option value=1>Matrícula</option>
      </select></td>
    </tr>
    <tr>
      <th>De: </th>
      <td><input type='text' name='dtInicio' id='dtInicio' value='' required style='border: solid 1px #ccc;' onchange=\"confereData(this.value,'dtInicio')\"/></td>
      <th>Até: </th>
      <td><input type='text' name='dtFinal' id='dtFinal' value='' required style='border: solid 1px #ccc;' onchange=\"confereData(this.value,'dtFinal')\"/></td>
      <td></td>
    </tr>
            </table>";
        }
    }else{
        echo @$mensagem;
    }

    ?>
</td>
</tr>
<tr>
    <td colspan="6" align="right"><label for="nome">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
      
      <span class="btn_pos">
      <?php if(isset($_POST['buscar'])!=1){?>  
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Pesquisar Aluno"/>
        <input type="hidden" name="buscar" value="1" />
      <?php }else{?>
        <input type="submit" name="btn_add_aluno" class="form_buttom" value="Gerar boleto"  onclick="return verificaAlunos()"/>
        <input type="hidden" name="boletos" value="1" />
      <?php }?>
      </span></td>
    </tr>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> 
    
</tbody></table>

    </div>
</form>
</div>


    <?php }else{?>
      <div class="form_comp">
        Boletos gerados com sucesso!<br>
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
        var classe = $("select#classe");
        classe.html("<option value='0'>Todas</option>");
        console.log(id);
        if (id > 0) {
            $.post("ws/lista_turma.php", "idCurso="+id, function( data ) {
                if(data!=""){
                    console.log(data);
                    
                    turmas.append("<option value='" + data.id + "'>" + data.nome + "</option>");
                }
            });
        }
    }
    function carregarClasse(id) {
        var msgErro = "O serviço está temporariamente indisponível. Tente novamente em alguns segundos";
        var classe = $("select#classe");
        classe.html("<option value='0'>Todas</option>");
        console.log(id);
        if (id > 0) {
            $.post("ws/lista_classe.php", "idTurma="+id, function( data ) {
                if(data!=""){
                    console.log(data);
                    
                    classe.append("<option value='" + data.id + "'>" + data.nome + "</option>");
                }
            });
        }
    }

    function confereData(data,campo){
        var data_1 = document.getElementById('dtInicio').value.split('/').reverse();
        var data_2 = document.getElementById('dtFinal').value.split('/').reverse();
        
        var data_inicio = new Date(data_1[0]+"-"+data_1[1]+"-"+data_1[2]);
        var data_final = new Date(data_2[0]+"-"+data_2[1]+"-"+data_2[2]);
        
        if (data_inicio > data_final) {
            alert("Data inicial não pode ser maior que a data final");
            document.getElementById('dtFinal').value="";
            //return false;
        } else {
            return true
        }

    }

    function verificaAlunos(){
        var checkboxes = document.getElementsByName('aluno[]');
        var selected = [];
        var selecionado =0;
        for (var i=0; i<checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                selecionado++;
            }
        }
        if(selecionado>0){
           
            return true;
        }else{
            alert('Selecione os alunos desejados');
            return false;
        }
    }

    
    $(document).ready(function() {
        $('#dtInicio, #dtFinal').datepicker({dateFormat: 'dd/mm/yy'});
    });

</script>