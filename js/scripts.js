//fun&ccedil; &atilde;o que retorna somente numeros
function soNumeros(e) {
    var charCode = e.charCode ? e.charCode : e.keyCode;
    // charCode 8 = backspace   
    // charCode 9 = tab
    if (charCode != 8 && charCode != 9) {
        // charCode 48 equivale a 0   
        // charCode 57 equivale a 9
        if (charCode < 45 || charCode > 57) {
            return false;
        }
    }
}

//fun&ccedil;&atilde;o utilizada na passagem de disciplinas para classe
function passarItensSelects(idDe, idPara, idParaHidden, idHidden, tudo) {
    document.getElementById(idHidden).value = '';
    idDe += (tudo) ? ' option' : ' option:selected';
    $("#" + idPara).append($("#" + idDe));
    $("#" + idParaHidden + " option").each(function() {
        if (document.getElementById(idHidden).value == '') {
            document.getElementById(idHidden).value = this.value;
        } else {
            document.getElementById(idHidden).value += "," + this.value;
        }
    });
}
/* verificar a quest&atilde;o do respons&aacute;vel e adaptar esse script */
function consultarResponsavel(el, path, tipo, indice) {
    var param = $.trim(document.getElementById("cpfResponsavel").value.replace('-', ''));
    console.log(param);
    if (param.length > 0) {
        var msgErro = "O servi&ccedil;o est&aacute; temporariamente indispon &iacute;vel. Tente novamente em alguns segundos";
        el.src = path + "app/view/css/img/loading.gif";
        $("div#alertaResponsavel").hide();

        $.ajax({
            type: "GET",
            url: path + "https://webtecno.net.br/ws/responsavel.php?param=" + param + "&path=" + path + "&tipo=" + tipo,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            timeout: 5000,
            async: false,
            success: function(msg) {
                if (msg[0] != 0) {
                    var tabela = $("table.responsaveis");
                    var classe = ' class="alternada" ';
                    if (tabela.find("tr").length % 2 == 0) {
                        classe = "";
                    }

                    tabela.append('<tr ' + classe + '><td>' + msg[2] + '</td><td>'+ msg[10] +'</td><td><input type="radio" value="'+msg[0]+'" name="respFinanc" onclick="repopularResponsaveis();"></td>' +
                            '<td><img style="float:left;cursor:pointer;" onclick="removerResponsavel(this);" title="Remover respons&aacute;vel" src="' + path + 'app/view/css/img/delete.png"></td>' +
                            '<td><img style="float:left;cursor:pointer;" onclick="copiarEndResponsavel(this, ' + msg[0] + ',\'' + path + '\');" title="Copiar endere&ccedil;o do respons&aacute;vel" src="' + path + 'app/view/css/img/casa.png">' +
                            '<span class="idResponsaveis">' + msg[0] + '</span></td></tr>');
                    repopularResponsaveis();
                } else {
                    $("div#alertaResponsavel").find("strong").text(param);
                    $("div#alertaResponsavel").show();
                }
                el.src = path + "app/view/css/img/alerta.png";
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                el.src = path + "app/view/css/img/alerta.png";
                alert(msgErro);
            }
        });

        document.getElementById("cpfResponsavel").focus();
        document.getElementById("cpfResponsavel").value = "";
    } else {
        el.src = path + "app/view/css/img/alerta.png";
        alert("Um CPF v&aacute;lido deve ser informado");
    }
}

function removerResponsavel(el) {
    removerLinhaTabela(el);
    repopularResponsaveis();
}

//Verifica&ccedil;&atilde;o de CPF
function verificaCPF(tipo) {
    if(tipo == 'vinculaResponsavel'){
        var strCPF = document.getElementById("cpfResponsavel").value;
    }else if(tipo == 'addResponsavel'){
        var strCPF = document.getElementById("cpf").value;
    }
    strCPF = strCPF.replace(/[^\d]+/g,'');
    var Soma;
    var Resto;
    Soma = 0;
  if (strCPF == "00000000000") return false;
     
  for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
  Resto = (Soma * 10) % 11;
   
    if ((Resto == 10) || (Resto == 11))  Resto = 0;
    if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;
   
  Soma = 0;
    for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
    Resto = (Soma * 10) % 11;
   
    if ((Resto == 10) || (Resto == 11))  Resto = 0;
    if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;
    return true;
    
}
