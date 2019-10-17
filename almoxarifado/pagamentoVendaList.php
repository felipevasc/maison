<?php
include("cabecalho.php");

$pagamentoVenda = new pagamentoVenda();
$rs = $pagamentoVenda->getByVenda($_GET['venda']);
?>
<table class="table table-hover table-striped table-ordered">
    <caption style="text-align: center;">
        Pagamentos da Venda
    </caption>
    <thead>
        <tr>
            <th>Data</th>
            <th>Valor</th>
            <th>Forma de Pagamento</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $formaPagamento = new formaPagamento();
        foreach ($rs as $row) {
            ?>
            <tr>
                <td><?php echo funcoes::formatarData($row['data']); ?></td>
                <td><?php echo number_format($row['valor'], 2, '.', ','); ?></td>
                <td><?php echo $formaPagamento->getDescricao($row['forma_pagamento']); ?></td>
                <td>
                    <img src="../auxiliares/ico/cancelar.png" style="width: 20px; cursor: pointer;" title="Remover" onclick="confirma('Remover o pagamento desta venda?', function(){ abrirPagina('pagamentoVendaCrud.php?acao=deletar&id=<?php echo $row['id']; ?>'); })"/>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
 <div class="btn-group btn-group-justified1 btn-lg ">
    <a type="button" class="btn btn-info btn-lg" onclick="abrirPagina('vendaFechar.php?venda=<?php echo $_GET['venda']; ?>')">
        <img src="../auxiliares/ico/impressora.png" style="width: 25px;"/>
        Voltar
    </a>
 </div>
<?php
include("rodape.php");
?>