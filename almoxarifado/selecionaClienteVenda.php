<?php
include("cabecalho.php");
?>
    <table style="width: 100%; margin: 0 auto; font-size: 12px;">
        <tbody>
            <tr>
                <td>
                    <label class="label label-primary">Pesquisar Cliente</label><br/>
                    <input class="form-control" placeholder="Nome do Cliente" onkeyup="carregarPagina('clientePesquisa.php?nome='+this.value, 'listagem_clientes')"/>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="text-align: center" id="listagem_clientes"> - Informe o nome do cliente - </div>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <button class='btn btn-large btn-block btn-warning' onclick="abrirPagina('clienteForm.php')" type="button">Novo Cliente</button>
                </td>
            </tr>
        </tfoot>
    </table>
<?php
include("rodape.php");
?>