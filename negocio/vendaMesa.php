<?php
class vendaMesa extends generico {
    public function getMesasVenda($id_venda) {
        return $this->getByCondicao("WHERE venda = {$id_venda} ORDER BY id");
    }
}
?>