<?php
include("cabecalho.php");

$mesa = new mesa();
if (empty($_GET['restaurante'])) {
    echo "<option value=''>Selecione um Restaurante</option>";
}
else {
    echo "<option value=''>Selecione uma Mesa</option>";
    funcoes::montaSelect($mesa->getByRestaurante($_GET['restaurante']), "id", "nome", "");
}