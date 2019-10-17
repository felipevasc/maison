<?php
include ("./cabecalho.php");

$funcionario = new funcionario();
$funcionarioLinha  = $funcionario->get($_SESSION['id_funcionario']);

?>
<?php

if ($_SESSION['login'] !== 'maisonrel' && $_SESSION['login'] !== 'admin' && $_SESSION['login'] !== 'MIRELAREL') {
    die("Usuário sem Privilégio de acesso!!");
}
?>
<form method="POST" action="sangriaCrud.php">
    <table class="form">
        <caption>
            Cadastro de Sangria
        </caption>
        <tbody>
            <tr>
                <td>Responsável :</td>
                <td><input type="text" value="<?php echo $funcionarioLinha['nome'] ?>" readonly="true" name="funcionario" data-tipo="str" required=""/></td>
            </tr>
            <tr>
                <td>Valor da Sangria:</td>
                <td><input type="text" name="valor" data-tipo="monetario" required=""/></td>
            </tr>
            <tr>
                <td>Data da Sangria:</td>
                <td><input type="text" name="data" data-tipo="data" readonly="true" value="<?php echo date("Y-m-d"); ?>" required=""/></td>
            </tr>            
        </tbody>
        <tfoot>
            <tr>                
                <td style="text-align: right">
                    <input type="reset" value="Limpar"/>
                    <input type="submit" value="OK"/>
                </td>
            </tr>
        </tfoot>
    </table>
</form>  
<?php
include ("./rodape.php");
?>