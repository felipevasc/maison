<?php
$_GET['ajax'] = true;
include("cabecalho.php");
$categoria = new categoria();
$item = new item();
$venda = new venda();

$categoriaLinha = $categoria->get($_GET['categoria']);
$vendaLinha = $venda->get($_GET['venda']);

//echo "<label style='display: inline-block; width: 100%;' class='label label-success'>{$categoriaLinha['nome']}</label>";
$categoriaTabela0 = $categoria->getByPai($categoriaLinha['id']);

$itemTabela = $item->getByCategoria($categoriaLinha['id']);
if (count($itemTabela) > 0) {
    foreach ($itemTabela as $itemLinha) {
        if (!$item->checkDisponivel($itemLinha['id'], $vendaLinha['entrega'])) {
            continue;
        }
        echo "<span onclick=\"abrirPagina('vendaAdicionarItem.php?venda={$vendaLinha['id']}&item={$itemLinha['id']}')\">" . $item->exibeItem($itemLinha['id']) . "</span><span style='display: inline-block; position: relative; width: 90%;'><img style='float: right; position: absolute; top: -42px; right: -38px; cursor: pointer;' title='Visualizar imagem' onclick=\"abrirFoto('{$itemLinha['imagem']}');\" src='../auxiliares/ico/vestido.png'/></span>";
    }
} else if (count($categoriaTabela0) == 0) {
    echo " - Sem itens cadastrados para esta categoria - ";
}
if (count($categoriaTabela0) > 0) {
    echo " <div class='abas-ui'>";
    echo "  <ul>";
    foreach ($categoriaTabela0 as $categoriaLinha0) {
        echo "<li><a href='#tabs-{$categoriaLinha0['id']}'>{$categoriaLinha0['nome']}</a></li>";
    }
    echo "  </ul>";
    foreach ($categoriaTabela0 as $categoriaLinha0) {
        echo "<div id='tabs-{$categoriaLinha0['id']}'>";
        $categoriaTabela1 = $categoria->getByPai($categoriaLinha0['id']);
        $itemTabela1 = $item->getByCategoria($categoriaLinha0['id']);
        if (count($itemTabela1) > 0) {
            foreach ($itemTabela1 as $itemLinha1) {
                if (!$item->checkDisponivel($itemLinha1['id'], $vendaLinha['entrega'])) {
                    continue;
                }
                echo "<span onclick=\"abrirPagina('vendaAdicionarItem.php?venda={$vendaLinha['id']}&item={$itemLinha1['id']}')\">" . $item->exibeItem($itemLinha1['id']) . "</span><span style='display: inline-block; position: relative; width: 90%;'><img style='float: right; position: absolute; top: -42px; right: -38px; cursor: pointer;' title='Visualizar imagem' onclick=\"abrirFoto('{$itemLinha1['imagem']}');\" src='../auxiliares/ico/vestido.png'/></span>";
            }
        } else if (count($categoriaTabela1) == 0) {
            echo " - Sem itens cadastrados para esta categoria - ";
        }
        if (count($categoriaTabela1) > 0) {
            echo " <div class='abas-ui'>";
            echo "  <ul>";
            foreach ($categoriaTabela1 as $categoriaLinha1) {
                echo "<li><a href='#tabs-{$categoriaLinha1['id']}'>{$categoriaLinha1['nome']}</a></li>";
            }
            echo "  </ul>";
            foreach ($categoriaTabela1 as $categoriaLinha1) {
                echo "<div id='tabs-{$categoriaLinha1['id']}'>";
                $itemTabela2 = $item->getByCategoria($categoriaLinha1['id']);
                if (count($itemTabela2) > 0) {
                    echo "<hr/>";
                    foreach ($itemTabela2 as $itemLinha2) {
                        if (!$item->checkDisponivel($itemLinha2['id'], $vendaLinha['entrega'])) {
                            continue;
                        }
                        echo "<span onclick=\"abrirPagina('vendaAdicionarItem.php?venda={$vendaLinha['id']}&item={$itemLinha2['id']}')\">" . $item->exibeItem($itemLinha2['id']) . "</span>";
                    }
                }

                echo "</div>";
            }
            echo "</div>";
        }
        echo "</div>";
    }
    echo "</div>";
}