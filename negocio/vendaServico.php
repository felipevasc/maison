<?php
class vendaServico extends generico {
    public function set($dados_formulario) {
        if ($dados_formulario['tipo'] == "desconto") {
            $dados_formulario['valor'] = $dados_formulario['valor'] * -1;
        }
        return parent::set($dados_formulario);
    }
    public function getByVenda($id_venda) {
        return $this->getByCondicao("WHERE venda = {$id_venda} ORDER BY id");
    }
    public function getServicosByVenda($id_venda) {
        return $this->getByCondicao("WHERE valor > 0 and venda = {$id_venda} ORDER BY id");
    }
    public function getDescontosByVenda($id_venda) {
        return $this->getByCondicao("WHERE valor < 0 and venda = {$id_venda} ORDER BY id");
    }
}
?>