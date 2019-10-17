<?php
class tipoDormitorioDao extends genericoDao {
    public function __construct() {
        $this->campos['descricao']['tipo'] = "str"; 
        $this->campos['valor']['tipo'] = "monetario";
        parent::__construct("tipos_dormitorios");
    }
}
?>