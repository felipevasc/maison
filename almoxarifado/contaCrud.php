<?php
include ("./cabecalho.php");

$conta = new conta();
if (!empty($_GET['acao']) && $_GET['acao'] == "deletar") {
    $ok = $conta->delete($_GET['id']);
    if ($ok) {
        funcoes::alerta("Conta removida com sucesso!", "contaList.php");
    }
    else {
        funcoes::alerta("Falha ao remover a conta!", "contaList.php");
    }
}
else if (!empty($_GET['acao']) && $_GET['acao'] == "pagar") {
    $tmp['id'] = $_GET['conta'];
    $tmp['pago'] = "TRUE";
    $tmp['data_pagamento'] = date("d/m/Y");
    $ok = $conta->update($tmp);
    if ($ok) {
        funcoes::alerta("Conta paga com sucesso!", "contaPagar.php");
    }
    else {
        funcoes::alerta("Falha ao pagar a conta!", "contaPagar.php");
    }
}
else {
    if ($_POST['pago'] == "TRUE") {
        $_POST['data_pagamento'] = date("d/m/Y");
    }
    $x = $conta->set($_POST);
    if ($x) {
        funcoes::alerta("Conta cadastrada com sucesso!", "contaForm.php");    
    }
    else {
        funcoes::alerta("Erro ao cadastrar a conta!", "contaForm.php");    
    }
}
include ("./rodape.php");
?>