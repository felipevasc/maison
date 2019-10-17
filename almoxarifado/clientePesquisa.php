<?php
include("cabecalho.php");

$cliente = new cliente();

$clienteTabela = $cliente->getByName($_GET['nome']);

?>
<div class='panel panel-default'>
    
    <table class="table table-hover table-striped table-ordered listagem">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>CPF</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($clienteTabela as $clienteLinha) {
                ?>
                <tr style="font-size: 12px;">
                    <td><?php echo $clienteLinha['nome']; ?></td>
                    <td><nobr><?php echo $clienteLinha['cpf']; ?></nobr></td>
                    <td>
                        <img title="Selecionar" src="../auxiliares/ico/check_red.png" style="cursor: pointer;" onclick="abrirPagina('realizarVenda.php?cliente=<?php echo $clienteLinha['id']; ?>');"/>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>    
</div>
<?php
include("rodape.php");
?>