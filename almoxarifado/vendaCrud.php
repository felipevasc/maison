<?php
include ("./cabecalho.php");

$nomeTabela = funcoes::nomeTabelaByCaminho(__FILE__);
$tabela = new $nomeTabela();
$mesa = new mesa();
$acao = "";
if (empty($_POST['mesa'])) {
    $_POST['mesa'] = $mesa->getMesaVazia();
}
if (!empty($_GET['acao']) && $_GET['acao'] == "deletar") {
    $ok = $tabela->delete($_GET['id']);
    $acao = "Remoção de Aluguel";
    $destino = "clienteRel.php";
}
else if (!empty($_POST['acao']) && $_POST['acao'] == "transferir") {
    $ok = $tabela->transferir($_POST);
    $acao = "Transferência de Aluguel";
    $mesa = new mesa();
    $mesaLinha = $mesa->get($_POST['mesa']);
    $destino = "vendaMesas.php?restaurante={$mesaLinha['restaurante']}";
}
else if (!empty($_GET['acao']) && $_GET['acao'] == "reabrir") {
    $ok = $tabela->reabrir($_GET);
    $acao = "Fechamento de Aluguel";
    $destino = "vendaMesas.php?restaurante=1";
    funcoes::alerta("Aluguel reaberto com sucesso", "{$destino}");
    exit;
}
else if (!empty($_POST['acao']) && $_POST['acao'] == "fechar") {
    $venda = new venda();
    $vendaLinha = $venda->get($_POST['id']);
    if ($vendaLinha['valor_pago'] < $venda->getTotal($vendaLinha['id'])) {
        $ok = FALSE;
        $acao = "Fechamento de Aluguel";
        $destino = "vendaFechar.php?venda={$vendaLinha['id']}";
        funcoes::alerta("Só é possivel fechar a venda, após efetuar o pagamento", "{$destino}");
        exit;
    }
    else {
        $ok = $tabela->fechar($_POST);
        $acao = "Fechamento de Aluguel";
        $destino = "vendaMesas.php?restaurante={$_POST['restaurante']}";
        funcoes::alerta("Os códigos dos produtos já foram colocados novamente?", "{$destino}");
        exit;
    }
        
}
else if (!empty($_POST['acao']) && $_POST['acao'] == "pagar") {
    $linha = $tabela->get($_POST['id']);
    $_POST['valor_atual'] = $_POST['valor_pago'];
    $_POST['valor_pago'] += $linha['valor_pago'];
    if ($_POST['valor_pago'] > $tabela->getTotal($_POST['id'])) {
        funcoes::alerta("Erro ao realizar o pagamento. O valor pago supera o valor do aluguel!", "vendaFechar.php?venda={$_POST['id']}");
        exit;
    }
    if ($_SESSION['login'] !== 'NAOMY'  && $_SESSION['login'] !== 'MIRELA' && $_SESSION['login'] !== 'admin' && $_SESSION['login'] !== 'maisonrel') {
        funcoes::alerta("Error! Você não tem permissão para efetuar pagamentos!", "vendaFechar.php?venda={$_POST['id']}");
        exit;
    }
    $_POST['funcionario_pagamento'] = $_SESSION['id_funcionario'];
    
    $ok = $tabela->update($_POST);
    $acao = "Pagamento de Aluguel";
    $destino = "vendaFechar.php?venda={$_POST['id']}";
}
else if (!empty($_POST['id'])) {
    $ok = $tabela->update1($_POST);
    $acao = "Atualização do Aluguel";
    $destino = "vendaVisualizarPedido.php?venda={$_POST['id']}";
}
else {
    $ok = $tabela->set($_POST);
    $acao = "Abertura de Aluguel";
    $destino = "realizarVenda.php?mesa={$_POST['mesa']}";
}

if ($ok) {
    $label = $tabela->label;
    funcoes::alerta("{$acao} efetuado com sucesso!", "{$destino}");
}
else {
    $label = $tabela->label;
    funcoes::alerta("Falha ao realizar {$acao}!", "{$destino}");
}
include ("./rodape.php");
?>