<?php
include('conexao.php');
include('functions.php');
// Require composer autoload
include 'vendor/MPDF/mpdf.php';
include 'docsupport/barcode-php/barcode.php';
$mpdf = new mPDF('', 'A4');
$mpdf->shrink_tables_to_fit = 1;

session_start();
$html = '';
#$dados = json_encode(base64_decode($_REQUEST['boleto']),true);
$dados = $_REQUEST['boleto'];

// +----------------------------------------------------------------------+
// | BoletoPhp - Versão Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo está disponível sob a Licença GPL disponível pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Você deve ter recebido uma cópia da GNU Public License junto com     |
// | esse pacote; se não, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colaborações de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do	  |
// | PHPBoleto de João Prado Maia e Pablo Martins F. Costa                |
// |                                                                      |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------------+
// | Equipe Coordenação Projeto BoletoPhp: <boletophp@boletophp.com.br>         |
// | Desenvolvimento Boleto Santander-Banespa : Fabio R. Lenharo                |
// +----------------------------------------------------------------------------+


// ------------------------- DADOS DINÂMICOS DO SEU CLIENTE PARA A GERAÇÃO DO BOLETO (FIXO OU VIA GET) -------------------- //
// Os valores abaixo podem ser colocados manualmente ou ajustados p/ formulário c/ POST, GET ou de BD (MySql,Postgre,etc)	//

// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento = 5;
$taxa_boleto = 0;
#$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 
$data_venc = formatoData($dados['venc']);
#$valor_cobrado = "2950,00"; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado = $dados['valor_parc']; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado = str_replace(",", ".",$valor_cobrado);
$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

$dadosboleto["nosso_numero"] = substr($dados['nosso_numero'],0,7);  // Nosso numero sem o DV - REGRA: Máximo de 7 caracteres!
#$dadosboleto["nosso_numero"] = '';  // Nosso numero sem o DV - REGRA: Máximo de 7 caracteres!
$dadosboleto["numero_documento"] = $dados['identificador'];	// Num do pedido ou nosso numero
$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
$dadosboleto["data_processamento"] = formatoData($dados['dtProcessamento']); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

// DADOS DO SEU CLIENTE
$dadosboleto["sacado"] = $dados['NomeResp']." CPF:".$dados['cpf'];
$dadosboleto["endereco1"] = $dados['endereco']." ,".$dados['numero']." - ".$dados['complemento'];
$dadosboleto["endereco2"] = $dados['cep']. " - ".$dados['bairro']. " - ".$dados['cidade']. " - ".$dados['estado'];

// INFORMACOES PARA O CLIENTE
$dadosboleto["demonstrativo1"] = "Mensalidade escolar, parcela ".$dados['num_parc']."/12 do aluno ".$dados['nome'].", RM: ".$dados['rm'];
$dadosboleto["demonstrativo2"] = "matriculado na classe ".$dados['classe'];
$dadosboleto["demonstrativo3"] = "Escola Futuros Gênios";
#$dadosboleto["instrucoes1"] = "- Após o vencimento cobrar multa contratual de 2% ";
#$dadosboleto["instrucoes2"] = "- e juros de morade 1% ao mês.";
#$dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: xxxx@xxxx.com.br";
#dadosboleto["instrucoes4"] = "&nbsp; Emitido pelo sistema Projeto BoletoPhp - www.boletophp.com.br";
$dadosboleto["instrucoes1"] = "Mensalidade escolar, parcela ".$dados['num_parc']."/12 do aluno ".$dados['nome'].", RM: ".$dados['rm'];
$dadosboleto["instrucoes2"] = "- Matriculado na classe ".$dados['classe'];
$dadosboleto["instrucoes3"] = "- Após o vencimento cobrar multa contratual de 2% ";
$dadosboleto["instrucoes4"] = "- e juros de mora de 1% ao mês.";

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "";		
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "";


// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


// DADOS PERSONALIZADOS - SANTANDER BANESPA
$dadosboleto["codigo_cliente"] = "9196412"; // Código do Cliente (PSK) (Somente 7 digitos)
$dadosboleto["ponto_venda"] = "0437"; // Ponto de Venda = Agencia
$dadosboleto["carteira"] = "101";  // Cobrança Simples - SEM Registro
$dadosboleto["carteira_descricao"] = "RCR";  // Descrição da Carteira

