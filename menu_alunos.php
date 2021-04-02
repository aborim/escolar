<table width="100%" border="0" cellpadding="0" cellspacing="0" class="abasAluno">
    <tr>
    <td><a href="menu_restrito.php?op=ver_aluno&idAluno=<?=$idAluno?>&nome=<?=base64_encode($nomeAluno)?>">Dados do aluno</a></td>
    <td><a href="menu_restrito.php?op=vincular_responsavel&idAluno=<?=$idAluno?>&nome=<?=base64_encode($nomeAluno)?>">Vincular Respons&aacute;veis</a></td>
    <td><a href="menu_restrito.php?op=ficha_medica&idAluno=<?=$idAluno?>&nome=<?=base64_encode($nomeAluno)?>">Ficha M&eacute;dica</a></td>
    <td><a href="menu_restrito.php?op=documentos_adicionais&idAluno=<?=$idAluno?>&nome=<?=base64_encode($nomeAluno)?>">Documentos adicionais</a></td>
    <td><a href="menu_restrito.php?op=informacoes_gerais&idAluno=<?=$idAluno?>&nome=<?=base64_encode($nomeAluno)?>">Informa&ccedil; &otilde;es gerais do aluno</a></td>
    </tr>
</table>