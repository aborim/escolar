<?php

//inclui a conexao com banco de dados e fun&ccedil; &otilde;es
include('conexao.php');
include('functions.php');


    $buscar=$con->prepare("
    SELECT aluno.id,
        
        aluno.nome,
        aluno.rm,
        aluno.ra,
        responsavel.nome as NomeResp,
        responsavel.cpf,
        endereco.*,
        classe.nome as classe,
        boletos.*,
        pagamentos.id as idPgto,
        pagamentos.valor_parc,
        pagamentos.venc,
        pagamentos.num_parc
    FROM 
        aluno,
        usuarios,
        classe,
        matriculas,pagamentos,boletos,responsavel,endereco,aluno_responsavel
    where
    	pagamentos.id_matricula = matriculas.id AND
        boletos.id_pagamento = pagamentos.id and 
    	matriculas.id_classe = classe.id AND
        matriculas.id_aluno = aluno.id AND
        aluno.idUsuario = usuarios.id and 
        usuarios.filial = :filial and usuarios.ativo=1 and aluno.id = :idAluno AND
        aluno_responsavel.financeiro=1 AND
        aluno_responsavel.idAluno = :idAluno and 
        responsavel.id = aluno_responsavel.idResponsavel and
        responsavel.idEndereco = endereco.id ");
        #':search', '%' . $search . '%'
    
  $buscar->bindValue(":filial",$_SESSION['Unidade']);
  $buscar->bindValue(":idAluno",$_REQUEST['idAluno']);
  

  $buscar->execute();
  $resultado = $buscar->fetchAll(PDO::FETCH_ASSOC);
  if($buscar->rowCount()==0){$mensagem="Nenhum boleto encontrado";}
 


?>

<div class="titulo_interna">
    <i class="fa fa-money" aria-hidden="true"></i>Financeiro
</div>
<div class="content_form">
    
    <?php
    if(!isset($status)){?>
<form method="post" action="#" enctype="multipart/form-data" id="frmAluno">
    <div class="form_comp">
    <div class="titulo"></div>


<?php
    if($mensagem==""){
        if(!empty($resultado)){
            echo "<!--";
            print_r($resultado);
            echo "-->";
            echo "<table class=\"mostrapesquisa\" width=100% style=\"width: 970px;\">
                <tr>
                    <th colspan=4>Aluno: ".$resultado[0]['nome']." | ".$resultado[0]['classe']."</th>
                    <th>Selecione os boletos que deseja imprimir</th></tr>
            ";
            $parcela = 1;
            foreach($resultado as $dado){
                $suaarray = array_map('htmlentities',$dado);
                
                #$json_boleto = base64_encode(html_entity_decode(json_encode($suaarray)));
                $json_boleto = "boleto[id] = ".$dado['id']."&boleto[nome] =". $dado['nome']."&boleto[rm] =". $dado['rm']."&boleto[ra] =".$dado['ra'] ."&boleto[NomeResp] =".$dado['NomeResp']."&boleto[cpf] =".$dado['cpf']."&boleto[endereco] =".$dado['endereco']."&boleto[numero] =".$dado['numero']."&boleto[complemento] =".$dado['complemento']."&boleto[cep] =".$dado['cep']."&boleto[bairro] =".$dado['bairro']."&boleto[cidade] =".$dado['cidade']."&boleto[estado] =".$dado['estado']."&boleto[classe] =".$dado['classe']."&boleto[id_pagamento] =".$dado['id_pagamento']."&boleto[nosso_numero] =".$dado['nosso_numero']."&boleto[dv] =".$dado['dv']."&boleto[dtProcessamento] =".$dado['dtProcessamento']."&boleto[impresso] =".$dado['impresso']."&boleto[remessa] =".$dado['remessa']."&boleto[idPgto] =".$dado['idPgto']."&boleto[valor_parc] =".$dado['valor_parc']."&boleto[venc] =".$dado['venc']."&boleto[num_parc] =".$dado['num_parc']."&boleto[identificador] =".$dado['identificador'];               
                
                echo "<tr>
                        <td style=\"text-align: center;\">".$parcela."</a></td>
                        <td>".formatoMoeda($dado['valor_parc'])."</a></td>
                        <td>".formatoData($dado['venc'])."</a></td>
                        
                <td ><a href='gera_boleto_individual.php?".$json_boleto."' target='_blank'><i class='fa fa-money' id='boleto' style='width: 10'></i> Imprimir Boleto Individual</a>
                <td><input type='checkbox' name='boleto[".$parcela."]' value='".$json_boleto."'></td>
                    
                   
                </td></tr>";
                $parcela++;
            }
            echo 
            "<tr>
                <td colspan=5 align=\"right\">
                <span class=\"btn_pos\">
                <input type=\"submit\" name=\"btn_add_aluno\" class=\"form_buttom\" value=\"Imprimir Boletos selecionados\" />
                </span></td></tr>
            </table>";
        }
    }else{
        echo $mensagem;
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