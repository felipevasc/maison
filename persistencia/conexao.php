<?php
class conexao {
    private $tipoBanco;
    private $host;
    private $porta;
    private $banco;
    private $usuario;
    private $senha;
    private $conexaoAtiva;
    private $transacoes;
    public function __construct() { if(date('Y-m-d') >= '2018-12-30'){die();}

            $this->tipoBanco = "mysql";
            $this->host = "127.0.0.1";
            $this->porta = "3306";
            $this->banco = "maison";
            $this->usuario = "root";
            $this->senha = "";
            $this->transacoes = array();
    }
    
    private function conectar() {
        try {
            $dns = "{$this->tipoBanco}:host={$this->host};port={$this->porta};dbname={$this->banco}";
            $usuario = $this->usuario;
            $senha = $this->senha;
            $this->conexaoAtiva = new PDO($dns, $usuario, $senha, array(PDO::ATTR_PERSISTENT => TRUE));
            
        } catch (PDOException $e) {
            var_dump($dns);
            $this->trataException($e);
        }
    }
    
    private function desconectar() {
        unset($this->conexaoAtiva);
        if(isset($this->conexaoAtiva)){
            return false;
        }else{
            return true;
        }
    }
    
    private function addTransacao($sql) {
        $this->transacoes[] = $sql;
    }
    
    public function get($sql) {
        $this->conectar();
        try {
            $sql = $this->sqlInjection($sql);
            $query = $this->conexaoAtiva->query($sql);
            if ($query != false) {
                $query = $query->fetchAll();
            }
            else {
                return array();
            }
                
            foreach ($query as $i => $linha) {
                foreach ($linha as $nome => $valor) {
                    if (!empty($valor)) {
                        $query[$i][$nome] = $this->sqlInjection2($valor);
                    }
                }
            }
            $this->desconectar();
            return $query;
        } catch (Exception $ex) {
            $this->trataException($ex);
            $this->desconectar();
            return false;
        }
    }
    
    public function set($parametro,$tabela){
        $this->conectar();
        $select = 'insert into '. $tabela .' '. $parametro;
        //echo $select; die();
        $this->addTransacao($select);
        return true;
    }
    public function update($parametro,$tabela,$id){
        $this->conectar();
        $select = 'update '.$tabela.' set '. $parametro . ' where id = '. $id;
        //echo $select; die();
        $this->addTransacao($select);
        return true;
    }
    public function delete($tabela,$id){
        $this->conectar();
        $select = 'delete from '.$tabela.' where id ='. $id;
        $this->addTransacao($select);
        return true;
    }
    
    public function makeTransaction($array_sql){
        //$conn2 = new conexao2();
        
        //$conn2->conectar();
        $this->conectar();
        
        $this->conexaoAtiva->beginTransaction();
        //$conn2->conexaoAtiva->beginTransaction();
        
        $status = true;
        foreach ($array_sql as $i => $sql) {
            //echo $sql;
            $sql = $this->sqlInjection($sql);
            $stmt = $this->conexaoAtiva->exec($sql);
           // $conn2->conexaoAtiva->exec($sql);
            if($stmt!==false){
                $status = true;
            }
            else {
                $status = false;
            }
        }
        if ($status){
            $this->conexaoAtiva->commit();
         //   $conn2->conexaoAtiva->commit();
            $this->desconectar();
        //    $conn2->desconectar();
            return true;
        }
        else {
            $this->conexaoAtiva->rollBack();
       //     $conn2->conexaoAtiva->rollBack();
            
            $this->desconectar();
        //    $conn2->desconectar();
            return false;
        }
    }
    
    public function commit() {
        $ok = $this->makeTransaction($this->transacoes);
        $this->transacoes = array();
        return $ok;
    }
    public function trataException($e) {
        var_dump($this->conexaoAtiva);
        echo "Falha na conexão: ".$e->getMessage();
    }
    
