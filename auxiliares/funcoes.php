<?php
class funcoes {
    static $cores = [[0, 200, 0], [0, 0, 200], [200, 0, 0], [0, 100, 200], [0, 200, 100], [200, 100, 0], [100, 0, 200], [100, 200, 0], [220, 220, 220], [151, 181, 205], [200, 0, 100], [50, 100, 150], [150, 50, 100], [100, 150, 50], [150, 100, 50], [100, 50, 150], [25, 25, 25], [200, 200, 200], [88, 255, 100], [255, 88, 100], [100, 250, 88]];
    public static function ajustarTipoCampo($tipo, $valor) {
        if ($tipo == "int") {
            if (is_numeric($valor)) {
                return $valor;
            }
            else {
                return "NULL";
            }
        }
        if ($tipo == "time") {
            if (!empty($valor)) {
                return $valor;
            }
            else {
                return "NULL";
            }
        }
        if ($tipo == "data") {
            $tmp = explode("/", $valor);
            if (count($tmp) == 3) {
                return $tmp[2]."-".$tmp[1]."-".$tmp[0];
            }
            else {
                $tmp = explode("-", $valor);
                if (count($tmp) == 3) {
                    return $tmp[0]."-".$tmp[1]."-".$tmp[2];
                }
                else {
                    return "NULL";
                }
            }
        }
        if ($tipo == 'pass') {
            if (empty($valor)) {
                return "";
            }
            else {
                return sha1($valor);
            }
        }
        if ($tipo == 'bool') {
            if ($valor === true || $valor == 'TRUE' || $valor == 'true' || $valor == 't') {
                return 'TRUE';
            }
            else if ($valor === false || $valor == 'FALSE' || $valor == 'false' || $valor == 'f') {
                return 'FALSE';
            }
            else {
                return 'NULL';
            }
        }
        return $valor;
    }
    
    public static function definirSemana($dia_referencia, &$primeiro_dia, &$ultimo_dia) {
        $dia = date("w", $dia_referencia);
        $primeiro = 7 - (8 - $dia);
        if ($primeiro == 7) {
            $primeiro = 0;
        }
        $primeiro_dia = mktime(0, 0, 0, date("m", $dia_referencia), date("d", $dia_referencia) - $primeiro, date("Y", $dia_referencia));
        $ultimo_dia = mktime(0, 0, 0, date("m", $dia_referencia), (date("d", $dia_referencia) - $primeiro) + 6, date("Y", $dia_referencia));
    }
    
    public static function formatarMonetario($valor) {
        return "<nobr>R$ ".number_format($valor, 2, ",", ".")."</nobr>";
    }
    
    public static function formatarData($valor) {
        $hora = "";
        if (strlen($valor) > 10) {
            $hora = substr($valor, 10);
            $valor = substr($valor, 0, 10);
        }
        if (empty($valor)) {
            return '';
        }
        $tmp = explode("-", $valor);
        if (count($tmp) < 3) {
            return $valor;
        }
        return $tmp[2]."/".$tmp[1]."/".$tmp[0].$hora;
    }
    
    public static function desformatarData($valor) {
        $hora = "";
        if (strlen($valor) > 10) {
            $hora = substr($valor, 10);
            $valor = substr($valor, 0, 10);
        }
        if (empty($valor)) {
            return '';
        }
        $tmp = explode("/", $valor);
        if (count($tmp) < 3) {
            return $valor;
        }
        return $tmp[2]."-".$tmp[1]."-".$tmp[0].$hora;
    }
    
    public static function formatarSexo($valor) {
        if (empty($valor)) {
            return '';
        }
        if ($valor == 'F' || $valor == 'f') {
            return "Feminino";
        }
        else {
            return "Masculino";
        }
    }
    
    public static function formatarBooleano($valor) {
        if ($valor == 't' || $valor === true || $valor == 1) {
            return "Sim";
        }
        else {
            return "Não";
        }
    }
    
    public static function removerPrimeiroDoArray($array) {
        $i = 0;
        $novoArray = array();
        if (empty($array)) {
            return array();
        }
        foreach ($array as $nome => $valor) {
            if ($i > 0) {
                $novoArray[] = $valor;
            }
            $i++;
        }
        return $novoArray;
    }
    
    public static function removerUltimoDoArray($array) {
        $i = 1;
        $novoArray = array();
        foreach ($array as $nome => $valor) {
            if ($i < count($array)) {
                $novoArray[$nome] = $valor;
            }
            $i++;
        }
        return $novoArray;
    }
    
    public static function redirecionar($pagina) {
        echo "<script> $(function(){ mascara(); }); </script>";
        echo '<meta http-equiv="refresh" content=0;url="'.$pagina.'"/>';
        die();
    }
    
