<?php
include './cabecalho.php';
$cliente = new cliente();
$venda = new venda();
$vendaItem = new vendaItem();


$vendaLinha = $venda->get($_GET['venda']);
$clienteLinha = $cliente->get($vendaLinha['cliente']);
$valor = $venda->getTotal($vendaLinha['id']);

$data = date("d/m/Y");
?>
<div id="conteudo">
    <div style="border: #666666 solid 1px; border-radius: 10px; width: 100%; padding: 10px; width: 100%;">
        <div style="width: 100%; text-align: justify"> <br/>Contrato de Locação de Vestidos, Trakes e Acessórios. <br/>
        1. IDENTIFICAÇÃO DAS PARTES <br/><br/>
        LOCATÁRIO: <?php echo $clienteLinha['nome']; ?><br/>
        ENDEREÇO: <?php echo $clienteLinha['endereco']; ?>, <?php echo $clienteLinha['cidade']; ?> - <?php echo $clienteLinha['estado']; ?>, com CPF de número: <?php echo $clienteLinha['cpf']; ?><br/><br/>
        
        LOCADORA:[Maison Trajes Finos], com sede em Tianguá, na Rua Doze de Agosto, nº 1075, bairro Planalto, CEP 62320-000, no Estado do Ceará, inscrita no cnpj sob o n° 218636620001-65.<br/><br/>
        
        As partes identificadas acima têm, entre si, justo e acertado o presente Contrato de Locação de Artigos de Vestuário, que se regerá
        pelas cláusulas e termos aqui descritos.<br/><br/>
        
        2. OBRIGAÇÕES DO LOCATÁRIO<br/>
        Cláusula 1ª. O LOCATÁRIO deverá fornecer à LOCADORA todas as informações necessárias à realização do aluguel, devendo
        especificar os detalhes necessários à perfeita consecução do mesmo.<br/><br/>
        Cláusula 2ª. O LOCATÁRIO deverá efetuar o pagamento na forma e condiçoes estabelecidas na Cláusula 7ª.<br/><br/>
        
        3. OBRIGAÇÕES DA LOCADORA<br/>
        Cláusula 3ª. A LOCADORA estará com sua loja aberta para a retirada dos trajes e/ou acessórios locados e respectiva
        devolução, de segunda a sexta-feira, no horário de 8:00 às 17:30 e aos sábados, no horário de 8:00 às 14:00.<br/><br/>
        
        Cláusula 4ª. A locação terá inicio no dia <?php echo funcoes::formatarData($vendaLinha['entrega']); ?> e término
        no dia <?php echo funcoes::formatarData($vendaLinha['devolucao']); ?>.<br/>
        Parágrafo primeiro. A LOCADORA não se responsabilizará pelos trajes que não forem retirados no dia e horário
        estabelecidos no caput da presente cláusula.<br/><br/>
        
        Parágrafo segundo. A LOCADORA se compromete a entregar os trajes e seus respectivos acessórios devidamente lavados
        e passados e em perfeito estado de conservação e uso, na data estabelecida na cláusula 4ª.<br/><br/>
        
        Parágrafo terceiro. Caso seja constatada algum dano nos trajes ou acessórios locados, no momento da locação, a LOCADORA
        se compromete a efetuar a substituição ou troca dos mesmos, independente do seu preço de locação, ou a devida devolução
        do valor pago, tudo conforme a disponibilidade do produto ou coveniência da LOCADORA.<br/><br/>
        
        Cláusula 5ª. É dever da LOCADORA oferecer ao LOCATÁRIO a cópia do presente instrumento, contendo todas as especifidades da locação
        contratada.<br/><br/>
        
        4. DO PREÇO E DAS CONDIÇÕES DE PAGAMENTO<br/>
        Cláusula 6ª. A presente locação será remunerado pela quantia total de <?php echo funcoes::formatarMonetario($valor); ?>
        (valor expresso) por dia, referente aos produtos efetivamente locados, devendo ser pago em dinheiro ou cheque, ou outra forma
        de pagamento em que ocorra a prévia concordância de ambas as partes.<br/>
        Parágrafo primeiro. O LOCATÁRIO poderá fazer reserva antecipada dos trajes e/ou acessórios mediante o pagamento antecipado
        de 20% do valor total do aluguel e assinatura do presente instrumento.<br/><br/>
        Parágrafo segundo. O valor referente à reserva não será devolvido sob qualquer hipótese, mesmo em caso de cancelamento
        do contrato.<br/><br/>
        
        5. DO INADIPLEMENTO, DESCUMPRIMENTO E DA MULTA<br/>
        Cláusula 7ª. Em caso de inadimplemento por parte do LOCATÁRIO quanto ao pagamento do aluguel, deverá incidir
        sobre o valor do presente instrumento, multa pecuniária de 2%, juros de mora de 1% ao mês e correção monetária.<br/>
        Parágrafo único. Em caso de cobrança judicial, devem ser acrescidas custas processuais e 20% de honorários advocatícios.<br/><br/>
        
        6. DA RESCISÃO IMOTIVADA<br/>
        Cláusula 8ª. Poderá o presente instrumento ser rescindido por qualquer uma das partes, em qualquer momento, sem que haja
        qualquer tipo de motivo relevante, não obstante a outra parte deverá ser avisada previamente por escrito, no prazo de 30 dias.<br/><br/>
        
        Cláusula 9ª. Caso o LOCATÁRIO já tenha realizado o pagamento pelo serviço, e mesmo assim, requisite a rescisão imotivada
        do presente contrato, terá o valor da quantia paga devolvido, deduzindo-se 60% do pagamento total do valor dos produtos locados.<br/><br/>
        
        7. DAS CONDIÇÕES GERAIS<br/><br/>
        Cláusula 10ª. Caso os trajes e/ou acessórios sejam devolvido com excesso de sujeira ou manchas, será cobrada uma taxa de R$ 100,00 (valor expresso)
        para a limpeza das peças.<br/>
        Cláusula 11ª. Se houver qualquer dano aos trajes e/ou acessórios locados, o LOCATÁRIO pagará o valor integral do valor da peça
        comprada para cada peça danificada.<br/>
        Cláusula 12ª. A não devolução no prazo de 5 (cinco) dias a contar da data prevista, dos trajes e/ou acessórios descritos nesse contrato, será
        considerada EXTRAVIO ou ROUBO, sendo que o LOCATÁRIO terá que pagar 100% (valor expresso) o valor do aluguel de cada peça.<br/>
        Parágrafo primeiro. Se for ultrapassada a data prevista para a devolução dos trajes e/ou acessórios, em prazo inferior ao 
        descrito no caput da presente cláusula, o LOCATÁRIO pagará o valor equivalente a um dia de aluguel extra para cada dia de atraso, acrescido de multa de 10% (valor expresso).<br/>
        Parágrafo segundo. Os valores descritos no parágrafo primeiro e no caput da presente cláusula, poderão ser aplicados proporcionalmente a trajes e/ou 
        acessórios avulsos, constantes do rol de produtos locados, que não foram devolvidos na data prevista.<br/>
        Cláusula 13ª. Salvo com a expressa autorização do LOCATÁRIO, não pode a LOCADORA transferir ou subcontratar os trajes e/ou 
        acessórios definidos neste instrumento, sob o risco de ocorrer a rescisão imediata.<br/>
        Cláusula 14ª. A não devolução de cabides e capas, incide em multa no valor de R$ 50,00.<br/><br/>
        
        8. DO OBJETO DO CONTRATO<br/><br/>
        <table border="1" style="margin: 0 auto; border-collapse: collapse;">
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
                </td>
            </tr>
            </table><br/>
            
            9. VALORES DA LOCAÇÃO<br/><br/>
            <?php echo funcoes::formatarMonetario($valor); ?><br/><br/>
            Data da retirada <?php echo funcoes::formatarData($vendaLinha['entrega']); ?><br/>
        
        <br/>
        Cliente/Locatária:_____________________________________________________________________________<br/><br/>
        Maison/Locadora:_____________________________________________________________________________<br/><br/>
        Data:<?php echo funcoes::formatarData($data); ?>
        <br/>
        
        <br/><br/>
        </div><br/><br/>
        <div style="page-break-after: always"></div>
        
        <div style="border: #666666 solid 1px; border-radius: 10px; width: 100%; padding: 10px;">
        Termo de Entrega<br/><br/>
        
        Declaro que todos os objetos retirados na loja Maison Trajes Finos estão em perfeito estado.<br/><br/>
        Todos os produtos e acessórios que acompanha o referido contrato foram conferido e entregues sem nenhum defeito.<br/><br/>
        <table>
            <tr>
                <td style="text-align: center;">____________/____________/____________________</td>
                <td style="text-align: center;">_______________________________________________________________________</td>
            </tr>
            <tr>
                <td style="text-align: center;">Data</td>
                <td style="text-align: center;">Cliente/LOCATÁRIO</td>
            </tr>
        </table>
    </div>
    <br/><br/>        
    <br/><br/><br/>

</div>
<div class="botoes">
    <?php funcoes::gerarPdf(true); ?>
</div>
<?php
include './rodape.php';