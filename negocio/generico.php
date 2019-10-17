<?php
abstract class generico {
    protected $dao;
    public $campos;
    public $label;
    public $referencia_get;
    
    public function __construct() {
        $nome_classe = get_class($this);
        $nome_classe = $nome_classe."Dao";
        $this->dao = new $nome_classe();
        $this->campos = $this->dao->campos;
        if (empty($this->dao->label)) {
            $this->label = funcoes::capitalizar(str_replace("_", " ", get_class($this)));
        }
        else {
            $this->label = $this->dao->label;
        }
        $this->referencia_get = $this->dao->referencia_get;
    }

    public function set($dados_formulario) {
        return $this->dao->set($dados_formulario);
    }
    public function update($dados_formulario) {
        return $this->dao->update($dados_formulario, $dados_formulario['id']);
    }
    public function delete($id) {
        return $this->dao->delete($id);
    }
    
    public function obterTodos() {
        $rs = $this->dao->get("*", "ORDER BY 2");
        return $rs;
    }
    
    public function get($id) {
        $row = $this->dao->get("*", "WHERE id = {$id}");
        if (count($row) > 0) {
            return $row[0];
        }
        else {
            return false;
        }
    }
    
    public function getByCondicao($condicao) {
        $rs = $this->dao->get("*", $condicao);
        return $rs;
    }
    
    public function getCamposByCondicao($campos, $condicao) {
        $rs = $this->dao->get($campos, $condicao);
        return $rs;
    }
    
    public function montarForm() {
        $campos = $this->campos;
        $tabela = "";
        if (!empty($_GET['id'])) {
            $rs = $this->getByCondicao("WHERE id = {$_GET['id']}");
            $row = $rs[0];
            $tabela .= "<input type='hidden' name='id' value='{$row['id']}'/>";
        }
        else if (!empty($this->referencia_get) && !empty($_GET[$this->referencia_get])) {
            $referencia = $this->referencia_get;
            $valor_referencia = $_GET[$referencia];
            $rs = $this->getByCondicao("WHERE {$referencia} = '{$valor_referencia}'");

            if (count($rs) > 0) {
                $row = $rs[0];
                $_GET['id'] = $row['id'];
                $tabela .= "<input type='hidden' name='id' value='{$row['id']}'/>";
            }
        }
        $tabela .= "<div class='panel panel-default'>";
        $tabela .= "<div class='panel-heading'>Cadastro de {$this->label}</div>";
        $tabela .= "<table class='form_mobile'>";
        $tabela .= "    <tbody>";
        
        foreach ($campos as $nome => $array) {
            if (empty($_GET['id']) && !empty($array['nao_cadastra'])) {
                continue;
            }
            if (!empty($_GET['id']) && !empty($array['nao_edita'])) {
                continue;
            }
            if (empty($row[$nome]) && !empty($_GET[$nome])) {
                $row[$nome] = $_GET[$nome];
            }
            else if (empty($_GET['id']) && !empty($array['default'])) {
                $row[$nome] = $array['default'];
            }
            else if (empty($row[$nome])) {
                $row[$nome] = '';
            }
            
                
            if (empty($array['label'])) {
                $label = str_replace("_", " ", $nome);
            }
            else {
                $label = $array['label'];
            }
            $label = funcoes::capitalizar($label);
            $tabela .= "<tr>";
            $tabela .= "    <td>";
            $tabela .= "        {$label}<br/>";
            
            if (!empty($array['select'])) {
                $arraySelect = $array['select'];
                $campos_extras = '';
                if (!empty($array['select_campos'])) {
                    $tabelaSelect = $array['select_campos']['tabelaOrigem'];
                    unset($array['select_campos']['tabelaOrigem']);
                    foreach ($arraySelect as $row_select_tmp) {
                        foreach ($array['select_campos'] as $campo => $funcao) {
                            if (empty($campos_extras[$campo])) {
                                $campos_extras[$campo] = array();
                            }
                            $campos_extras[$campo][] = $tabelaSelect->$funcao($row_select_tmp['id']);
                        }
                    }       
                }
                $tabela .= "<select name='{$nome}'>";
                $tabela .= "    <option value=''>Selecione um {$label}</option>";
                if (empty($array['select_label'])) {
                    $campos_label = "descricao";
                }
                else {
                    $campos_label = $array['select_label'];
                }
                $tabela .= funcoes::montaSelect($arraySelect, "id", $campos_label, $row[$nome], false, $campos_extras);
                $tabela .= "</select>";
            }
            else {
                $max = '';
                if (!empty($array['max'])) {
                    $max = $array['max'];
                }
                $tabela .= funcoes::montaInput($nome, $array['tipo'], $row[$nome], $max);
            }
            if (!empty($array['obs'])) {
                $tabela .= "<br/><span style='font-size: 9px;'>{$array['obs']}</span>";
            }
            $tabela .= "    </td>";
            $tabela .= "</tr>";
            if ($array['tipo'] == 'pass') {
                $tabela .= "<tr><td>Confirmar {$label}<br/>".funcoes::montaInput($nome, "confirm_pass", '')."</td></tr>";
            }
        }
        $tabela .= "    </tbody>";
        $tabela .= "    <tfoot>";
        $tabela .= "        <tr>";
        $tabela .= "            <td>";
        $tabela .= "                <button class='btn btn-large btn-block btn-success' type='submit'>Cadastrar</button>";
        $tabela .= "                <button class='btn btn-large btn-block btn-info' type='button' onclick=\"history.go(-1)\">Voltar</button>";
        $tabela .= "            </td>";
        $tabela .= "        </tr>";
        $tabela .= "    </tfoot>";
        $tabela .= "</table>";
        $tabela .= "</div>";
        return $tabela;
    }
    
