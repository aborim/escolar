<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css"/> 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>

<div class="titulo_interna">
<i class="fa fa-graduation-cap" aria-hidden="true"></i>Acad&ecirc;mico
</div>
<?php
include("conexao.php");
include("functions.php");
//busca os cursos para apresentar na listagem
$buscar=$con->prepare("
SELECT curso.nome, curso.descricao, curso.grau, curso.id FROM curso where 1");
  //$buscar->bindValue(":filial",$_SESSION['Unidade']);
  $buscar->execute();
  $resultado = $buscar->fetchAll(PDO::FETCH_ASSOC);
?>
<table id="lista_curso" class="display" style="width:75%">
    <thead>
        <tr>
            
            <th>Nome</th>
            <th>Descri&ccedil;&atilde;o</th>
            <th>Grau</th>
            
            <th>A&ccedil;&otilde;es</th>
        </tr>
    </thead>

    <?php
        foreach($resultado as $dado){
            switch(@$dado['grau']){
                case 1:
                    $grau = "Curso Berçário";
                break;
                case 2:
                    $grau = "Educação Infantil";
                break;
                
            }
            echo "<tr>";
                    echo "
                    <td><a href='menu_restrito.php?op=ver_curso&idCurso=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['nome']."</a></td>
                    <td><a href='menu_restrito.php?op=ver_curso&idCurso=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['descricao']."</a></td>
                    <td><a href='menu_restrito.php?op=ver_curso&idCurso=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['grau']."</a></td>
            <td>
            <a href='?op=add_curso&idCurso=".$dado['id']."&fun=ed'><i class='fa fa-edit' id='edit'></i></a>
            </td>";
            echo "</tr>";
        }
    ?>
</table>

<script>
$(document).ready(function() {
    $('#lista_curso').DataTable({
        "language": {
            "lengthMenu": "Mostrando _MENU_ registros por p&aacute;gina",
            "zeroRecords": "Nenhum registro encontrado",
            "info": "Mostrando _PAGE_ de _PAGES_ p&aacute;ginas",
            "infoEmpty": "Nenhum registro dispon&atilde;ï¿½vel",
            "infoFiltered": "(De um total de  _MAX_ registros)",
            "search":         "Buscar:",
            "paginate": {
                "first":      "Primeiro",
                "last":       "&uacute;ltimo",
                "next":       "Pr&oacute;ximo",
                "previous":   "Anterior"
            }
        }
    });
} );
</script>
