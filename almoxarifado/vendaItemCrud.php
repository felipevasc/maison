<?php
include ("./cabecalho.php");

$nomeTabela = funcoes::nomeTabelaByCaminho(__FILE__);
$tabela = new $nomeTabela();
$acao = "";
if (!empty($_GET['acao']) && $_GET['acao'] == "deletar") {
    $itemVendaLinha = $tabela->get($_GET['id']);
    $destino = "vendaVisualizarPedido.php?venda={$itemVendaLinha['venda']}";
    $ok = $tabela->delete($_GET['id']);
    $acao = "Remoção";
}else if (!empty($_GET['acao']) && $_GET['acao'] == 'atualizar') {
    $ok = $tabela->update($_GET);
    $itemVendaLinha = $tabela->get($_GET['id']);
    $acao = "AtualizaÃ§Ã£o";
    $destino = "vendaVisualizarPedido.php?venda={$itemVendaLinha['venda']}";
}

else if (!empty($_GET['acao']) && $_GET['acao'] == 'atualizar1') {
    $tmp['id'] = $_GET['id'];
    $tmp['obs'] = $_POST['obs'];
    $ok = $tabela->update($tmp);
    echo $ok;
    $vendaItemLinha = $tabela->get($_GET['id']);
    $acao = "Atualização";
    $destino = "vendaVisualizarPedido.php?venda={$vendaItemLinha['venda']}";
}
else if (!empty($_GET['acao']) && $_GET['acao'] == 'alterar') {
    $ok = $tabela->update($_GET);
    $itemVendaLinha = $tabela->get($_GET['id']);
    $acao = "Edição";
    
}
else if (!empty($_POST['id'])) {
    $ok = $tabela->update($_POST);
    $acao = "Atualização";
    $destino = "clientList.php";
}
else {
    $vendaMesa = new vendaMesa();
    $mesa = new mesa();
    $item = new item();
    
    $ok = $tabela->set($_POST);
    $vendaMesaTabela = $vendaMesa->getMesasVenda($_POST['venda']);
    if (count($vendaMesaTabela) > 0) {
        $destino = "realizarVenda.php?mesa={$vendaMesaTabela[0]['mesa']}";
    }
    else {
        $destino = "realizarVenda.php?venda={$_POST['venda']}";
    }
    $itemLinha = $item->get($_POST['item']);
    $acao = "Cadastro";
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