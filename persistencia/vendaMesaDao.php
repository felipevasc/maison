<?php
class vendaMesaDao extends genericoDao {
    public function __construct() {
        $this->campos['venda']['tipo'] = "int"; 
        $this->campos['mesa']['tipo'] = "int";
        parent::__construct("vendas_mesas");
    }
}
?>