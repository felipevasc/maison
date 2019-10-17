<?php
include ("./cabecalho.php");

$conta = new conta();



if (empty($_GET['dia'])) {
    $dia_referencia = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
}
else {
    $dia_referencia = $_GET['dia'];
}
$primeiro_dia = '';
$ultimo_dia = '';
funcoes::definirSemana($dia_referencia, $primeiro_dia, $ultimo_dia);

$data_inicio = date("Y-m-d", $primeiro_dia);
$data_fim = date("Y-m-d", $ultimo_dia);
$contaTabela = $conta->obterTodos($data_inicio, $data_fim);
?>
<?php

if ($_SESSION['login'] !== 'maisonrel' && $_SESSION['login'] !== 'admin' && $_SESSION['login'] !== 'MIRELAREL') {
    die("Usuário sem Privilégio de acesso!!");
}
?>
<div id="conteudo">
    <table class="list">
        <caption>
            <span class="middle">
                <img src="../auxiliares/ico/voltar.png" title="Semana Anterior" style="cursor: pointer;" onclick="window.location.href='contaList.php?dia=<?php echo $dia_referencia - (7 * 24 * 60 * 60); ?>'"/>
            </span>
            <span class="middle">
                Listagem de Débitos<br/>
                Semana: <?php echo date("d/m/Y", $primeiro_dia)." à ".date("d/m/Y", $ultimo_dia); ?>
            </span>
            <span class="middle">
                <img src="../auxiliares/ico/avancar.png" title="Próxima Semana" style="cursor: pointer;" onclick="window.location.href='contaList.php?dia=<?php echo $dia_referencia + (7 * 24 * 60 * 60); ?>'"/>
            </span>
        </caption>
        <thead>
            <tr>
                <th>Descrição do Debito</th>
                <th>Valor do Debito</th>
                <th>Data de Criação do Debito</th>
                <th>Data Para Pagar o Debito</th>
                <th>Situação do Debito</th>
                <th>Ações</th>
            </tr>
        </thead>        
        <tbody>
            <?php
            $total_debito = 0;
            $total_pago = 0;
            foreach($contaTabela as $contaLinha){
                $total_debito += $contaLinha['valor'];
                ?>
                <tr>
                    <td><?php echo $contaLinha['descricao']; ?></td>
                    <td><?php echo funcoes::formatarMonetario($contaLinha['valor']); ?></td>
                    <td><?php echo funcoes::formatarData($contaLinha['data_criacao']); ?></td>
                    <td><?php echo funcoes::formatarData($contaLinha['data_pagar']); ?></td>
                    <td><?php 
                        if($contaLinha['pago']== 0){
                            echo 'Conta Não Paga!';
                        }
                        else{
                            $total_pago += $contaLinha['valor'];
                            echo 'Conta Paga!!';
                        }
                        ?>
                    </td>
                    <td>
                        <img src="../auxiliares/ico/cancelar.png" style="cursor: pointer;" title="Remover Conta" onclick="confirma('Tem certeza que deseja remover a conta selecionada?', function() { abrirPagina('contaCrud.php?acao=deletar&id=<?php echo $contaLinha['id']; ?>') }); "/>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="100%">
                    Total de Contas: <?php echo count($contaTabela); ?> / 
                    Total das Contas: <?php echo funcoes::formatarMonetario($total_debito); ?> /
                    Total Pago: <?php echo funcoes::formatarMonetario($total_pago); ?> /
                    Total em Débito: <?php echo funcoes::formatarMonetario(($total_debito - $total_pago)); ?>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="botoes">
    <span class="botao" onclick="abrirPagina('contaForm.php');">
        <img src="../auxiliares/ico/mais.png"/><br/>
        Adicionar
    </span>
</div>
<?php
include ("./rodape.php");
?>