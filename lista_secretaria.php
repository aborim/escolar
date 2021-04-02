<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css"/> 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>

<div class="titulo_interna">
    <i class="fa fa-users" aria-hidden="true"></i>Pessoas
</div>
<?php
 
include("conexao.php");
include("functions.php");
//busca os responsaveis para apresentar na listagem
$buscar=$con->prepare("
SELECT secretaria.id, secretaria.nome,secretaria.email, secretaria.cpf,secretaria.rg FROM secretaria,usuarios where secretaria.idUsuario = usuarios.id and usuarios.filial = :filial and usuarios.ativo=1 ");
  $buscar->bindValue(":filial",$_SESSION['Unidade']);
  $buscar->execute();
  $resultado = $buscar->fetchAll(PDO::FETCH_ASSOC);
?>
<table id="lista_secretaria" class="display" style="width:75%">
    <thead>
        <tr>
            
            <th>Nome</th>
            <th>E-mail</th>
            <th>CPF</th>
            <th>RG</th>
            
            
            <th>A&ccedil; &otilde;es</th>
        </tr>
    </thead>

    <?php
        foreach($resultado as $dado){
            echo "<tr>";
                    echo "
                    <td><a href='menu_restrito.php?op=ver_secretaria&idSecretaria=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['nome']."</a></td>
                    <td><a href='menu_restrito.php?op=ver_secretaria&idSecretaria=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['email']."</a></td>
                    <td><a href='menu_restrito.php?op=ver_secretaria&idSecretaria=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['cpf']."</a></td>
                    <td><a href='menu_restrito.php?op=ver_secretaria&idSecretaria=".$dado['id']."&nome=".base64_encode($dado['nome'])."'>".$dado['rg']."</a></td>
                    
            <td>
            <a href='?op=add_secretaria&idSecretaria=".$dado['id']."&fun=ed'><i class='fa fa-edit' id='edit'></i></a>
            </td>";
            echo "</tr>";
        }
    ?>
</table>

<script>
$(document).ready(function() {
    $('#lista_secretaria').DataTable({
        "language": {
            "lengthMenu": "Mostrando _MENU_ registros por p&aacute;gina",
            "zeroRecords": "Nenhum registro encontrado",
            "info": "Mostrando _PAGE_ de _PAGES_ p&aacute;ginas",
            "infoEmpty": "Nenhum registro dispon &atilde;­vel",
            "infoFiltered": "(De um total de  _MAX_ registros)",
            "search":         "Buscar:",
            "paginate": {
                "first":      "Primeiro",
                "last":       " &uacute;ltimo",
                "next":       "Pr &oacute;ximo",
                "previous":   "Anterior"
            }
        }
    });
} );
</script>
