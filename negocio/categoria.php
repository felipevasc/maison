<?php
class categoria extends generico {
    public function getByPai($id_pai = '') {
        if (empty($id_pai)) {
            return $this->getByCondicao("WHERE categoria_pai is null");
        }
        else {
            return $this->getByCondicao("WHERE categoria_pai = {$id_pai} LIMIT 100");
        }
    }
}
?>