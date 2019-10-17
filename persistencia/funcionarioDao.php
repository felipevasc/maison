<?php
class funcionarioDao extends genericoDao {
    public function __construct() {
        $this->campos['nome']['tipo'] = "str"; 
        $this->campos['login']['tipo'] = "str"; 
        $this->campos['senha']['tipo'] = "pass"; 
        parent::__construct("funcionarios");
    }
}
?>