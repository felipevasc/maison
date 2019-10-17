<?php
include("cabecalho.php");

$mesa = new mesa();
$venda = new venda();
$cliente = new cliente();

$vendaTabela = $venda->getVendasClientes();
?>
<div id="conteudo" style="width: 100%; text-align: center;">
    <table class="list">
        <caption>
            Vendas dos Clientes
        </caption>
    </table>
    <?php
    $cor = "laranja";
    
    foreach ($vendaTabela as $vendaLinha) {
        $clienteLinha = $cliente->get($vendaLinha['cliente']);
        $situacao_venda = $venda->getSituacaoVenda($vendaLinha['id']);
        if ($situacao_venda == 2) {
            $cor = "verde";
            $situacao = "Atendido";
            $img = "mesa_atendida.png";
        }
        else if ($situacao_venda == 1) {
            $cor = "vermelho";
            $situacao = "Pronto";
            $img = "mesa_espera.png";
        }
        else {
            $cor = "laranja";
            $situacao = "Preparando";
            $img = "mesa_gente.png";
        }
        ?>
        <a name="v_<?php echo $vendaLinha['id']; ?>"></a>
        <span title="Visualizar Venda" style="cursor: pointer;" class="alunos <?php echo $cor; ?>" onclick="abrirPagina('realizarVenda.php?venda=<?php echo $vendaLinha['id']; ?>');">
            <span class="clicavel">
                <img src="../auxiliares/ico/<?php echo $img; ?>" /><br/>
                <?php echo $clienteLinha['nome']; ?>
            </span>
            <span class="acoes" style="text-align: center; width: 90%; font-weight: bold; color: #000000; background-color: rgba(255, 255, 255, 0.5); margin: 0 auto; border-radius: 10px;">
                <?php echo $situacao; ?>
            </span>
        </span>
        <?php
    }
    ?>
</div>
<hr/>
<button class='btn btn-large btn-block btn-success' type="button" onclick="abrirPagina('selecionaClienteVenda.php');">Nova Venda</button>
<?php
include("rodape.php");