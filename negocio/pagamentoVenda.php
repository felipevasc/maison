<?php
class pagamentoVenda extends generico {
    public function getByVenda($id_venda) {
        return $this->getByCondicao("WHERE venda = {$id_venda}");
    }
    
    public function delete($id) {
        $row = $this->get($id);
        $venda = new venda();
        $vendaLinha = $venda->get($row['venda']);
        $post['id'] = $vendaLinha['id'];
        $post['valor_pago'] = $vendaLinha['valor_pago'] - $row['valor'];
        $venda->update($post);
        return parent::delete($id);
    }
    
    public function getPagamentos($dt_inicio, $dt_fim, $forma_pagamento) {
        $sql = "WHERE true";
        if (!empty($dt_inicio)) {
            $inicio = funcoes::desformatarData($dt_inicio);
            $sql .= " and p.data > '{$inicio} 00:00:00' ";
        }
        if (!empty($dt_fim)) {
            $fim = funcoes::desformatarData($dt_fim);
            $sql .= " and p.data < '{$fim} 23:59:59' ";
        }
        if (!empty($forma_pagamento)) {
            $sql .= " and p.forma_pagamento = {$forma_pagamento} ";
        }
        $rs = $this->getCamposByCondicao("c.nome as cliente, p.data, p.valor, v.id as id_venda, f.login as funcionario, v.id as id_venda, p.forma_pagamento ", 
                    "p JOIN vendas v ON (p.venda = v.id) "
                . "LEFT JOIN funcionarios f ON (p.funcionario = f.id) "
                . "JOIN clientes c ON (v.cliente = c.id) "
                . "{$sql} "
                . "ORDER BY p.data DESC ");
        return $rs;
    }
}
?>