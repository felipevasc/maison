<?php
include("cabecalho.php");

$mesa = new mesa();
$cliente = new cliente();
$venda = new venda();
$vendaItem = new vendaItem();
$funcionario = new funcionario();

if (!empty($_GET['mesa'])) {
    $mesaLinha = $mesa->get($_GET['mesa']);
    $id_venda = $mesa->getVendaAberta($mesaLinha['id']);
    $titulo = "Organizador: " . $mesaLinha['nome'];
} else if (!empty($_GET['venda'])) {
    $id_venda = $_GET['venda'];
    $vendaLinha = $venda->get($id_venda);
    $clienteLinha = $cliente->get($vendaLinha['cliente']);
    $titulo = "Cliente: " . $clienteLinha['nome'];
} else if (!empty($_GET['cliente'])) {
    $mesaLinha['id'] = '';
    $clienteLinha = $cliente->get($_GET['cliente']);
    $titulo = "Cliente: " . $clienteLinha['nome'];
}
$tempo_referencia = mktime(0, 0, 0, date('m'), date('d') + 3, date('Y'));

if (empty($id_venda)) {
    ?>
    <div class="btn-group btn-group-justified ">
        <a type="button" class="btn btn-info btn-lg" onclick="abrirPagina('vendaMesas.php?restaurante=<?php echo $mesaLinha['restaurante']; ?>')">
            <img src="../auxiliares/ico/voltar.png" style="width: 25px;"/>
            Voltar
        </a>
        <a type="button" class="btn btn-warning btn-lg" onclick="abrirPagina('vendaMesas.php?restaurante=1')">
            <img src="../auxiliares/ico/seta_cima.png" style="width: 20px;"/>
            Unir a Outra Venda
        </a>
    </div>
    <hr/>
    <?php echo "<table class='form_mobile'><caption>{$titulo}</caption></table>"; ?>
    <form method="POST" action="vendaCrud.php">
        <input type="hidden" name="mesa" value="<?php echo $mesaLinha['id']; ?>"/>
        <table class="form_mobile">
            <tbody>
                <tr>
                    <td>                        
                        Vendedora:<br/>
                        <select name="funcionario">
                            <option value="">Escolha a Vendedora</option>
    <?php
    funcoes::montaSelect($funcionario->obterTodos(), "id", "nome", $_SESSION['id_funcionario']);
    ?>
                        </select>
                        <input type="hidden" name="cliente" value="<?php echo $clienteLinha['id']; ?>"/>
                        Data Para Retirada:<br/>
                        <input type="text" data-tipo="data" name="entrega" value="<?php echo date('d/m/Y'); ?>"/>
                        Data Para Devolução:<br/>
                        <input type="text" data-tipo="data" name="devolucao" value="<?php echo date('d/m/Y', $tempo_referencia); ?>"/>
                        Data do Casamento:<br/>
                        <input type="text" data-tipo="data" name="casamento" />
                        Noiva:<br/>
                        <input type="radio" name="noiva" value="1"/>Sim
                        <input type="radio" name="noiva" value="0" checked/>Não
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td>
                        <button class='btn btn-large btn-block btn-success' type="submit">Abrir Aluguel</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>        
    <?php
} else {
    ?>
    <div class="btn-group btn-group-justified btn-lg " style="padding: 0">
        <a type="button" class="btn btn-info btn-lg" onclick="abrirPagina('vendaMesas.php?restaurante=<?php echo $mesaLinha['restaurante']; ?>')">
            <img src="../auxiliares/ico/voltar.png" style="width: 25px;"/>
            Voltar
        </a>
        <a type="button" class="btn btn-default btn-lg" onclick="abrirPagina('vendaVisualizarPedido.php?venda=<?php echo $id_venda; ?>')">
            <img src="../auxiliares/ico/cabideico.png" style="width: 25px;"/>
            Visualizar Produtos Selecionados
        </a>
        <a type="button" class="btn btn-danger btn-lg" onclick="abrirPagina('vendaFechar.php?venda=<?php echo $id_venda; ?>')">
            <img src="../auxiliares/ico/check.png" style="width: 25px;"/>
            Fechar/Pagar/Imprimir Contrato
        </a>
    </div>
    <?php
    $categoria = new categoria();
    $item = new item();
    $cliente = new cliente();
    $vendaLinha = $venda->get($id_venda);
    $clienteLinha = $cliente->get($vendaLinha['cliente']);
    echo "<label class='label label-success' style='display: inline-block; padding: 5px; width: 100%; font-size: 16px;'>Cliente: {$clienteLinha['nome']}</label>";
    $categoriaTabela = $categoria->getByPai();
    echo " <div class='abas-ui'>";
    echo "  <ul>";
    echo "      <li><a href='#tabs-todos'>Pesquisar</a></li>";
    foreach ($categoriaTabela as $categoriaLinha) {
        echo "<li><a href='realizarVendaItens.php?venda={$vendaLinha['id']}&categoria={$categoriaLinha['id']}'>{$categoriaLinha['nome']}</a></li>";
    }
    echo "  </ul>";
    ?>
    <div id='tabs-todos'>
        <label class="label label-primary" style="margin: 0; display: inline-block; width: 100%; text-align: left">Pesquisar Produto</label><br/>
        <input class="form-control" type="text" name="produto" id="nome_produto" placeholder="Nome do Produto" onkeyup="if (this.value == '') {
                                        $('#listagem_produtos').html('')
                                    } else {
                                        carregarPagina('itemPesquisa.php?venda=<?php echo $id_venda; ?>&nome=' + this.value, 'listagem_produtos');
                                    }"/>

        <script>
            $(function () {
                $('#nome_produto').focus();
            })
        </script>
        <div id="listagem_produtos"></div>
    </div>
    <?php
    $categoriaTabela = array();
    foreach ($categoriaTabela as $categoriaLinha) {
        echo "<div id='tabs-{$categoriaLinha['id']}'>";
        $categoriaTabela0 = $categoria->getByPai($categoriaLinha['id']);

        $itemTabela = $item->getByCategoria($categoriaLinha['id']);
        if (count($itemTabela) > 0) {
            foreach ($itemTabela as $itemLinha) {
                if (!$item->checkDisponivel($itemLinha['id'], $vendaLinha['entrega'])) {
                    continue;
                }
                echo "<span onclick=\"abrirPagina('vendaAdicionarItem.php?venda={$id_venda}&item={$itemLinha['id']}')\">" . $item->exibeItem($itemLinha['id']) . "</span><span style='display: inline-block; position: relative; width: 90%;'><img style='float: right; position: absolute; top: -42px; right: -38px; cursor: pointer;' title='Visualizar imagem' onclick=\"abrirFoto('{$itemLinha['imagem']}');\" src='../auxiliares/ico/vestido.png'/></span>";
            }
        } else if (count($categoriaTabela0) == 0) {
            echo " - Sem itens cadastrados para esta categoria - ";
        }
        if (count($categoriaTabela0) > 0) {
            echo " <div class='abas-ui' style='display: none;'>";
            echo "  <ul>";
            foreach ($categoriaTabela0 as $categoriaLinha0) {
                echo "<li><a href='#tabs-{$categoriaLinha0['id']}'>{$categoriaLinha0['nome']}</a></li>";
            }
            echo "  </ul>";
            foreach ($categoriaTabela0 as $categoriaLinha0) {
                echo "<div id='tabs-{$categoriaLinha0['id']}'>";
                $categoriaTabela1 = $categoria->getByPai($categoriaLinha0['id']);
                $itemTabela1 = $item->getByCategoria($categoriaLinha0['id']);
                if (count($itemTabela1) > 0) {
                    foreach ($itemTabela1 as $itemLinha1) {
                        if (!$item->checkDisponivel($itemLinha1['id'], $vendaLinha['entrega'])) {
                            continue;
                        }
                        echo "<span onclick=\"abrirPagina('vendaAdicionarItem.php?venda={$id_venda}&item={$itemLinha1['id']}')\">" . $item->exibeItem($itemLinha1['id']) . "</span><span style='display: inline-block; position: relative; width: 90%;'><img style='float: right; position: absolute; top: -42px; right: -38px; cursor: pointer;' title='Visualizar imagem' onclick=\"abrirFoto('{$itemLinha1['imagem']}');\" src='../auxiliares/ico/vestido.png'/></span>";
                    }
                } else if (count($categoriaTabela1) == 0) {
                    echo " - Sem itens cadastrados para esta categoria - ";
                }
                if (count($categoriaTabela1) > 0) {
                    echo " <div class='abas-ui'>";
                    echo "  <ul>";
                    foreach ($categoriaTabela1 as $categoriaLinha1) {
                        echo "<li><a href='#tabs-{$categoriaLinha1['id']}'>{$categoriaLinha1['nome']}</a></li>";
                    }
                    echo "  </ul>";
                    foreach ($categoriaTabela1 as $categoriaLinha1) {
                        echo "<div id='tabs-{$categoriaLinha1['id']}'>";
                        $itemTabela2 = $item->getByCategoria($categoriaLinha1['id']);
                        if (count($itemTabela2) > 0) {
                            echo "<hr/>";
                            foreach ($itemTabela2 as $itemLinha2) {
                                if (!$item->checkDisponivel($itemLinha2['id'], $vendaLinha['entrega'])) {
                                    continue;
                                }
                                echo "<span onclick=\"abrirPagina('vendaAdicionarItem.php?venda={$id_venda}&item={$itemLinha2['id']}')\">" . $item->exibeItem($itemLinha2['id']) . "</span>";
                            }
                        }

                        echo "</div>";
                    }
                    echo "</div>";
                }
                echo "</div>";
            }
            echo "</div>";
        }
        echo " </div>";
    }
    echo " </div>";
}
include("rodape.php");
