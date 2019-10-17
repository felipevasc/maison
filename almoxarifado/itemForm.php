<?php
include ("./cabecalho.php");

$nomeTabela = funcoes::nomeTabelaByCaminho(__FILE__);
$tabela = new $nomeTabela();
?>

<form method="POST" enctype="multipart/form-data" action="<?php echo $nomeTabela; ?>Crud.php">
    <?php
    echo $tabela->montarForm();
    ?>
</form>
<?php
include ("./rodape.php");
?>