<?php
class setorDao extends genericoDao {
    public function __construct() {
        $this->campos['descricao']['tipo'] = "str"; 
        parent::__construct("setores");
    }
}
?>