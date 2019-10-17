<?php
function novo_autoload ($class_name) {
    if(file_exists("../auxiliares/{$class_name}.php")){
        require_once "../auxiliares/{$class_name}.php";
    }
    else if(file_exists("../negocio/{$class_name}.php")){
        require_once "../negocio/{$class_name}.php";
    }
    else if(file_exists("../persistencia/{$class_name}.php")){
        require_once "../persistencia/{$class_name}.php";
    }
    else if(file_exists("../visao/{$class_name}.php")){
        require_once "../visao/{$class_name}.php";
    }
}
spl_autoload_register("novo_autoload");