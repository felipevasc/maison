<?php
include ("./cabecalho.php");

$nomeTabela = funcoes::nomeTabelaByCaminho(__FILE__);
$tabela = new $nomeTabela();
?>
<table class="form_mobile">
    <tbody>
        <tr>
            <td>
                Buscar:<br/>
                <input type="text" onkeyup="filtrarCliente(this.value);"/> 
            </td>
        </tr>
    </tbody>
   
</table>

<form method="POST" action="<?php echo $nomeTabela; ?>Crud.php">
    <?php
    echo $tabela->montarList();
    ?>
</form>
<?php
funcoes::gerarPdf(false, 'conteudo');

include ("./rodape.php");
?>