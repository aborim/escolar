<div class="form_end">
    <div><label>CEP</label><input type="text" name="txt_cep" id="txt_cep" class="form_campo" placeholder="00000-000" value="<?php if(@$resultado[0]['cep']!=""){echo $resultado[0]['cep'];}?>"></div>
    <div><label>Endere&ccedil;o</label><input type="text" name="txt_endereco" id="txt_endereco" class="form_campo"  value="<?php if(@$resultado[0]['endereco']!=""){echo $resultado[0]['endereco'];}?>"></div>
    <div><label>N&uacute;mero</label><input type="text" name="txt_num" id="txt_num" class="form_campo"  value="<?php if(@$resultado[0]['numero']!=""){echo $resultado[0]['numero'];}?>"></div>
    <div><label>Complemento</label><input type="text" name="txt_comp" id="txt_comp" class="form_campo" value="<?php if(@$resultado[0]['complemento']!=""){echo $resultado[0]['complemento'];}?>" ></div>
    <div><label>Bairro</label><input type="text" name="txt_bairro" id="txt_bairro" class="form_campo" value="<?php if(@$resultado[0]['bairro']!=""){echo $resultado[0]['bairro'];}?>" ></div>
    <div><label>Munic&iacute;pio</label><input type="text" name="txt_cidade" id="txt_cidade" class="form_campo" value="<?php if(@$resultado[0]['cidade']!=""){echo $resultado[0]['cidade'];}?>" ></div>
    <div><label>Estado</label><input type="text" name="txt_estado" id="txt_estado" class="form_campo" value="<?php if(@$resultado[0]['estado']!=""){echo $resultado[0]['estado'];}?>"></div>
</div>
<script type="text/javascript">
    $('#txt_cep').change(function() {
        $.getJSON('https://viacep.com.br/ws/'+ $('#txt_cep').val() +'/json/', function(data) {
            var endereco    = data.logradouro;
            var bairro      = data.bairro;
            var cidade      = data.localidade;
            var uf          = data.uf;

            $('#txt_endereco').val(endereco);
            $('#txt_cidade').val(cidade);
            $('#txt_bairro').val(bairro);
            $('#txt_estado').val(uf);
        });
    });

    

</script>