    public static function alerta($texto, $redirecionar='') {
        if (empty($redirecionar)) {
            $funcao = "function() { $('.alerta_x').remove(); }";
        }
        else {
            $funcao = "function() { window.location.href='{$redirecionar}' }";
        }
        echo "<script>";
        echo "  alerta(\"{$texto}\", {$funcao});";
        echo "</script>";
    }
    
    public static function formataNomeMes($mes) {
        switch ($mes) {
            case 1:
                return "Janeiro";
            case 2:
                return "Fevereiro";
            case 3:
                return "Março";
            case 4:
                return "Abril";
            case 5:
                return "Maio";
            case 6:
                return "Junho";
            case 7:
                return "Julho";
            case 8:
                return "Agosto";
            case 9:
                return "Setembro";
            case 10:
                return "Outubro";
            case 11:
                return "Novembro";
            case 12:
                return "Dezembro";
            default :
                return "";
        }
    }
    
    public static function gerarPdf($abrir = false, $local = "conteudo") {
        if ($abrir) {
            ?>
            <script>
                $(function(){
                    gerarPdf();
                });
            </script>
            <?php
        }
        else {
            ?>
            <span class="botao" onclick="gerarPdf(true, '<?php echo $local; ?>');">
                <img src="../auxiliares/ico/pdf.png"/><br/>
                Gerar PDF
            </span>
            <?php
        }
    }
    public static function gerarPdfSemCabecalho($abrir = false, $local = "conteudo") {
        if ($abrir) {
            ?>
            <script>
                $(function(){
                    gerarPdfSemCabecalho();
                });
            </script>
            <?php
        }
        else {
            ?>
            <span class="botao" onclick="gerarPdfSemCabecalho(true, '<?php echo $local; ?>');">
                <img src="../auxiliares/ico/pdf.png"/><br/>
                Gerar PDF
            </span>
            <?php
        }
    }
    
