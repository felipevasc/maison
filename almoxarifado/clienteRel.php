<?php
include ("./cabecalho.php");

$cliente = new cliente();
$venda = new venda();
$vendaItem = new vendaItem();
$item = new item();
$funcionario = new funcionario();




?>
<form method="get">
    <table class="form_mobile">
        <tbody>
            <tr>
                <td>
                    Cliente:<br/>
                    <select name="cliente">
                        <option value="">Selecione um Cliente</option>
                        <?php
                        if (empty($_GET['cliente'])) {
                            $_GET['cliente'] = '';
                        }                    
                        funcoes::montaSelect($cliente->obterTodos(), 'id', 'nome', $_GET['cliente']); 
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    Data Inicio:
                    <?php
                    if (empty($_GET['inicio'])) {
                        $_GET['inicio'] = date("d/m/Y");
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
                        $_GET['fim'] = date("d/m/Y");
                    }
                    ?>
                    <input type="text" data-tipo="data" name="fim" value="<?php echo $_GET['fim']; ?>"/>
                </td>
            </tr>
        </tbody>
    </table> 
    <input type="submit" class="btn btn-success">
</form>

<table class='table table-hover table-striped table-ordered'>
    <caption>
        Relatório de Alugueis de Cliente
    </caption>
    <thead>
        <tr>
            <th>Cliente</th>
            <th>Funcionário</th>
            <th>Data Retirada</th>
            <th>Data Devolução</th>
            <th>Cod Pedido</th>
            <th>Produtos</th>
            <th>Valor da Venda</th>
            <th>Valor Pago</th>            
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $vendaTabela = $venda->obterTodosByParametros1($_GET['cliente'], $_GET['inicio'], $_GET['fim']);
        foreach ($vendaTabela as $vendaLinha) {
            $funcionarioLinha  = $funcionario->get($vendaLinha['funcionario']);
            $clienteLinha = $cliente->get($vendaLinha['cliente']);
            $valor = $venda->getTotal($vendaLinha['id']);
            ?>
            <tr>
                <td><?php echo $clienteLinha['nome'];?> </td>
                <td><?php echo $funcionarioLinha['nome'];  ?></td>
                <td><?php echo funcoes::formatarData($vendaLinha['entrega']);?></td>
                <td><?php echo funcoes::formatarData($vendaLinha['devolucao']);?></td>
                <td><?php echo $vendaLinha['id'];?></td>
                <td>
                    <?php
                    $vendaItemTabela = $vendaItem->getByVenda($vendaLinha['id']);
                    foreach ($vendaItemTabela as $vendaItemLinha) {
                        $itemLinha = $item->get($vendaItemLinha['item']);
                        echo $itemLinha['nome']."<br/>";
                        echo $vendaItemLinha['obs']."<br/>";
                    }
                    ?>
                </td>
                <td><?php echo funcoes::formatarMonetario($valor);?></td>
                <td><?php echo funcoes::formatarMonetario($vendaLinha['valor_pago']);?></td>
                <td style="text-align: center; vertical-align: middle;" rowspan="<?php echo $rowspan_acoes; ?>">
                    <img src="../auxiliares/ico/check.png" style="width: 20px; cursor: pointer;" title="Reabrir Venda" onclick="confirma('Tem certeza que deseja reabrir a venda selecionada?', function(){ abrirPagina('vendaCrud.php?acao=reabrir&id=<?php echo $vendaLinha['id']; ?>') })";/>
                    <img src="../auxiliares/ico/cancelar.png" style="width: 20px; cursor: pointer;" title="Remover Item" onclick="confirma('Tem certeza que deseja remover a venda selecionada?', function(){ abrirPagina('vendaCrud.php?acao=deletar&id=<?php echo $vendaLinha['id']; ?>') })";/>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
