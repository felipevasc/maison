<?php
class adicionalVendaItem extends generico {
    public function getByVendaItem($id_venda_item) {
        return $this->getByCondicao("WHERE venda_item = {$id_venda_item} ORDER BY id");
    }
}
?>