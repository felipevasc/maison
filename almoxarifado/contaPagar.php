<?php
include ("./cabecalho.php");

$conta = new conta();

$contaTabela = $conta->getNaoPagos();


?>
<?php

if ($_SESSION['login'] !== 'maisonrel' && $_SESSION['login'] !== 'admin' && $_SESSION['login'] !== 'MIRELAREL') {
    die("Usuário sem Privilégio de acesso!!");
}
?>
<div id="conteudo">
    <table class="list">
        <caption>
            Listagem de Contas a Pagar
        </caption>
        <thead>
            <tr>
                <th>Descrição do Debito</th>
                <th>Valor(R$)</th>
                <th>Data de Criação do Debito:</th>
                <th>Data para Pagar o Debito:</th>
                <th>Situação do Pagamento</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            foreach ($contaTabela as $contaLinha) {
                $total += $contaLinha['valor'];
                ?>
                <tr>
                    <td><?php echo $contaLinha['descricao']; ?></td>
                    <td><?php echo $contaLinha['valor']; ?></td>
                    <td><?php echo $contaLinha['data_criacao']; ?></td>
                    <td><?php echo $contaLinha['data_pagar']; ?></td>
                    <td><?php 
                            if($contaLinha['pago']== 0){
                                echo 'Conta Não Paga!';
                            }
                            else{
                                echo 'Conta Paga!';
                            }
                            ?></td>
                    <td>
                        <img src="../auxiliares/ico/check.png" title="Pagar Pedido" onclick="confirma('Tem certeza que deseja colocar o conta como pago?', function() { abrirPagina('contaCrud.php?acao=pagar&conta=<?php echo $contaLinha['id']; ?>'); });"/>
                        <img src="../auxiliares/ico/cancelar.png" style="cursor: pointer;" title="Remover Conta" onclick="confirma('Tem certeza que deseja remover a conta selecionada?', function() { abrirPagina('contaCrud.php?acao=deletar&id=<?php echo $contaLinha['id']; ?>') }); "/>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="100%">Valor em débito atual: <?php echo $total; ?></td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="botoes">
    <span class="botao" onclick="abrirPagina('contaForm.php');">
        <img src="../auxiliares/ico/mais.png"/><br/>
        Adicionar
    </span>
     <?php
    funcoes::gerarPdf(false, 'conteudo');
    ?>  
</div>
<?php
include ("./rodape.php");
?>