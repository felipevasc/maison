<?php
class adicionalVendaItemDao extends genericoDao {
    public function __construct() {
        $this->campos['adicional'] = array(
            "tipo" => "int",
            "select" => (new adicional())->obterTodos(),
            "select_label" => "nome",
            "list" => (new adicional),
            "list_campo" => "nome"
        );
        $this->campos['venda_item'] = array(
            "tipo" => "int",
            "select" => (new vendaItem())->obterTodos(),
            "select_label" => "nome",
            "list" => (new vendaItem),
            "list_campo" => "nome"
        );
        parent::__construct("adicionais_vendas_itens");
    }
}
?>