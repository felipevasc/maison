<?php
include './cabecalho.php';
$cliente = new cliente();
$sangria = new sangria();
$funcionario = new funcionario();

$sangriaLinha = $sangria->get($_GET['sangria']);
$valor = $sangria->getTotal($sangriaLinha['id']);
$funcionarioLinha  = $funcionario->get($sangriaLinha['funcionario']);

$data = date("d/m/Y");
?>
<div id="conteudo">
    <div style="border: #666666 solid 1px; border-radius: 10px; width: 100%; padding: 10px; width: 100%;">
        <table border="1" style="margin: 0 auto; border-collapse: collapse;">
            <tr>
                <td style="text-align: right;">Sangria realizada por:</td>
                <td colspan="3"><?php echo $sangriaLinha['funcionario']; ?></td>
            </tr>
            <tr>
                <td style="text-align: right;">A importância total de:</td>
                <td colspan="3"><?php echo funcoes::formatarMonetario($sangriaLinha['valor']); ?></td>
            </tr>
            <tr>
                <td style="text-align: center;" colspan="1">Na data:</td>
                <td style="text-align: left;" colspan="3">
                    <?php echo funcoes::formatarData($data); ?>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">Que constam na <br/>Sangria de Código:</td>
                <td colspan="3"><?php echo $sangriaLinha['id']; ?></td>
            </tr>
        </table>        
    <br/>

</div>
<div style="border: #666666 solid 1px; border-radius: 10px; width: 100%; padding: 10px;">
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center; text-decoration: underline;">________________________________________</td>
                <td style="text-align: center; text-decoration: underline;">________________________________________</td>
            </tr>
            <tr>
                <td style="text-align: center;">Responsável Pela Sangria</td>
                <td style="text-align: center;">Maison Trajes Finos</td>
            </tr>
            <tr>
                <td colspan="2" style="font-size: 10px; text-align: center; text-decoration: underline; font-style: italic;">
                    Tianguá, <?php echo funcoes::formatarData($data); ?>
                </td>
            </tr>
        </table>
</div>
<div class="botoes">
    <?php funcoes::gerarPdf(true); ?>
</div>
<?php
include './rodape.php';