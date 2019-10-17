<?php
class produtoItemDao extends genericoDao {
    public function __construct() {
        $this->campos['produto'] = array(
            "tipo"=>"int",
            "list"=>(new produto()),
            "list_campo"=>"nome",
            "select"=>(new produto())->obterTodos(),
            "select_label"=>"nome"
        );
        $this->campos['item'] = array(
            "tipo"=>"int",
            "list"=>(new item()),
            "list_campo"=>"nome",
            "select"=>(new item())->obterTodos(),
            "select_label"=>"nome"
        );
        $this->campos['quantidade'] = array(
            "tipo"=>"float"
        );
        $this->campos['detalhes'] = array(
            "tipo"=>"text"
        );
        parent::__construct("produtos_itens");
    }
}
?>