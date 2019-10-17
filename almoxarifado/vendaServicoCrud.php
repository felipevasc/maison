<?php
include ("./cabecalho.php");

$nomeTabela = funcoes::nomeTabelaByCaminho(__FILE__);
$tabela = new $nomeTabela();
$acao = "";
if (!empty($_GET['acao']) && $_GET['acao'] == "deletar") {
    $ok = $tabela->delete($_GET['id']);
    $acao = "Remoção";
    $destino = "vendaFechar.php?venda={$_GET['venda']}";
}
else if (!empty($_GET['acao']) && $_GET['acao'] == 'atualizar') {
    $ok = $tabela->update($_GET);
    $itemVendaLinha = $tabela->get($_GET['id']);
    $acao = "Atualização";
    $destino = "vendaVisualizarPedido.php?venda={$itemVendaLinha['venda']}";
}
else {    
    $ok = $tabela->set($_POST);
    $destino = "vendaFechar.php?venda={$_POST['venda']}";
    $acao = "Cadastro";
}

if ($ok) {
    $label = $tabela->label;
    funcoes::alerta("{$acao} de {$label} efetuado com sucesso!", "{$destino}");
}
else {
    $label = $tabela->label;
    funcoes::alerta("Falha ao realizar {$acao} de {$label}!", "{$destino}");
}
include ("./rodape.php");
?>