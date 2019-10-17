<?php
include './cabecalho.php';
$cliente = new cliente();
$venda = new venda();
$vendaItem = new vendaItem();
$funcionario = new funcionario();

$vendaLinha = $venda->get($_GET['venda']);
$clienteLinha = $cliente->get($vendaLinha['cliente']);
$valor = $venda->getTotal($vendaLinha['id']);
$funcionarioLinha  = $funcionario->get($vendaLinha['funcionario']);

$data = date("d/m/Y");
?>
<div id="conteudo">
    <div style="border: #666666 solid 1px; border-radius: 10px; width: 100%; padding: 10px; width: 100%;">
        <table border="1" style="margin: 0 auto; border-collapse: collapse;">
            <tr>
                <td style="text-align: right;">Recebemos de:</td>
                <td colspan="3"><?php echo $clienteLinha['nome']; ?></td>
            </tr>
            <tr>
                <td style="text-align: right;">A importância total de:</td>
                <td colspan="3"><?php echo funcoes::formatarMonetario($vendaLinha['valor_pago']); ?></td>
            </tr>
            <tr>
                <td style="text-align: center;" colspan="1">Referente aos produtos:</td>
                <td style="text-align: left;" colspan="3">
                    <?php
                    $vendaItemTabela = $vendaItem->getByVenda($vendaLinha['id']);
                    $item = new item();
                    foreach ($vendaItemTabela as $vendaItemLinha) {
                        $itemLinha = $item->get($vendaItemLinha['item']);
                        echo $itemLinha['nome']."<br/>";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">Que constam no <br/>aluguel de código:</td>
                <td colspan="3"><?php echo $vendaLinha['id']; ?></td>
            </tr>
        </table>        
    <br/><br/>
    P.S: A importância total é referente a soma de todos os pagamentos efetuados até o momento.
    <div style="border: #666666 solid 1px; border-radius: 10px; width: 100%; padding: 10px;">
        <table>
            <tr>
                <td style="text-align: center;">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<?php echo funcoes::formatarData($data); ?>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
                <td style="text-align: center;">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<?php echo $funcionarioLinha['nome'];  ?>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
            </tr>
            <tr>
                <td style="text-align: center;">Data</td>
                <td style="text-align: center;">Maison Trajes Finos</td>
            </tr>
        </table>
    </div>    
    <br/><br/><br/>

</div>
<div class="botoes">
    <?php funcoes::gerarPdf(true); ?>
</div>
<?php
include './rodape.php';