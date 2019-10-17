<?php
include ("./cabecalho.php");

$sangria = new sangria();



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
$sangriaTabela = $sangria->obterTodos($data_inicio, $data_fim);
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
                <img src="../auxiliares/ico/voltar.png" title="Semana Anterior" style="cursor: pointer;" onclick="window.location.href='sangriaList.php?dia=<?php echo $dia_referencia - (7 * 24 * 60 * 60); ?>'"/>
            </span>
            <span class="middle">
                Listagem de Sangria<br/>
                Semana: <?php echo date("d/m/Y", $primeiro_dia)." à ".date("d/m/Y", $ultimo_dia); ?>
            </span>
            <span class="middle">
                <img src="../auxiliares/ico/avancar.png" title="Próxima Semana" style="cursor: pointer;" onclick="window.location.href='sangriaList.php?dia=<?php echo $dia_referencia + (7 * 24 * 60 * 60); ?>'"/>
            </span>
        </caption>
        <thead>
            <tr>
                <th>Funcionário Responsável</th>
                <th>Valor da Sangria</th>
                <th>Data da Sangria</th>
                <th>Ações</th>
            </tr>
        </thead>        
        <tbody>
            <?php
            $total_debito = 0;
            $total_pago = 0;
            foreach($sangriaTabela as $sangriaLinha){
                $total_debito += $sangriaLinha['valor'];
                ?>
                <tr>
                    <td><?php echo $sangriaLinha['funcionario']; ?></td>
                    <td><?php echo funcoes::formatarMonetario($sangriaLinha['valor']); ?></td>
                    <td><?php echo funcoes::formatarData($sangriaLinha['data']); ?></td>                    
                    <td>
                        <img src="../auxiliares/ico/cancelar.png" style="cursor: pointer;" title="Remover Sangria" onclick="confirma('Tem certeza que deseja remover a sangria selecionada?', function() { abrirPagina('sangriaCrud.php?acao=deletar&id=<?php echo $sangriaLinha['id']; ?>') }); "/>
                        <a type="button" class="btn btn-info btn-lg" onclick="abrirPagina('sangriaRecibo.php?sangria=<?php echo $sangriaLinha['id']; ?>', true)">
                            <img src="../auxiliares/ico/impressora.png" style="width: 25px;"/>
                            Imprimir Recibo
                        </a>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="100%">
                    Total de Sangrias: <?php echo count($sangriaTabela); ?> / 
                    Total das Sangrias: <?php echo funcoes::formatarMonetario($total_debito); ?> /                   
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="botoes">
    <span class="botao" onclick="abrirPagina('sangriaForm.php');">
        <img src="../auxiliares/ico/mais.png"/><br/>
        Adicionar
    </span>
</div>
<?php
include ("./rodape.php");
?>