<?php
class mesa extends generico {
    public function getByRestaurante($id_restaurante) {
        return $this->getCamposByCondicao(" "
                . "(SELECT MIN(entrega) as entrega "
                . "FROM vendas v "
                . " JOIN vendas_mesas vm ON (v.id = vm.venda) "
                . "WHERE vm.mesa = m.id and v.fechada < '1900-01-01') as data, "
                . "m.*", 
                "m "
                . "WHERE restaurante = {$id_restaurante} "
                . "ORDER BY 1");
    }
    
    public function checkVendaAberta($id_mesa) {
        $id_venda = $this->getVendaAberta($id_mesa);
        if (empty($id_venda)) {
            return FALSE;
        }
        else {
            return TRUE;
        }
    }
    
    public function getVendaAberta($id_mesa) {
        $rs = $this->getCamposByCondicao("v.id",
                    "m JOIN vendas_mesas vm ON (m.id = vm.mesa) "
                    . "JOIN vendas v ON (vm.venda = v.id) "
                . "WHERE m.id = {$id_mesa} "
                    . "and v.fechada < '1900-01-01'");
        if (count($rs) > 0) {
            return $rs[0]['id'];
        }
        else {
            return FALSE;
        }
    }
    
    public function getMesaVazia($iterador = 0){
        $rs = $this->obterTodos();
        foreach ($rs as $row) {
            if (!$this->checkVendaAberta($row['id'])) {
                return $row['id'];
            }
        }
        $tmp['restaurante'] = 1;
        $tmp['nome'] = 'Nova';
        $tmp['ativa'] = true;
        $this->set($tmp);
        if ($iterador < 100) {
            return $this->getMesaVazia($iterador + 1);
        }
        else {
            return FALSE;
        }
    }
}
?>