// SEUS DADOS
$dadosboleto["identificacao"]   = $_SESSION['UnidadeFantasia'];
$dadosboleto["cpf_cnpj"]        = $_SESSION['UnidadeCNPJ'];
$dadosboleto["endereco"]        = $_SESSION['UnidadeEndereco'];
$dadosboleto["cidade_uf"]       = "";
$dadosboleto["cedente"]         = $_SESSION['UnidadeFantasia']." - ".$_SESSION['UnidadeEndereco'];
$dadosboleto['logo']            = $_SESSION['UnidadeLogo'];

/*
print_r($dados);
exit;
*/
// NÃO ALTERAR!
include("docsupport/Boletos/include/funcoes_santander_banespa.php"); 
#include("docsupport/Boletos/include/layout_santander_banespa.php");
ob_start();
?>

<HTML>
<HEAD>
<TITLE><?php echo $dadosboleto["identificacao"]; ?></TITLE>
<META http-equiv=Content-Type content=text/html charset=ISO-8859-1>
<!--<meta name="Generator" content="Projeto BoletoPHP - www.boletophp.com.br - Licença GPL" />-->
<style type=text/css>
body{
    margin: 0px;
    padding: 0px;
}
table {border-spacing: 0px;}
td    {padding: 0px;}

.instrucoes {  font: bold 10px Arial; color: black; width:1000px;text-align: center;}
.cp {  font: bold 10px Arial; color: black;}
.ti {  font: 9px Arial, Helvetica, sans-serif}
.ld { font: bold 15px Arial; color: #000000}
.ct { FONT: 9px "Arial Narrow"; COLOR: #000033}
.cn { FONT: 9px Arial; COLOR: black }
.bc { font: bold 20px Arial; color: #000000 }
.ld2 { font: bold 12px Arial; color: #000000 }
</style> 
</head>

<body text="#000000" bgColor="#ffffff" topMargin="0" rightMargin="0">
<table width="666" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td valign="top" class="instrucoes">
            <div>Instruções de Impressão</DIV>
        </td>
    </tr>
    <tr>
    <td valign="top" class="cp">
        <div ALIGN="left">
            <p>
                <li>Imprima em impressora jato de tinta (ink jet) ou laser em qualidade normal ou alta (Não use modo econômico).</li>
                <li>Utilize folha A4 (210 x 297 mm) ou Carta (216 x 279 mm) e margens mínimas à esquerda e à direita do formulário.</li>
                <li>Corte na linha indicada. Não rasure, risque, fure ou dobre a região onde se encontra o código de barras.</li>
                <li>Caso não apareça o código de barras no final, clique em F5 para atualizar esta tela.</li>
                <li>Caso tenha problemas ao imprimir, copie a seqüencia numérica abaixo e pague no caixa eletrônico ou no internet banking:</li><br><br>
                
                <span class="ld2">Linha Digitável: <?php echo $dadosboleto["linha_digitavel"]?><br>
                Valor: &nbsp;R$ <?php echo $dadosboleto["valor_boleto"]?><br></span>
            </p>
        </div>
    </td>
    </tr>
</table>
<br>

<table cellspacing=0 cellpadding=0 width=666 >
    <tbody>
        <tr>
            <td class=ct width=666>
                <img height=1 src=/escolar/docsupport/Boletos/imagens/6.png width=665 border=0>
            </td>
        </tr>
        <tr>
            <td style="width:666px;text-align: right;">
                <b class="ct" >Recibo do Sacado</b>
            </td>
        </tr>
    </tbody>
</table>

<table width=666 cellspacing=5 cellpadding=0 ><tr><td width=41></TD></tr></table>
<table width=666 cellspacing=5 cellpadding=0 >
  <tr>
    <td width=41><IMG SRC="/escolar/images/logos/<?php echo $dadosboleto["logo"]; ?>" width="100"></td>
    <td class=ti width=455><?php echo $dadosboleto["identificacao"]; ?> 
        <?php echo isset($dadosboleto["cpf_cnpj"]) ? "<br>".$dadosboleto["cpf_cnpj"] : '' ?><br>
        <?php echo $dadosboleto["endereco"]; ?><br>
        <?php echo $dadosboleto["cidade_uf"]; ?><br>
    </td>
    <td width=150 class=ti>&nbsp;</td>
  </tr>
</table>
<BR>

<table cellspacing=0 cellpadding=0 width=666 style="border:0 solid;">
    <tr>
        <td class=cp width=150 >
            <span class="campo">
                <IMG src="/escolar/docsupport/Boletos/imagens/logosantander.jpg" width="140" height="37">
            </span>
        </td>
        <td width=3 valign=bottom>
            <img height=22 src=/escolar/docsupport/Boletos/imagens/3.png width=2 >
        </td>
        <td class=cpt width=58 valign=bottom>
            <div align=center>
                <font class=bc>
                    <?php echo $dadosboleto["codigo_banco_com_dv"]?>
                </font>
            </div>
        </td>
        <td width=3 valign=bottom>
            <img height=22 src=/escolar/docsupport/Boletos/imagens/3.png width=2 >
        </td>
        <td class=ld align=right width=453 valign=bottom>
            <span class=ld> 
                <span class="campotitulo" style="font-size: 13px;font-family: Arial, Helvetica, sans-serif">
                <?php echo $dadosboleto["linha_digitavel"]?>
                </span>
            </span>
        </td>
    </tr>
    <tr>
        <td colspan=5>
            <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=680 style="margin:0px">
        </td>
</tr>
</table>

<table cellspacing=0 cellpadding=0 width=666 style="border:0px solid">
    <tbody>
        <tr>
            <td class=ct valign=top width=7>
                <img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 style="margin:0px;top:0px">
            </td>
            <td class=ct valign=top width=298 >Cedente</td>
            <td class=ct valign=top width=7 >
                <img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0>
            </td>
                <td class=ct valign=top width=126 >Agência/Código do Cedente</td>
                <td class=ct valign=top width=7 >
                    <img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0>
                </td>
                <td class=ct valign=top width=34 >Espécie</td>
                <td class=ct valign=top width=7 >
                    <img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0>
                </td>
                <td class=ct valign=top width=53 >Quantidade</td>
                <td class=ct valign=top width=7 >
                    <img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0>
                </td>
                <td class=ct valign=top width=80 >Nosso número</td>
            </tr>
            <tr>
                <td class=cp valign=top width=7 height=12>
                    <img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0>
                </td>
                <td class=cp valign=top width=344 height=12> 
  <span class="campo" style="
  font: 7px Arial;
    color: black;
    /*width: 280px;
    position: absolute;
    left: 37px;
    top: 345px;*/
">
<?php echo $dadosboleto["cedente"]; ?></span></td>
<td class=cp valign=top width=7 height=12>
    <img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0>
</td>
<td class=cp valign=top width=126 height=12> 
  <span class="campo">
  <?php $tmp2 = $dadosboleto["codigo_cliente"];
     $tmp2 = substr($tmp2,0,strlen($tmp2)-1).'-'.substr($tmp2,strlen($tmp2)-1,1);
  ?>

  <?php echo $dadosboleto["ponto_venda"]." <img src='/escolar/docsupport/Boletos/imagens/b.png' width=10 height=1> ".$tmp2?>
  </span></td>
<td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top  width=34 height=12><span class="campo">
  <?php echo $dadosboleto["especie"]?>
</span> 
 </td>
<td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top  width=53 height=12><span class="campo">
  <?php echo $dadosboleto["quantidade"]?>
</span> 
 </td>
<td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top align=right width=120 height=12> 
  <span class="campo">
  <?php $tmp = $dadosboleto["nosso_numero"];
     
     $tmp = substr($tmp,6,strlen($tmp)-1).'-'.substr($tmp,strlen($tmp)-1,1);
     print $tmp; ?>
  </span>
</td>
</tr>

<tr>
    <td valign=top width=7 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0>
    </td>
    <td valign=top width=344 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=344 border=0>
    </td>
    <td valign=top width=7 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0>
    </td>
    <td valign=top width=126 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=126 border=0>
    </td>
    <td valign=top width=7 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0>
    </td>
    <td valign=top width=34 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=34 border=0>
    </td>
    <td valign=top width=7 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0>
    </td>
    <td valign=top width=53 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=53 border=0>
    </td>
    <td valign=top width=7 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0>
    </td>
    <td valign=top width=120 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=120 border=0>
    </td>
</tr>
</tbody>
</table>

<table cellspacing=0 cellpadding=0 border=0>
    <tbody><tr><td class=ct valign=top width=7 >
        <img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0>
    </td><td class=ct valign=top colspan=3 >Número 
do documento</td><td class=ct valign=top width=7 >
    <img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0>
</td>
    <td class=ct valign=top width=128 height=13>CPF/CNPJ</td>
    <td class=ct valign=top width=7 height=13>
        <img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0>
    </td>
    <td class=ct valign=top width=134 height=13>Vencimento</td>
    <td class=ct valign=top width=7 height=13>
        <img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td>
        <td class=ct valign=top width=180 height=13>Valor 
documento</td></tr><tr><td class=cp valign=top width=7 height=12>
    <img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td>
    <td class=cp valign=top colspan=3 height=12> 
  <span class="campo">
  <?php echo $dadosboleto["numero_documento"]?>
  </span></td>
<td class=cp valign=top width=7 height=12>
    <img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td>
    <td class=cp valign=top width=132 height=12> 
  <span class="campo">
  <?php echo $dadosboleto["cpf_cnpj"]?>
  </span></td>
<td class=cp valign=top width=7 height=12>
    <img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td>
    <td class=cp valign=top width=134 height=12> 
  <span class="campo">
  <?php echo $dadosboleto["data_vencimento"]?>
  </span></td>
<td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12> 
  <span class="campo">
  <?php echo $dadosboleto["valor_boleto"]?>
  </span></td>
</tr><tr><td valign=top width=7 height=1>
    <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0>
</td>
    <td valign=top width=113 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=113 border=0>
    </td>
    <td valign=top width=7 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0>
    </td>
    <td valign=top width=72 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=72 border=0>
    </td>
    <td valign=top width=7 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0>
    </td>
    <td valign=top width=132 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=132 border=0>
    </td>
    <td valign=top width=7 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0>
    </td>
    <td valign=top width=134 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=134 border=0>
    </td>
        <td valign=top width=7 height=1>
            <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0>
        </td>
    <td valign=top width=180 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=180 border=0>
    </td>
</tr>
</tbody>
</table>

<table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=113 height=13>(-) 
Desconto / Abatimentos</td><td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=112 height=13>(-) 
Outras deduções</td><td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=113 height=13>(+) 
Mora / Multa</td><td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=113 height=13>(+) 
Outros acréscimos</td><td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>(=) 
Valor cobrado</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top align=right width=113 height=12></td><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top align=right width=112 height=12></td><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top align=right width=113 height=12></td><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top align=right width=113 height=12></td><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12></td></tr><tr><td valign=top width=7 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=113 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=113 border=0></td><td valign=top width=7 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=112 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=112 border=0></td><td valign=top width=7 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=113 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=113 border=0></td><td valign=top width=7 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=113 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=113 border=0></td><td valign=top width=7 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=180 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=659 height=13>Sacado</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top width=659 height=12> 
  <span class="campo">
  <?php echo $dadosboleto["sacado"]?>
  </span></td>
</tr><tr><td valign=top width=7 height=1>
    <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td>
    <td valign=top width=659 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=659 border=0></td></tr>
    </tbody>
</table>
<table cellspacing=0 cellpadding=0 border=0>
    <tbody><tr>
        <td class=ct  width=7 height=12></td>
        <td class=ct  width=564 >Demonstrativo</td>
        <td class=ct  width=7 height=12></td><td class=ct  width=88 >Autenticação 
mecânica</td></tr><tr><td  width=7 ></td><td class=cp width=564 >
<span class="campo">
  <?php echo $dadosboleto["demonstrativo1"]?><br>
  <?php echo $dadosboleto["demonstrativo2"]?><br>
  <?php echo $dadosboleto["demonstrativo3"]?><br>
  </span>
  </td><td  width=7 ></td><td  width=88 ></td></tr></tbody></table><table cellspacing=0 cellpadding=0 width=666 border=0><tbody><tr><td width=7></td><td  width=500 class=cp> 
<br><br><br> 
</td><td width=159></td></tr></tbody></table><table cellspacing=0 cellpadding=0 width=666 border=0><tr><td class=ct width=666></td></tr><tbody><tr><td class=ct width=666> 
<div align=right>Corte na linha pontilhada</div></td></tr><tr><td class=ct width=666><img height=1 src=/escolar/docsupport/Boletos/imagens/6.png width=665 border=0></td></tr></tbody></table><br><table cellspacing=0 cellpadding=0 width=666 border=0><tr><td class=cp width=150> 
  <span class="campo"><IMG 
      src="/escolar/docsupport/Boletos/imagens/logosantander.jpg" width="140" height="37"
      border=0></span></td>
<td width=3 valign=bottom><img height=22 src=/escolar/docsupport/Boletos/imagens/3.png width=2 border=0></td><td class=cpt width=58 valign=bottom><div align=center><font class=bc><?php echo $dadosboleto["codigo_banco_com_dv"]?></font></div></td><td width=3 valign=bottom><img height=22 src=/escolar/docsupport/Boletos/imagens/3.png width=2 border=0></td><td class=ld align=right width=453 valign=bottom><span class=ld> 
<span class="campotitulo"  style="font-size: 13px;font-family: Arial, Helvetica, sans-serif">
<?php echo $dadosboleto["linha_digitavel"]?>
</span></span></td>
</tr><tbody><tr><td colspan=5><img height=2 src=/escolar/docsupport/Boletos/imagens/2.png width=666 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=472 height=13>Local 
de pagamento</td><td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>Vencimento</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top width=472 height=12>Pagável 
em qualquer Banco até o vencimento</td><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12> 
  <span class="campo">
  <?php echo $dadosboleto["data_vencimento"]?>
  </span></td>
</tr><tr><td valign=top width=7 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=472 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=472 border=0></td><td valign=top width=7 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=180 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=472 height=13>Cedente</td><td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>Ponto Venda / Ident. 
cedente</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top width=472 height=12> 
  <span class="campo" style="
  font: 9px Arial;
    color: black;
    position: absolute;
    left: 40px;
    top: 626px;
">
  <?php echo $dadosboleto["cedente"]?>
  </span></td>
<td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12> 
  <span class="campo">
  <?php $tmp2 = $dadosboleto["codigo_cliente"];
     $tmp2 = substr($tmp2,0,strlen($tmp2)-1).'-'.substr($tmp2,strlen($tmp2)-1,1);
  ?>

  <?php echo $dadosboleto["ponto_venda"]." <img src='/escolar/docsupport/Boletos/imagens/b.png' width=10 height=1> ".$tmp2?>
  </span></td>
</tr>

<tr>
    <td valign=top width=7 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0>
    </td>
    <td valign=top width=472 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=472 border=0>
    </td>
    <td valign=top width=7 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0>
    </td>
    <td valign=top width=180 height=1>
        <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=180 border=0>
    </td></tr></tbody></table>
    
<table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13> 
<img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td>
<td class=ct valign=top width=73 height=13>Data do documento</td>
<td class=ct valign=top width=7 height=13> 
    <img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td>
<td class=ct valign=top width=73 height=13>N<u>o</u> 
documento</td><td class=ct valign=top width=7 height=13> <img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=62 height=13>Espécie 
doc.</td><td class=ct valign=top width=7 height=13> <img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=34 height=13>Aceite</td><td class=ct valign=top width=7 height=13> 
<img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td>
<td class=ct valign=top width=82 height=13 style="font-size:8px">Data processamento</td>
<td class=ct valign=top width=7 height=13> <img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>Nosso 
número</td></tr>
<tr>
    <td class=cp valign=top width=7 height=12>
        <img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td>
        <td class=cp valign=top  width=73 height=12><div align=left> 
  <span class="campo">
  <?php echo $dadosboleto["data_documento"]?>
  </span></div></td><td class=cp valign=top width=7 height=12>
      <img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td>
      <td class=cp valign=top width=73 height=12> 
    <span class="campo">
    <?php echo $dadosboleto["numero_documento"]?>
    </span></td>
  <td class=cp valign=top width=7 height=12>
      <img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top  width=62 height=12><div align=left><span class="campo">
    <?php echo $dadosboleto["especie_doc"]?>
  </span> 
 </div></td><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top  width=34 height=12><div align=left><span class="campo">
 <?php echo $dadosboleto["aceite"]?>
 </span> 
 </div></td>
 <td class=cp valign=top width=7 height=12>
     <img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td>
     <td class=cp valign=top  width=82 height=12><div align=left> 
   <span class="campo">
   <?php echo $dadosboleto["data_processamento"]?>
   </span></div></td><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12> 
     <span class="campo">
     <?php echo $tmp; ?>
     </span></td>
</tr><tr><td valign=top width=7 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=113 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=113 border=0></td><td valign=top width=7 height=1> 
<img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=153 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=153 border=0></td><td valign=top width=7 height=1> 
<img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=62 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=62 border=0></td><td valign=top width=7 height=1> 
<img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=34 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=34 border=0></td><td valign=top width=7 height=1> 
<img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=82 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=82 border=0></td><td valign=top width=7 height=1> 
<img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=180 height=1> 
<img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=180 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr> 
<td class=ct valign=top width=7 height=13> <img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top COLSPAN="5" height=13> Carteira</td><td class=ct valign=top height=13 width=7>
<img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=53 height=13>Espécie</td><td class=ct valign=top height=13 width=7> 
<img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=123 height=13>Quantidade</td><td class=ct valign=top height=13 width=7> 
<img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=72 height=13> 
Valor Documento</td><td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>(=) 
Valor documento</td></tr><tr> <td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td valign=top class=cp height=12 COLSPAN="5"><div align=left>
 </div>    
<div align=left> <span class="campo">
  <?php echo $dadosboleto["carteira_descricao"]?>
</span></div></td><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top  width=53><div align=left><span class="campo">
<?php echo $dadosboleto["especie"]?>
</span> 
 </div></td><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top  width=123><span class="campo">
 <?php echo $dadosboleto["quantidade"]?>
 </span> 
 </td>
 <td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top  width=72> 
   <span class="campo">
   <?php echo $dadosboleto["valor_unitario"]?>
   </span></td>
 <td class=cp valign=top width=7 height=12> <img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12> 
   <span class="campo">
   <?php echo $dadosboleto["valor_boleto"]?>
   </span></td>
</tr><tr><td valign=top width=7 height=1> <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=7 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=75 border=0></td><td valign=top width=7 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=31 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=31 border=0></td><td valign=top width=7 height=1> 
<img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=83 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=83 border=0></td><td valign=top width=7 height=1> 
<img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=53 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=53 border=0></td><td valign=top width=7 height=1> 
<img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=123 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=123 border=0></td><td valign=top width=7 height=1> 
<img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=72 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=72 border=0></td><td valign=top width=7 height=1> 
<img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=180 border=0></td></tr></tbody> 
</table><table cellspacing=0 cellpadding=0 width=666 border=0><tbody><tr><td align=right width=10><table cellspacing=0 cellpadding=0 border=0 align=left><tbody> 
<tr> <td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td></tr><tr> 
<td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td></tr><tr> 
<td valign=top width=7 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=1 border=0></td></tr></tbody></table></td>
<td valign=top width=468 rowspan=5><font class=ct>Instruções 
(Texto de responsabilidade do cedente)</font><br><br><span class=cp> 
    <FONT class=campo>
<?php echo $dadosboleto["instrucoes1"]; ?><br>
<?php echo $dadosboleto["instrucoes2"]; ?><br>
<?php echo $dadosboleto["instrucoes3"]; ?><br>
<?php echo $dadosboleto["instrucoes4"]; ?></FONT><br><br> 
</span></td>
<td align=right width=188><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>(-) 
Desconto / Abatimentos</td></tr><tr> <td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12></td></tr><tr> 
<td valign=top width=7 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=180 border=0></td></tr></tbody></table></td></tr><tr><td align=right width=10> 
<table cellspacing=0 cellpadding=0 border=0 align=left><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td></tr><tr><td valign=top width=7 height=1> 
<img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=1 border=0></td></tr></tbody></table></td><td align=right width=188><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>(-) 
Outras deduções</td></tr><tr><td class=cp valign=top width=7 height=12> <img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12></td></tr><tr><td valign=top width=7 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=180 border=0></td></tr></tbody></table></td></tr><tr><td align=right width=10> 
<table cellspacing=0 cellpadding=0 border=0 align=left><tbody><tr><td class=ct valign=top width=7 height=13> 
<img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td></tr><tr><td valign=top width=7 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=1 border=0></td></tr></tbody></table></td><td align=right width=188> 
<table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>(+) 
Mora / Multa</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12></td></tr><tr> 
<td valign=top width=7 height=1> <img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=180 height=1> 
<img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=180 border=0></td></tr></tbody></table></td></tr><tr><td align=right width=10><table cellspacing=0 cellpadding=0 border=0 align=left><tbody><tr> 
<td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td></tr><tr><td valign=top width=7 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=1 border=0></td></tr></tbody></table></td><td align=right width=188> 
<table cellspacing=0 cellpadding=0 border=0><tbody><tr> <td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>(+) 
Outros acréscimos</td></tr><tr> <td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12></td></tr><tr><td valign=top width=7 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=180 border=0></td></tr></tbody></table></td></tr><tr><td align=right width=10><table cellspacing=0 cellpadding=0 border=0 align=left><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td></tr></tbody></table></td><td align=right width=188><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>(=) 
Valor cobrado</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12></td></tr></tbody> 
</table></td></tr></tbody></table><table cellspacing=0 cellpadding=0 width=666 border=0><tbody><tr><td valign=top width=666 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=666 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=659 height=13>Sacado</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top width=659 height=12><span class="campo">
<?php echo $dadosboleto["sacado"]?>
</span> 
</td>
</tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=cp valign=top width=7 height=12><img height=12 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top width=659 height=12><span class="campo">
<?php echo $dadosboleto["endereco1"]?>
</span> 
</td>
</tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=cp valign=top width=472 height=13> 
  <span class="campo">
  <?php echo $dadosboleto["endereco2"]?>
  </span></td>
<td class=ct valign=top width=7 height=13><img height=13 src=/escolar/docsupport/Boletos/imagens/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>Cód. 
baixa</td></tr><tr><td valign=top width=7 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=472 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=472 border=0></td><td valign=top width=7 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=/escolar/docsupport/Boletos/imagens/2.png width=180 border=0></td></tr></tbody></table><TABLE cellSpacing=0 cellPadding=0 border=0 width=666><TBODY><TR><TD class=ct  width=7 height=12></TD><TD class=ct  width=409 >Sacador/Avalista</TD><TD class=ct  width=250 ><div align=right>Autenticação 
mecânica - <b class=cp>Ficha de Compensação</b></div></TD></TR><TR><TD class=ct  colspan=3 ></TD></tr></tbody></table><TABLE cellSpacing=0 cellPadding=0 width=666 border=0><TBODY><TR><TD vAlign=bottom align=left height=50><?php #fbarcode($dadosboleto["codigo_barras"]); ?> 
 </TD>
</tr></tbody></table>
<TABLE cellSpacing=0 cellPadding=0 width=666 border=0>
<TR>
<TD class=ct width=666><span  style="font-size: 13px;font-family: Arial, Helvetica, sans-serif"><?php echo geraCodigoBarra($dadosboleto["codigo_barras"]);#@geraCodigoBarra($dadosboleto["linha_digitavel"]);?></span></TD>
</TR>

<TR>
<TD class=ct width=666><div align=right><?php echo $dadosboleto["codigo_barras"];?><br>Corte 
na linha pontilhada</div></TD></TR><TR><TD class=ct width=666><img height=1 src=/escolar/docsupport/Boletos/imagens/6.png width=665 border=0></TD></tr></table>
</BODY></HTML>
<?php
// Now collect the output buffer into a variable
$html = ob_get_contents();
ob_end_clean();

// send the captured HTML from the output buffer to the mPDF class for processing
$mpdf->WriteHTML($html);
$mpdf->Output();