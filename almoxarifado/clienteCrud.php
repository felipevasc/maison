<?php
include ("./cabecalho.php");

$nomeTabela = funcoes::nomeTabelaByCaminho(__FILE__);
$tabela = new $nomeTabela();
$acao = "";
$destino = "{$nomeTabela}List.php";
if (!empty($_GET['acao']) && $_GET['acao'] == "deletar") {
    $ok = $tabela->delete($_GET['id']);
    $acao = "Remoção";
}
else if (!empty($_POST['id'])) {
    $ok = $tabela->update($_POST);
    $acao = "Atualização";
}
else {
    $ok = $tabela->set($_POST);
    $acao = "Cadastro";
    $rs = $tabela->getByCondicao("WHERE upper(nome) like upper('{$_POST['nome']}') ORDER BY 1 DESC LIMIT 1");
    $destino = "realizarVenda.php?cliente={$rs[0]['id']}";
}

if ($ok) {
    $label = $tabela->label;
    funcoes::alerta("{$acao} de {$label} efetuado com sucesso!", "{$destino}");
}
else {
    $label = $tabela->label;
    funcoes::alerta("Falha ao realizar {$acao} de {$label}!", "{$nomeTabela}List.php");
}
include ("./rodape.php");
?>


