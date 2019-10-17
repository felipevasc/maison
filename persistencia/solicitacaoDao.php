<?php
class solicitacaoDao extends genericoDao {
    public function __construct() {
        $this->campos['produto'] = array(
            "tipo" => "int",
            "list" => (new produto()),
            "list_campo" => "nome",
            "select" => (new produto())->obterTodos(),
            "select_label" => "nome",
            "select_campos" => array(
                "tabelaOrigem" => (new produto()),
                "estoque" => "obterEstoque"
            )
        );
        $this->campos['restaurante'] = array(
            "tipo" => "int",
            "list" => (new restaurante()),
            "list_campo" => "nome",
            "select" => (new restaurante())->obterTodos(),
            "select_label" => "nome"
        );
        $this->campos['quantidade'] = array(
            "tipo" => "int",
            "max" => "$(getOptionSelected(getByName('produto'))).attr('estoque')"
        );
        $this->campos['data']['tipo'] = "data";
        parent::__construct("solicitacoes");
    }
}
?>