<?php
//traz apenas n&uacute;meros para o campo que quiser
function soNumero($str) {
    $str = preg_replace("/[^0-9]/", "", $str);
    return $str;
}
function tiraPonto($valor){
	$pontos = array(",", ".", "-");
	$result = str_replace($pontos, "", $valor);
	return $result;
}

function formatoData($data){
    $data = explode("-",$data);
    return substr($data[2],0,2)."/".$data[1]."/".$data[0];
}

function formatoDataBD($data){
    $data = explode("/",$data);
    return $data[2]."-".$data[1]."-".$data[0];
}

function formatoMoeda($valor){
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

function formatoMoedaBD($valor){
	return str_replace(',','.',substr($valor,2));
}

function array_diff_assoc_recursive($array1, $array2){
    foreach($array1 as $key => $value){
        if(is_array($value)){
              if(!isset($array2[$key])){
                  $difference[$key] = $value;
              }elseif(!is_array($array2[$key])){
                  $difference[$key] = $value;
              }else{
                  $new_diff = array_diff_assoc_recursive($value, $array2[$key]);
                  if($new_diff != FALSE){
                        $difference[$key] = $new_diff;
                  }
              }
          }elseif(!isset($array2[$key]) || $array2[$key] != $value){
              $difference[$key] = $value;
          }
    }return !isset($difference) ? 0 : $difference;
} 

function mb_str_pad($input, $pad_length, $pad_string, $pad_style, $encoding = "UTF-8") {
    return str_pad($input, strlen($input) - mb_strlen($input, $encoding) + $pad_length, $pad_string, $pad_style);
}

function getLastCreatedFile() {
    $caminho = 'financeiro/remessa/geradas/';

    $lastestCTime = '';
    $lastestFileName = '';

    $dir = dir($caminho);

    while (false !== ($file = $dir->read())) {
        $filePath = $caminho . $file;

        if (is_file($filePath) && filectime($filePath) > $lastestCTime) {
            $lastestCTime = filectime($filePath);
            $lastestFileName = $file;
        }
    }

    if ($lastestFileName) {
        return file($caminho . $lastestFileName);
    } else {
        return false;
    }
}

function geraDVboleto($nossoNumero){
    $var = strrev($nossoNumero);
    $j=2;
    $soma=0;
    for($i=0;$i<strlen($var);$i++){
        
        $soma += (int)substr($var,$i,1) * (int)$j++;
        
    }
    $pos = strpos($soma/11,'.');
    if($pos!=""){
        $resto = substr($soma/11,0,$pos);
    }else{
        $resto = $soma/11;
    }

    $sobra = $soma -($resto*11);

    if($sobra == 10){
        $dv = 1;
    }elseif(($sobra ==1)||($sobra ==0)){
        $dv = 0;
    }else{
        $dv =11-$sobra;
    }

    return $dv;
}

function geraRemessa($nomeFilial,$dados,$cnpjFilial){

    $valorTotal = 0; //valor total dos títulos
    $tamLinha = 400; //tamanho da linha CNAB400
    $nSequencialArquivo = 001; //numero sequencial para geração da remessa

    $nSequencialArquivo = mb_str_pad($nSequencialArquivo, 7, '0', STR_PAD_LEFT); //organiza o sequencial ativo pelo formato indicado
    //caso exista algum arquivo gera o próximo
    while (file_exists('financeiro/remessa/geradas/' . $nSequencialArquivo . '.txt')) {
        $nSequencialArquivo = ((int) $nSequencialArquivo) + 1;
        $nSequencialArquivo = mb_str_pad($nSequencialArquivo, 7, '0', STR_PAD_LEFT);
    }

    #if(file_exists('financeiro/remessa/geradas/' . $nSequencialArquivo . '.txt')){
    #    echo "abre arquivo ".$nSequencialArquivo;
    #}else{
    #    echo "criar arquivo ".$nSequencialArquivo;
    #}
    $numRegistro = 1;
    $regboleto=0;
    $dados_header = array();
    $dados_trailer = array();
    $dados_detalhe = array();

    $linhaRegistro = '';
    //REGISTRO HEADER DA REMESSA
    $dados_header['registro']               = '0';
    $dados_header['remessa']                = '1';
    $dados_header['literal_transmissao']    = 'REMESSA';
    $dados_header['codigo_servico']         = '01';
    $dados_header['literal_servico']        = mb_str_pad('COBRANCA', 15, ' ', STR_PAD_RIGHT);
    $dados_header['codigo_transmissao']     = mb_str_pad('04370919641201300343', 20,' ',STR_PAD_LEFT);
    $dados_header['nome_beneficiario']      = mb_str_pad(substr($nomeFilial, 0, 29) . '.', 30, ' ', STR_PAD_RIGHT);
    $dados_header['codigo_banco']           = mb_str_pad('033', 3, ' ', STR_PAD_RIGHT);
    $dados_header['nome_banco']             = mb_str_pad('SANTANDER', 15, ' ', STR_PAD_RIGHT);
    $dados_header['data_geracao']           = date('dmy');
    $dados_header['zeros']                  = mb_str_pad('', 16, '0', STR_PAD_RIGHT);
    $dados_header['mensagen1']              = mb_str_pad('', 47, ' ', STR_PAD_RIGHT);
    $dados_header['mensagen2']              = mb_str_pad('', 47, ' ', STR_PAD_RIGHT);
    $dados_header['mensagen3']              = mb_str_pad('', 47, ' ', STR_PAD_RIGHT);
    $dados_header['mensagen4']              = mb_str_pad('', 47, ' ', STR_PAD_RIGHT);
    $dados_header['mensagen5']              = mb_str_pad('', 47, ' ', STR_PAD_RIGHT);
    $dados_header['brancos1']               = mb_str_pad('', 34, ' ', STR_PAD_RIGHT);
    $dados_header['brancos2']               = mb_str_pad('', 6, ' ', STR_PAD_RIGHT);
    $dados_header['versao']                 = mb_str_pad('', 3, '0', STR_PAD_RIGHT);
    $dados_header['numero_sequencial']      = mb_str_pad($numRegistro, 6, '0', STR_PAD_LEFT);

    $numRegistro++;
    //FIM REGISTRO HEADER DA REMESSA
/* 
?   Nº. do chamado: 2731700
?   Empresa: ESCOLA DE EDUCACAO INFANTIL FUTUROS GENI
?  CNPJ: 5567782000169
?  Produto: Cobrança Eletrônica
?  Ag: 0437
?  Cc: 130034306
?  Convênio: 9196412
?  Ambiente do convênio: PRODUÇÃO
?  Código de estação: 2X2M
?  Meio de troca de arquivos: Internet Banking
?  Código de transmissão 240: 043700009196412
?  Código de transmissão 400: 04370919641201300343

?  Complemento: I06

?  Carteira a inserir no arquivo remessa: 5 Rápida com Registro (Empresa imprime)
?  Carteira a inserir nos boletos: 101*/


// DETALHAMENTO DA REMESSA    
    if (count($dados)) {//se existem boletos na query
         
        foreach ($dados as $value) {



            $nNumIn = $value['nossoNumero'];
            #codigo do registro 1 posicao
            $dados_detalhe['cod_registro']              = mb_str_pad('1', 1, '', STR_PAD_LEFT);
            #tipo beneficiario 2 posicoes
            $dados_detalhe['cod_insc_beneficiario']     = mb_str_pad('02', 2, '', STR_PAD_LEFT);
            #cnpj caso beneficiario seja 02 14 digitos
            $dados_detalhe['num_inscricao']             = mb_str_pad($cnpjFilial, 14, '0', STR_PAD_LEFT);
            #codigo da agencia 4 digitos
            $dados_detalhe['agencia']                   = mb_str_pad('0437', 4, '0', STR_PAD_LEFT);
            #conta de movimento 8 digitos
            $dados_detalhe['conta_movimento']           = mb_str_pad('9196412', 8, '0', STR_PAD_LEFT);
            #conta beneficiario 8 digitos
            $dados_detalhe['conta_beneficiario']        = mb_str_pad('1300343', 8, '0', STR_PAD_LEFT);
            #numero de controle 25 digitos
            $dados_detalhe['controle']                  = mb_str_pad('', 25, '0', STR_PAD_LEFT);
            #nosso numero 8 digitos
            $dados_detalhe['nossonumero']               = mb_str_pad($nNumIn, 8, '0', STR_PAD_LEFT);
            #$dados_detalhe['nossonumero']               = mb_str_pad('', 8, '0', STR_PAD_LEFT);
            #data do segundo desconto 6 digitos
            $dados_detalhe['segundodesconto']           = mb_str_pad('', 6, '0', STR_PAD_LEFT);
            #branco 1 digito
            $dados_detalhe['branco']                    = mb_str_pad('', 1, ' ', STR_PAD_LEFT);
            #multa 1 digito
            $dados_detalhe['multa']                     = mb_str_pad('4', 1, '0', STR_PAD_LEFT);
            #percentual de atraso 4 digitos
            $dados_detalhe['percentualAtraso1']         = mb_str_pad('0200', 4, '0', STR_PAD_LEFT);
            #unidade de valor moeda corrente 2 digitos
            $dados_detalhe['moedaCorrente']             = mb_str_pad('00', 2, '0', STR_PAD_LEFT);
            #valor do titulo em outra unidade  13 digitos
            $dados_detalhe['titulo1']                   = mb_str_pad('', 13, '0', STR_PAD_LEFT);
            #brancos 4 digitos
            $dados_detalhe['branco2']                   = mb_str_pad('', 4, ' ', STR_PAD_LEFT);
            #data de cobranca da multa 6 digitos
            $dados_detalhe['dataMulta']                 = mb_str_pad('', 6, '0', STR_PAD_LEFT);
            #codigo da carteira 1 digito
            $dados_detalhe['codCarteira']               = mb_str_pad('5', 1, '0', STR_PAD_LEFT);
            #codigo da ocorrencia 2 digitos
            $dados_detalhe['cod_ocorrencia']            = mb_str_pad('01', 2, '0', STR_PAD_LEFT);

            #numero composto pelo rm+parcela+ano o resto completa com zeros a esquerda
            
            #seu numero 10 digitos
            $dados_detalhe['seuNumero']                 = mb_str_pad($value['identificador'], 10, '0', STR_PAD_LEFT);
            #$dados_detalhe['seuNumero']                 = mb_str_pad($value['nossoNumero'], 10, '0', STR_PAD_LEFT);
            #data de vencimento 6 digitos
            $dados_detalhe['vencimento']                = mb_str_pad(substr(soNumero(formatoData($value['venc'])),0,6), 6, '0', STR_PAD_LEFT);
            #valor do titulo 13 digitos
            $dados_detalhe['valor_titulo']              = mb_str_pad(soNumero(number_format($value['valor_parc'], 2, '.', '')), 13, '0', STR_PAD_LEFT);
            #numero do banco 3 digitos
            $dados_detalhe['cod_banco']                 = mb_str_pad('033', 3, '0', STR_PAD_LEFT);
            #agencia do banco 5 digitos
            $dados_detalhe['agencia_cobradora']         = mb_str_pad('0437', 5, '0', STR_PAD_RIGHT);
            #especie de documento 2 digitos
            $dados_detalhe['especie']                   = mb_str_pad('06', 2, '0', STR_PAD_LEFT);
            #Aceite 1 digito
            $dados_detalhe['aceite']                    = mb_str_pad('N', 1, ' ', STR_PAD_LEFT);
            #data de processamento do boleto ou geração do boleto 6 digitos
            $dados_detalhe['emissao']                   = mb_str_pad(substr(soNumero(formatoData($value['dtProcessamento'])),0,6), 6, '0', STR_PAD_LEFT);
            #Instruções de cobrança 2 digitos cada
            $dados_detalhe['instrucao1']                = mb_str_pad('', 2, '0', STR_PAD_LEFT);
            $dados_detalhe['instrucao2']                = mb_str_pad('', 2, '0', STR_PAD_LEFT);
            #Calsulo de % de mora(1% ao mes)
            $valorMora =  round(($value['valor_parc']/100)*0.0333,2);
            #valor da mora 13 digitos
            $dados_detalhe['mora']                      = mb_str_pad(soNumero($valorMora), 13, '0', STR_PAD_LEFT);
            #data limite para desconto 6 digitos
            $dados_detalhe['desconto_ate']              = mb_str_pad('', 6, '0', STR_PAD_LEFT);
            #valor de desconto
            $dados_detalhe['valor_desconto1']            = mb_str_pad('', 13, '0', STR_PAD_LEFT);
            #Valor do iof 13 digitos
            $dados_detalhe['iof1']                       = mb_str_pad('0', 11, '0', STR_PAD_LEFT);
            #valor do abatimento 11 digitos
            $dados_detalhe['abatimento1']                = mb_str_pad('0', 12, '0', STR_PAD_LEFT);
            #tipo inscricao pagador - 2 digitos
            $dados_detalhe['cod_inscricao']             = mb_str_pad('01', 5, '0', STR_PAD_LEFT);
            #14 posicoes
            $dados_detalhe['cpf']                       = mb_str_pad($value['cpf'], 14, '0', STR_PAD_LEFT);
            #40 posicoes
            $dados_detalhe['nome']                      = substr(mb_str_pad($value['nome'], 40, ' ', STR_PAD_RIGHT),0,40);
            #40 posicoes
            $dados_detalhe['logradouro']                = substr(mb_str_pad(substr($value['logradouro'],0,39), 40, ' ', STR_PAD_RIGHT),0,40);
            #12 posicoes
            $dados_detalhe['bairro']                    = substr(mb_str_pad(substr($value['bairro'],0,12), 12, ' ', STR_PAD_RIGHT),0,12);
            #cep 5 primeiras posicoes do campo 
            $dados_detalhe['cep']                       = mb_str_pad(substr($value['cep'],0,5), 5, '0', STR_PAD_LEFT);
            #cep 3 ultimas posicoes do campo
            $dados_detalhe['cep_comp']                  = mb_str_pad(substr($value['cep'],-3), 3, '0', STR_PAD_LEFT);
            #15 posicoes
            $dados_detalhe['cidade']                    = mb_str_pad($value['cidade'], 13, ' ', STR_PAD_RIGHT);
            #2 posicoes
            $dados_detalhe['estado']                    = mb_str_pad($value['estado'], 2, ' ', STR_PAD_RIGHT);
            #30 posicoes avalista(fiador) do boleto
            $dados_detalhe['sacador']                   = mb_str_pad('', 30, ' ', STR_PAD_LEFT);
            #branco 1 posicao
            $dados_detalhe['branco3']                   = mb_str_pad('', 1, ' ', STR_PAD_LEFT);
            #identificador complemento 1 posicao
            $dados_detalhe['identificadorComp']         = mb_str_pad('I', 1, '', STR_PAD_LEFT);
            #complemento 2 posicoes
            $dados_detalhe['complemento']               = mb_str_pad('06', 2, '', STR_PAD_LEFT);
            #brancos 6 posicoes
            $dados_detalhe['brancos4']                  = mb_str_pad('', 6, ' ', STR_PAD_LEFT);
            #dias corridos para protesto 2 posicoes
            $dados_detalhe['dias_protesto']             = mb_str_pad('', 2, '0', STR_PAD_LEFT);
            #branco 1 posicao
            $dados_detalhe['branco5']                   = mb_str_pad('', 1, ' ', STR_PAD_LEFT);
            #sequencial dos registros 6 posicoes
            $dados_detalhe['numero_sequencial']         = mb_str_pad($numRegistro, 6, '0', STR_PAD_LEFT);
            
            // Calcula o total dos tÃ­tulos
            $valorTotal += $value['valor_parc'];
            // Monta a linha referente ao registro do boleto
            $numRegistro++;
            $linhaRegistro .= implode('', $dados_detalhe);
            $linhaRegistro .= "\r\n";
            unset($dados_detalhe);
            
            }
        }
    

        #dados do Trailler da remessa
    #codigo do registro 1 campo
    $dados_trailer['tipo']              = '9';
    #quantidade de documentos 6 campos
    $dados_trailer['qtd_docs']          = mb_str_pad(($numRegistro), 6, '0', STR_PAD_LEFT);
    #valor somatorio 13 campos
    $dados_trailer['valor_total']       = mb_str_pad(soNumero($valorTotal), 13, '0', STR_PAD_LEFT);
    #zeros 374 campos
    $dados_trailer['zeros']           = mb_str_pad('', 374, '0', STR_PAD_RIGHT);
    #sequencial com total de linhas do registro 6 campos
    $dados_trailer['numero_sequencial'] = mb_str_pad($numRegistro, 6, '0', STR_PAD_LEFT);


    $linhaHeader = implode('', $dados_header);

    $linhaTrailer = implode('', $dados_trailer);

    if ($tamLinha == mb_strlen($linhaHeader, 'UTF-8') && $tamLinha == mb_strlen($linhaTrailer, 'UTF-8')) {

        $arquivo = 'financeiro/remessa/geradas/' . $nSequencialArquivo . '.txt';
        $fp = fopen($arquivo, 'w+');
        fwrite($fp, $linhaHeader . "\r\n");
        fwrite($fp, $linhaRegistro);
        fwrite($fp, $linhaTrailer . "\r\n");
        fclose($fp);
   
            return $arquivo;
            /*
            echo "<pre>";
            print_r($dados_header);
            print_r($linhaRegistro);
            print_r($dados_trailer);
            echo "</pre>";*/
    }else{
        echo "ocorreu algum erro<br>";
        echo "header".mb_strlen($linhaHeader, 'UTF-8')."<br>";
        echo "trailer".mb_strlen($linhaTrailer, 'UTF-8');
    }
}