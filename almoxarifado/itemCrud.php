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
}

if ($ok) {
    $id_categoria = '';
    if (!empty($_POST['categoria'])) {
        $id_categoria = $_POST['categoria'];
    }
    $label = $tabela->label;
    funcoes::alerta("{$acao} de {$label} efetuado com sucesso!", "{$nomeTabela}List.php?categoria={$id_categoria}");
}
else {
    $label = $tabela->label;
    funcoes::alerta("Falha ao realizar {$acao} de {$label}!", "{$nomeTabela}List.php");
}
include ("./rodape.php");
?>