<?php
include './cabecalho.php';
$cliente = new cliente();
$clienteLinha = $cliente->get($_GET['cliente']);
?>
<div id="conteudo">
    <div style="border: #666666 solid 1px; border-radius: 10px; width: 100%; padding: 10px;">
        <table>
            <tr>
                <td style="text-align: right;">Locatária:</td>
                <td colspan="3"><?echo</td>
            </tr>
            <tr>
                <td style="text-align: right;">Endereço:</td>
                <td colspan="3">___________________________________________________________________________________________________________________________</td>
            </tr>
            <tr>
                <td style="text-align: right;">Fone:</td>
                <td colspan="1">(__)________________________________________________</td>
                <td style="text-align: right;">Cidade:</td>
                <td colspan="1">__________________________________________________________</td>
            </tr>
            <tr>
                <td style="text-align: right;">RG:</td>
                <td colspan="1">__________________________________________________________</td>
                <td style="text-align: right;">Valor da Locação:</td>
                <td colspan="1">__________________________________________________________</td>
            </tr>
            <tr>
                <td style="text-align: center;" colspan="1">Dados do(s) produto(s) locado(s):</td>
                <td style="text-align: left;" colspan="3">___________________________________________________________________________________________________________________________</td>
            </tr>
            <tr>
                <td style="text-align: center;" colspan="4">________________________________________________________________________________________________________________________________</td>
            </tr>
            <tr>
                <td style="text-align: center;" colspan="4">________________________________________________________________________________________________________________________________</td>
            </tr>
            <tr>
                <td style="text-align: right;">Condições de Pgto. Na encomenda:</td>
                <td colspan="1">________________________________________________</td>
                <td style="text-align: right;">Quando Retira:</td>
                <td colspan="1">_____________/________________/___________________</td>
            </tr>
            <tr>
                <td style="text-align: right;">Prazo da Locação: Data retirada:</td>
                <td colspan="1">____________/____________________/________________</td>
                <td style="text-align: right;">Data devolução:</td>
                <td colspan="1">____________/____________________/________________</td>
            </tr>
        </table>
        <div style="width: 100%; text-align: justify"> A locatária se compromete em entregar os devidos trajes e/ou acessórios alugados no prazo estipulado e combinado com a loja.
        Caso haja algum atraso na devolução do(s) produto(s) locador(s), a locatária se compromete em informar a loja via telefone, para que se possa verificar a possibilidade de extensão da locação.
        Excedendo o prazo de entrega dos produtos será cobrado uma multa correspondente a 10% do valor total do aluguel.
        Caso os trajes e/ou acessórios sejam devolvidos com excesso de sujeira ou manchas, será cobrado uma taxa de R$ 50,00, para limpeza das peças.
        Se houver qualquer dano aos trajes e/ou acessórios locados, será cobrada uma multa de R$ 50,00 a R$ 100,00, para reparo das danificações.
        Caso haja perda ou extravio das peças locadas, será cobrado o valor total de venda do produto.        
        A não devolução de cabides e capas, incide em ulta no valor R$ 60,00. A desistência do aluguel incide na multa de 50% do valor de aluguel do vestido. <br/>
        Por de acordo assina o presente contrato.</div>
        <br/>
        <br/>
        Cliente/Locatária:_____________________________________________________________________________
    </div>
    <br/><br/>
    <div style="border: #666666 solid 1px; border-radius: 10px; width: 100%; padding: 10px;">
        Recebemos a(s) mercadoria(s) constante neste termo de Aluguel.
        <table>
            <tr>
                <td style="text-align: center;">____________/____________/____________________</td>
                <td style="text-align: center;">_______________________________________________________________________</td>
            </tr>
            <tr>
                <td style="text-align: center;">Data</td>
                <td style="text-align: center;">Maison Trajes Finos</td>
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