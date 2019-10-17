<?php
include("cabecalho.php");

$venda = new venda();
$vendaMesa = new vendaMesa();
$cliente = new cliente();
$mesa = new mesa();
$vendaItem = new vendaItem();
$item = new item();
$adicionalVendaItem = new adicionalVendaItem();
$adicional = new adicional();

$vendaLinha = $venda->get($_GET['venda']);
$vendaMesaTabela = $vendaMesa->getMesasVenda($vendaLinha['id']);

?>
<div class="btn-group btn-group-justified btn-lg ">
    <a type="button" class="btn btn-info btn-lg" onclick="abrirPagina('realizarVenda.php?mesa=<?php echo $vendaMesaTabela[0]['mesa']; ?>')">
        <img src="../auxiliares/ico/voltar.png" style="width: 25px;"/>
        Voltar
    </a>
    <a type="button" class="btn btn-danger btn-lg" onclick="abrirPagina('vendaEditar.php?venda=<?php echo $vendaLinha['id']; ?>')">
        <img src="../auxiliares/ico/check.png" style="width: 25px;"/>
        Editar
    </a>
    <a type="button" class="btn btn-danger btn-lg" onclick="abrirPagina('vendaFechar.php?venda=<?php echo $vendaLinha['id']; ?>')">
        <img src="../auxiliares/ico/check.png" style="width: 25px;"/>
        Fechar/Pagar/Imprimir Contrato
    </a>
</div>
<table class="table table-hover table-striped table-ordered">
    <caption style="text-align: center;">
        Listagem de Pedido<br/>
        <?php
        if (!empty($vendaLinha['cliente'])) {
            $clienteLinha = $cliente->get($vendaLinha['cliente']);
            echo "Cliente: ".$clienteLinha['nome'];
        }
        ?>
    </caption>
    <thead>
        <tr>
            <th style="text-align: center;">Item</th>
            <th style="text-align: center;">Quantidade</th>
            <th style="text-align: center;">Valor</th>
            <th style="text-align: center;">Imagem</th>
            <th style="text-align: center;">Total</th>
            <th style="text-align: center;">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $qtd_total = 0;
        $valor_total = 0;
        $vendaItemTabela = $vendaItem->getByVenda($vendaLinha['id']);
        foreach ($vendaItemTabela as $vendaItemLinha) {
            $itemLinha = $item->get($vendaItemLinha['item']);
            if (empty($vendaItemLinha['pronto'])) {
                $class = "alert-warning";
                $title = "Item solicitado, ainda não feito";
            }
            else if (empty($vendaItemLinha['entregue'])) {
                $class = "alert-success";
                $title = "Item pronto, ainda não entregue";
            }
            else {
                $class = "alert-info";
                $title = "Item entregue";
            }
            $qtd_total += $vendaItemLinha['quantidade'];
            $valor_total += $vendaItemLinha['quantidade'] * $itemLinha['valor'];
            $adicionalVendaItemTabela = $adicionalVendaItem->getByVendaItem($vendaItemLinha['id']);
            ?>
            <tr class="<?php echo $class; ?>">
                <td title="<?php echo $title; ?>" style="text-align: left;"><?php echo "Nome:".$itemLinha['nome']; ?><br><?php echo "Observações:".$vendaItemLinha['obs']; ?></td>
                <td style="text-align: center;"><?php echo $vendaItemLinha['quantidade']; ?></td>
                <td style="text-align: center;"><?php echo "R$ ".number_format($vendaItemLinha['valor'], 2, ',', '.'); ?></td>
                <td style="text-align: center; cursor: pointer;" title="Visualizar imagem" onclick="abrirFoto('<?php echo $itemLinha['imagem']; ?>')"><img src="<?php echo $itemLinha['imagem']; ?>" style="width: 120px; height: 160px;"/></td>
                <td style="text-align: center;"><?php echo "R$ ".number_format($itemLinha['valor'] * $vendaItemLinha['quantidade'], 2, ',', '.'); ?></td>
                <td style="text-align: center; vertical-align: middle;" rowspan="<?php echo $rowspan_acoes; ?>">
                    <?php
                    if (empty($vendaItemLinha['pronto'])) {
                        ?>
                        <img onclick="abrirPagina('vendaItemCrud.php?id=<?php echo $vendaItemLinha['id']; ?>&pronto=true&acao=atualizar')" title="Marcar item como pronto" src="../auxiliares/ico/bola_amarela.png" style="width: 20px; cursor: pointer;"/>
                        <?php
                    }
                    else if (empty($vendaItemLinha['entregue'])) {
                        ?>
                        <img onclick="abrirPagina('vendaItemCrud.php?id=<?php echo $vendaItemLinha['id']; ?>&entregue=true&acao=atualizar')" title="Marcar item como entregue" src="../auxiliares/ico/bola_vermelha.png" style="width: 20px; cursor: pointer;"/>
                        <?php
                    }
                    else {
                        ?>
                        <img title="Item entregue" src="../auxiliares/ico/check.png" style="width: 20px;"/>
                        
                        <?php
                    }
                    ?>
                    
                    <img src="../auxiliares/ico/cancelar.png" style="width: 20px; cursor: pointer;" title="Remover Item" onclick="confirma('Tem certeza que deseja remover o item selecionado?', function(){ abrirPagina('vendaItemCrud.php?acao=deletar&id=<?php echo $vendaItemLinha['id']; ?>') })";/>
                    <img src="../auxiliares/ico/lapis.png" style="width: 20px; cursor: pointer;" title="Editar Item" onclick="abrirPagina('vendaAdicionarItem1.php?venda=<?php echo $vendaLinha['id']; ?>&item=<?php echo $vendaItemLinha['id']; ?>');"/>
                </td>
            </tr>
            <?php
            foreach ($adicionalVendaItemTabela as $adicionalVendaItemLinha) {
                $adicionalLinha = $adicional->get($adicionalVendaItemLinha['adicional']);
                ?>
                <tr class="<?php echo $class; ?>">
                    <td style="text-align: left; padding-left: 15px;">
                        <img src="../auxiliares/ico/bola_roxa.png" style="width: 16px;"/>
                        <?php echo $adicionalLinha['nome']; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php echo $vendaItemLinha['quantidade']; ?>
                    </td>
                    <td style="text-align: center;"><?php echo "R$ ".number_format($adicionalLinha['valor'], 2, ',', '.'); ?></td>
                <td style="text-align: center;"><?php echo "R$ ".number_format($adicionalLinha['valor'] * $vendaItemLinha['quantidade'], 2, ',', '.'); ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <!--<td style="text-align: right;"><img title="Todos Itens Pronto" src="../auxiliares/ico/pronto.jpg" style="width: 200px; height: 67px;"/></td>
            <td style="text-align: right;"><img title="Todos Itens Entregue" src="../auxiliares/ico/entregue.jpg" style="width: 200px; height: 67px;"/></td>
            <td style="text-align: right;"><img title="Todos Itens Preparando" src="../auxiliares/ico/preparando.jpg" style="width: 200px; height: 67px;"/></td>-->
            <td colspan="4"style="text-align: right;">Valor Total:</td>
            <td style="text-align: center;"><?php echo "R$ ".number_format($valor_total, 2, '.', ','); ?></td>
            <td></td>
        </tr>
    </tfoot>
</table>
<?php
include("rodape.php");
