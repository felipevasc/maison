<?php
include("cabecalho.php");
$mesa = new mesa();
$venda = new venda();
$mesaLinha = $mesa->get($_GET['id']);
$id_venda_aberta = $mesa->getVendaAberta($mesaLinha['id']);
if (!empty($id_venda_aberta)) {
    $situacao_venda = $venda->getSituacaoVenda($id_venda_aberta);
}
else {
    $situacao_venda = "3";
}
echo $situacao_venda;