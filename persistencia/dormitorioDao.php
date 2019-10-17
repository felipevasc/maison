<?php
class dormitorioDao extends genericoDao {
    public function __construct() {
        $this->campos['tipo_dormitorio'] = array(
            "tipo" => "int",
            "list" => (new tipoDormitorio()),
            "list_campo" => "descricao",
            "select" => (new tipoDormitorio())->obterTodos(),
            "select_label" => "descricao"
        );
        $this->campos['descricao']['tipo'] = "str";
        parent::__construct("dormitorios");
    }
}
?>