    public static function gerarGraficoBarras($valores, $width = 500, $height = 200) {
        $id = funcoes::nomeParaArray($valores);
        
        $graficos2d = array();
        foreach ($valores as $descricao => $v) {
            foreach ($v as $nome_grafico => $valor) {
                $graficos2d[] = $nome_grafico;
            }
            break;
        }
        $objeto2 = "";
        $objeto2 .= "[";
        foreach ($graficos2d as $x => $nome_grafico) {
            if ($x > 0) {
                $objeto2 .= ",";
            }
            $objeto2 .= "[";
            $i = 0;
            foreach ($valores as $descricao => $v) {
                if ($i > 0) {
                    $objeto2 .= ",";
                }
                $valor = $v[$graficos2d[$x]];

                $cor = funcoes::$cores[$i][0].",".funcoes::$cores[$i][1].",".funcoes::$cores[$i][2];
                $objeto2 .= "{";
                $objeto2 .= "value: {$valor},";
                $objeto2 .= "color: \"rgba({$cor}, 0.7)\",";
                $objeto2 .= "highlight: \"rgba({$cor}, 1)\",";
                $objeto2 .= "label: \"{$descricao}\"";
                $objeto2 .= "}";
                $i++;
            }
            $objeto2 .= "]";
        }
        $objeto2 .= "]";
        $objeto = "{";
        $objeto .= "labels: [";
        foreach ($valores as $v) {
            $i = 0;
            foreach ($v as $label => $x) {
                if ($i > 0) {
                    $objeto .= ",";
                }
                $objeto .= '"'.$label.'"';
                $i++;
            }
            break;
        }
        $objeto .= "],";
        $objeto .= "datasets: [";
        $i = 0;
        foreach ($valores as $descricao => $valor) {
            if ($i > 0) {
                $objeto .= ",";
            }
            $cor = funcoes::$cores[$i][0].",".funcoes::$cores[$i][1].",".funcoes::$cores[$i][2];
            $objeto .= "{";
            $objeto .= "label: \"<span style='display: inline-block; margin-bottom: 3px; vertical-align: middle; width: 20px; height: 20px; border-radius: 3px; background-color: rgba({$cor}, 1);'></span> <span style='display: inline-block; vertical-align: middle;'><label>{$descricao}</label></span>\",";
            $objeto .= "fillColor : \"rgba({$cor},0.3)\",";
            $objeto .= "strokeColor : \"rgba({$cor},0.8)\",";
            $objeto .= "highlightFill: \"rgba({$cor},0.75)\",";
            $objeto .= "highlightStroke: \"rgba({$cor},1)\",";
            $objeto .= "pointColor: \"rgba({$cor},1)\",";
            $objeto .= "pointStrokeColor: \"rgba({$cor},1)\",";
            $objeto .= "pointHighlightStroke: \"rgba({$cor},1)\",";
            $objeto .= "data : [";
            $j = 0;
            foreach ($valor as $v) {
                if ($j > 0) {
                    $objeto .= ",";
                }
                $objeto .= $v;
                $j++;
            }
            $objeto .= "]";
            $objeto .= "}";
            $i++;
        }
        $objeto .= "]";
        $objeto .= "}";
        ?>
        <div style="width: <?php echo $width; ?>px; height: <?php echo $height + 80; ?>px; border: #AAAAAA solid 1px; border-radius: 5px; margin: 0 auto;">
            <canvas class="<?php echo $id; ?>" id="barra_<?php echo $id; ?>" style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px; display: none;"></canvas><br/>
            <canvas class="<?php echo $id; ?>" id="linha_<?php echo $id; ?>" style="width: <?php echo $width; ?>px; height: <?php echo $height - 30; ?>px; display: none;"></canvas><br/>
            <canvas class="<?php echo $id; ?>" id="radar_<?php echo $id; ?>" style="width: <?php echo $width; ?>px; height: <?php echo $height - 30; ?>px; display: none;"></canvas><br/>
            <?php
            $w = floor(300 / count($graficos2d)) - 10;
            echo "<span style='display: none; width: {$width}px; text-align: center;' class='middle {$id}' id='pizza_{$id}'>";
            foreach ($graficos2d as $x => $nome_grafico) {
                ?><span class='middle' style='margin: 10px; width: <?php echo $w; ?>px;'><canvas class="<?php echo $id; ?>" id="pizza_<?php echo $id."_".$x; ?>" style="width: <?php echo $w; ?>px; height: <?php echo $height - 60; ?>px; display: none;"></canvas><br/><?php 
                echo $nome_grafico."</span>";
            }
            echo "</span>";
            ?>
                
            <span>
                <span id="<?php echo $id; ?>_barras" ><img src="../auxiliares/ico/barras.png" style="cursor: pointer; width: 25px;"/></span>
                <script>
                    $('#<?php echo $id; ?>_barras').click(function(){
                        gerarGraficoBarras('<?php echo $id; ?>', <?php echo $objeto; ?> );
                    })
                </script>
                <span id="<?php echo $id; ?>_linhas"><img src="../auxiliares/ico/linhas.png" style="cursor: pointer; width: 25px;"/></span>
                <script>
                    $('#<?php echo $id; ?>_linhas').click(function(){
                        gerarGraficoLinhas('<?php echo $id; ?>', <?php echo $objeto; ?> );
                    })
                </script>
                <span id="<?php echo $id; ?>_radar"><img src="../auxiliares/ico/radar.png" style="cursor: pointer; width: 25px;"/></span>
                <script>
                    $('#<?php echo $id; ?>_radar').click(function(){
                        gerarGraficoRadar('<?php echo $id; ?>', <?php echo $objeto; ?> );
                    })
                </script>
                <span class="pizza" id="<?php echo $id; ?>_pizza"><img src="../auxiliares/ico/pizza.png" style="cursor: pointer; width: 25px;"/></span>
                <script>
                    $('#<?php echo $id; ?>_pizza').click(function(){
                        gerarGraficoPizza('<?php echo $id; ?>', <?php echo $objeto2; ?> );
                    })
                </script>
        </div>
        <div id="<?php echo $id; ?>_legenda" style="width: 400px; height: 200px; text-align: left; margin: 0 auto;"></div>
        <div id="<?php echo $id; ?>_legenda" style="width: 400px; height: 200px; text-align: left; margin: 0 auto;"></div>
        <div id="<?php echo $id; ?>_legenda" style="width: 400px; height: 200px; text-align: left; margin: 0 auto;"></div>
        <script>
            gerarGraficoBarras('<?php echo $id; ?>', <?php echo $objeto; ?>);
        </script>
        <?php
    }
    
    public static function nomeParaArray($array) {
        $nome = "";
        foreach ($array as $nome => $valor) {
            if (is_array($valor)) {
                $nome .= funcoes::nomeParaArray($valor);
            }
            else {
                $nome .= $nome."=".$valor;
            }
        }
        return md5($nome.time().rand(0, 99));
    }
    
    public static function montaSelect($array, $campo_valor, $campos_label, $selected, $imprimir = true, $campos_extras = "") {
        $options = "";
        foreach ($array as $i => $linha) {
            if ($linha[$campo_valor] == $selected) {
                $s = "selected='selected'";
            }
            else {
                $s = "";
            }
            $c_e = "";
            if (!empty($campos_extras)) {
                foreach ($campos_extras as $nome_campo => $valores_campo) {
                    $c_e .= " {$nome_campo}='{$valores_campo[$i]}' ";
                }
            }
            $options .= "<option {$s} {$c_e} value='{$linha[$campo_valor]}'>";
            if (!is_array($campos_label)) {
                $tmp = $campos_label;
                $campos_label = array();
                $campos_label[0] = $tmp;
            }
            
            foreach ($campos_label as $i => $campo) {
                if ($i > 0) {
                    $options .= " - ";
                }
                $options .= $linha[$campo];
            }
            $options .= "</option>";
        }
        if ($imprimir) {
            echo $options;
        }
        else {
            return $options;
        }
    }
    
