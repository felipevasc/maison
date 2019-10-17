<?php
include ("./cabecalho.php");

$nomeTabela = funcoes::nomeTabelaByCaminho(__FILE__);
$tabela = new $nomeTabela();

$cliente = new cliente();
$venda = new venda();
$vendaItem = new vendaItem();
$item = new item();
$categoria = new categoria();
?>
<form method="get">
    <table class="form_mobile">
        <tbody>
            <tr>
                <td>
                    Categoria:<br/>
                    <select name="categoria">
                        <option value="">Selecione uma Categoria</option>
                        <?php
                        if (empty($_GET['categoria'])) {
                            $_GET['categoria'] = '';
                        }                    
                        funcoes::montaSelect($categoria->obterTodos(), 'id', 'nome', $_GET['categoria']); 
                        ?>
                    </select>
                </td>
            </tr>            
        </tbody>
    </table> 
    <input type="submit" class="btn btn-success">
</form>

    

<form method="POST" action="<?php echo $nomeTabela; ?>Crud.php">
    <?php
    echo $tabela->montarList();
    ?>
</form>
<?php
include ("./rodape.php");
?>