<?php
//inclui a conexao com banco de dados e fun&ccedil; &otilde;es
include('conexao.php');
include('functions.php');
if($_REQUEST['acao']=='pesquisar'){
    
    if($_REQUEST['nome']!="" || $_REQUEST['rm']!=""){
        
        //armazena os dados das vari&aacute;veis do formul&aacute;rio
        if(($_POST['nome']!="")&&($_POST['rm']=="")){
            $comp = "AND aluno.nome like :nomeAluno and aluno.id = matriculas.id_aluno ";
        }else if(($_POST['nomeAluno']=="")&&($_POST['rm']!="")){
            $comp = "AND aluno.rm = :rm and aluno.id = matriculas.id_aluno ";
        }else{
            $comp = "AND aluno.nome like :nomeAluno AND aluno.rm = :rm and aluno.id = matriculas.id_aluno ";
        }
    
        if(($_POST['nome']!="")||($_POST['rm']!="")){
            $buscar=$con->prepare("
            SELECT aluno.id as idAluno,
            aluno.imagem,
            aluno.nome,
            aluno.rm,
            aluno.ra,
            matriculas.*
        FROM 
            aluno,
            usuarios,
            matriculas
        where   
            aluno.idUsuario = usuarios.id and 
            usuarios.filial = :filial and usuarios.ativo=1 ".$comp);
            #':search', '%' . $search . '%'
        $buscar->bindValue(":filial",$_SESSION['Unidade']);
        
        if(($_POST['nome']!="")&&($_POST['rm']=="")){
            $buscar->bindValue(":nomeAluno",'%' . $_POST['nome'] . '%');
        }
        
        if(($_POST['nome']=="")&&($_POST['rm']!="")){
            $buscar->bindValue(":rm",$_POST['rm']);
        }
        
        if(($_POST['nome']!="")&&($_POST['rm']!="")){
            $buscar->bindValue(":rm",$_POST['rm']);
            $buscar->bindValue(":nomeAluno",'%' . $_POST['nome'] . '%');
        }
    
        
        $buscar->execute();
        $resultado = $buscar->fetchAll(PDO::FETCH_ASSOC);

        
        if($buscar->rowCount()==0){
            $mensagem="Nenhuma matrícula localizada";}
        }else{
    
            $mensagem = "nenhum dado selecionado";
        }
    }
    elseif($_REQUEST['curso']!="" || $_REQUEST['turma']!="" || $_REQUEST['classe']!=""){
        
        $buscaAlunos = $con->prepare("SELECT aluno.id as idAluno,
        aluno.nome as nomeAluno,
        aluno.rm as rmAluno,
        aluno.ra as raAluno,
        classe.nome as classeNome,
        matriculas.id as matId
        
    FROM 
        aluno,
        usuarios,
        classe_aluno,
        classe,
        matriculas
    where   
        aluno.idUsuario = usuarios.id and 
        usuarios.filial = :idFilial and usuarios.ativo=1 AND
        aluno.id = classe_aluno.idAluno and 
        classe.id = classe_aluno.idClasse and 
        classe.id=:idClasse and matriculas.id_aluno = aluno.id");

        

        $buscaAlunos->execute(array(
            ":idFilial"   => $_SESSION['Unidade'],
            ":idClasse" => $_POST['classe']
        ));
        $resultadoAluno = $buscaAlunos->fetchAll(PDO::FETCH_ASSOC);
        

    }
    
}
?>

<div class="titulo_interna"><i class="fa fa-file-text" aria-hidden="true"></i>Relatorios</div>
<div class="clearfix"></div>
<div class="content_form">
<div class="titulo">Parcelas pagas</div>
<div id="conteudo">
    <form method="POST" action="#" id="frmGeraImprime" enctype="multipart/form-data" novalidate="novalidate">
        <hr>
        <div>
            Nome do aluno: <input type="text" value="" id="nome" name="nome" maxlength="60" style="border: solid 1px #999;">&nbsp;&nbsp;
            ou RM: <input type="text" value="" id="rm" name="rm" maxlength="15" style="border: solid 1px #999;">
        </div>
        <hr>
        <div style="padding-bottom:10px;">
            Filtros adicionais:
        </div>
        <table>
            <tbody><tr>
                <td>
                    Curso:					
                    <select id="curso" name="curso" title="Curso" onchange="carregarTurmas(this.value)">
                        <option value="0">Todos</option>
                        <?php
                        #busca os cursos
                        $queryCurso = $con->prepare("select id, nome from curso order by nome asc");
                        $queryCurso->execute();
                        $resCurso = $queryCurso->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($resCurso as $curso) {
                            echo "<option value='".$curso['id']."'>".$curso['nome']."</option>";
                        }
                        
                        ?>
                        
                </td>
                <td>
                    Turma: 
                    <select id="turma" name="turma" title="Turma" onchange="carregarClasses(this.value)">
                        
                                            </select>
                </td>
                <td>
                    Classe: 
                    <select id="classe" name="classe" title="Classe">
                        
                                            </select><input type='hidden' name='acao' value='pesquisar'><input type='submit' value='Pesquisar' class='cancel'>
                </td>
               
            </tr>
            <tr><td colspan="99">&nbsp;</td></tr>
            <tr>
                <td colspan="99">
                
                    
                </td>
            </tr>
        </tbody></table> 
        
    


    <?php 
    if($resultado !=""){
        echo "<p>&nbsp;</p>";
        echo "<table width='100%'>";
        echo "<tr style='background:#999'>
        <td align='center'><input type='checkbox' name='selAll' id='selAll' value='1'></td>
        <td align='center'>Nome</td>
        <td align='center'>RM</td>
        <td align='center'>RA</td>
        
        </tr>";
        
        foreach($resultado as $aluno){
            
            echo "<tr style='background:#e9e9e9'>
            
            <td align='center'><input type='checkbox' name='alunos[]' class='alunos' value='".$aluno['idAluno']."'></td>
            <td align='center' style='font-size: 12px;'>".$aluno['nome']."</td>
            <td align='center' style='font-size: 12px;'>".$aluno['rm']."</td>
            <td align='center' style='font-size: 12px;'>".$aluno['ra']."</td>
        
            </tr>";
        }

        echo "
        <link rel='stylesheet' href='https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'>
                    <link rel='stylesheet' href='css/datepick.css'>
                    <script src='https://code.jquery.com/jquery-1.12.4.js'></script>
                    <script src='https://code.jquery.com/ui/1.12.1/jquery-ui.js'></script>
                    <script>
                    $( function() {
                        $( '#dtInicio' ).datepicker({dateFormat: 'dd/mm/yy'});
                        $( '#dtFim' ).datepicker({dateFormat: 'dd/mm/yy'});
                    } );
                    </script>
                    <table class='formulario'>
                        <tbody>
                            <tr>
                            <td>
                                Período  
                            </td>
                            <td>
                                <input type='text' name='dtInicio' id='dtInicio' style='width: 100px;border: solid 1px #999;'>
                                à 
                                <input type='text' name='dtFinal' id='dtFim' style='width: 100px;border: solid 1px #999;'>
                                &nbsp;&nbsp;
                            </td>
                        </tr>
                    </tbody></table>
        <tr><td><input type='button' id='btImprimir' value='Imprimir' class='valid' aria-invalid='false'></td></tr>
        </table>
        <input type='hidden' name='imprime' value='1'>
        ";
    }elseif($resultadoAluno!=""){
        echo "<p>&nbsp;</p>";
        echo "<table width='100%'>";
        echo "<tr style='background:#999'>
        <td align='center'><input type='checkbox' name='selAll' id='selAll' value='1'></td>
        <td align='center'>Nome</td>
        <td align='center'>RM</td>
        <td align='center'>RA</td>
        
        </tr>";
        echo "<tr><td colspan='4'>".$resultadoAluno[0]['classeNome']."</td></tr>";
        foreach($resultadoAluno as $aluno){
            echo "<tr style='background:#e9e9e9'>
            <td align='center'><input type='checkbox' name='alunos[]' class='alunos' value='".$aluno['idAluno']."'></td>
            <td align='center' style='font-size: 12px;'>".$aluno['nomeAluno']."</td>
            <td align='center' style='font-size: 12px;'>".$aluno['rmAluno']."</td>
            <td align='center' style='font-size: 12px;'>".$aluno['raAluno']."</td>
        
            </tr>";
        }

        echo "
        <tr><td><input type='button' id='btImprimir' value='Imprimir' class='valid' aria-invalid='false'></td></tr>
        </table>
        <input type='hidden' name='imprime' value='1'>
        ";
    }
    
    

    ?></form>
</div>

<script type="text/javascript">
    function carregarTurmas(id) {
        var msgErro = "Não foi possível carregar os dados";
        var turmas = $("select#turma");
        turmas.html("<option value='0'>Todas</option>");

        if (id > 0) {
            
            $.ajax({
                type: "GET", url: "ws/lista_turma.php?idCurso=" + id,
                contentType: "application/json;",
                dataType: "JSON",
                success: function (msg) {
                    
                    var len = Object.keys(msg).length;
                    var turma = []; // = new Array();
                    turma.push(msg);
                    for (var i = 0; i < len; i++) {
                       turmas.append("<option value='" + turma[i].id + "'>" + turma[i].nome + "</option>");
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(msgErro);
                }
            });
        }
    }

    function carregarClasses(id) {
        var classes = $("select#classe");
        classes.html("<option value='0'>Todas</option>");

        if (id > 0) {
            
            $.ajax({
                type: "GET", url: "ws/lista_classe.php?idTurma=" + id,
                contentType: "application/json;",
                dataType: "JSON",
                success: function (msg) {
                    
                    var len = Object.keys(msg).length;
                    var classe = []; // = new Array();
                    classe.push(msg);
                    for (var i = 0; i < len; i++) {
                       classes.append("<option value='" + classe[i].id + "'>" + classe[i].nome + "</option>");
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(msgErro);
                }
            });
        }
    }

    $('#selAll').click(function () {
        if ($(this).is(':checked')) {
            $('.alunos').each(function () {
                $(this).attr('checked', 'checked');
            });
        }
        else {
            $('.alunos').each(function () {
                $(this).removeAttr('checked');
            });
        }
    });


    $('#btImprimir').click(function (e) {
        var elemento = document.getElementById('frmGeraImprime');
        elemento.action = "imprimir.php?parcelas_pagas=1";
        elemento.target = "_blank";

        if ($('#frmGeraImprime').valid()) {
            elemento.submit();
        }
    });


    $(document).ready(function () {
        $('#dtInicio, #dtFim').datepicker();

        $('#frmGeraImprime').validate({
            rules: {
                conta: {required: false},
                dtInicio: {required: true},
                dtFinal: {required: true}
            },
            messages: {
                conta: {required: ""},
                dtInicio: {required: " <span style='color:red'>Selecione uma data inicial</span>"},
                dtFinal: {required: " <span style='color:red'>Selecione uma data final</span>"}
            }
        });
    })

</script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.js"></script>