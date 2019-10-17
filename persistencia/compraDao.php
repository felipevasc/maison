<?php
class compraDao extends genericoDao {
    public function __construct() {
        $this->campos['data']['tipo'] = "data"; 
        $this->campos['produto']['tipo'] = "int"; 
        $this->campos['produto']['list'] = (new produto());
        $this->campos['produto']['list_campo'] = "nome";
        $this->campos['produto']['select'] = (new produto())->obterTodos();
        $this->campos['produto']['select_label'] = "nome";
        $this->campos['quantidade']['tipo'] = "int"; 
        $this->campos['setor']['tipo'] = "int"; 
        $this->campos['setor']['select'] = (new setor())->obterTodos(); 
        $this->campos['setor']['select_label'] = "descricao"; 
        $this->campos['setor'] = array(
            "tipo" => "int",
            "select" => (new setor())->obterTodos(),
            "select_label" => "descricao",
            "list" => (new setor()),
            "list_campo" => "descricao"
        );

        parent::__construct("compras");
    }
}
?>