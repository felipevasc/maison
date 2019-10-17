<?php
abstract class genericoDao {
    protected $conexao;
    protected $tabela;
    public $campos;
    public $label;
    public $referencia_get;
    
    public function __construct($tabela) {
        $this->conexao = new conexao();
        $this->tabela = $tabela;
    }
    
    function get($campos,$parametro){
        if(strlen($campos)<=0){
            $campos = '*';
        }
        $select = 'select '.$campos.' from '.$this->tabela. ' ' . $parametro;
        
        return $this->conexao->get($select);
    }
    
    function getTotal($sql){
        $select = 'select count(*) as total from '.$this->tabela. ' ' . $sql;
        return $this->conexao->get($select);
    }
    
    function setCampo($campo, $valor, $id){
        return $this->conexao->update("{$campo} = '{$valor}'",$this->tabela,$id);
    }
    
    function update($dados,$id){
        $campos = $this->campos;
        $parametros = "";
        foreach ($dados as $nome => $valor) {
            if (!empty($campos[$nome]['tipo'])) {
                $valor = funcoes::ajustarTipoCampo($campos[$nome]['tipo'], $valor);
                if (!empty($parametros)) {
                    $parametros .= ", ";
                }
                if ($campos[$nome]['tipo'] == 'file') {
                    var_dump($_FILES);
                    if (!empty($_FILES[$nome.'_file']['tmp_name'])) {
                        $nome_arquivo = md5(time()).".jpg";
                        $caminho = "../auxiliares/fotos/$nome_arquivo";
                        move_uploaded_file($_FILES[$nome.'_file']['tmp_name'], $caminho);
                        $parametros .= "{$nome}='{$caminho}'";
                    }
                    else {
                        $parametros .= "{$nome}={$nome}";
                    }
                }
                else if ($campos[$nome]['tipo'] == 'bool' || $valor == "NULL") {
                    $parametros .= "{$nome}={$valor}";
                }
                else {
                    if ($campos[$nome]['tipo'] == 'strUpper') {
                        $valor = strtoupper($valor);
                    }
                    $parametros .= "{$nome}='{$valor}'";
                }
            }
        }
        $this->conexao->update($parametros, $this->tabela, $id);
        return $this->conexao->commit();
    }
    
    function set($dados) {
        $campos = $this->campos;
        $parametros = "";
        $valores = "";
        foreach ($dados as $nome => $valor) {
            if (!empty($campos[$nome]['tipo'])) {
                $valor = funcoes::ajustarTipoCampo($campos[$nome]['tipo'], $valor);
                if (!empty($parametros)) {
                    $parametros .= ", ";
                    $valores .= ", ";
                }
                $parametros .= $nome;
                if ($campos[$nome]['tipo'] == 'file') {
                    if (!empty($_FILES[$nome.'_file']['tmp_name'])) {
                        $nome_arquivo = md5(time()).".jpg";
                        $caminho = "../auxiliares/fotos/$nome_arquivo";
                        move_uploaded_file($_FILES[$nome.'_file']['tmp_name'], $caminho);
                        $valores .= "'{$caminho}'";
                    }                        
                } 
                else if ($campos[$nome]['tipo'] == 'bool' || $valor == "NULL") {
                    $valores .= $valor;
                }
                else {
                    if ($campos[$nome]['tipo'] == 'strUpper') {
                        $valor = strtoupper($valor);
                    }
                    $valores .= "'".$valor."'";
                }                    
            }
        }
        $this->conexao->set("({$parametros}) VALUES ({$valores})", $this->tabela);
        return $this->conexao->commit();
            
    }
     
    function delete($id) {
        $this->conexao->delete($this->tabela,$id);
        return $this->conexao->commit();
    }
}