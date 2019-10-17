<?php
include("cabecalho.php");

$venda = new venda();
$vendaItem = new vendaItem();
$item = new item();
$vendaMesa = new vendaMesa();
$mesa = new mesa();
$adicional = new adicional();

$vendaItemLinha = $vendaItem->get($_GET['item']);
?>
<form method="POST" action="vendaItemCrud.php?acao=atualizar1&id=<?php echo $vendaItemLinha['id']; ?>">
    <input type="hidden" name="venda" value="<?php echo $_GET['venda']; ?>"/>
    <input type="hidden" name="item" value="<?php echo $_GET['item']; ?>"/>
    <table class="form_mobile">
        <caption>
            Adicionar Item<br/>
            Mesa:
            <?php
            $vendaMesaTabela = $vendaMesa->getMesasVenda($_GET['venda']);
            foreach ($vendaMesaTabela as $i => $vendaMesaLinha) {
                if ($i > 0) {
                    echo " / ";
                }
                $mesaLinha = $mesa->get($vendaMesaLinha['mesa']);
                echo $mesaLinha['nome'];
            }
            ?>
        </caption>
        <tbody>
            <tr>
                <td>
                    <?php 
                     $itemLinha = $item->get($vendaItemLinha['item']);
                    ?>
                    Item: <?php echo $itemLinha['nome']; ?>
                </td>
            </tr>         
            <tr>
                <td>
                    Observação
                    <textarea id="obs" name="obs"><?php echo $vendaItemLinha['obs']; ?></textarea>                    
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <button class='btn btn-large btn-block btn-success' type="submit">Adicionar Item</button>
                    <button class='btn btn-large btn-block btn-info' type='button' onclick="history.go(-1)">Voltar</button>
                </td>
            </tr>
        </tfoot>
    </table>    
</form>

<?php

include("rodape.php");