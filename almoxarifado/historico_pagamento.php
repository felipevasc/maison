<?php
include("cabecalho.php");

$pagamentoVenda = new pagamentoVenda();
if (empty($_GET['submit'])) {
    $_GET['data_inicio'] = date("d/m/Y");
    $_GET['data_fim'] = "";
    $_GET['forma_pagamento'] = "0";
}
?>
<form class="form-inline" style="text-align: left; padding-left: 20px">

    <label for="data_inicio">Data de Início:</label>
    <input type="text" class="form-control" id="data_inicio" name="data_inicio" data-tipo="data" value="<?php if(!empty($_GET['data_inicio'])) echo $_GET['data_inicio'];  ?>"/>
    <label for="data_fim">Data Final:</label>
    <input type="text" class="form-control" id="data_fim" name="data_fim" data-tipo="data" value="<?php if(!empty($_GET['data_fim'])) echo $_GET['data_fim'] ?>"/>
    <label for="forma_pagamento">Forma de Pagamento:</label>
    <select name="forma_pagamento">
        <option value=""> - Todos COM Sangria - </option>
        <option value="-2" <?php if ($_GET['forma_pagamento'] == -2) echo "selected"; ?> >- Todos SEM Sangria -</option>
        <option value="-1" <?php if ($_GET['forma_pagamento'] == -1) echo "selected"; ?> >DESPESAS</option>
        <option value="0" <?php if ($_GET['forma_pagamento'] === '0') echo "selected"; ?> >RECEITAS</option>
        <?php
        $formaPagamento = new formaPagamento();
        $formaPagamento_rs = $formaPagamento->obterTodos();
        foreach ($formaPagamento_rs as $formaPagamento_row) {
            if ($formaPagamento_row['id'] == $_GET['forma_pagamento']) {
                $selected = "selected";
            }
            else {
                $selected = "";
            }
            echo "<option {$selected} value='{$formaPagamento_row['id']}'>{$formaPagamento_row['descricao']}</option>";
        }
        ?>
    </select><br/>
    <button type="submit" name="submit" value="true" class="btn btn-primary">Pesquisar</button>
</form>
<?php

    ?>

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
<?php

