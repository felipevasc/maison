<?php
class cliente extends generico {
    public function getByName($nome) {
        $nome = str_replace(" ", "%", $nome);
        return $this->getByCondicao("WHERE upper(nome) like upper('%{$nome}%')");
    }
    
    public function getAniversariantes() {
        $hoje = date("m-d");
        return $this->getByCondicao("WHERE data like '%{$hoje}'");
    }
    
    public function montarListBootstrap() {
        $nome_classe = get_class($this);
        $campos = $this->campos;
        $rs = $this->obterTodos();
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
            $tabela .= "        <img style='width: 26px; cursor: pointer;' src='../auxiliares/ico/ficha.png' title='Ficha Cliente' onclick=\"abrirPagina('relatorioContratoComumCliente.php?cliente={$row['id']}');\"/>";
            $tabela .= "        <img style='width: 26px; cursor: pointer;' src='../auxiliares/ico/aluguel.png' title='Relatório de Alugueis' onclick=\"abrirPagina('ClienteRel.php?cliente={$row['id']}');\"/>";
            $tabela .= "        <img style='width: 26px; cursor: pointer;' src='../auxiliares/ico/lista.png' title='Recibo Cliente' onclick=\"abrirPagina('clienteRecibo.php?cliente={$row['id']}');\"/>";
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