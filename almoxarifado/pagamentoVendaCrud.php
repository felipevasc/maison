<?php
include ("./cabecalho.php");

$nomeTabela = funcoes::nomeTabelaByCaminho(__FILE__);
$tabela = new $nomeTabela();
$acao = "";
if (!empty($_GET['acao']) && $_GET['acao'] == "deletar") {
    $row = $tabela->get($_GET['id']);
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
}

if ($ok) {
    $label = $tabela->label;
    funcoes::alerta("{$acao} de {$label} efetuado com sucesso!", "vendaFechar.php?venda={$row['venda']}");
}
else {
    $label = $tabela->label;
    funcoes::alerta("Falha ao realizar {$acao} de {$label}!", "vendaFechar.php?venda={$row['venda']}");
}
include ("./rodape.php");
?>