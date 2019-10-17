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


if (empty($_GET['submit'])) {
    $_GET['pago'] = "1";
    $_GET['inicio'] = $data_inicio;
    $_GET['fim'] = $data_fim;
}
$contaTabela = $conta->obterTodosByParametros1($_GET['pago'], $_GET['inicio'], $_GET['fim']);


?>
<?php

if ($_SESSION['login'] !== 'maisonrel' && $_SESSION['login'] !== 'admin' && $_SESSION['login'] !== 'MIRELAREL') {
    die("Usuário sem Privilégio de acesso!!");
}
?>

<form method="get">
    <table class="form_mobile">
        <tbody>            
            
            <tr>
                <td>
                    Pago:<br/>
                    <select name="pago">
        <option value="1" <?php if ($_GET['pago'] == 1) echo "selected"; ?> >Pago</option>     
    </select>
                </td>
            </tr>
            <tr>
                <td>
                    Data Inicio:
                    <?php
                    if (empty($_GET['inicio'])) {
                        $_GET['inicio'] = $data_inicio;
                    }
                    ?>
                    <input type="text" data-tipo="data" name="inicio" value="<?php echo $_GET['inicio']; ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    Data Fim:
                    <?php
                    if (empty($_GET['fim'])) {
                        $_GET['fim'] = $data_fim;
                    }
                    ?>
                    <input type="text" data-tipo="data" name="fim" value="<?php echo $_GET['fim']; ?>"/>
                </td>
            </tr>
        </tbody>
    </table> 
    <input type="submit" class="btn btn-success">
</form>


<div id="conteudo">
    <table class='table table-hover table-striped table-ordered'>
        
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