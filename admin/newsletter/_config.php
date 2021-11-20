<?php

// PhpSpreadsheet's

require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$diaDePosted  = new DateTime($dia_de); 
$diaAtePosted = new DateTime($dia_ate); 

$firstRow = 0;

$sheet->setCellValue('A1', 'Email');
$sheet->setCellValue('B1', 'Data');
$sheet->setCellValue('C1', 'Hora');

$i = 1;
foreach ($leadsResult as $leadResult) {
   $i++;
   $dia = new DateTime($leadResult['created_at']); 
   $hora = new DateTime($leadResult['created_at']); 
   $sheet->setCellValue('A'.$i.'', $leadResult['email']);
   $sheet->setCellValue('B'.$i.'', $dia->format('d/m/Y'));
   $sheet->setCellValue('C'.$i.'', $hora->format('H:i:s'));
}

$writer = new Xlsx($spreadsheet); 
$writer->save('downloads/leads-'. $diaDePosted->format('d-m-Y') .'-TO-'. $diaAtePosted->format('d-m-Y') .'.xlsx');

$download     = true;
$actual_link  = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$downloadLink = $actual_link . "downloads/leads-" . $diaDePosted->format('d-m-Y') .'-TO-'. $diaAtePosted->format('d-m-Y') .'.xlsx';