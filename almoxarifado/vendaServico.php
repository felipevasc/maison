<?php
include("cabecalho.php");

$venda = new venda();
$vendaMesa = new vendaMesa();
$cliente = new cliente();
$mesa = new mesa();

$vendaLinha = $venda->get($_GET['venda']);
$vendaMesaTabela = $vendaMesa->getMesasVenda($vendaLinha['id']);
$total_venda = $venda->getTotal($vendaLinha['id']);
?>
<div class="btn-group btn-group-justified btn-lg ">
    <a type="button" class="btn btn-info btn-lg" onclick="abrirPagina('vendaFechar.php?venda=<?php echo $vendaLinha['id']; ?>')">
        <img src="../auxiliares/ico/voltar.png" style="width: 25px;"/>
        Voltar
    </a>
</div>
<table class="table">
    <caption style="text-align: center;">
        Adicionar Serviço<br/>
        Mesa:
        <?php
        foreach ($vendaMesaTabela as $i => $vendaMesaLinha) {
            $mesaLinha = $mesa->get($vendaMesaLinha['venda']);
            if ($i > 0) {
                echo " / ";
            }
            echo $mesaLinha['nome'];
        }
        ?><br/>
        <?php
        if (!empty($vendaLinha['cliente'])) {
            $clienteLinha = $cliente->get($vendaLinha['cliente']);
            echo "Cliente: ".$clienteLinha['nome'];
        }
        ?>
    </caption>
</table>
<form method="POST" action="vendaServicoCrud.php">
    <input type="hidden" name="venda" value="<?php echo $vendaLinha['id']; ?>"/>
    <input type="hidden" name="tipo" value="servico"/>
    <table class="form_mobile">
        <tbody>
            <tr>
                <td>Valor Total: R$ <span id="total"><?php echo number_format($total_venda, 2, '.', ''); ?></span></td>
            </tr>
            <tr>
                <td>
                    Descrição<br/>
                    <input type="text" name="descricao" value="Multa de Atraso"/>
                </td>
            </tr>
            <tr>
                <td>
                    Valor %<br/>
                    <input type="text" id="porcentual" data-tipo="monetario" value="10.00" onblur="if (checkValorMaximo(this, 100)) ajustarValorServico();"/>
                </td>
            </tr>
            <tr>
                <td>
                    Valor R$<br/>
                    <input type="text" id="valor" data-tipo="monetario" name="valor" value="<?php echo number_format($total_venda * 0.1, 2, '.', ''); ?>" onblur="if (checkValorMaximo(this, <?php echo $total_venda; ?>)) ajustarPercentualServico();"/>
                </td>
            </tr>
            <tr>
                <td>
                    Quantidade<br/>
                    <input type="text" data-tipo="float" name="quantidade" value="1"/>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <button class='btn btn-large btn-block btn-success' type='submit'>Adicionar Serviço</button>
                </td>
            </tr>
        </tfoot>
    </table>    
</form>

<?php
include("rodape.php");