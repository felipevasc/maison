<?php
class produto extends generico {
    public function obterEstoque($id) {
        $rs = $this->getCamposByCondicao("SUM(c.quantidade) - CASE WHEN SUM(s.quantidade) IS NULL THEN 0 ELSE SUM(s.quantidade) END as estoque",
                    "p JOIN compras c ON (p.id = c.produto) "
                    . " LEFT JOIN solicitacoes s ON (p.id = s.produto) "
                . "WHERE p.id = {$id} ");
        if (count($rs) == 0 || empty($rs[0]['estoque'])) {
            return "0";
        }
        else {
            return $rs[0]['estoque'];
        }
    }
}
?>