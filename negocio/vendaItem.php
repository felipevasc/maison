<?php
class vendaItem extends generico {
    public function set($dados_formulario) {
        $item = new item();
        $adicionalVendaItem = new adicionalVendaItem();
        
        $itemLinha = $item->get($dados_formulario['item']);
        
        $dados_formulario['hora_pedido'] = date("Y-m-d H:i:s");
        $ok = parent::set($dados_formulario);
        if ($ok) {
            if (!empty($dados_formulario['adicional'])) {
                $tmpTabela = $this->getByCondicao("WHERE item = {$dados_formulario['item']} and venda = {$dados_formulario['venda']} and hora_pedido = '{$dados_formulario['hora_pedido']}' ORDER BY id DESC");
                $tmpLinha = $tmpTabela[0];
                foreach ($dados_formulario['adicional'] as $id_adicional) {
                    $tmp = array();
                    $tmp['venda_item'] = $tmpLinha['id'];
                    $tmp['adicional'] = $id_adicional;
                    $adicionalVendaItem->set($tmp);
                }
            }
            return $ok;
        }
        else {
            return $ok;
        }
    }
    public function getByVenda($id_venda) {
        return $this->getByCondicao("WHERE venda = {$id_venda} ORDER BY hora_pedido");
    }
    public function getPendentes($id = 0) {
        return $this->getCamposByCondicao("i.nome as item, vi.quantidade, vi.obs, vi.hora_pedido, vi.id, v.cliente, v.id as venda, vi.pronto, vi.quantidade",
                    "vi JOIN vendas v ON (vi.venda = v.id) "
                    . "JOIN itens i ON (vi.item = i.id) "
                . "WHERE v.fechada is null "
                    . "and vi.pronto is not true "
                    . "and vi.id > {$id} "
                . "ORDER BY vi.id");
    }
    public function getProntos($id = 0) {
        return $this->getCamposByCondicao("i.nome as item, vi.quantidade, vi.obs, vi.hora_pedido, vi.id, v.cliente, v.id as venda, vi.pronto, vi.quantidade",
                    "vi JOIN vendas v ON (vi.venda = v.id) "
                    . "JOIN itens i ON (vi.item = i.id) "
                . "WHERE v.fechada is null "
                    . "and vi.pronto is true "
                    . "and vi.entregue is not true "
                    . "and vi.id > {$id} "
                . "ORDER BY vi.id");
    }
    public function getQtdPendentesByVenda($id_venda) {
        $rs = $this->getCamposByCondicao("vi.id",
                    "vi JOIN vendas v ON (vi.venda = v.id) "
                    . "JOIN itens i ON (vi.item = i.id) "
                . "WHERE v.fechada is null "
                    . "and vi.pronto is not true "
                    . "and v.id = {$id_venda} ");
        return count($rs);
    }
    public function getQtdProntosByVenda($id_venda) {
        $rs = $this->getCamposByCondicao("vi.id",
                    "vi JOIN vendas v ON (vi.venda = v.id) "
                    . "JOIN itens i ON (vi.item = i.id) "
                . "WHERE v.fechada is null "
                    . "and vi.pronto is true "
                    . "and vi.entregue is not true "
                    . "and v.id = {$id_venda} ");
        return count($rs);
    }
    
    public function getMaisVendidos($data_inicio, $data_fim) {
        $data_fim = funcoes::desformatarData($data_fim);
        $data_inicio = funcoes::desformatarData($data_inicio);
        $rs = $this->getCamposByCondicao("item, SUM(quantidade) as qtd", 
                "WHERE hora_pedido BETWEEN '$data_inicio' and '$data_fim'
                GROUP BY item
                ORDER BY SUM(quantidade) DESC");
        return $rs;
    }
    
    public function getValoresFuncionarios($data_inicio, $data_fim) {
        $data_fim = funcoes::desformatarData($data_fim);
        $data_inicio = funcoes::desformatarData($data_inicio);
        $rs = $this->getCamposByCondicao("sum(i.valor), v.funcionario",
                    "vi 
                    JOIN vendas v ON (v.id = vi.item)
                    JOIN itens i ON (vi.item = i.id)
                WHERE data BETWEEN '2016-10-01' and '2016-11-01'
                GROUP BY v.funcionario
                ORDER BY sum(i.valor)");
        return $rs;
    }
    
    public function getByData($data_inicio, $data_fim) {
        $data_fim = funcoes::desformatarData($data_fim);
        $data_inicio = funcoes::desformatarData($data_inicio);
        $rs = $this->getCamposByCondicao("v.entrega, c.nome as cliente, c.numero, i.nome as item, vi.pronto, vi.entregue, v.devolucao, v.id as venda",
                "vi JOIN itens i ON (vi.item = i.id) "
                . "JOIN vendas v ON (vi.venda = v.id) "
                . "JOIN clientes c ON (v.cliente = c.id) "
            . "WHERE v.entrega >= '{$data_inicio}' "
                . "and v.entrega <= '{$data_fim}' "
                . "and v.fechada < '1990-01-01' "
            . "ORDER BY c.nome, v.entrega, v.id, i.nome");
        return $rs;
    }
}
?>