<?php
include("cabecalho.php");

$vendaItem = new vendaItem();
$vendaMesa = new vendaMesa();
$cliente = new cliente();
$mesa = new mesa();

$vendaItemTabela = $vendaItem->getPendentes();
$id_maior = 0;
?>
<div class="pedidos">
    <?php
    $_GET['ajax'] = true;
    $_GET['autoload'] = true;
    do {
        $parar = TRUE;
        $_GET['id_maior'] = $id_maior;
        include("vendaItemOpcao.php");
    } while (!$parar);
    ?>
    <span class="ultimo"></span>
    <span class="qtds">
        <span style="background-image: url('../auxiliares/ico/bola_azul.png')" id="qtd_aguardando" />0</span>
        <span style="background-image: url('../auxiliares/ico/bola_verde.png')" id="qtd_preparando" />0</span>
    </span>
    <input type="hidden" id="id_maior" value="<?php echo $id_maior; ?>"/>
</div>
<div class="btn-group btn-group-justified">
    <a class='btn btn-large btn-info' type="button" onclick="voltarPedido()">Voltar [F]</a>
    <a class='btn btn-large btn-danger' id="bt_cancelar" type="button" onclick="setAguardando()">Aguardando [D]</a>
    <a class='btn btn-large btn-success' id="bt_pronto" type="button" onclick="setPronto()">Pronto [S]</a>
    <a class='btn btn-large btn-info' type="button" onclick="avancarPedido()">Avançar [A]</a>
    
</div>

<script>
    $(function(){
        reconheceTeclasCozinha();
        carregaPedido();
    })
</script>
<?php
include("rodape.php");