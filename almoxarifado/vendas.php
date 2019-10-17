<?php
include("cabecalho.php");
if (empty($_GET['inicio'])) {
    $_GET['inicio'] = date('d/m/Y', mktime(0, 0, 0, date('m'), date('d') + 1 - date('w'), date('Y')));
}
if (empty($_GET['fim'])) {
    $_GET['fim'] = date('d/m/Y', mktime(0, 0, 0, date('m'), date('d') + 7 - date('w'), date('Y')));
}
?>
<form method="get">
    <table class="form_mobile">
        <tbody>
            <tr>
                <td>
                    Data Inicio:
                    <?php
                    if (empty($_GET['inicio'])) {
                        $_GET['inicio'] = '';
                    }
                    ?>
                    <input type="text" data-tipo="data" name="inicio" value="<?php echo $_GET['inicio']; ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    Data Fim:
                    <?php
                    if (empty($_GET['fim'])) {
                        $_GET['fim'] = '';
                    }
                    ?>
                    <input type="text" data-tipo="data" name="fim" value="<?php echo $_GET['fim']; ?>"/>
                </td>
            </tr>
        </tbody>
    </table> 
    <input type="submit" class="btn btn-success">
</form>
<table id="tabela" class='table table-hover table-striped table-ordered'>
    <caption>
        Relatório de Vendas
    </caption>
    <thead>
        <tr>
            <th>Cliente</th>
            <th>Contato</th>
            <th>Data Entrega</th>
            <th>Pedido</th>
            <th>Pronto</th>
            <th>Entregue</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $vendaItem = new vendaItem();

        $tabela = $vendaItem->getByData($_GET['inicio'], $_GET['fim']);
        $id_venda = 0;
        $qtd = 0;
        foreach ($tabela as $linha) {
            
            if ($id_venda != $linha['venda']) {
                $tmp[$id_venda] = $qtd;
                $id_venda = $linha['venda'];
                $qtd = 0;
            }
            $qtd++;
        }
        $tmp[$id_venda] = $qtd;
        $id_venda = 0;
        $i = 0;
        foreach ($tabela as $item) {
            if ($id_venda != $item['venda']) {
                $i++;
            }
            if ($i % 2 == 0) {
                $cor = "#FFFFFF";
            }
            else {
                $cor = "#DDDDDD";
            }
            ?>
            <tr style="background: none; background-color: <?php echo $cor; ?>">
                <?php
                if ($id_venda != $item['venda']) {
                    ?>
                    <td style="vertical-align: middle;" rowspan="<?php echo $tmp[$item['venda']]; ?>"><?php echo $item['cliente']; ?></td>
                    <td style="vertical-align: middle;" rowspan="<?php echo $tmp[$item['venda']]; ?>"><?php echo $item['numero']; ?></td>
                    <td style="vertical-align: middle;" rowspan="<?php echo $tmp[$item['venda']]; ?>"><?php echo funcoes::formatarData($item['entrega']); ?></td>
                    <?php
                }
                ?>
                <td><?php echo $item['item']; ?></td>                
                <td>
                    <?php
                    if (empty($item['pronto'])) {
                        echo "Não";
                    } else {
                        echo "Sim";
                    }
                    ?>
                </td>                
                <td>
                    <?php
                    if (empty($item['entregue'])) {
                        echo "Não";
                    } else {
                        echo "Sim";
                    }
                    ?>
                </td>
                <?php
                if ($id_venda != $item['venda']) {
                    
                    ?>
                    <td rowspan="<?php echo $tmp[$item['venda']]; ?>" style="text-align: center; vertical-align: middle;" rowspan="<?php echo $rowspan_acoes; ?>">
                        
                        <img src="../auxiliares/ico/bola_verde.png" style="width: 20px; cursor: pointer;" title="Pronto / Entregue" onclick="abrirPagina('vendaVisualizarPedido.php?venda=<?php echo $item['venda']; ?>');"/>
                        <img src="../auxiliares/ico/bola_preto.png" style="width: 20px; cursor: pointer;" title="Fechar Pedido / Devolução / Pagar" onclick="abrirPagina('vendaFechar.php?venda=<?php echo $item['venda']; ?>');"/>
                    </td>
                    <?php
                    $id_venda = $item['venda'];
                }
                ?>                
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
<?php
include("rodape.php");
?>