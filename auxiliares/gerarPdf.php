<?php
include("mpdf/pdf.php");
$mpdf = new pdf('utf-8','A4','','','2','1','35','18');
//Cabeçalho diferente por pagina $mpdf->mirrorMargins = true;
//$mpdf->SetHtmlHeader("BBB<br/><br/> - Cabeçalho Esquerda", 'O');
//$mpdf->SetHtmlHeader("AAA<br/><br/> - Cabeçalho Direita", 'E');
$mpdf->SetHTMLHeader();
$mpdf->SetFooter("Página: {PAGENO}/{nb}");
$css = file_get_contents('css.css');
$mpdf->WriteHTML($css,1);
$css = file_get_contents('pdf.css');
$mpdf->WriteHTML($css,1);
$mpdf->WriteHTML($_POST['html'], 2);
$mpdf->Output();
?>