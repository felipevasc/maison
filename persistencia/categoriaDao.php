<?php
class categoriaDao extends genericoDao {
    public function __construct() {
        $this->campos['nome'] = array(
            "tipo" => "str"
        );
        $this->campos['categoria_pai'] = array(
            "tipo" => "int",
            "list" => (new subcategoria()),
            "list_campo" => "nome",
            "select" => (new subcategoria())->obterTodos(),
            "select_label" => "nome"
        );
        parent::__construct("categorias");
    }
}
?>