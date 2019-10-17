<?php
date_default_timezone_set('America/Sao_Paulo');

if (empty($_SESSION['ok'])) {
    session_start();
    $_SESSION['ok'] = true;
}
if (empty($_SESSION['login'])) {
    header("Location: login.php");
    die('Efetue o login');
}
if (empty($_GET['autoload'])) {
    require '../auxiliares/autoload.php';
}
if (empty($_POST['ajax']) && empty($_GET['ajax'])) {
    ?>
    <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Maison Trajes Finos</title>
            <script src="../auxiliares/jquery.js"></script>
            <script src="../auxiliares/jquery-ui.js"></script>
            <script src="../auxiliares/chart/Chart.js"></script>
            <script src="../auxiliares/bootstrap.js"></script>
            <script src="../auxiliares/js.js"></script>
            <link rel="stylesheet" type="text/css" href="../auxiliares/jquery-ui.css">
            <link rel="stylesheet" type="text/css" href="../auxiliares/bootstrap.css">
            <link rel="stylesheet" type="text/css" href="../auxiliares/css.css?a=1">
        <img src="../auxiliares/img/aguarde.gif" style="display: none;"/>
        <img src="../auxiliares/img/carregando.gif" style="display: none;"/>
        <meta charset="UTF-8"/>
        <script>
            $(function () {
                $('.menu-ui2').menu();
                $('.menu-ui2 ul li').css('width', '400px');
                $('.menu-ui2').show('slow');
            })
        </script>
    </head>
    <body>
        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td style=" vertical-align: top; width: 200px;">
                        <ul class="menu-ui2" style=" margin: 0; border: 20; padding: 0; width: 200px; display: none">
                            <li style="text-align: center;"><?php echo $_SESSION['login']; ?></li>
                            <li onclick="abrirPagina('vendaMesas.php?restaurante=1');">
                                <span class="middle">
                                    <img src="../auxiliares/ico/home.png"/>
                                </span>
                                <span class="middle">
                                    Tela Principal
                                </span>
                            </li>
                            <li>
                                <span class="middle">
                                    <img src="../auxiliares/ico/cliente.png"/>
                                </span>
                                <span class="middle">
                                    Clientes
                                </span>
                                <ul>
                                    <li onclick="abrirPagina('clienteForm.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/mais.png"/>
                                        </span>
                                        <span class="middle">
                                            Cadastro
                                        </span>
                                    </li>
                                    <li onclick="abrirPagina('clienteList.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/lista.png"/>
                                        </span>
                                        <span class="middle">
                                            Listagem
                                        </span>
                                    </li>
                                    <li onclick="abrirPagina('relatorioContratoComum.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/ficha.png"/>
                                        </span>
                                        <span class="middle">
                                            Ficha Limpa 
                                        </span>
                                    </li>
                                    <li onclick="alert('Bloqueado');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/resultado.png"/>
                                        </span>
                                        <span class="middle">
                                            Histórico
                                        </span>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <span class="middle">
                                    <img src="../auxiliares/ico/fornece.png"/>
                                </span>
                                <span class="middle">
                                    Funcionários
                                </span>
                                <ul>
                                    <li onclick="abrirPagina('funcionarioRelVenda.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/resultado.png"/>
                                        </span>
                                        <span class="middle">
                                            Relátorio de Vendas
                                        </span>
                                    </li>
                                    <li onclick="abrirPagina('funcionarioList.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/mais.png"/>
                                        </span>
                                        <span class="middle">
                                            Listagem de Funcionários
                                        </span>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <span class="middle">
                                    <img src="../auxiliares/ico/vestido.png"/>
                                </span>
                                <span class="middle">
                                    Produtos
                                </span>
                                <ul>
                                    <li onclick="abrirPagina('itemForm.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/mais.png"/>
                                        </span>
                                        <span class="middle">
                                            Cadastro
                                        </span>
                                    </li>
                                    <li onclick="abrirPagina('categoriaList.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/mais.png"/>
                                        </span>
                                        <span class="middle">
                                            Categorias
                                        </span>
                                    </li>                                    
                                    <li onclick="abrirPagina('itemList.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/lista.png"/>
                                        </span>
                                        <span class="middle">
                                            Listagem
                                        </span>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <span class="middle">
                                    <img src="../auxiliares/ico/menos.png"/>
                                </span>
                                <span class="middle">
                                    Despesas
                                </span>
                                <ul>
                                    <li onclick="abrirPagina('contaForm.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/mais.png"/>
                                        </span>
                                        <span class="middle">
                                            Cadastro
                                        </span>
                                    </li>
                                    <li onclick="abrirPagina('contaList.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/lista.png"/>
                                        </span>
                                        <span class="middle">
                                            Visualizar
                                        </span>
                                    </li>
                                    <li onclick="abrirPagina('contaListMes.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/lista.png"/>
                                        </span>
                                        <span class="middle">
                                            Visualizar Mês
                                        </span>
                                    </li>
                                    <li onclick="abrirPagina('contaPagar.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/cifrao.png"/>
                                        </span>
                                        <span class="middle">
                                            Pagar
                                        </span>
                                    </li>
                                    <li onclick="abrirPagina('sangriaForm.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/mais.png"/>
                                        </span>
                                        <span class="middle">
                                            Cadastrar Sangria
                                        </span>
                                    </li>
                                    <li onclick="abrirPagina('sangriaList.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/lista.png"/>
                                        </span>
                                        <span class="middle">
                                            Visualizar Sangria
                                        </span>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <span class="middle">
                                    <img src="../auxiliares/ico/aluguel.png"/>
                                </span>
                                <span class="middle">
                                    Aluguéis
                                </span>
                                <ul>
                                    <li onclick="abrirPagina('selecionaClienteVenda.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/mais.png"/>
                                        </span>
                                        <span class="middle">
                                            Novo
                                        </span>
                                    </li>                                       
                                    <li onclick="abrirPagina('vendas.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/resultado.png"/>
                                        </span>
                                        <span class="middle">
                                            Planilha de Vendas
                                        </span>
                                    </li>
                                    <li onclick="abrirPagina('funcionarioRel.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/resultado.png"/>
                                        </span>
                                        <span class="middle">
                                            Relatório Funcionários
                                        </span>
                                    </li>
                                    <li onclick="abrirPagina('itemRel.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/resultado.png"/>
                                        </span>
                                        <span class="middle">
                                            Relatório Itens
                                        </span>
                                    </li>
                                    <li onclick="abrirPagina('clienteRel.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/resultado.png"/>
                                        </span>
                                        <span class="middle">
                                            Historico Clientes
                                        </span>
                                    </li>
                                    <li onclick="abrirPagina('clienteRelPrevisao.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/resultado.png"/>
                                        </span>
                                        <span class="middle">
                                            Previsão de Recebimento
                                        </span>
                                    </li>
                                    <li onclick="abrirPagina('historico_pagamento.php');">
                                        <span class="middle">
                                            <img src="../auxiliares/ico/resultado.png"/>
                                        </span>
                                        <span class="middle">
                                            Controle de Caixa
                                        </span>
                                    </li>
                                </ul>
                            </li>
                            <li onclick="abrirPagina('logoff.php');">
                                <span class="middle">
                                    <img src="../auxiliares/ico/cancelar.png"/>
                                </span>
                                <span class="middle">
                                    Sair
                                </span>
                            </li>
                        </ul>
                    </td>
                    <td style="vertical-align: top; text-align: center;">
                        <div id="dialog" style="display: none; height: 620px;">
                            <img src="../auxiliares/ico/x.png" style="width: 450px; height: 600px;"/>
                        </div>

                        <?php
                    }