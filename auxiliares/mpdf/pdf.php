<?php
date_default_timezone_set('America/Sao_Paulo');
include("mpdf.php");
class pdf extends mPDF {
    public function SetHTMLHeader($header = '', $OE = '', $write = false) {
        $data_hora = date("d/m/Y H:i:s");
        $html = "<table style='width: 100%;'>";
        $html .= "  <tbody>";
        $html .= "      <tr>";
        $html .= "          <td style='text-align: left; height: 15px; width: 15px;'><img src='./img/maisonF1.png'/></td>";
        $html .= "          <td style='text-align: center; font-weight: bold; font-size: 20px;'>Maison Trajes Finos <br/><span style='text-align: center; font-weight: normal; font-size: 12px;'> Rua 12 de Agosto, 1075 Planalto Tianguá-CE </span><br/><span style='text-align: center; font-weight: normal; font-size: 12px;'> Cel:(88) 9.9299-7906(Claro) / 9.9858-2916(Tim) </span></td>";
        $html .= "      </tr>";
        $html .= "      <tr>";
        $html .= "          <td style='font-size: 12px; text-align: top; height: 15px; padding-right: 10px; color: #000000; border-bottom: #333333 solid 2px;' colspan='3'>Gerado pelo sistema de controle Maison Trajes Finos em {$data_hora}</td>";
        $html .= "      </tr>";
        $html .= "  </tbody>";
        $html .= "</table>";
        parent::SetHTMLHeader($html);
    }
}