<?php
include("cabecalho.php");
$vendaItem = new vendaItem();
$vendaMesa = new vendaMesa();
$cliente = new cliente();
$mesa = new mesa();
$adicionalVendaItem = new adicionalVendaItem();
$adicional = new adicional();

$vendaItemTabela = $vendaItem->getPendentes($_GET['id_maior']);
$id_maior = $_GET['id_maior'];
foreach ($vendaItemTabela as $i => $vendaItemLinha) {
    if ($id_maior < $vendaItemLinha['id']) {
        $id_maior = $vendaItemLinha['id'];
    }
    if (!empty($vendaItemLinha['cliente'])) {
        $clienteLinha = $cliente->get($vendaItemLinha['cliente']);
        $titulo = "Cliente: ".$clienteLinha['nome'];
    }
    else {
        $mesaTabela = $vendaMesa->getMesasVenda($vendaItemLinha['venda']);
        if (count($mesaTabela) > 0) {
            $mesaLinha = $mesa->get($mesaTabela[0]['mesa']);
            $titulo = "Organizador: ".$mesaLinha['nome'];
        }
        else {
            $titulo = "Desconhecido";
        }
    }
    if ($vendaItemLinha['pronto'] === null) {
        $classe2 = "";
        $situacao = "Aguardando";
        $img = "<img class='situacao' src='../auxiliares/ico/bola_azul.png'/>";
        $nova_situacao = "FALSE";
    }
    else {
        $classe2 = 'preparando';
        $situacao = "Preparando";
        $img = "<img class='situacao' src='../auxiliares/ico/bola_verde.png'/>";
        $nova_situacao = "NULL";
    }
    ?><span class="pedido <?php echo $classe2; ?>">
        <input type="hidden" class="ids" value="<?php echo $vendaItemLinha['id']; ?>"/>
        Nº: <?php echo $vendaItemLinha['id']; ?>
        <br/>
        <?php echo $titulo; ?>
        <hr style="margin-top: 0; margin-bottom: 5px;"/>
        <span class="texto">
            <?php 
            echo $vendaItemLinha['quantidade']." - ".$vendaItemLinha['item']; 
            $adicionalVendaItemTabela = $adicionalVendaItem->getByVendaItem($vendaItemLinha['id']);
            foreach ($adicionalVendaItemTabela as $adicionalVendaItemLinha) {
                $adicionalLinha = $adicional->get($adicionalVendaItemLinha['adicional']);
                echo "<br/>&nbsp;&nbsp;&nbsp;&nbsp;<span class='middle'><img src='../auxiliares/ico/mais.png' style='width: 16px'/></span><span class='middle'>{$adicionalLinha['nome']}</span>";
            }
            ?>
        </span>
        <br/>
        <span class="obs"><?php echo $vendaItemLinha['obs']; ?></span>
        <span class="situacao">
            <?php echo $situacao; ?>
        </span>
        <?php echo $img; ?>
        <input type="hidden" id="situacao_<?php echo $vendaItemLinha['id']; ?>" value="<?php echo $nova_situacao; ?>"/>
    </span><?php
    $parar = false;
    break;
}