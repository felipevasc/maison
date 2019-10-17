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
$mesaLinha = $mesa->get($vendaMesaTabela[0]['mesa']);
?>
<form method="POST" action="vendaCrud.php" onsubmit="if (parseFloat($('#valor_pago').val()) < 0) { alert('O valor a pagar está negativo'); setTimeout(removerMascara, 1000); return false; }">
    <div class="btn-group btn-group-justified btn-lg ">
        <a type="button" class="btn btn-info btn-lg" onclick="abrirPagina('realizarVenda.php?mesa=<?php echo $vendaMesaTabela[0]['mesa']; ?>')">
            <img src="../auxiliares/ico/voltar.png" style="width: 25px;"/>
            Voltar
        </a>
        <a type="button" class="btn btn-warning btn-lg" onclick="confirma('Utilize o desconto por item abaixo! Utilizar aqui somente em casos especiais! Continuar?', function () {
                    abrirPagina('vendaDesconto.php?venda=<?php echo $vendaLinha['id']; ?>')
                })">

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
            Detalhes do Pedido<br/>        
            <?php
            if (!empty($vendaLinha['cliente'])) {
                $clienteLinha = $cliente->get($vendaLinha['cliente']);
                echo "Cliente: " . $clienteLinha['nome'];
            }
            ?>
        </caption>
        <thead>
            <tr>
                <th style="width: 40px;"></th>
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
                } else if (empty($vendaItemLinha['entregue'])) {
                    $class = "alert-success";
                    $title = "Item pronto, ainda não entregue";
                } else {
                    $class = "alert-info";
                    $title = "Item entregue";
                }
                $valor_total += $vendaItemLinha['quantidade'] * $vendaItemLinha['valor'];
                ?>
                <tr class="img-hover">
                    <td style="text-align: left;"><img src="../auxiliares/ico/seta-vermelha.png" class="img-remover" title="Aplicar Desconto" onclick="confirma('Tem certeza adicionar desconto neste item?', function () {
                                abrirPagina('vendaDesconto.php?venda=<?php echo $vendaLinha['id']; ?>&item=<?php echo $vendaItemLinha['item']; ?>')
                            })"/></td>
                    <td style="text-align: left;"><?php echo $itemLinha['nome']; ?></td>
                    <td style="text-align: center;"><?php echo $vendaItemLinha['quantidade']; ?></td>
                    <td style="text-align: center;"><?php echo "R$ " . number_format($vendaItemLinha['valor'], 2, ',', '.'); ?></td>
                    <td style="text-align: center;"><?php echo "R$ " . number_format($vendaItemLinha['valor'] * $vendaItemLinha['quantidade'], 2, ',', '.'); ?></td>
                </tr>
                <?php
                $adicionalVendaItemTabela = $adicionalVendaItem->getByVendaItem($vendaItemLinha['id']);
                foreach ($adicionalVendaItemTabela as $adicionalVendaItemLinha) {
                    $adicionalLinha = $adicional->get($adicionalVendaItemLinha['adicional']);
                    $valor_total += $vendaItemLinha['quantidade'] * $adicionalLinha['valor'];
                    ?>
                    <tr class="img-hover">
                        <td style="text-align: left;"><img src="../auxiliares/ico/cancelar.png" class="img-remover" title="Remover Adicional" onclick="confirma('Tem certeza que deseja remover o adicional do pedido selecionado?', function () {
                                    abrirPagina('adicionalVendaItemCrud.php?acao=deletar&id=<?php echo $adicionalVendaItemLinha['id']; ?>&venda=<?php echo $_GET['venda']; ?>')
                                })"/></td>
                        <td style="text-align: left; padding-left: 15px;">
                            <img src="../auxiliares/ico/bola_roxa.png" style="width: 16px;"/>
                            <?php echo $adicionalLinha['nome']; ?>
                        </td>
                        <td style="text-align: center;">
                            <?php echo $vendaItemLinha['quantidade']; ?>
                        </td>
                        <td style="text-align: center;"><?php echo "R$ " . number_format($adicionalLinha['valor'], 2, ',', '.'); ?></td>
                        <td style="text-align: center;"><?php echo "R$ " . number_format($adicionalLinha['valor'] * $vendaItemLinha['quantidade'], 2, ',', '.'); ?></td>
                    </tr>
                    <?php
                }
            }
            if (count($vendaServicoTabela) == 0 && count($vendaDescontoTabela) == 0) {
                $class = "alert-info";
            } else {
                $class = "";
            }
            ?>
            <tr class="<?php echo $class; ?>">
                <td colspan="4" style="text-align: right; font-weight: bold;">Valor Total:</td>
                <td style="text-align: center; font-weight: bold;"><?php echo "R$ <span id='total'>" . number_format($valor_total, 2, '.', '') . "</span>"; ?></td>
            </tr>
            <?php
            if (count($vendaServicoTabela) > 0) {
                foreach ($vendaServicoTabela as $vendaServicoLinha) {
                    $valor_total += $vendaServicoLinha['valor'] * $vendaServicoLinha['quantidade'];
                    ?>
                    <tr class="img-hover">
                        <td style="text-align: left;"><img src="../auxiliares/ico/cancelar.png" class="img-remover" title="Remover Serviço" onclick="confirma('Tem certeza que deseja remover o serviço selecionado?', function () {
                                    abrirPagina('vendaServicoCrud.php?acao=deletar&id=<?php echo $vendaServicoLinha['id']; ?>&venda=<?php echo $_GET['venda']; ?>')
                                })"/></td>
                        <td><?php echo $vendaServicoLinha['descricao']; ?></td>
                        <td style="text-align: center;"><?php echo $vendaServicoLinha['quantidade']; ?></td>
                        <td style="text-align: center;"><?php echo "R$ " . number_format($vendaServicoLinha['valor'], 2, '.', ''); ?></td>
                        <td style="text-align: center;"><?php echo "R$ " . number_format($vendaServicoLinha['valor'] * $vendaServicoLinha['quantidade'], 2, '.', ''); ?></td>
                    </tr>
                    <?php
                }
                if (count($vendaDescontoTabela) == 0) {
                    $class = "alert-info";
                } else {
                    $class = "";
                }
                ?>
                <tr class="<?php echo $class; ?>">
                    <td colspan="4" style="text-align: right; font-weight: bold;">Valor Total:</td>
                    <td style="text-align: center; font-weight: bold;"><?php echo "R$ <span id='total'>" . number_format($valor_total, 2, '.', '') . "</span>"; ?></td>
                </tr>
                <?php
            }

            if (count($vendaDescontoTabela) > 0) {
                foreach ($vendaDescontoTabela as $vendaServicoLinha) {
                    $valor_total += $vendaServicoLinha['valor'] * $vendaServicoLinha['quantidade'];
                    ?>
                    <tr class="img-hover">
                        <td style="text-align: left;"><img src="../auxiliares/ico/cancelar.png" class="img-remover" title="Remover Desconto" onclick="confirma('Tem certeza que deseja remover o desconto selecionado?', function () {
                                    abrirPagina('vendaServicoCrud.php?acao=deletar&id=<?php echo $vendaServicoLinha['id']; ?>&venda=<?php echo $_GET['venda']; ?>')
                                })"/></td>
                        <td> - <?php echo $vendaServicoLinha['descricao']; ?></td>
                        <td style="text-align: center;"><?php echo $vendaServicoLinha['quantidade']; ?></td>
                        <td style="text-align: center;"><?php echo "R$ " . number_format($vendaServicoLinha['valor'], 2, '.', ''); ?></td>
                        <td style="text-align: center;"><?php echo "R$ " . number_format($vendaServicoLinha['valor'] * $vendaServicoLinha['quantidade'], 2, '.', ''); ?></td>
                    </tr>
                    <?php
                }
                ?>
                <tr class="alert-info">
                    <td colspan="4" style="text-align: right; font-weight: bold;">Valor Total:</td>
                    <td style="text-align: center; font-weight: bold;"><?php echo "R$ <span id='total'>" . number_format($valor_total, 2, '.', '') . "</span>"; ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr style="cursor: pointer;" title="Ver pagamentos" onclick="abrirPagina('pagamentoVendaList.php?venda=<?php echo $vendaLinha['id']; ?>')">
                <td colspan="4" style="text-align: right;">Pago:</td>
                <td style="text-align: center;">
                    <?php echo number_format($vendaLinha['valor_pago'], 2, '.', ''); ?>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right;">Restante:</td>
                <td style="text-align: center;">
                    <span id="restante"><?php echo number_format($valor_total - $vendaLinha['valor_pago'], 2, '.', ''); ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right;">Pagar:</td>
                <td style="text-align: center;">
                    <input type="hidden" id="valor_total" value="<?php echo number_format($venda->getTotal($vendaLinha['id']), 2, '.', ''); ?>"/>
                    <input type="text" id="valor_pago" name="valor_pago" data-tipo="monetario" value="<?php echo number_format($valor_total - $vendaLinha['valor_pago'], 2, '.', ''); ?>" style="border-radius: 10px; padding: 3px; width: 150px;" onblur="calcularTroco(this)"/>                
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right;">Troco:</td>
                <td style="text-align: center;">
                    R$ <span id="troco">0.00</span>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right;">Tipo Pagamento:</td>
                <td style="text-align: center;">
                    <select name="forma_pagamento" style="border-radius: 10px; padding: 5px;">
                        <?php
                        funcoes::montaSelect($formaPagamento->obterTodos(), "id", "descricao", "");
                        ?>
                    </select>
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
        <input type="hidden" name="data" value="<?php echo $vendaLinha['data']; ?>"/>
        <input type="hidden" name="acao" value="fechar"/>
        <input type="hidden" name="restaurante" value="<?php echo $mesaLinha['restaurante']; ?>"/>
        <tfoot>
            <tr>
                <td>
                    <div class="btn-group btn-group-justified1 btn-lg ">
                        <a type="button" class="btn btn-info btn-lg" onclick="abrirPagina('relatorioContratoAluguel.php?venda=<?php echo $vendaMesaTabela[0]['venda']; ?>', true)">
                            <img src="../auxiliares/ico/impressora.png" style="width: 25px;"/>
                            Imprimir Comum
                        </a>
                        <a type="button" class="btn btn-info btn-lg" onclick="abrirPagina('relatorioContratoAluguelNoiva.php?venda=<?php echo $vendaMesaTabela[0]['venda']; ?>', true)">
                            <img src="../auxiliares/ico/impressora.png" style="width: 25px;"/>
                            Imprimir Noiva
                        </a>
                        <a type="button" class="btn btn-info btn-lg" onclick="abrirPagina('relatorioRecibo.php?venda=<?php echo $vendaMesaTabela[0]['venda']; ?>', true)">
                            <img src="../auxiliares/ico/impressora.png" style="width: 25px;"/>
                            Imprimir Recibo
                        </a>
                        <a type="button" class="btn btn-info btn-lg" onclick="abrirPagina('cupomVenda.php?venda=<?php echo $vendaMesaTabela[0]['venda']; ?>', true)">
                            <img src="../auxiliares/ico/impressora.png" style="width: 25px;"/>
                            Senha Pedido
                        </a>
                        <button type="submit" class="btn btn-success btn-lg">
                            <img src="../auxiliares/ico/check.png" style="width: 25px;"/>
                            Arquivar Pedido / Finalizar
                        </button>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
</form>

<?php
include("rodape.php");
