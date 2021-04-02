<?php
include 'conexao.php';
include 'functions.php';
include 'vendor/MPDF/mpdf.php';

if($_REQUEST['parcelas_pagas']==1){
    $mpdf = new mPDF('', 'A4');
        
    session_start();
    ob_start();
    $html = '';

    #cabeçalho da impressão
    echo "<table width='100%' align='center'>
    <tr>
        <td width='50'><img src='images/logos/".htmlentities($_SESSION['UnidadeLogo'])."' style='width: 100;'></td>
        <td width='300'><span style='font-family:Verdana;font-weight:bolder'>".htmlentities($_SESSION['UnidadeFantasia'])."</span><br><span style='font-family:Verdana;font-size:10px;'>".htmlentities($_SESSION['UnidadeSlogan'])."</span></td>
        <td align='center'><span style='font-family:Verdana;font-weight:bolder;font-size:16px;'>".htmlentities("Relação de parcelas pagas")."</span></td>
    </tr>
    </table>
    <hr>
    <table width='100%' align='center'>
    <tr style='background:#999;color:#fff;font-weight:bolder;font-family:Verdana'>
        <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>RM</span></td>
        <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>Aluno</span></td>
        <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>Vencimento</span></td>
        <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>Data Pagamento</span></td>
        <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>Valor</span></td>
        <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>Valor Pago</span></td>
        <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>Classe</span></td>
        <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>Local Baixa</span></td>
    </tr>
    ";

    #busca as parcelas pagas pelos alunos selecionados
    foreach ($_POST['alunos'] as $key => $aluno) {
        
        $anoVigente='2020';

        $hoje = date("Y-m-d");
        
        $dtInicio = formatoDataBD($_REQUEST['dtInicio']);
        $dtFinal = formatoDataBD($_REQUEST['dtFinal']);
      
       if($_REQUEST['dtInicio']!=""&&$_REQUEST['dtFinal']!=""){
        
            if(( $dtInicio == $dtFinal ) && ( $dtInicio == $hoje ) && ( $dtFinal == $hoje )){
                $compQuery = " and pagamentos.data_pagamento='".$hoje."'";
                
            }elseif(( $dtInicio == $dtFinal ) && ( $dtInicio != $hoje ) && ( $dtFinal != $hoje )) {
                $compQuery = " and pagamentos.data_pagamento='".$dtInicio."'";
                
            }else{
                $compQuery = " and 
                pagamentos.data_pagamento >= '".$dtInicio."' and 
                pagamentos.data_pagamento <= '".$dtFinal."'";
            }
       } 
       

        $buscaMatriculas = $con->prepare("SELECT 
            aluno.nome as nomeAluno,
            aluno.rm as RM,
            classe.nome as classe,
            planos.plano,
            pagamentos.*
            FROM 
            classe,
            matriculas,
            aluno,
            planos,
            pagamentos
            where 
            aluno.id=matriculas.id_aluno AND 
            classe.id_plano = planos.id AND 
            matriculas.id_classe = classe.id AND 
            matriculas.id_aluno=:idAluno AND 
            classe.anoVigente=:anoVigente AND
            pagamentos.pg=1 AND 
            matriculas.id = pagamentos.id_matricula
            ". $compQuery);
          
        $buscaMatriculas->execute(array(':idAluno'=>$aluno,':anoVigente'=>$anoVigente));
        $resultadoMatriculas = $buscaMatriculas->fetchAll(PDO::FETCH_ASSOC);
        if(count($resultadoMatriculas)!=0){
                foreach ($resultadoMatriculas as $dadosAluno) {
                    echo "<tr style='font-family:Verdana;font-size:12px;'>
                    <td><span style='font-family:Verdana;font-size:12px;color:#000'>".$dadosAluno['RM']."</span></td>
                    <td><span style='font-family:Verdana;font-size:12px;color:#000'>".utf8_encode($dadosAluno['nomeAluno'])."</span></td>
                    <td><span style='font-family:Verdana;font-size:12px;color:#000'>".formatoData($dadosAluno['venc'])." </span></td>
                    <td><span style='font-family:Verdana;font-size:12px;color:#000'>".formatoData($dadosAluno['data_pagamento'])." </span></td>
                    <td><span style='font-family:Verdana;font-size:12px;color:#000'>".formatoMoeda($dadosAluno['valor_parc'])."</span> </td>
                    <td><span style='font-family:Verdana;font-size:12px;color:#000'>".formatoMoeda($dadosAluno['valor_pago'])."</span> </td>
                    <td><span style='font-family:Verdana;font-size:12px;color:#000'>".utf8_encode($dadosAluno['classe'])." </td>
                    <td><span style='font-family:Verdana;font-size:12px;color:#000'>"; if($dadosAluno['local_baixa']==1){echo "Escola</span></td></tr>";}else{echo "Banco</span></td></tr>";}    
                }

                
        }else{
            echo "conjunto vazio";
        }
        
    }
    
    
    echo "</table><hr>";
    // Now collect the output buffer into a variable
    $html = ob_get_contents();
    ob_end_clean();

    // send the captured HTML from the output buffer to the mPDF class for processing
    $mpdf->WriteHTML($html);
    $mpdf->Output();
}elseif($_REQUEST['parcelas_apagar']==1){
    $mpdf = new mPDF('', 'A4');
        
    session_start();
    ob_start();
    $html = '';

    #cabeçalho da impressão
    echo "<table width='100%' align='center'>
    <tr>
        <td width='50'><img src='images/logos/".htmlentities($_SESSION['UnidadeLogo'])."' style='width: 100;'></td>
        <td width='300'><span style='font-family:Verdana;font-weight:bolder'>".htmlentities($_SESSION['UnidadeFantasia'])."</span><br><span style='font-family:Verdana;font-size:10px;'>".htmlentities($_SESSION['UnidadeSlogan'])."</span></td>
        <td align='center'><span style='font-family:Verdana;font-weight:bolder;font-size:16px;'>".htmlentities("Relação de parcelas à pagar")."</span></td>
    </tr>
    </table>
    <hr>
    <table width='100%' align='center'>
    <tr style='background:#999;color:#fff;font-weight:bolder;font-family:Verdana'>
        <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>RM</span></td>
        <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>Aluno</span></td>
        <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>Vencimento</span></td>
        <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>Data Pagamento</span></td>
        <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>Valor</span></td>
        <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>Valor Pago</span></td>
        <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>Classe</span></td>
        <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>Local Baixa</span></td>
    </tr>
    ";

    #busca as parcelas pagas pelos alunos selecionados
    foreach ($_POST['alunos'] as $key => $aluno) {
        
        $anoVigente='2020';

        $hoje = date("Y-m-d");
        
        $dtInicio = formatoDataBD($_REQUEST['dtInicio']);
        $dtFinal = formatoDataBD($_REQUEST['dtFinal']);
      
        
       if($_REQUEST['dtInicio'] != "" && $_REQUEST['dtFinal']!=""){
            if(( $dtInicio == $dtFinal ) && ( $dtInicio == $hoje ) && ( $dtFinal == $hoje )){
                $compQuery = " and pagamentos.venc='".$hoje."'";
                
            }elseif(( $dtInicio == $dtFinal ) && ( $dtInicio != $hoje ) && ( $dtFinal != $hoje )) {
                $compQuery = " and pagamentos.venc='".$dtInicio."'";
                
            }else{
                $compQuery = " and 
                pagamentos.venc >= '".$dtInicio."' and 
                pagamentos.venc <= '".$dtFinal."'";
            }
       }
        

        $buscaMatriculas = $con->prepare("SELECT 
            aluno.nome as nomeAluno,
            aluno.rm as RM,
            classe.nome as classe,
            planos.plano,
            pagamentos.*
            FROM 
            classe,
            matriculas,
            aluno,
            planos,
            pagamentos
            where 
            aluno.id=matriculas.id_aluno AND 
            classe.id_plano = planos.id AND 
            matriculas.id_classe = classe.id AND 
            matriculas.id_aluno=:idAluno AND 
            classe.anoVigente=:anoVigente AND
            pagamentos.pg <> 1 AND 
            matriculas.id = pagamentos.id_matricula
            ". $compQuery);
            
            
        $buscaMatriculas->execute(array(':idAluno'=>$aluno,':anoVigente'=>$anoVigente));
        $resultadoMatriculas = $buscaMatriculas->fetchAll(PDO::FETCH_ASSOC);
        if(count($resultadoMatriculas)!=0){
           foreach ($resultadoMatriculas as $key => $value) {
            echo "<tr style='font-family:Verdana;font-size:12px;'>
            <td><span style='font-family:Verdana;font-size:12px;color:#000'>".$value['RM']."</span></td>
            <td><span style='font-family:Verdana;font-size:12px;color:#000'>".utf8_encode($value['nomeAluno'])."</span></td>
            <td><span style='font-family:Verdana;font-size:12px;color:#000'>".formatoData($value['venc'])." </span></td>
            <td><span style='font-family:Verdana;font-size:12px;color:#000'>".formatoData($value['data_pagamento'])." </span></td>
            <td><span style='font-family:Verdana;font-size:12px;color:#000'>".formatoMoeda($value['valor_parc'])."</span> </td>
            <td><span style='font-family:Verdana;font-size:12px;color:#000'> </span> </td>
            <td><span style='font-family:Verdana;font-size:12px;color:#000'>".utf8_encode($value['classe'])." </td>
            <td><span style='font-family:Verdana;font-size:12px;color:#000'>Em aberto</td></tr>";
           }     
        }
        
    }
    
    
    echo "</table><hr>";
    // Now collect the output buffer into a variable
    $html = ob_get_contents();
    ob_end_clean();

    // send the captured HTML from the output buffer to the mPDF class for processing
    $mpdf->WriteHTML($html);
    $mpdf->Output();
}elseif($_REQUEST['pos_consolidada']==1){
    $mpdf = new mPDF('', 'A4');
        
    session_start();
    ob_start();
    $html = '';

    #cabeçalho da impressão
    echo "<table width='100%' align='center'>
    <tr>
        <td width='50'><img src='images/logos/".htmlentities($_SESSION['UnidadeLogo'])."' style='width: 100;'></td>
        <td width='300'><span style='font-family:Verdana;font-weight:bolder'>".htmlentities($_SESSION['UnidadeFantasia'])."</span><br><span style='font-family:Verdana;font-size:10px;'>".htmlentities($_SESSION['UnidadeSlogan'])."</span></td>
        <td align='center'><span style='font-family:Verdana;font-weight:bolder;font-size:16px;'>".htmlentities("Posição financeira")."</span></td>
    </tr>
    </table>
    <hr>
    
    ";

    #busca as parcelas pagas pelos alunos selecionados
    foreach ($_POST['alunos'] as $key => $aluno) {
        
        $anoVigente='2020';

        $buscaMatriculas = $con->prepare("SELECT 
            aluno.nome as nomeAluno,
            aluno.rm as RM,
            classe.nome as classe,
            planos.plano,
            pagamentos.*
            FROM 
            classe,
            matriculas,
            aluno,
            planos,
            pagamentos
            where 
            aluno.id=matriculas.id_aluno AND 
            classe.id_plano = planos.id AND 
            matriculas.id_classe = classe.id AND 
            matriculas.id_aluno=:idAluno AND 
            classe.anoVigente=:anoVigente AND
            matriculas.id = pagamentos.id_matricula
            ");
            
            
        $buscaMatriculas->execute(array(':idAluno'=>$aluno,':anoVigente'=>$anoVigente));
        $resultadoMatriculas = $buscaMatriculas->fetchAll(PDO::FETCH_ASSOC);
        if(count($resultadoMatriculas)!=0){
            echo "<table width='100%' align='center'>";
            echo "<tr><td style='background:#999;color:#fff;font-weight:bolder;font-family:Verdana'>Aluno: </td><td colspan='3'>".utf8_encode($resultadoMatriculas[0]['nomeAluno'])."</td></tr>";
            echo "<tr><td style='background:#999;color:#fff;font-weight:bolder;font-family:Verdana'>RM: </td><td colspan='3'>".$resultadoMatriculas[0]['RM']."</td></tr>";
            echo "<tr><td style='background:#999;color:#fff;font-weight:bolder;font-family:Verdana'>Classe: </td><td colspan='2'>".utf8_encode($resultadoMatriculas[0]['classe'])."</td><td style='background:#999;color:#fff;font-weight:bolder;font-family:Verdana'>Ano Vigente:</td><td>$anoVigente</td></tr>";
            echo "
                <tr style='background:#999;color:#fff;font-weight:bolder;font-family:Verdana'>
                <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>Vencimento</span></td>
                <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>Data Pagamento</span></td>
                <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>Valor</span></td>
                <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>Valor Pago</span></td>
                
                <td><span style='font-family:Verdana;font-weight:bolder;font-size:14px;color:#fff'>Local Baixa</span></td>
            </tr>";
            

           foreach ($resultadoMatriculas as $key => $value) {
            echo "<tr style='font-family:Verdana;font-size:12px;'>
            <td width='100'><span style='font-family:Verdana;font-size:12px;color:#000'>".formatoData($value['venc'])." </span></td>
            <td><span style='font-family:Verdana;font-size:12px;color:#000'>".formatoData($value['data_pagamento'])." </span></td>
            <td><span style='font-family:Verdana;font-size:12px;color:#000'>".formatoMoeda($value['valor_parc'])."</span> </td>
            <td><span style='font-family:Verdana;font-size:12px;color:#000'>".formatoMoeda($value['valor_pago'])."</span> </td>
            
            <td><span style='font-family:Verdana;font-size:12px;color:#000'>"; 
                if($value['valor_pago'] == 0){
                    echo "Em aberto";
                }else{
                    if($dadosAluno['local_baixa']==1){
                        echo "Escola";
                    }else{
                        echo "Banco";
                    }
                }
            echo "</span></td></tr>";
           }     
        }
        
    }
    
    
    echo "</table><hr>";
    // Now collect the output buffer into a variable
    $html = ob_get_contents();
    ob_end_clean();

    // send the captured HTML from the output buffer to the mPDF class for processing
    $mpdf->WriteHTML($html);
    $mpdf->Output();
}

?>