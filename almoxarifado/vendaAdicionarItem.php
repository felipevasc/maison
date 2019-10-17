<?php
include("cabecalho.php");

$venda = new venda();
$item = new item();
$vendaMesa = new vendaMesa();
$mesa = new mesa();
$adicional = new adicional();

$itemLinha = $item->get($_GET['item']);
?>
<form method="POST" action="vendaItemCrud.php">
    <input type="hidden" name="venda" value="<?php echo $_GET['venda']; ?>"/>
    <input type="hidden" name="item" value="<?php echo $_GET['item']; ?>"/>
    <table class="form_mobile">
        <caption>
            Adicionar Item<?php echo $_GET['item']; ?><br/>
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
                    Item: <?php echo $itemLinha['nome']; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Valor:<br/>
                    <input type="text" name="valor" value="<?php echo number_format($itemLinha['valor'], 2, '.', ''); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    Quantidade<br/>
                    <input type="number" name="quantidade" value="1"/>
                </td>
            </tr>
            <?php
            $adicionalTabela = $adicional->getByCategoria($itemLinha['categoria']);
            foreach ($adicionalTabela as $i => $adicionalLinha) {
                $adicionalTabela[$i]['valor'] = "R$ ".number_format($adicionalLinha['valor'], 2, ',', '.');
            }
            if (count($adicionalTabela) > 0) {
                $select = "<span><select name='adicional[]' style='width: 75%;'>";
                $select .= "<option value=''>Selecione um Adicional</option>";
                $select .= funcoes::montaSelect($adicionalTabela, "id", array('nome', 'valor'), "", FALSE);
                $select .= "</select><img src='../auxiliares/ico/menos.png' style='cursor: pointer;' onclick='$(this).parent().remove();'></span>";
                ?>
                <tr>
                    <td>
                        <span onclick="$('#adicionais').append('<?php echo addslashes($select); ?>')" style="cursor: pointer; color: #009900;" title="Novo Adicional">Acrescentar Adicional <img src="../auxiliares/ico/mais.png"/></span><br/>
                        <span id="adicionais"></span>
                    </td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td>
                    Observação
                    <textarea name="obs" placeholder="Nenhuma"></textarea>
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