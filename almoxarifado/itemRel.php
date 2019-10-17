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
                    Data Inicio:
                    <?php
                    if (empty($_GET['inicio'])) {
                        $_GET['inicio'] = '';
                    }
                    ?>
                    <input type="text" data-tipo="data" name="inicio" value="<?php echo $_GET['inicio']; ?>" required="required"/>
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
                    <input type="text" data-tipo="data" name="fim" value="<?php echo $_GET['fim']; ?>" required="required"/>
                </td>
            </tr>
        </tbody>
    </table> 
    <input type="submit" class="btn btn-success">
</form>
<?php
if (!empty($_GET['inicio'])) {
    ?>
<!--<script>
    function exibirTabela() {
        var x = prompt('Informe a senha para exibir o relatório');
        if (x == 'Le56vi80') {
            $('#tabela').show();
        }
    }
</script>
<button onclick="exibirTabela()">
    Informar senha
</button>-->
<?php

if ($_SESSION['login'] !== 'maisonrel' && $_SESSION['login'] !== 'admin') {
    die("Usuário sem Privilégio de acesso!!");
}
?>

    <table id="tabela" class='table table-hover table-striped table-ordered'>
        <caption>
            Relatório de Ítens
        </caption>
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantidade Locada</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $vendaTabela = $vendaItem->getMaisVendidos($_GET['inicio'], $_GET['fim']);
            foreach ($vendaTabela as $vendaLinha) {
                $item_row = $item->get($vendaLinha['item']);
                ?>
                <tr>
                    <td><?php echo $item_row['nome'];  ?></td>
                    <td><?php echo $vendaLinha['qtd'];?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?php
}
?>