    public function montarList() {
        return $this->montarListBootstrap();
    }
    
    public function __call($name, $arguments) {
        if (substr($name, 0, 3) == "get") {
            $id = $arguments[0];
            $campo = substr($name, 3);
            $tmp = str_split($campo);
            $campo = "";
            $tmp[0] = strtolower($tmp[0]);
            foreach ($tmp as $letra) {
                if (ord($letra) >= 97 && ord($letra) <= 122) {
                    $campo .= $letra;
                }
                else {
                    $campo .= "_".strtolower($letra);
                }
            }
            $rs = $this->getCamposByCondicao("{$campo}", "WHERE id = {$id}");
            if (count($rs) > 0) {
                return $rs[0][$campo];
            }
            else {
                return FALSE;
            }
        }
    }
    
    public function motarListMobile() {
        $nome_classe = get_class($this);
        $campos = $this->campos;
        $rs = $this->obterTodos();
        $tabela = "";
        $tabela .= "<table class='list_mobile'>";
        $tabela .= "    <caption>";
        $tabela .= "        {$this->label}";
        $tabela .= "    </caption>";
        $tabela .= "    <thead>";
        $i = 0;
        $campos_selecionados = array();
        $tabela .= "        <tr>";
        foreach ($campos as $nome => $array) {
            if ($i > 2) {
                break;
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
            $tabela .= "        <img src='../auxiliares/ico/caneta.png' title='Editar' onclick=\"abrirPagina('{$nome_classe}Form.php?id={$row['id']}')\"/>";
            $tabela .= "        <img src='../auxiliares/ico/cancelar.png' title='Remover' onclick=\"confirma('Tem certeza que deseja remover o {$this->label} selecionado?', function(){ abrirPagina('{$nome_classe}Crud.php?acao=deletar&id={$row['id']}') });\"/>";
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
        $tabela .= "<div class='botoes'>";
        $tabela .= "    <span class='botao' onclick=\"abrirPagina('{$nome_classe}Form.php')\">";
        $tabela .= "        {$this->label}<br/>";
        $tabela .= "        <img src='../auxiliares/ico/mais.png'/>";
        $tabela .= "    </span>";
        $tabela .= "</div>";
        return $tabela;
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