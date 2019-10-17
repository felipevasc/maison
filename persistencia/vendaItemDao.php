<?php
class vendaItemDao extends genericoDao {
    public function __construct() {
        $this->campos['venda']['tipo'] = "int"; 
        $this->campos['item']['tipo'] = "int";
        $this->campos['quantidade']['tipo'] = "float";
        $this->campos['obs']['tipo'] = "str";
        $this->campos['pronto']['tipo'] = "bool";
        $this->campos['entregue']['tipo'] = "bool";
        $this->campos['hora_pedido']['tipo'] = "time";
        $this->campos['valor']['tipo'] = "float";
        parent::__construct("vendas_itens");
    }
}
?>