<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css"/> 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>

<div class="titulo_interna">
<i class="fa fa-cogs" aria-hidden="true"></i>Parâmetros
</div>
<?php
include("conexao.php");
include("functions.php");
//busca os turmas para apresentar na listagem
  $buscar=$con->prepare("SELECT * FROM planos where anoVigente=:anoVigente");
  $buscar->bindValue(":anoVigente",$_SESSION['UsuarioAno']);
  $buscar->execute();
  $resultado = $buscar->fetchAll(PDO::FETCH_ASSOC);
?>
<table id="lista_turma" class="display" style="width:75%">
    <thead>
        <tr>
            
            <th>Plano</th>
            <th>Valor</th>
            <th>Número de Parcelas</th>
            <th>Dia de Vencimento</th>
            <th>A&ccedil;&otilde;es</th>
        </tr>
    </thead>

    <?php
        foreach($resultado as $dado){
            echo "<tr>";
                    echo "
                    <td><a href='menu_restrito.php?op=ver_plano&idPlano=".$dado['id']."&nome=".base64_encode($dado['plano'])."'>".$dado['plano']."</a></td>
                    <td><a href='menu_restrito.php?op=ver_plano&idPlano=".$dado['id']."&nome=".base64_encode($dado['plano'])."'>".formatoMoeda($dado['valor'])."</a></td>
                    <td><a href='menu_restrito.php?op=ver_plano&idPlano=".$dado['id']."&nome=".base64_encode($dado['plano'])."'>".$dado['n_parc']."</a></td>
                    <td><a href='menu_restrito.php?op=ver_plano&idPlano=".$dado['id']."&nome=".base64_encode($dado['plano'])."'>".$dado['dia_venc']."</a></td>
            <td>
            <a href='?op=add_plano&idPlano=".$dado['id']."&fun=ed'><i class='fa fa-edit' id='edit'></i></a>
            </td>";
            echo "</tr>";
        }
    ?>
</table>

<script>
$(document).ready(function() {
    $('#lista_turma').DataTable({
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
