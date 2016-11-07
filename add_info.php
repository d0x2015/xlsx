<?php
//подключаем библиотеку для работы с Excel
require_once 'Classes/PHPExcel.php';
//подключаем настройки бд
require_once 'inc/bd.php';
mysql_query('SET NAMES utf8');
$result = mysql_query("select id,name,project from services");
//загружаем файл .xlsx
$pExcel = PHPExcel_IOFactory::load('gate.xlsx');
$aSheet = $pExcel->getActiveSheet();
//заносим данные из БД в массив
while($data = mysql_fetch_array($result)){
	$arr[] = $data;
}	
// цикл по листам
foreach( $pExcel->getWorksheetIterator() as $worksheet ) {
	echo '<table border="1">';
// цикл по строкам
foreach( $worksheet->getRowIterator() as $idRow => $row ) {
	echo '<tr>';
// цикл по колонкам
foreach( $row->getCellIterator() as $idCell => $cell ) {
	$value = $cell->getValue();
//цикл подсчета кол-ва данные в БД
for($i=0; $i<count($arr); $i++) {
//поиск значений
	if($value == 'IDP') {
	if($idRow != 1) $idRow = 1;
//добавление данных из бд в ячейку
	$worksheet->getCellByColumnAndRow($idCell, $idRow+$i)->setValue($arr[$i]['id']);	
//поиск значений
	}elseif($value == 'Менеджер') {
	if($idRow != 1) $idRow = 1;
//добавление данных из бд в ячейку
	$worksheet->getCellByColumnAndRow($idCell, $idRow+$i)->setValue($arr[$i]['name']);
//поиск значений
	}elseif($value == 'Проект') {
	if($idRow != 1) $idRow = 1;
//добавление данных из бд в ячейку
	$worksheet->getCellByColumnAndRow($idCell, $idRow+$i)->setValue($arr[$i]['project']);
	}
}
//вывод
	echo '<td>'.$value.'</td>';
}
	echo '</tr>';
}
    echo '</table>';
}
//сохранение данных в файл
$objWriter = PHPExcel_IOFactory::createWriter($pExcel, 'Excel2007');
$objWriter->save('gate.xlsx');


?>