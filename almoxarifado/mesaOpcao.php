<?php
include("cabecalho.php");
$mesa = new mesa();
$venda = new venda();
$nomeCliente = "Novo Aluguel";
$data = "____-__-__";
$data1 = "____-__-__";
if ($_GET['id'] == 0) {
    $cor = "";
    $situacao = "Adicionar";
    $img = "../auxiliares/ico/mesa.png";
    $mesaLinha['id'] = 0;
    $situacao_venda = ""; 
    $mesaLinha['nome'] = "";
    $pagina = "selecionaClienteVenda.php";
}
else {
    $mesaLinha = $mesa->get($_GET['id']);
    $id_venda_aberta = $mesa->getVendaAberta($mesaLinha['id']);
    if (!empty($id_venda_aberta)) {
        $cliente = new cliente();
        $vendaLinha = $venda->get($id_venda_aberta);
        $clienteLinha = $cliente->get($vendaLinha['cliente']);
        $nomeCliente = $clienteLinha['nome'];
        $data = funcoes::formatarData($vendaLinha['entrega']);
        $data1 = funcoes::formatarData($vendaLinha['devolucao']);
        $situacao_venda = $venda->getSituacaoVenda($id_venda_aberta);
        if ($situacao_venda == 2) {
            $cor = "verde";
            $situacao = "Atendido";
            $img = "../auxiliares/ico/mesa_atendida.png";
        }
        else if ($situacao_venda == 1) {
            $cor = "vermelho";
            $situacao = "Pronto";
            $img = "../auxiliares/ico/mesa_espera.png";
        }
        else {
            $cor = "laranja";
            $situacao = "Preparando";
            $img = "../auxiliares/ico/mesa_gente.png";
        }
        $vendaItem = new vendaItem();
        $vendaItemTabela = $vendaItem->getByVenda($vendaLinha['id']);
        if (count($vendaItemTabela) > 0) {
            $item = new item();
            $itemLinha = $item->get($vendaItemTabela[0]['item']);
            $img = $itemLinha['imagem'];
        }
    }
    else {
        exit;
        $situacao_venda = "3";
        $cor = "";
        $situacao = "Livre";
        $img = "../auxiliares/ico/mesa.png";
    }  
    $pagina = "vendaVisualizarPedido.php?venda={$vendaLinha['id']}";
}

?>
<a name="m_<?php echo $mesaLinha['id']; ?>"></a>
<input type="hidden" id="situacao_<?php echo $mesaLinha['id']; ?>" value="<?php echo $situacao_venda; ?>"/>
<span title="<?php echo $situacao; ?>" style="cursor: pointer;" class="alunos <?php echo $cor; ?>" onclick="abrirPagina('<?php echo $pagina; ?>');">
    <span class="clicavel">
        <img src="<?php echo $img; ?>" /><br/>
        <?php echo $nomeCliente; ?>
    </span>
    <span class="acoes" style="text-align: center; width: 90%; font-weight: bold; color: #000000; background-color: rgba(255, 255, 255, 0.5); margin: 0 auto; border-radius: 10px;">
        <?php echo "Retirada:<br/>".funcoes::formatarData($data)."<br/>Devolução:<br/>".funcoes::formatarData($data1); ?>
    </span>
</span>