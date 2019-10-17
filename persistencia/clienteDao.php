<?php
class clienteDao extends genericoDao {
    public function __construct() {
        $this->campos['nome']['tipo'] = "strUpper";
        $this->campos['nome']['label'] = "Nome do Cliente";
        $this->campos['data']['tipo'] = "data"; 
        $this->campos['data']['label'] = "Data de Nascimento"; 
        $this->campos['cpf']['tipo'] = "cpf";
        $this->campos['rg']['tipo'] = "str";
        $this->campos['numero']['tipo'] = "int";
        $this->campos['numero']['label'] = "Telefone P/ Contato"; 
        $this->campos['endereco']['tipo'] = "str";
        $this->campos['cidade']['tipo'] = "str";
        $this->campos['estado']['tipo'] = "str";
        $this->campos['complemento']['tipo'] = "str";
        parent::__construct("clientes");
    }
}
?>