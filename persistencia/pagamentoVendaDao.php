<?php
class pagamentoVendaDao extends genericoDao {
    public function __construct() {
        $this->campos['venda']['tipo'] = "int"; 
        $this->campos['data']['tipo'] = "date";
        $this->campos['valor']['tipo'] = "float";        
        $this->campos['funcionario']['tipo'] = "int";        
        $this->campos['forma_pagamento']['tipo'] = "int";        
        parent::__construct("pagamentos_vendas");
    }
}
?>