<?php
class vendaDao extends genericoDao {
    public function __construct() {
        $this->campos['data']['tipo'] = "str"; 
        $this->campos['fechada']['tipo'] = "str";
        $this->campos['cliente']['tipo'] = "int";
        $this->campos['entrega']['tipo'] = "data";
        $this->campos['devolucao']['tipo'] = "data";
        $this->campos['forma_pagamento']['tipo'] = "int";
        $this->campos['valor_pago']['tipo'] = "int";
        $this->campos['funcionario']['tipo'] = "str"; 
        $this->campos['noiva']['tipo'] = "int";
        $this->campos['casamento']['tipo'] = "data";
        
        parent::__construct("vendas");
    }
}
?>