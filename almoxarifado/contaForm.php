<?php
include ("./cabecalho.php");
?>
<?php

if ($_SESSION['login'] !== 'maisonrel' && $_SESSION['login'] !== 'admin' && $_SESSION['login'] !== 'MIRELAREL') {
    die("Usuário sem Privilégio de acesso!!");
}
?>
<form method="POST" action="contaCrud.php">
    <table class="form">
        <caption>
            Cadastro de Debitos
        </caption>
        
        <tbody>
            <tr>
                <td>Descrição do Debito:</td>
                <td><input type="text" name="descricao" data-tipo="str" required=""/></td>
            </tr>
            <tr>
                <td>Valor do Debito:</td>
                <td><input type="text" name="valor" data-tipo="monetario" required=""/></td>
            </tr>
            <tr>
                <td>Data de Criação do Debito:</td>
                <td><input type="text" name="data_criacao" data-tipo="data" required=""/></td>
            </tr>
            <tr>
                <td>Data para Pagar o Debito:</td>
                <td><input type="text" name="data_pagar" data-tipo="data" required=""/></td>
            </tr>
            <tr>
                <td>Debito Pago já foi pago:</td>
                <td>
                    <label><input type="radio" name="pago" <?php if (!empty($pedidoLinha['pago'])) echo "checked"; ?> value="TRUE"/>Sim</label><br/>
                    <label><input type="radio" name="pago" <?php if (empty($pedidoLinha['pago'])) echo "checked"; ?> value="FALSE"/>Não</label>
                </td>
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
