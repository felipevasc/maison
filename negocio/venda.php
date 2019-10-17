<?php
class venda extends generico {
    public function obterTodosByParametros($cliente, $inicio, $fim) {
        $inicio = funcoes::desformatarData($inicio);
        $fim = funcoes::desformatarData($fim);
        $sql_cliente = '';
        $sql_inicio = '';
        $sql_fim = '';
        if (!empty($cliente)) {
            $sql_cliente = " and cliente = {$cliente} ";
        }
        if (!empty($inicio)) {
            $sql_inicio = " and entrega >= '{$inicio} 00:00:00' ";
        }
        if (!empty($fim)) {
            $sql_fim = " and entrega <= '{$fim} 23:59:59' ";
        }
        return $this->getByCondicao("WHERE true {$sql_cliente} {$sql_inicio} {$sql_fim}");
    }
    public function obterTodosByParametros1($cliente, $inicio, $fim) {
        $inicio = funcoes::desformatarData($inicio);
        $fim = funcoes::desformatarData($fim);
        $sql_cliente = '';
        $sql_inicio = '';
        $sql_fim = '';
        if (!empty($cliente)) {
            $sql_cliente = " and cliente = {$cliente} ";
        }
        if (!empty($inicio)) {
            $sql_inicio = " and data >= '{$inicio} 00:00:00' ";
        }
        if (!empty($fim)) {
            $sql_fim = " and data <= '{$fim} 23:59:59' ";
        }
        return $this->getByCondicao("WHERE true {$sql_cliente} {$sql_inicio} {$sql_fim} ORDER BY data");
    }
    public function obterTodosByFuncionario($funcionario, $inicio, $fim) {
        $inicio = funcoes::desformatarData($inicio);
        $fim = funcoes::desformatarData($fim);
        $sql_cliente = '';
        $sql_inicio = '';
        $sql_fim = '';
        if (!empty($funcionario)) {
            $sql_cliente = " and funcionario = {$funcionario} ";
        }
        if (!empty($inicio)) {
            $sql_inicio = " and data >= '{$inicio} 00:00:00' ";
        }
        if (!empty($fim)) {
            $sql_fim = " and data <= '{$fim} 23:59:59' ";
        }
        return $this->getByCondicao("WHERE true {$sql_cliente} {$sql_inicio} {$sql_fim} ORDER BY data");
    }
    public function transferir($dados_formulario) {
        $vendaMesa = new vendaMesa();
        if (empty($dados_formulario['mesa'])) {
            return FALSE;
        }
        $vendaMesaTabela = $vendaMesa->getMesasVenda($dados_formulario['venda']);
        foreach ($vendaMesaTabela as $vendaMesaLinha) {
            $vendaMesa->delete($vendaMesaLinha['id']);
        }
        $tmp['venda'] = $dados_formulario['venda'];
        $tmp['mesa'] = $dados_formulario['mesa'];
        $vendaMesa->set($tmp);
        return TRUE;
    }
    
    public function fechar($dados_formulario) {
        $dados_formulario['fechada'] = date("Y-m-d H:i:s");
        return $this->update($dados_formulario);
    }
    public function reabrir($dados_formulario) {
        $dados_formulario['fechada'] = "0000-00-00 00:00:00";
        return $this->update($dados_formulario);
    }
    
    public function set($dados_formulario) {
        $mesa = new mesa();
        $vendaMesa = new vendaMesa();
        
        if ($mesa->checkVendaAberta($dados_formulario['mesa'])) {
            return false;
        }
        $dados_formulario['data'] = date("Y-m-d H:i:s");
        parent::set($dados_formulario);
        if (!empty($dados_formulario['cliente'])) {
            $sql_cliente = " cliente = {$dados_formulario['cliente']} ";
        }
        else {
            $sql_cliente = " cliente IS NULL ";
        }
        $rs = $this->getByCondicao("WHERE {$sql_cliente} and data = '{$dados_formulario['data']}' and fechada < '1900-01-01' ORDER BY id DESC LIMIT 1");
        if (count($rs) == 0) {
            return false;
        }
        else {
            $row = $rs[0];
            $tmp['venda'] = $row['id'];
            $tmp['mesa'] = $dados_formulario['mesa'];
            $vendaMesa->set($tmp);
            if ($mesa->checkVendaAberta($dados_formulario['mesa'])) {
                return true;
            }
            else {
                return false;
            }
        }
    }
    
    public function getSituacaoVenda($id_venda) {
        $vendaItem = new vendaItem();
        $vendaItemTabela = $vendaItem->getByVenda($id_venda);
        $situacao = 2;//atendido
        foreach ($vendaItemTabela as $vendaItemLinha) {
            if (empty($vendaItemLinha['entregue']) && !empty($vendaItemLinha['pronto'])) {
                $situacao = 1;//Pronto
                //break;
            }
            else if (empty($vendaItemLinha['pronto'])) {
                $situacao = 0;//Preparando
                break;
            }
        }
        return $situacao;
    }
    
    public function getTotal($id_venda) {
        $vendaItem = new vendaItem();
        $vendaServico = new vendaServico();
        $item = new item();
        
        $vendaItemTabela = $vendaItem->getByVenda($id_venda);
        $total = 0;
        foreach ($vendaItemTabela as $vendaItemLinha) {
            $itemLinha = $item->get($vendaItemLinha['item']);
            $total += ($vendaItemLinha['valor'] * $vendaItemLinha['quantidade']);
        }
        
        $vendaServicoTabela = $vendaServico->getByVenda($id_venda);
        foreach ($vendaServicoTabela as $vendaServicoLinha) {
            $total += ($vendaServicoLinha['valor'] * $vendaServicoLinha['quantidade']);
        }
        
        return $total;
    }
    
    public function getVendasClientes() {
        return $this->getByCondicao("WHERE fechada < '2000-01-01' and cliente is not null");
    }
    
    public function update($dados_formulario) {
        if (!empty($dados_formulario['valor_atual'])) {
            $pagamentoVenda = new pagamentoVenda();
            $post_tmp['venda'] = $dados_formulario['id'];
            $post_tmp['data'] = date("Y-m-d H:i:s");
            $post_tmp['valor'] = $dados_formulario['valor_atual'];
            $post_tmp['funcionario'] = $dados_formulario['funcionario_pagamento'];
            $post_tmp['forma_pagamento'] = $dados_formulario['forma_pagamento'];
            
            $pagamentoVenda->set($post_tmp);
        }
        return parent::update($dados_formulario);
    }
    public function update1($dados_formulario) {
        if (!empty($dados_formulario['valor_atual'])) {
            $pagamentoVenda = new pagamentoVenda();
            $post_tmp['venda'] = $dados_formulario['id'];
            $post_tmp['data'] = date("Y-m-d H:i:s");
            $post_tmp['valor'] = $dados_formulario['valor_atual'];
            $post_tmp['funcionario'] = $dados_formulario['funcionario'];
            $post_tmp['forma_pagamento'] = $dados_formulario['forma_pagamento'];
            
            $pagamentoVenda->set($post_tmp);
        }
        return parent::update($dados_formulario);
    }
    
    public function getNoivas() {
        $hoje = date("m-d");
        return $this->getCamposByCondicao("c.*, v.casamento", "v JOIN clientes c ON (v.cliente = c.id) WHERE v.casamento like '%{$hoje}' and v.noiva = 1");
    }
}
?>