if ($_SESSION['login'] !== 'maisonrel' && $_SESSION['login'] !== 'admin' && $_SESSION['login'] !== 'MIRELAREL') {
    die("Usuário sem Privilégio de acesso!!");
}
?>
<div id="conteudo">
    <table id="tabela" class='table table-hover table-striped table-ordered' >
        <caption>Histórico de Pagamentos</caption>
        <thead>
            <tr>
                <th>Cod Aluguel</th>
                <th>Cliente</th>
                <th>Funcionario</th>
                <th>Data</th>
                <th>Tipo Pagamento</th>
                <th>Valor Total Aluguel</th>
                <th>Recebido</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $formaPagamento = new formaPagamento();
            $conta = new conta();
            $sangria = new sangria();
            $pagamentoVenda_rs = $pagamentoVenda->getPagamentos($_GET['data_inicio'], $_GET['data_fim'], $_GET['forma_pagamento']);
            $conta_rs = array();
            $sangria_rs = array();
            if ($_GET['forma_pagamento'] === '' || $_GET['forma_pagamento'] == -1) {
                $conta_rs = $conta->obterTodos($_GET['data_inicio'], $_GET['data_fim']);
            }
            if ($_GET['forma_pagamento'] === '') {
                $sangria_rs = $sangria->obterTodos($_GET['data_inicio'], $_GET['data_fim']);
            }
            
            if ($_GET['forma_pagamento'] == -2) {
                $conta_rs = $conta->obterTodos($_GET['data_inicio'], $_GET['data_fim']);
                $_GET['forma_pagamento'] = 0;
                $pagamentoVenda_rs = $pagamentoVenda->getPagamentos($_GET['data_inicio'], $_GET['data_fim'], $_GET['forma_pagamento']);
            }
            
            $total = 0;
            $totalDinheiro = 0;
            $totalCredito = 0;
            $totalDebito = 0;
            $totalDeposito = 0;
            $totalDespesas = 0;
            $totalDespesasVer = 0;
            $totalSangriaVer = 0;
            $x=0;
            $venda = new venda();
            foreach ($pagamentoVenda_rs as $pagamentoVenda_row) {
                $total += $pagamentoVenda_row['valor'];
                if($formaPagamento->getDescricao($pagamentoVenda_row['forma_pagamento']) === "DINHEIRO"){
                    $totalDinheiro += $pagamentoVenda_row['valor'];
                }
                else if($formaPagamento->getDescricao($pagamentoVenda_row['forma_pagamento']) === "CARTÃO CREDITO"){
                    $totalCredito += $pagamentoVenda_row['valor'];
                }
                else if($formaPagamento->getDescricao($pagamentoVenda_row['forma_pagamento']) === "CARTÃO DEBITO"){
                    $totalDebito += $pagamentoVenda_row['valor'];
                }
                else if($formaPagamento->getDescricao($pagamentoVenda_row['forma_pagamento']) === "DEPOSITO BANCARIO"){
                    $totalDeposito += $pagamentoVenda_row['valor'];
                }
                else if($formaPagamento->getDescricao($pagamentoVenda_row['forma_pagamento']) === "DESPESAS"){
                    $totalDespesas += $pagamentoVenda_row['valor'];
                }              
                
                ?>
                <tr>
                    <td><?php echo $pagamentoVenda_row['id_venda']; ?></td>
                    <td><?php echo $pagamentoVenda_row['cliente']; ?></td>
                    <td><?php echo $pagamentoVenda_row['funcionario']; ?></td>
                    <td><?php echo funcoes::formatarData($pagamentoVenda_row['data']); ?></td>
                    <td><?php echo $formaPagamento->getDescricao($pagamentoVenda_row['forma_pagamento']); ?></td>
                    <td><?php echo funcoes::formatarMonetario($venda->getTotal($pagamentoVenda_row['id_venda'])); ?></td>
                    <td><?php echo funcoes::formatarMonetario($pagamentoVenda_row['valor']); ?></td>
                </tr>
                <?php
            }
            foreach ($conta_rs as $conta_row) {
                $total -= $conta_row['valor'];
                $totalDespesasVer += $conta_row['valor'];
                ?>
                <tr>
                    <td>-</td>
                    <td><?php echo $conta_row['descricao']; ?></td>
                    <td> - </td>
                    <td><?php echo funcoes::formatarData($conta_row['data_pagamento']); ?></td>
                    <td>Despesa</td>
                    <td><?php echo funcoes::formatarMonetario($conta_row['valor']); ?></td>
                    <td><?php echo funcoes::formatarMonetario($conta_row['valor']); ?></td>
                </tr>
                <?php
            }
            foreach ($sangria_rs as $sangria_row) {
                $total -= $sangria_row['valor'];
                $totalSangriaVer += $sangria_row['valor'];
                ?>
                <tr>
                    <td>-</td>
                    <td>-</td>
                    <td> <?php echo $sangria_row['funcionario']; ?> </td>
                    <td><?php echo funcoes::formatarData($sangria_row['data']); ?></td>
                    <td>Sangria</td>
                    <td><?php echo funcoes::formatarMonetario($sangria_row['valor']); ?></td>
                    <td><?php echo funcoes::formatarMonetario($sangria_row['valor']); ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>                
                <td colspan="100%" style="text-align: right; margin-right: 10px"> Total Dinheiro: <?php echo funcoes::formatarMonetario($totalDinheiro); ?></td>
            </tr>
            <tr>
                <td colspan="100%" style="text-align: right; margin-right: 10px"> Total C.Credito: <?php echo funcoes::formatarMonetario($totalCredito); ?></td>
            </tr>
            <tr>
                <td colspan="100%" style="text-align: right; margin-right: 10px"> Total C.Debito: <?php echo funcoes::formatarMonetario($totalDebito); ?></td>
            </tr>
            <tr>
                <td colspan="100%" style="text-align: right; margin-right: 10px"> Total Deposito: <?php echo funcoes::formatarMonetario($totalDeposito); ?></td>
            </tr>
            <tr>
                <td colspan="100%" style="text-align: right; margin-right: 10px"> Total Despesas: <?php echo funcoes::formatarMonetario($totalDespesasVer); ?></td>
            </tr>
            <tr>
                <td colspan="100%" style="text-align: right; margin-right: 10px"> Total Sangria: <?php echo funcoes::formatarMonetario($totalSangriaVer); ?></td>
            </tr>
            <tr>
                <td colspan="100%" style="text-align: right; margin-right: 10px"> Total: <?php echo funcoes::formatarMonetario($total); ?></td>
            </tr>
        </tfoot>
    </table>
</div>
 <?php
funcoes::gerarPdf(false, 'conteudo');

include ("./rodape.php");
?>
