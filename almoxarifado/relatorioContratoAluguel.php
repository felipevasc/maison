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
$funcionarioPagamentoLinha = $funcionario->get($_SESSION['id_funcionario']);

$data = date("d/m/Y");
?>
<div id="conteudo">
    <div style="border: #666666 solid 1px; border-radius: 10px; width: 100%; padding: 10px; width: 100%;">
        <table border="1" style="margin: 0 auto; border-collapse: collapse; width: 90%; height: 90%;">
            <tr>
                <td style="text-align: right;">Locatária:</td>
                <td colspan="3"><?php echo $clienteLinha['nome']; ?></td>
            </tr>
            <tr>
                <td style="text-align: right;">Endereço:</td>
                <td colspan="3"><?php echo $clienteLinha['endereco']; ?></td>
            </tr>
            <tr>
                <td style="text-align: right;">Fone:</td>
                <td colspan="1"><?php echo $clienteLinha['numero']; ?></td>
                <td style="text-align: right;">Cidade:</td>
                <td colspan="1"><?php echo $clienteLinha['cidade']; ?></td>
            </tr>
            <tr>
                <td style="text-align: right;">RG:</td>
                <td colspan="1"><?php echo $clienteLinha['rg']; ?></td>
                <td style="text-align: right;">CPF:</td>
                <td colspan="1"><?php echo $clienteLinha['cpf']; ?></td>                
            </tr>
            <tr>
                <td style="text-align: right;">Valor da Locação:</td>
                <td colspan="1"><?php echo funcoes::formatarMonetario($valor); ?></td>
                <td style="text-align: right;">Valor Pago No Ato:</td>
                <td colspan="1"><?php echo funcoes::formatarMonetario($vendaLinha['valor_pago']); ?></td>                
            </tr>
            <tr>
                <td colspan="1" style="text-align: right;">Código do Aluguel:</td>
                <td colspan="1"><?php echo $vendaLinha['id']; ?></td>
                <td colspan="1" style="text-align: right;">Valor Restante a ser pago:</td>
                <td style="text-align: center;">
                    <?php echo funcoes::formatarMonetario($valor - $vendaLinha['valor_pago']); ?>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;" colspan="1">Dados do(s) produto(s) locado(s):</td>
                <td style="text-align: left;" colspan="3">
                    <?php
                    $vendaItemTabela = $vendaItem->getByVenda($vendaLinha['id']);
                    $item = new item();
                    foreach ($vendaItemTabela as $vendaItemLinha) {
                        $itemLinha = $item->get($vendaItemLinha['item']);
                        echo $itemLinha['nome']."<br/>";
                        echo $vendaItemLinha['obs']."<br/>";
                    }
                    ?>
                    <br/><br/><br/><br/>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">Prazo da Locação: Data retirada:</td>
                <td colspan="1"><?php echo funcoes::formatarData($vendaLinha['entrega']); ?></td>
                <td style="text-align: right;">Data devolução:</td>
                <td colspan="1"><?php echo funcoes::formatarData($vendaLinha['devolucao']); ?></td>
            </tr>
        </table>
        <div style="width: 100%; text-align: justify; font-size:12"> <br/>1.0)A locatária se compromete em entregar os devidos trajes e/ou acessórios alugados no prazo estipulado e combinado com a loja.
        1.1)Caso haja algum atraso na devolução do(s) produto(s) locado(s), a locatária se compromete em informar a loja via telefone, para que se possa verificar a possibilidade de extensão da locação.
        <br/>1.2)Excedendo o prazo de entrega dos produtos será cobrado uma multa correspondente a 10% do valor total do aluguel.
        <br/>1.3)Caso os trajes e/ou acessórios sejam devolvidos com excesso de sujeira ou manchas, será cobrado uma taxa de R$ 50,00, para limpeza das peças.
        <br/>1.4)Se houver qualquer dano que inutilize aos trajes e/ou acessórios locados(Coroa, Tiara, Véu, Terço de Noiva e qualquer outro objeto) será cobrado o valor total de compra dos produtos.
        <br/>1.5)Caso haja perda ou extravio das peças locadas, será cobrado o valor total de venda do produto.        
        <br/>1.6)A não devolução de capas, incide em multa no valor R$ 60,00. 
        <br/>1.7)A desistência do aluguel até quinze dias antes do seu evento entrando em contato com a loja não incide em multa, contudo os adiantamentos pagos se tornam créditos para alugueis futuros e que é intransferível entre clientes. 
        <br/>1.8)A desistência do aluguel no prazo inferior a quinze dias antes do seu evento é cobrada uma multa no valor de 50% do valor de aluguel do vestido. O crédito que possa ser gerado por essa multa devido a adiantamentos é intransferível entre clientes.<br/>
        <div style="font-size:16"><b>A devolução do(s) produto(s) locado(s) somente poderá ser feita pela Locatária(Cliente) presente neste contrato.</b></div><div style="font-size:18"><b>As entregas devem ser realizadas exclusivamente durante o horário de funcionamento Segunda-Feira a Sexta-Feira das 8:00 às 18:00 e aos sábado das 8:00 às 16:00. Por favor não insistir.  </b></div><br/>        Por de acordo assina o presente contrato.</div>
        <br/>
        <br/>
        Cliente/Locatária:_____________________________________________________________________________
    </div>
    <br/>
    <div style="border: #666666 solid 1px; border-radius: 10px; width: 100%; padding: 10px;">
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center; text-decoration: underline;"><?php echo $funcionarioPagamentoLinha['nome'];  ?></td>
                <td style="text-align: center; text-decoration: underline;"><?php echo $funcionarioLinha['nome'];  ?></td>
            </tr>
            <tr>
                <td style="text-align: center;">Caixa Responsável</td>
                <td style="text-align: center;">Atendente Responsável</td>
            </tr>
            <tr>
                <td colspan="2" style="font-size: 10px; text-align: center; text-decoration: underline; font-style: italic;">
                    Tianguá, <?php echo funcoes::formatarData($data); ?>
                </td>
            </tr>
        </table>
    </div>    
</div>
<div class="botoes">
    <?php funcoes::gerarPdf(true); ?>
</div>
<?php
include './rodape.php';