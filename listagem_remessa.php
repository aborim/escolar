<?php


?>

<div class="titulo_interna">
    <i class="fa fa-money" aria-hidden="true"></i>Financeiro
</div>
<div class="content_form">
    
    <?php
    if(!isset($status)){?>
<form method="post" action="#" enctype="multipart/form-data" id="frmAluno">
    <div class="form_comp">
    <div class="titulo">Listagem de remessas geradas</div>

<table class="formulario"  style="width: 890px;">
<tbody >
    <tr><td colspan="101"></td></tr>

    <tr >
      <th>Arquivo</th>
      <th>Data de criação</th>
      
    </tr>

    
        <?php
         $path = "financeiro/remessa/geradas";
         $diretorio = scandir($path);
        
         
         for($i=2;$i<sizeof($diretorio);$i++){
            echo "<tr>";
            echo "<td><a href='".$path."/".$diretorio[$i]."'>".$diretorio[$i]."</a></td>";
            echo "<td style='font-size:12px'>".date ("d/m/Y H:i:s.",filectime($path."/".$diretorio[$i]))."</td>";
            echo "</tr>";
         }
         
         
        ?>
    
    
</tbody></table>

</td>
</tr>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> 
    
</tbody></table>

    </div>
</form>
</div>


    <?php }?>
      