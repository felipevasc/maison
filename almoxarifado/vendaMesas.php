<?php
include("cabecalho.php");
$restaurante = new restaurante();
$mesa = new mesa();
$vendaMesa = new vendaMesa();
$venda = new venda();
$cliente = new cliente();
$aniversariantes = $cliente->getAniversariantes();
$noivas = $venda->getNoivas();
if (empty($_GET['data_limite'])) {
    $tempo_referencia = mktime(0, 0, 0, date('m'), date('d') + 7 - date('w'), date('Y'));
} else {
    $tmp = explode("/", $_GET['data_limite']);
    $tempo_referencia = mktime(0, 0, 0, $tmp[1], $tmp[0], $tmp[2]);
}
$restauranteLinha = $restaurante->get($_GET['restaurante']);

?><div id="conteudo2" style="width: 100%;">
    <div style="text-align: left" class="busca">
        <span class="middle" style="width: 170px; text-align: center;">
            <form method="GET" action="vendaMesas.php">
                <input type="hidden" name="restaurante" value="<?php echo $restauranteLinha['id']; ?>"/>
                <span class="data" style="text-align: left; <?php if (!empty($_GET['mes'])) echo "display: none;"; ?>">                
                    <label class="label label-primary" style="display: inline-block; width: 100%; margin: 0;">Data limite para exibição</label><br/>
                    <input <?php if (!empty($_GET['mes'])) echo "disabled='disabled'"; ?> type="text" name="data_limite" value="<?php echo date('d/m/Y', $tempo_referencia); ?>" data-tipo="data" class="form-control" size="10"/>
                </span>
                <span class="mes" <?php if (empty($_GET['mes'])) echo "style='display: none;'"; ?>>
                    <label class="label label-primary">Mês de referencia</label><br/>
                    <select class="form-control" name="mes" <?php if (empty($_GET['mes'])) echo " disabled='disabled'"; ?>>
                        <option <?php if (!empty($_GET['mes']) && $_GET['mes'] == 1) echo "selected"; ?> value="1">Janeiro</option>
                        <option <?php if (!empty($_GET['mes']) && $_GET['mes'] == 2) echo "selected"; ?> value="2">Fevereiro</option>
                        <option <?php if (!empty($_GET['mes']) && $_GET['mes'] == 3) echo "selected"; ?> value="3">Março</option>
                        <option <?php if (!empty($_GET['mes']) && $_GET['mes'] == 4) echo "selected"; ?> value="4">Abril</option>
                        <option <?php if (!empty($_GET['mes']) && $_GET['mes'] == 5) echo "selected"; ?> value="5">Maio</option>
                        <option <?php if (!empty($_GET['mes']) && $_GET['mes'] == 6) echo "selected"; ?> value="6">Junho</option>
                        <option <?php if (!empty($_GET['mes']) && $_GET['mes'] == 7) echo "selected"; ?> value="7">Julho</option>
                        <option <?php if (!empty($_GET['mes']) && $_GET['mes'] == 8) echo "selected"; ?> value="8">Agosto</option>
                        <option <?php if (!empty($_GET['mes']) && $_GET['mes'] == 9) echo "selected"; ?> value="9">Setembro</option>
                        <option <?php if (!empty($_GET['mes']) && $_GET['mes'] == 10) echo "selected"; ?> value="10">Outubro</option>
                        <option <?php if (!empty($_GET['mes']) && $_GET['mes'] == 11) echo "selected"; ?> value="11">Novembro</option>
                        <option <?php if (!empty($_GET['mes']) && $_GET['mes'] == 12) echo "selected"; ?> value="12">Dezembro</option>
                    </select>
                </span>
                <label onclick="$('.data').show('slow').find('input').removeAttr('disabled'); $('.mes').hide('slow').find('select').attr('disabled', 'disabled');">
                    <input <?php if (empty($_GET['mes'])) echo "checked"; ?> type="radio" name="tipo"/>Data 
                </label>
                <label onclick="$('.mes').show('slow').find('select').removeAttr('disabled'); $('.data').hide('slow').find('input').attr('disabled', 'disabled');">
                    <input <?php if (!empty($_GET['mes'])) echo "checked"; ?> type="radio" name="tipo"/>Mês 
                </label>
                <br/>
                <input type="submit" value="Alterar" class="btn btn-large btn-block btn-info"/>
            </form>
        </span>
        <span onclick="$('.alunos.vermelho, .alunos.laranja').hide('slow'); $('.alunos.verde').show('slow');">
            <img src="../auxiliares/ico/bola_verde.png"/>Entregue
        </span>
        <span onclick="$('.alunos.vermelho, .alunos.verde').hide('slow'); $('.alunos.laranja').show('slow');">
            <img src="../auxiliares/ico/bola_amarela.png"/>Preparando
        </span>
        <span onclick="$('.alunos.laranja, .alunos.verde').hide('slow'); $('.alunos.vermelho').show('slow');">
            <img src="../auxiliares/ico/bola_vermelha.png"/>Pronto
        </span>
        <span onclick="$('.alunos').show('slow');">
            <img src="../auxiliares/ico/bola_preto.png"/>Todos
        </span>
        <?php
        $listagem_aniversariantes = "<table class=\\'table table-hover table-striped table-ordered\\'>";
        $listagem_aniversariantes .= "<thead><tr><th>Nome</th><th>Nascimento</th><th>Telefone</th></tr></thead>";
        $listagem_aniversariantes .= "<tbody>";
        foreach ($aniversariantes as $aniversariante) {
            $listagem_aniversariantes .= "<tr><td>{$aniversariante['nome']}</td><td>" . funcoes::formatarData($aniversariante['data']) . "<td>{$aniversariante['numero']}</td></td></tr>";
        }
        if (count($aniversariantes) == 0) {
            $listagem_aniversariantes .= "<tr><td colspan=\\'2\\'>Não há clientes aniversariando hoje</td></tr>";
        }
        $listagem_aniversariantes .= "<tbody>";
        $listagem_aniversariantes .= "<tfoot><tr><td colspan=\\'2\\' style=\\'text-align: right; font-style: italic;\\'>Total de aniversariantes de hoje: " . count($aniversariantes) . "</th></tr></tfoot>";
        $listagem_aniversariantes .= "</table>";

        $listagem_noivas = "<table class=\\'table table-hover table-striped table-ordered\\'>";
        $listagem_noivas .= "<thead><tr><th>Nome</th><th>Casamento</th><th>Telefone</th></tr></thead>";
        $listagem_noivas .= "<tbody>";
        foreach ($noivas as $noiva) {
            $listagem_noivas .= "<tr><td>{$noiva['nome']}</td><td>" . funcoes::formatarData($noiva['casamento']) . "<td>{$noiva['numero']}</td></td></tr>";
        }
        if (count($aniversariantes) == 0) {
            $listagem_noivas .= "<tr><td colspan=\\'2\\'>Não há clientes com casamento hoje</td></tr>";
        }
        $listagem_noivas .= "<tbody>";
        $listagem_noivas .= "<tfoot><tr><td colspan=\\'2\\' style=\\'text-align: right; font-style: italic;\\'>Total de casados de hoje: " . count($noivas) . "</th></tr></tfoot>";
        $listagem_noivas .= "</table>";
        ?>
        <style>
            .ui-dialog {
                width: 500px !important;
            }
        </style>
        <span onclick="alerta('<?php echo $listagem_aniversariantes; ?>')" style="position: relative; cursor: pointer;">
            <img src="../auxiliares/ico/calendario2.png"/>Aniversariantes
            <span style="display: inline-block; position: absolute; right: 130px; bottom: 0px; font-size: 11px;"><strong><?php echo count($aniversariantes); ?></strong></span>
        </span>
        <span onclick="alerta('<?php echo $listagem_noivas; ?>')" style="position: relative; cursor: pointer;">
            <img src="../auxiliares/ico/calendario2.png"/>Noivas
            <span style="display: inline-block; position: absolute; right: 70px; bottom: 0px; font-size: 11px;"><strong><?php echo count($noivas); ?></strong></span>
        </span>
        <span class="middle" style="vertical-align: top;">
            <form method="GET" action="vendaVisualizarPedido.php">
                <table class="" style="float: center; width: 150px; margin-right: auto; background: none;" align="left">
                    <tr>
                        <td style="background: none;">
                            <label class="label label-primary" style="display: inline-block; width: 100%; margin: 0">Consultar Aluguel</label><br/>
                            <input placeholder="Numero Aluguel" type="text" name="venda" class="form-control" size="10"/><br/>
                            <button style="margin: 0" class='btn btn-large btn-block btn-info' type='submit'>Consultar</button>
                        </td>
                    </tr>
                </table>
            </form>
        </span>
    </div>
    <?php
    $_GET['ajax'] = true;
    $_GET['autoload'] = true;
    $_GET['id'] = "0";
    include("mesaOpcao.php");
    $mesaTabela = $mesa->getByRestaurante($restauranteLinha['id']);
    foreach ($mesaTabela as $mesaLinha) {
        $_GET['id'] = $mesaLinha['id'];
        $id_venda_aberta = $mesa->getVendaAberta($mesaLinha['id']);
        if (empty($id_venda_aberta)) {
            continue;
        }
        $vendaLinha = $venda->get($id_venda_aberta);
        $tmp = explode("-", $vendaLinha['entrega']);
        $tempo = mktime(0, 0, 0, $tmp[1], $tmp[2], $tmp[0]);
        if (!empty($_GET['mes'])) {
            if ($tmp[1] != $_GET['mes']) {
                continue;
            }
        } else if ($tempo > $tempo_referencia) {
            continue;
        }
        echo "<span id='mesa_{$mesaLinha['id']}'>";
        include("mesaOpcao.php");
        echo " </span>";
        ?>
        <script>
            $(function () {
                atualizaMesa('<?php echo $mesaLinha['id']; ?>');
            })
        </script>
        <?php
    }
    ?>
</div>
<?php
include("rodape.php");
