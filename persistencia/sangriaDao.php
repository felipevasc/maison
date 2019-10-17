<?php
class sangriaDao extends genericoDao {
    public function __construct() {
        $this->campos['funcionario']['tipo'] = "str";
        $this->campos['valor']['tipo'] = "int";
        $this->campos['data']['tipo'] = "data";
        parent::__construct("sangrias");
    }
}