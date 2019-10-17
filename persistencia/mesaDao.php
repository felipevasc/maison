<?php
class mesaDao extends genericoDao {
    public function __construct() {
        $this->campos['restaurante'] = array(
            "tipo" => "int",
            "list" => (new restaurante()),
            "list_campo" => "nome",
            "select" => (new restaurante())->obterTodos(),
            "select_label" => "nome"
        );
        $this->campos['nome']['tipo'] = "str";
        $this->campos['ativa']['tipo'] = "bool";
        parent::__construct("mesas");
    }
}
?>