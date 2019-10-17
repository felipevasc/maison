<?php
include("cabecalho.php");

$venda = new venda();
$vendaMesa = new vendaMesa();
$cliente = new cliente();
$mesa = new mesa();
$vendaItem = new vendaItem();
$item = new item();
$vendaServico = new vendaServico();
$formaPagamento = new formaPagamento();
$adicionalVendaItem = new adicionalVendaItem();
$adicional = new adicional();

$vendaLinha = $venda->get($_GET['venda']);
$vendaMesaTabela = $vendaMesa->getMesasVenda($vendaLinha['id']);
?>
<form method="POST" action="vendaCrud.php">
    <div class="btn-group btn-group-justified btn-lg ">
        <a type="button" class="btn btn-info btn-lg" onclick="abrirPagina('realizarVenda.php?mesa=<?php echo $vendaMesaTabela[0]['mesa']; ?>')">
            <img src="../auxiliares/ico/voltar.png" style="width: 25px;"/>
            Voltar
        </a>
        <a type="button" class="btn btn-warning btn-lg" onclick="abrirPagina('vendaDesconto.php?venda=<?php echo $vendaLinha['id']; ?>')">
            <img src="../auxiliares/ico/seta-vermelha.png" style="width: 25px;"/>
            Ceder Desconto
        </a>
        <a type="button" class="btn btn-primary btn-lg" onclick="abrirPagina('vendaServico.php?venda=<?php echo $vendaLinha['id']; ?>')">
            <img src="../auxiliares/ico/seta-verde.png" style="width: 25px;"/>
            Adicionar Serviço
        </a>
    </div>
    <table class="table table-hover table-striped table-ordered">
        <caption style="text-align: center;">
            Listagem de Pedido<br/>
            Mesa:
            <?php
            foreach ($vendaMesaTabela as $i => $vendaMesaLinha) {
                $mesaLinha = $mesa->get($vendaMesaLinha['mesa']);
                if ($i > 0) {
                    echo " / ";
                }
                echo $mesaLinha['nome'];
            }
            ?><br/>
            <?php
            if (!empty($vendaLinha['cliente'])) {
                $clienteLinha = $cliente->get($vendaLinha['cliente']);
                echo "Garçom: ".$clienteLinha['nome'];
            }
            ?>
        </caption>
        <thead>
            <tr>
                <th style="text-align: center;">Item</th>
                <th style="text-align: center;">Quantidade</th>
                <th style="text-align: center;">Valor</th>
                <th style="text-align: center;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $valor_total = 0;
            $vendaItemTabela = $vendaItem->getByVenda($vendaLinha['id']);
            $vendaServicoTabela = $vendaServico->getServicosByVenda($vendaLinha['id']);
            $vendaDescontoTabela = $vendaServico->getDescontosByVenda($vendaLinha['id']);
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
                $valor_total += $vendaItemLinha['quantidade'] * $itemLinha['valor'];
                $adicionalVendaItemTabela = $adicionalVendaItem->getByVendaItem($vendaItemLinha['id']);
                ?>
                <tr>
                    <td style="text-align: left;"><?php echo $itemLinha['nome']; ?></td>
                    <td style="text-align: center;"><?php echo $vendaItemLinha['quantidade']; ?></td>
                    <td style="text-align: center;"><?php echo "R$ ".number_format($itemLinha['valor'], 2, ',', '.'); ?></td>
                    <td style="text-align: center;"><?php echo "R$ ".number_format($itemLinha['valor'] * $vendaItemLinha['quantidade'], 2, ',', '.'); ?></td>
                </tr>
                <?php
                foreach ($adicionalVendaItemTabela as $adicionalVendaItemLinha) {
                    $adicionalLinha = $adicional->get($adicionalVendaItemLinha['adicional']);
                    $valor_total += $vendaItemLinha['quantidade'] * $adicionalLinha['valor'];
                    ?>
                    <tr>
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
            if (count($vendaServicoTabela) == 0 && count($vendaDescontoTabela) == 0) {
                $class = "alert-info";
            }
            else {
                $class = "";
            }
            ?>
            <tr class="<?php echo $class; ?>">
                <td colspan="3" style="text-align: right; font-weight: bold;">Valor Total:</td>
                <td style="text-align: center; font-weight: bold;"><?php echo "R$ <span id='total'>".number_format($valor_total, 2, '.', '')."</span>"; ?></td>
            </tr>
            <?php

            if (count($vendaServicoTabela) > 0) {
                foreach ($vendaServicoTabela as $vendaServicoLinha) {
                    $valor_total += $vendaServicoLinha['valor'] * $vendaServicoLinha['quantidade'];
                    ?>
                    <tr>
                        <td><?php echo $vendaServicoLinha['descricao']; ?></td>
                        <td style="text-align: center;"><?php echo $vendaServicoLinha['quantidade']; ?></td>
                        <td style="text-align: center;"><?php echo "R$ ".number_format($vendaServicoLinha['valor'], 2, '.', ''); ?></td>
                        <td style="text-align: center;"><?php echo "R$ ".number_format($vendaServicoLinha['valor'] * $vendaServicoLinha['quantidade'], 2, '.', ''); ?></td>
                    </tr>
                    <?php
                }
                if (count($vendaDescontoTabela) == 0) {
                    $class = "alert-info";
                }
                else {
                    $class = "";
                }
                ?>
                <tr class="<?php echo $class; ?>">
                    <td colspan="3" style="text-align: right; font-weight: bold;">Valor Total:</td>
                    <td style="text-align: center; font-weight: bold;"><?php echo "R$ <span id='total'>".number_format($valor_total, 2, '.', '')."</span>"; ?></td>
                </tr>
                <?php
            }

            if (count($vendaDescontoTabela) > 0) {
                foreach ($vendaDescontoTabela as $vendaServicoLinha) {
                    $valor_total += $vendaServicoLinha['valor'] * $vendaServicoLinha['quantidade'];
                    ?>
                    <tr>
                        <td> - <?php echo $vendaServicoLinha['descricao']; ?></td>
                        <td style="text-align: center;"><?php echo $vendaServicoLinha['quantidade']; ?></td>
                        <td style="text-align: center;"><?php echo "R$ ".number_format($vendaServicoLinha['valor'], 2, '.', ''); ?></td>
                        <td style="text-align: center;"><?php echo "R$ ".number_format($vendaServicoLinha['valor'] * $vendaServicoLinha['quantidade'], 2, '.', ''); ?></td>
                    </tr>
                    <?php
                }
                ?>
                <tr class="alert-info">
                    <td colspan="3" style="text-align: right; font-weight: bold;">Valor Total:</td>
                    <td style="text-align: center; font-weight: bold;"><?php echo "R$ <span id='total'>".number_format($valor_total, 2, '.', '')."</span>"; ?></td>
                </tr>
                <?php
            }

            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;">Pago:</td>
                <td style="text-align: center;">
                    <?php echo number_format($vendaLinha['valor_pago'], 2, '.', ''); ?>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;">Restante:</td>
                <td style="text-align: center;">
                    <?php echo number_format($valor_total - $vendaLinha['valor_pago'], 2, '.', ''); ?>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;">Total pago:</td>
                <td style="text-align: center;">
                    <input type="hidden" id="valor_total" value="<?php echo number_format($venda->getTotal($vendaLinha['id']), 2, '.', ''); ?>"/>
                    <input type="text" name="valor_pago" data-tipo="monetario" value="<?php echo number_format($valor_total, 2, '.', ''); ?>" style="border-radius: 10px; padding: 3px; width: 150px;" onblur="if (this.value == '') this.value = '0.00'; $('#troco').html(formataFloat(parseFloat(this.value) - parseFloat($('#valor_total').val()), 2))"/>                
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;">Troco:</td>
                <td style="text-align: center;">
                    R$ <span id="troco">0.00</span>
                </td>
            </tr>
        </tfoot>
    </table>
    <div style="float: right;">

            <input type="hidden" name="id" value="<?php echo $vendaLinha['id']; ?>"/>
            <input type="hidden" name="acao" value="pagar"/>
            <input type="hidden" name="restaurante" value="<?php echo $mesaLinha['restaurante']; ?>"/>
            <button type="submit" class="btn btn-success btn-lg">
                <img src="../auxiliares/ico/check.png" style="width: 25px;"/>
                Pagar
            </button>

    </div>
</form>
<form method="POST" action="vendaCrud.php">
    <table class="form_mobile">
        <input type="hidden" name="id" value="<?php echo $vendaLinha['id']; ?>"/>
        <input type="hidden" name="acao" value="fechar"/>
        <input type="hidden" name="restaurante" value="<?php echo $mesaLinha['restaurante']; ?>"/>
        <tbody>
            <!--
            <tr>
                <td style="text-align: right; padding-right: 10px;">
                    Forma de Pagamento <br/>
                    <select name="forma_pagamento">
                        <?php
                        funcoes::montaSelect($formaPagamento->obterTodos(), "id", "descricao", "");
                        ?>
                    </select>
                </td>
            </tr> -->
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <div class="btn-group btn-group-justified1 btn-lg ">
                        <a type="button" class="btn btn-info btn-lg" onclick="abrirPagina('relatorioContratoAluguel.php?venda=<?php echo $vendaMesaTabela[0]['venda']; ?>', true)">
                            <img src="../auxiliares/ico/impressora.png" style="width: 25px;"/>
                            Imprimir Contrato Comum
                        </a>
                        <a type="button" class="btn btn-info btn-lg" onclick="abrirPagina('relatorioContratoAluguelNoiva.php?venda=<?php echo $vendaMesaTabela[0]['venda']; ?>', true)">
                            <img src="../auxiliares/ico/impressora.png" style="width: 25px;"/>
                            Imprimir Contrato Noiva
                        </a>
                        <a type="button" class="btn btn-info btn-lg" onclick="abrirPagina('relatorioRecibo.php?venda=<?php echo $vendaMesaTabela[0]['venda']; ?>', true)">
                            <img src="../auxiliares/ico/impressora.png" style="width: 25px;"/>
                            Imprimir Recibo
                        </a>
                        <button type="submit" class="btn btn-success btn-lg">
                            <img src="../auxiliares/ico/check.png" style="width: 25px;"/>
                            Fechar
                        </button>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
</form>

<?php
include("rodape.php");
