<?php
include("cabecalho.php");
$venda = new venda();
$funcionario = new funcionario();
$vendaLinha = $venda->get($_GET['venda']);
$funcionarioLinha  = $funcionario->get($vendaLinha['funcionario']);
?>
 <form method="POST" action="vendaCrud.php">
        <input type="hidden" name="id" value="<?php echo $vendaLinha['id']; ?>"/>
        <table class="form_mobile">
            <tbody>
                <tr>
                    <td>                        
                        Vendedora:<br/>
                          <?php $funcionario = new funcionario();                if ($_SESSION['login'] !== 'maisonrel' && $_SESSION['login'] !== 'admin' && $_SESSION['login'] !== 'MIRELAREL') { ?>
                        <select readonly disabled name="funcionario">
                            
                            <option  value="<?php echo $vendaLinha['funcionario']; ?>"><?php echo $funcionarioLinha['nome'];?></option>
                          </select> <?php }else{ ?>
                        
                        <select name="funcionario">
                            <option value="">Escolha a Vendedora</option>
                            <?php
                            $funcionario = new funcionario();
                            funcoes::montaSelect($funcionario->obterTodos(), "id", "nome", $vendaLinha['funcionario']);
                            ?>
                          </select><?php } ?>
                        Data Para Retirada:<br/>
                        <input type="text" data-tipo="data" name="entrega" value="<?php echo funcoes::formatarData($vendaLinha['entrega']); ?>"/>
                        Data Para Devolução:<br/>
                        <input type="text" data-tipo="data" name="devolucao" value="<?php echo funcoes::formatarData($vendaLinha['devolucao']); ?>"/>
                        Data Que o Pedido Foi Realizado:<br/>
                        <input type="text" data-tipo="data" name="data" disabled readonly value="<?php echo funcoes::formatarData($vendaLinha['data']); ?>"/>
                        
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td>
                        <button class='btn btn-large btn-block btn-success' type="submit">Editar</button>
                        <button class='btn btn-large btn-block btn-success' type="button" onclick="history.go(-1)">Voltar</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>    
<?php
include("rodape.php");
?>