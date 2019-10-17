<?php
class restauranteDao extends genericoDao {
    public function __construct() {
        $this->campos['nome']['tipo'] = "str"; 
        parent::__construct("restaurantes");
    }
}
?>