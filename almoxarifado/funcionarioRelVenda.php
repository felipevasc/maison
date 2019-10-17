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
                    Funcionário:<br/>
                    <select name="funcionario">
                        <option value="<?php echo $_SESSION['id_funcionario']?>"><?php echo $_SESSION['login']?></option>
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
<!--<script>
    function exibirTabela() {
        var x = prompt('Informe a senha para exibir o relatório');
        if (x == 'Levi1234') {
            $('#tabela').show();
        }
    }
</script>
<button onclick="exibirTabela()">
    Informar senha
</button>-->

<div id="conteudo">
<table id="tabela" class='table table-hover table-striped table-ordered'>
    <caption>
        Relatório de Vendas dos Funcionários
    </caption>
    <thead>
        <tr>
            <th>Funcionário</th>
            <th>Cliente</th>
            <th>Data do Pedido</th>
            <th>Data Retirada</th>
            <th>Data Devolução</th>
            <th>Cod Pedido</th>
            <th>Produto</th>
            <th>Valor da Venda</th>          
        </tr>
    </thead>
    <tbody>
        <?php
        $vendaTabela = $venda->obterTodosByFuncionario($_SESSION['id_funcionario'], $_GET['inicio'], $_GET['fim']);
        $total_venda = 0;
        $total_pago = 0;
        $numero_vendas = 0;
        foreach ($vendaTabela as $vendaLinha) {
            $funcionarioLinha  = $funcionario->get($vendaLinha['funcionario']);
            $clienteLinha = $cliente->get($vendaLinha['cliente']);
            $valor = $venda->getTotal($vendaLinha['id']);
            $total_venda += $valor;
            $numero_vendas ++;
            $total_pago += $vendaLinha['valor_pago'];
            ?>
            <tr>
                <td><?php echo $funcionarioLinha['nome'];  ?></td>
                
                <td><?php echo ($clienteLinha['nome']);?></td>
                <td><?php echo funcoes::formatarData($vendaLinha['data']);?></td>
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
            </tr>
            <?php
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" style="text-align: right">Valor Total de Vendas</td>
            <td><?php echo funcoes::formatarMonetario($total_venda); ?></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: right">Quantidade de Vendas Realizadas:</td>
            <td><?php echo $numero_vendas; ?></td>
            <td></td>
        </tr>
    </tfoot>
</table>
</div>
 <?php
funcoes::gerarPdf(false, 'conteudo');

include ("./rodape.php");
?>
