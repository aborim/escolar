<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css"/> 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>

<div class="titulo_interna">
<i class="fa fa-graduation-cap" aria-hidden="true"></i>Acad&ecirc;mico
</div>
<?php
include("conexao.php");
include("functions.php");
//busca os cursos para apresentar na listagem
$buscar=$con->prepare("SELECT nome, anoVigente, situacao, id FROM classe where periodoLetivo=:ano ");
  $buscar->bindValue(":ano",$_SESSION['UsuarioAno']);
  $buscar->execute();
  $resultado = $buscar->fetchAll(PDO::FETCH_ASSOC);
 
?>
<table id="lista_curso" class="display" style="width:75%">
    <thead>
        <tr>
            
            <th>Nome</th>
            <th>Ano Vigente</th>
            <th>Situação</th>
            
            <th>A&ccedil;&otilde;es</th>
        </tr>
    </thead>

    <?php
        foreach($resultado as $dado){
            switch ($dado['situacao']) {
                case '0':
                    $situacao = "Provisória";
                    break;
                case '1':
                    $situacao = "Definida";
                    break;
                case '2':
                    $situacao = "Concluída";
                    break;
                case '3':
                    $situacao = "Desativada";
                    break;
                                
                default:
                    # code...
                    break;
            }

            echo "<tr>";
                    echo "
                    <td><a href='menu_restrito.php?op=ver_classe&idClasse=".$dado['id']."'>".$dado['nome']."</a></td>
                    <td><a href='menu_restrito.php?op=ver_classe&idClasse=".$dado['id']."'>".$dado['anoVigente']."</a></td>
                    <td><a href='menu_restrito.php?op=ver_classe&idClasse=".$dado['id']."'>".$situacao."</a></td>
            <td>
            <a href='?op=add_classe&idClasse=".$dado['id']."&fun=ed'><i class='fa fa-edit' id='edit'></i></a>
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
            "infoEmpty": "Nenhum registro dispon&atilde;­vel",
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
