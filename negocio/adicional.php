<?php
class adicional extends generico {
    public function getByCategoria($id_categoria) {
        return $this->getByCondicao("WHERE categoria = {$id_categoria} ORDER BY nome");
    }
}
?>