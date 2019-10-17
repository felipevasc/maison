<?php
include("cabecalho.php");

$id_venda = $_GET['venda'];

$vendaMesa = new vendaMesa();
$mesa = new mesa();
$restaurante = new restaurante();

$vendaMesaTabela = $vendaMesa->getMesasVenda($id_venda);
?>
<form method="POST" action="vendaCrud.php">
    <input type="hidden" name="acao" value="transferir"/>
    <input type="hidden" name="venda" value="<?php echo $id_venda; ?>"/>
    <table class="form_mobile">
        <caption>
            Transferência de Mesa<br/>
            Restaurante Atual: 
            <?php
            $mesaLinha = $mesa->get($vendaMesaTabela[0]['mesa']);
            $restauranteLinha = $restaurante->get($mesaLinha['restaurante']);
            echo $restauranteLinha['nome'];
            ?><br/>
            Mesa Atual: 
            <?php
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
                    Novo Restaurante:<br/>
                    <select name="restaurante" onchange="carregarPagina('mesaSelect.php?restaurante='+this.value, 'mesa')">
                        <option value="">Selecione um Restaurante</option>
                        <?php funcoes::montaSelect($restaurante->obterTodos(), "id", "nome", $restauranteLinha['id']); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    Nova Mesa:<br/>
                    <select name="mesa" id="mesa" required="required">
                        <option value="">Selecione uma Mesa</option>
                        <?php funcoes::montaSelect($mesa->getByRestaurante($restauranteLinha['id']), "id", "nome", ""); ?>
                    </select>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <button class='btn btn-large btn-block btn-success' type='submit'>Cadastrar</button>
                    <button class='btn btn-large btn-block btn-info' type='button' onclick="history.go(-1)">Voltar</button>
                </td>
            </tr>
        </tfoot>
    </table>
</form>

<?php

include("rodape.php");