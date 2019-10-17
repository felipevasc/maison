<?php
class contaDao extends genericoDao {
    public function __construct() {
        $this->campos['descricao']['tipo'] = "str";
        $this->campos['valor']['tipo'] = "monetario";
        $this->campos['data_criacao']['tipo'] = "data";
        $this->campos['data_pagar']['tipo'] = "data";
        $this->campos['pago']['tipo'] = "bool";
        $this->campos['data_pagamento']['tipo'] = "data";
        parent::__construct("contas");
    }
}