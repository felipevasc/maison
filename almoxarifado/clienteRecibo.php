<?php
include("cabecalho.php");
$funcionario = new funcionario();
?>
<form method="GET" action="relatorioReciboCliente.php" target="_BLANK" onsubmit="abrirPagina('clienteList.php')">
    <input type="hidden" name="cliente" value="<?php echo $_GET['cliente']; ?>"/>
    <table class='form_mobile'>
        <caption>
            Recibo
        </caption>
        <tbody>
            <tr>
                <td>
                    Valor:<br/>
                    <input name="valor" input="text" data-tipo="monetario"/>
                </td>
            </tr>
            <tr>
                <td>
                    Referente a:<br/>
                    <textarea name="referente"></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    Funcionário:<br/>
                    <select name="funcionario">
                        <option value="">Selecione um Funcionário</option>
                        <?php 
                        funcoes::montaSelect($funcionario->obterTodos(), "nome", "nome", "");
                        ?>
                    </select>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <button class='btn btn-large btn-block btn-success' type='submit'>Cadastrar</button>
                </td>
            </tr>
        </tfoot>
    </table>  
</form>
<?php
include("rodape.php");