    public static function formatarTurma($numero_turma) {
        return chr($numero_turma + 64);
    }
    
    public static function montaInput($nome, $tipo, $valor ='', $maximo = '') {
        if ($tipo == 'int') {
            return "<input type='text' data-tipo='int' name='{$nome}' value='{$valor}' />";
        }
        else if ($tipo == 'float') {
            return "<input type='text' data-tipo='float' name='{$nome}' value='{$valor}' />";
        }
        else if ($tipo == 'monetario') {
            return "<input type='text' data-tipo='monetario' name='{$nome}' value='{$valor}' />";
        }
        else if ($tipo == 'str') {
            return "<input type='text' data-tipo='str' name='{$nome}' value='{$valor}'/>";
        }
        else if ($tipo == 'strUpper') {
            return "<input type='text' name='{$nome}' value='{$valor}'/>";
        }
        else if ($tipo == 'nome') {
            return "<input type='text' data-tipo='nome' name='{$nome}' value='{$valor}'/>";
        }
        else if ($tipo == 'text') {
            return "<textarea name='{$nome}'>{$valor}</textarea>";
        }
        else if ($tipo == 'bool') {
            if (empty($valor) || $valor == 'FALSE') {
                $true = '';
                $false = "checked='checked'";
            }
            else {
                $true = "checked='checked'";
                $false = '';
            }
            return "<label><input type='radio' name='{$nome}' value='TRUE' {$true}/>Sim</label><br/>"
                . "<label><input type='radio' name='{$nome}' value='FALSE' {$false}/>Não</label>";
        }
        else if ($tipo == 'pass') {
            return "<input type='password' name='{$nome}' id='{$nome}'/>";
        }
        else if ($tipo == 'confirm_pass') {
            return "<input type='password' name='confirmar_{$nome}' onchange=\"if (this.value != $('#{$nome}').val()) { this.value = ''; alert('Os campos de {$nome} não conferem'); }\"/>";
        }
        else if ($tipo == 'data') {
            return "<input type='text' data-tipo='data' name='{$nome}' value='{$valor}'/>";
        }
        else if ($tipo == 'cpf') {
            return "<input type='text' data-tipo='cpf' name='{$nome}' value='{$valor}'/>";
        }
        else if ($tipo == 'time') {
            $valor = substr($valor, 0, 5);
            return "<input type='text' data-tipo='time' name='{$nome}' value='{$valor}'/>";
        }
        else if ($tipo == 'file') {
            $valor = substr($valor, 0, 5);
            return "<input type='file' name='{$nome}_file'/> <input type='hidden' value='img' name='{$nome}'/>";
        }
    }
    
    public static function capitalizar($nome) {
        $tmp = explode(" ", $nome);
        $novo_nome = "";
        foreach ($tmp as $palavra) {
            if (!empty($novo_nome)) {
                $novo_nome .= " ";
            }
            $palavra = strtolower($palavra);
            if (!in_array($palavra, array('de', 'da', 'do', 'das', 'e')) && strlen($palavra) > 1) {
                $novo_nome .= strtoupper(substr($palavra, 0, 1)).substr($palavra, 1);
            }
            else {
                $nome .= $palavra;
            }
        }
        return $novo_nome;
    }
    
    public static function nomeTabelaByCaminho($file) {
        $tmp = explode("\\", $file);
        $i = count($tmp) - 1;
        $nome_pagina = $tmp[$i];
        $nomeTabela = $nome_pagina;
        $nomeTabela = str_replace("Form.php", "", $nomeTabela);
        $nomeTabela = str_replace("Crud.php", "", $nomeTabela);
        $nomeTabela = str_replace("List.php", "", $nomeTabela);
        return $nomeTabela;
    }
    
    public static function formatarCampo($tipo, $valor) {
        if ($tipo == "data") {
            return funcoes::formatarData($valor);
        }
        else if ($tipo == "bool") {
            return funcoes::formatarBooleano($valor);
        }
        else if ($tipo == "nome") {
            return strtoupper($valor);
        }
        else if ($tipo == "monetario") {
            return "R$ ".number_format($valor, 2, ',', '.');
        }
        else if ($tipo == "file") {
            return "<img src='{$valor}' style='width: 180px; height: 240px;'/>";
        }
        else {
            return $valor;
        }
    }
} 