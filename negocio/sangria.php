<?php
class sangria extends generico {
    public function obterTodos($data_inicio = '', $data_fim = '') {
        $sql_data = '';
        if (!empty($data_inicio)) {
            $data_inicio = funcoes::desformatarData($data_inicio);
            $sql_data .= " and data >= '{$data_inicio}' ";
        }
        if (!empty($data_fim)) {
            $data_fim = funcoes::desformatarData($data_fim);
            $sql_data .= " and data <= '{$data_fim}' ";
        }
        //var_dump($sql_data);
        //die();
        return $this->getByCondicao("WHERE true {$sql_data} ORDER BY data DESC");
    }

    public function getNaoPagos() {
        return $this->getByCondicao("WHERE pago is not true ORDER BY data_pagar");
    }
    public function getPagos($data_inicio = '', $data_fim = '') {
        $sql_dt_inicio = '';
        $sql_dt_fim = '';
        if (!empty($data_inicio)) {
            $data_inicio = funcoes::ajustarData($data_inicio);
            $sql_dt_inicio = " and data_pagamento >= '{$data_inicio}' ";
        }
        if (!empty($data_fim)) {
            $data_fim = funcoes::ajustarData($data_fim);
            $sql_dt_fim = " and data_pagamento <= '{$data_fim}' ";
        }
        return $this->getByCondicao("WHERE pago is true {$sql_dt_inicio} {$sql_dt_fim} ORDER BY data_pagar");
    }
    
    public function getValorByContaPago($idConta, $data_inicio = '', $data_fim = ''){
        $sql_dt_inicio = '';
        $sql_dt_fim = '';
        if (!empty($data_inicio)) {
            $data_inicio = funcoes::ajustarData($data_inicio);
            $sql_dt_inicio = " and data >= '{$data_inicio}' ";
        }
        if (!empty($data_fim)) {
            $data_fim = funcoes::ajustarData($data_fim);
            $sql_dt_fim = " and data <= '{$data_fim}' ";
        }
        $tabela = $this->getCamposByCondicao("WHERE pago is not true sum(valor) as qtd", "WHERE conta = $idConta {$sql_dt_inicio} {$sql_dt_fim}");
        if (count($tabela) == 0) {
            return 0;
        }
        else {
            return $tabela[0]['qtd'];
        }
    }
    public function getValorByContaNaoPago($idConta, $data_inicio = '', $data_fim = ''){
        $sql_dt_inicio = '';
        $sql_dt_fim = '';
        if (!empty($data_inicio)) {
            $data_inicio = funcoes::ajustarData($data_inicio);
            $sql_dt_inicio = " and data >= '{$data_inicio}' ";
        }
        if (!empty($data_fim)) {
            $data_fim = funcoes::ajustarData($data_fim);
            $sql_dt_fim = " and data <= '{$data_fim}' ";
        }
        $tabela = $this->getCamposByCondicao("WHERE pago is not true sum(valor) as qtd", "WHERE conta = $idConta {$sql_dt_inicio} {$sql_dt_fim}");
        if (count($tabela) == 0) {
            return 0;
        }
        else {
            return $tabela[0]['qtd'];
        }
    }
    public function getValorByContaTotal($data_inicio = '', $data_fim = ''){
        $sql_dt_inicio = '';
        $sql_dt_fim = '';
        if (!empty($data_inicio)) {
            $data_inicio = funcoes::ajustarData($data_inicio);
            $sql_dt_inicio = " and data >= '{$data_inicio}' ";
        }
        if (!empty($data_fim)) {
            $data_fim = funcoes::ajustarData($data_fim);
            $sql_dt_fim = " and data <= '{$data_fim}' ";
        }
        return "WHERE {$sql_dt_inicio} {$sql_dt_fim}";
        $tabela = $this->getCamposByCondicao("sum(valor) as qtd", "WHERE {$sql_dt_inicio} {$sql_dt_fim}");
        if (count($tabela) == 0) {
            return "0";
        }
        else {
            return $tabela[0]['qtd'];
        }
    }
    
}
