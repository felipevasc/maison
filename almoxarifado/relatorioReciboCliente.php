<?php
include './cabecalho.php';
$cliente = new cliente();
$venda = new venda();
$vendaItem = new vendaItem();
$funcionario = new funcionario();


$data = date("d/m/Y");


$clienteLinha = $cliente->get($_GET['cliente']);
?>
<div id="conteudo">
    <div style="border: #666666 solid 1px; border-radius: 10px; width: 100%; padding: 10px; width: 100%;">
        <table border="1" style="margin: 0 auto; border-collapse: collapse;">
            <tr>
                <td style="text-align: right;">Recebemos de:</td>
                <td colspan="3"><?php echo $clienteLinha['nome']; ?>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
            </tr>
            <tr>
                <td style="text-align: right;">A importância de:</td>
                <td colspan="3">
                    R$ <?php echo number_format($_GET['valor'], 2, ',', '.'); ?>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;" colspan="1">Referente aos produtos:</td>
                <td style="text-align: left;" colspan="3">
                    <?php echo $_GET['referente']; ?>
                </td>
            </tr>            
        </table>        
    <br/><br/>
    <div style="border: #666666 solid 1px; border-radius: 10px; width: 100%; padding: 10px;">
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center; text-decoration: underline;"><?php echo $_GET['funcionario']; ?></td>
                <td style="text-align: center; text-decoration: underline;">Maison Trajes Finos</td>
            </tr>
            <tr>
                <td style="text-align: center;">Funcionário</td>
                <td style="text-align: center;">Loja</td>
            </tr>
            <tr>
                <td colspan="2" style="font-size: 10px; text-align: center; text-decoration: underline; font-style: italic;">
                    Tianguá, <?php echo funcoes::formatarData($data); ?>
                </td>
            </tr>
        </table>
    </div>    
    <br/><br/><br/>

</div>
<div class="botoes">
    <?php funcoes::gerarPdf(true); ?>
</div>
<?php
include './rodape.php';