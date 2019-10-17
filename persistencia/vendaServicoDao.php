<?php
class vendaServicoDao extends genericoDao {
    public function __construct() {
        $this->campos['venda']['tipo'] = "int"; 
        $this->campos['descricao']['tipo'] = "str";
        $this->campos['quantidade']['tipo'] = "float";
        $this->campos['valor']['tipo'] = "float";
        parent::__construct("vendas_servicos");
    }
}
?>