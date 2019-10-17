<?php
class item extends generico {
    public function getByCategoria($id_categoria = '') {
        if (empty($id_categoria)) {
            return $this->getByCondicao("WHERE categoria is null");
        }
        else {
            return $this->getByCondicao("WHERE categoria = {$id_categoria}");
        }
    }
    
    public function exibeItem($id_item) {
        $itemLinha = $this->get($id_item);
        $item = "<button type='button' class='btn btn-default btn-sm btn-block btn-circle' style='height: 250px; text-align: left !important;'>";
        $item .= "<span class='middle'>";
        $item .= " <span class='middle'>{$itemLinha['nome']}";
        $item .= "<br/>";
        $item .= "R$ ".number_format($itemLinha['valor'], 2, ',', '.');
        $item .= "<br/>";
        $item .= "</span><br/>";
        $item .= "<span style='font-size: 16px; display: inline-block; width: 90%; min-width: 300px; white-space: normal;'>{$itemLinha['detalhes']}</span>";
        if (true) {
            
        }
        $item .= "</span>";
        $item .= "<span class='middle' style='float: right; right: 50px;'><img src='{$itemLinha['imagem']}' style='width: 180px; height: 240px;'/></span>";
        $item .= "</button>";
        return $item;
    }
    
    public function getByNome($nome_item) {
        $nome_item = str_replace(" ", "%", $nome_item);
        return $this->getByCondicao("WHERE upper(nome) like upper('%{$nome_item}%') ORDER BY categoria, nome");
    }
    
    public function checkDisponivel($id_item, $data_referencia) {
        if (empty($data_referencia)) {
            return true;
        }
        $tmp = explode("-", substr($data_referencia, 0, 10));
        $time = mktime(0, 0, 0, $tmp[1], $tmp[2], $tmp[0]);
        $inicio = date('Y-m-d', $time - (5 * 24 * 60 * 60));
        $fim = date('Y-m-d', $time + (5 * 24 * 60 * 60));
        $rs = $this->getCamposByCondicao("vi.item", 
                            "i JOIN vendas_itens vi ON (i.id = vi.item) "
                            . "JOIN vendas v ON (vi.venda = v.id) "
                        . "WHERE i.id = {$id_item} "
                            . "and v.fechada < '2000-01-01' "
                            . "and v.entrega between '{$inicio}' and '{$fim}'");
        if (count($rs) > 0) {
            return false;
        }
        else {
            return true;
        }
    }
    
    public function montarListBootstrap() {
        $nome_classe = get_class($this);
        $campos = $this->campos;
        if (!empty($_GET['categoria'])) {
            $rs = $this->getByCategoria($_GET['categoria']);
        }
        else {
            $rs = $this->obterTodos();
        }
        $tabela = "";
        $tabela .= "<div id='conteudo'>";
        $tabela .= "<div class='panel panel-default'>";
        $tabela .= "<div class='panel-heading'>Listagem de {$this->label}</div>";
        $tabela .= "<div class='table-responsive'>";
        $tabela .= "<table class='table table-hover table-striped table-ordered'>";
        $tabela .= "    <thead>";
        $i = 0;
        $campos_selecionados = array();
        $tabela .= "        <tr>";
        foreach ($campos as $nome => $array) {
            if ($i > 2) {
            //    break;
            }
            if (!empty($array['nao_lista'])) {
                continue;
            }
            if (empty($array['label'])) {
                $label = str_replace("_", " ", $nome);
            }
            else {
                $label = $array['label'];
            }
            $label = funcoes::capitalizar($label);
            $tabela .= "<th>{$label}</th>";
            $campos_selecionados[] = $nome;
            $tipos[] = $array['tipo'];
            $i++;
        }
        $tabela .= "            <th>Ações</th>";
        $tabela .= "        </tr>";
        $tabela .= "    </thead>";
        $tabela .= "    <tbody>";
        foreach ($rs as $row) {
            $tabela .= "<tr>";
            foreach ($campos_selecionados as $nome) {
                if (!empty($campos[$nome]['list'])) {
                    if (empty($campos[$nome]['list_campo'])) {
                        $campos[$nome]['list_campo'] = "descricao";
                    }
                    $nome_campo = "get".$campos[$nome]['list_campo'];
                    $objeto = $campos[$nome]['list'];
                    $valor = $objeto->$nome_campo($row[$nome]);
                }
                else {
                    $valor = $row[$nome];
                }
                $valor = funcoes::formatarCampo($campos[$nome]['tipo'], $valor);
                $tabela .= "<td>{$valor}</td>";
            }
            $tabela .= "    <td>";
            $tabela .= "        <i class='icon-pencil' title='Editar' onclick=\"abrirPagina('{$nome_classe}Form.php?id={$row['id']}')\"></i>";
            $tabela .= "        <img style='width: 26px; cursor: pointer;' src='../auxiliares/ico/lapis.png' title='Editar' onclick=\"abrirPagina('{$nome_classe}Form.php?id={$row['id']}');\"/>";
            $tabela .= "        <img style='width: 26px; cursor: pointer;' src='../auxiliares/ico/cancelar.png' title='Remover' onclick=\"confirma('Tem certeza que deseja remover o {$this->label} selecionado?', function(){ abrirPagina('{$nome_classe}Crud.php?acao=deletar&id={$row['id']}') });\"/>";
            $tabela .= "    </td>";
            $tabela .= "</tr>";
        }
        
        $tabela .= "    </tbody>";
        $tabela .= "    <tfoot>";
        $tabela .= "        <tr>";
        $tabela .= "            <td colspan='".($i + 1)."'>Total: ".count($rs)."</td>";
        $tabela .= "        </tr>";
        $tabela .= "    </tfoot>";
        $tabela .= "</table>";
        $tabela .= "</div>";
        $tabela .= "</div>";
        $tabela .= "</div>";
        $tabela .= "<button class='btn btn-large btn-block btn-success' type='button'onclick=\"abrirPagina('{$nome_classe}Form.php')\">Adicionar {$this->label}</button>";
        $tabela .= "<button class='btn btn-large btn-block btn-info' type='button' onclick=\"abrirPagina('inicio.php')\">Voltar</button>";
//<button class="btn btn-large btn-block" type="button">Botões em bloco</button>";

        
        return $tabela;
    }
}
?>