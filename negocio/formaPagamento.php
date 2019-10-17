<?php
class formaPagamento extends generico {
public function obterTodos() {
        $rs = $this->dao->get("*", "ORDER BY 1");
        return $rs;
    }
}
?>