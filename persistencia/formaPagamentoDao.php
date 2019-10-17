<?php
class formaPagamentoDao extends genericoDao {
    public function __construct() {
        $this->campos['descricao'] = array(
            "tipo" => "str"
        );
        parent::__construct("formas_pagamentos");
    }
}
?>