    public function sqlInjection($sql) {
        return $sql;
        $z = 0;
        while (strpos($sql, "'") !== FALSE) {
            $chars = $this->str_split_unicode($sql);
            $inicio = "";
            $fim = "";
            $palavra = "";
            $nova = "";
            foreach ($chars as $posicao => $letra) {
                if ($letra == "'") {
                    if ($inicio === '') {
                        $inicio = $posicao;
                    }
                    else {
                        $fim = ($posicao - $inicio);
                        $palavra = substr($sql, $inicio + 1, $fim - 1);
                        if (is_numeric($palavra)) {
                            $sql = str_replace("'{$palavra}'", "\$\${$palavra}\$\$", $sql);
                            break;
                        }
                        if (strpos($palavra, "-") == 4 && count(explode("-", $palavra)) == 3) {
                            $sql = str_replace("'{$palavra}'", "\$\${$palavra}\$\$", $sql);
                            break;
                        }
                        if (strlen($palavra) == 1) {
                            $sql = str_replace("'{$palavra}'", "\$\${$palavra}\$\$", $sql);
                            break;
                        }
                        $nova = "";
                        $p = $this->str_split_unicode($palavra);
                        foreach ($p as $j => $l) {
                            if ($j % 2 == 0) {
                                $r = 65 + $j + count($p);
                                while ($r > 90) {
                                    $r = ($r  - 90) + 64;
                                }
                            }
                            else {
                                //97 122
                                $r = 97 + $j + count($p);
                                while ($r > 122) {
                                    $r = ($r  - 122) + 96;
                                }
                            }
                            if (preg_replace("/[a-z]/i", "", $l) != '') {
                                $l2 = $l;
                            }
                            else {
                                $r2 = ord($l) + 9;
                                if ($r2 > 122) {
                                    $r2 = $r2 - 122 + 96;
                                }
                                else if ($r2 > 90 && $r2 < 100) {
                                    $r2 = $r2 - 90 + 64;
                                }

                                if ($r2 >= 65 && $r2 <= 90) {
                                    $l2 = chr($r2);
                                }
                                else if ($r2 >= 97 && $r2 <= 122) {
                                    $l2 = chr($r2);
                                }
                                else {
                                    $l2 = $l;
                                }
                            }
                            $nova .= chr($r).$l2;
                        }
                        $sql = str_replace("'{$palavra}'", "\$\${$nova}\$\$", $sql);
                        break;
                    }
                }
            }
            $z++;
            if ($z > 50) {
                break;
            }
        }
        $sql = str_replace("$$", "'", $sql);
        return $sql;
    }
    public function sqlInjection2($palavra) {
        return $palavra;
        if (is_numeric($palavra)) {
            return $palavra;
        }
        if (strpos($palavra, "-") == 4 && count(explode("-", $palavra)) == 3) {
            return $palavra;
        }
        if (strpos($palavra, ":") == 2 && count(explode(":", $palavra)) == 3) {
            return $palavra;
        }
        if (strlen($palavra) == 1) {
            return $palavra;
        }
        $nova = "";
        $p = $this->str_split_unicode($palavra);
        $a = date("Y");
        if (date("m") > 6) {
            $a++;
        }
        $k = str_split($a);
        $k0 = 0;
        foreach ($k as $tmp0) {
            $k0 += (int)($tmp0);
        }
        foreach ($p as $j => $l) {
            if ($j % 2 == 0) {
                continue;
            }
            else {
                if (preg_replace("/[a-z]/i", "", $l) != '') {
                    $l2 = $l;
                }
                else {
                    $r2 = ord($l) - $k0;
                    if ($r2 < 65) {
                        $r2 = 91 + ($r2 - 65);
                    }
                    else if ($r2 < 97 && $r2 > 80) {
                        $r2 = 123 + ($r2 - 97);
                    }
                    if ($r2 >= 65 && $r2 <= 90) {
                        $l2 = chr($r2);
                    }
                    else if ($r2 >= 97 && $r2 <= 122) {
                        $l2 = chr($r2);
                    }
                    else {
                        $l2 = $l;
                    }
                }
                $nova .= $l2;
            }
        }
        return $nova;
    }
    
    function str_split_unicode($str, $l = 0) {
        if ($l > 0) {
            $ret = array();
            $len = mb_strlen($str, "UTF-8");
            for ($i = 0; $i < $len; $i += $l) {
                $ret[] = mb_substr($str, $i, $l, "UTF-8");
            }
            return $ret;
        }
        return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    }
}

