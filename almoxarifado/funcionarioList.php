<?php
include ("./cabecalho.php");

$nomeTabela = funcoes::nomeTabelaByCaminho(__FILE__);
$tabela = new $nomeTabela();
?>

<?php

if ($_SESSION['login'] !== 'maisonrel' && $_SESSION['login'] !== 'admin' && $_SESSION['login'] !== 'MIRELAREL') {
    die("Usuário sem Privilégio de acesso!!");
}
?>

<form method="POST" action="<?php echo $nomeTabela; ?>Crud.php">
    <?php
    echo $tabela->montarList();
    ?>
</form>
<?php
include ("./rodape.php");
?>