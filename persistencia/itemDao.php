<?php
class itemDao extends genericoDao {
    public function __construct() {
        $this->campos['nome']['tipo'] = "str"; 
        $this->campos['valor']['tipo'] = "monetario";
        $this->campos['detalhes']['tipo'] = "text";
        $this->campos['imagem']['tipo'] = "file";
        $this->campos['categoria'] = array(
            "tipo" => "int",
            "select" => (new categoria())->obterTodos(),
            "select_label" => "nome",
            "list" => (new categoria),
            "list_campo" => "nome"
        );
        parent::__construct("itens");
    }
}
?>