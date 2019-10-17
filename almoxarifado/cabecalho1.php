<?php
date_default_timezone_set('America/Sao_Paulo');
if (empty($_SESSION['ok'])) {
    session_start();
    $_SESSION['ok'] = true;
}
if (empty($_GET['autoload'])) {
    require '../auxiliares/autoload.php';
}
if (empty($_POST['ajax']) && empty($_GET['ajax'])) {
    ?>
    <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Almoxarifado</title>
            <script src="../auxiliares/jquery.js"></script>
            <script src="../auxiliares/jquery-ui.js"></script>
            <script src="../auxiliares/chart/Chart.js"></script>
            <script src="../auxiliares/bootstrap.js"></script>
            <script src="../auxiliares/js.js"></script>
            <link rel="stylesheet" type="text/css" href="../auxiliares/jquery-ui.css">
            <link rel="stylesheet" type="text/css" href="../auxiliares/bootstrap.css">
            <link rel="stylesheet" type="text/css" href="../auxiliares/css.css">
            <img src="../auxiliares/img/aguarde.gif" style="display: none;"/>
            <img src="../auxiliares/img/carregando.gif" style="display: none;"/>
            <meta charset="UTF-8"/>
        </head>
        <body>
            <div class="btn-group btn-group-justified ">
                <a type="button" class="btn btn-success btn-circle" onclick="alert('vendaMesas.php?restaurante=1')">Aluguéis</a>
                <div class="btn-group">
                    <a type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                        Operacional <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="javascript:void()" onclick="abrirPagina('cozinha.php')"><img src="../auxiliares/ico/mais.png"> Preparadora de Produtos </a></li>
                        <li><a href="javascript:void()" onclick="abrirPagina('compraList.php')">Compras de Produtos</a></li>
                        <li><a href="javascript:void()" onclick="abrirPagina('solicitacaoList.php')">Solicitações das Lojas</a></li>
                        <li><a href="javascript:void()" onclick="abrirPagina('clienteList.php')">Clientes</a></li>
                        <li><a href="javascript:void()" onclick="abrirPagina('vendaClientes.php')">Vendas Clientes</a></li>
                    </ul>
                </div>
                <div class="btn-group">
                    <a type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                        Lista de Produtos <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="javascript:void()" onclick="abrirPagina('itemList.php')">Itens</a></li>
                        <li><a href="javascript:void()" onclick="alert('adicionalList.php')">Itens Adicionais</a></li>
                        <li><a href="javascript:void()" onclick="abrirPagina('categoriaList.php')">Categorias</a></li>
                    </ul>
                </div>
                <div class="btn-group">
                    <a type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                        Configurações <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="javascript:void()" onclick="abrirPagina('mesaList.php')">Cadastro de Organizadores</a></li>
                        <li><a href="javascript:void()" onclick="abrirPagina('restauranteList.php')">Cadastro de Lojas</a></li>
                        <li><a href="javascript:void()" onclick="abrirPagina('clienteList.php')">Cadastro de Clientes</a></li>
                        <li><a href="javascript:void()" onclick="abrirPagina('setorList.php')">Cadastro de Setores</a></li>
                        <li><a href="javascript:void()" onclick="abrirPagina('produtoList.php')">Cadastro de Produtos</a></li>
                        <li><a href="javascript:void()" onclick="abrirPagina('formaPagamentoList.php')">Formas de Pagamento</a></li>
                    </ul>
                </div>
            </div>
            <?php
}