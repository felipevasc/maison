<?php
class funcionario extends generico {
    function getByLogin($login) {
        $rs = $this->getByCondicao("WHERE upper(login) = upper('{$login}')");
        if (count($rs) > 0) {
            return $rs[0];
        }
        else {
            return null;
        }
    }
}
?>