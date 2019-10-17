<?php
class subcategoria {
    public function obterTodos() {
        $conexao = new conexao();
        $rs = $conexao->get("SELECT * FROM categorias");
        if (count($rs) == 0) {
            return array(null);
        }
        return $rs;
    }
    public function getNome($id) {
        $conexao = new conexao();
        $rs = $conexao->get("SELECT * FROM categorias WHERE id = {$id}");
        if (count($rs) == 0) {
            return false;
        }
        return $rs[0]['nome'];
    }
}
?>