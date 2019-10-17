<?php
include './cabecalho.php';
$cliente = new cliente();
$venda = new venda();
$vendaItem = new vendaItem();
$funcionario = new funcionario();

$vendaLinha = $venda->get($_GET['venda']);
$clienteLinha = $cliente->get($vendaLinha['cliente']);
$valor = $venda->getTotal($vendaLinha['id']);
$funcionarioLinha  = $funcionario->get($vendaLinha['funcionario']);

$data = date("Y-m-d H:i:s");
?>
<div id="conteudo">
    <div style="width: 100%; padding: 5px; height: 100%;">       
        <table style="font-size: 30px; width:  100%; text-align: left">
            <thead>
            <td style="text-align:center;"><img src='./img/maisonF.PNG'/></td>

                <tr>
                    <td style="font-size: 15px;text-align:center;">Maison Trajes Finos</br>
                        Rua 12 de Agosto S/N</br>
                        Tianguá - Ceará - Brasil</br>
                        Data: <?php echo funcoes::formatarData($data); ?></br>
                        Atendente: <?php echo $funcionarioLinha['nome']; ?> 
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 20px;text-align: center;">NÚMERO DO PEDIDO: <?php echo $vendaLinha['id']; ?></td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: center;">
                        <hr/>
                    </td>
                </tr>
            </thead>
        </table>
    </div></div>
<div class="botoes">
    <?php funcoes::gerarPdfSemCabecalho(true); ?>
</div>
<?php
include './rodape.php';