<?php
class clienteDao extends genericoDao {
    public function __construct() {
        $this->campos['nome']['tipo'] = "str"; 
        parent::__construct("clientes");
    }
}
?>