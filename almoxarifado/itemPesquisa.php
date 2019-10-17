<?php

include("cabecalho.php");
$item = new item();
$categoria = new categoria();
$venda = new venda();

$itemTabela = $item->getByNome($_GET['nome']);
$vendaLinha = $venda->get($_GET['venda']);
$categoria_atual = 0;
echo "<div>";
foreach ($itemTabela as $itemLinha) {
    if (!$item->checkDisponivel($itemLinha['id'], $vendaLinha['entrega'])) {
        continue;
    }
    if ($categoria_atual <> $itemLinha['categoria']) {
        $categoria_atual = $itemLinha['categoria'];
        $categoriaLinha = $categoria->get($categoria_atual);
        echo "</div><hr/><div class='panel panel-default' style='text-align: center;'>";
        echo "<div class='panel-heading'>{$categoriaLinha['nome']}</div>";
    }
    echo "<span onclick=\"abrirPagina('vendaAdicionarItem.php?venda={$_GET['venda']}&item={$itemLinha['id']}')\">" . $item->exibeItem($itemLinha['id']) . "</span>";
}
echo "</div>";

