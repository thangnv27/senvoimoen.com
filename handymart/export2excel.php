<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/libs/PHPExcel/PHPExcel.php';

// Include wpload
include_once('../../../wp-load.php');

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("PPO VIET NAM")
                            ->setLastModifiedBy("PPO VIET NAM")
                            ->setTitle("Office 2007 XLSX Test Document")
                            ->setSubject("Office 2007 XLSX Test Document")
                            ->setDescription("")
                            ->setKeywords("")
                            ->setCategory("");


// Add header data
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'MÃ ĐƠN HÀNG')
        ->setCellValue('B1', 'KHÁCH HÀNG')
        ->setCellValue('C1', 'TỔNG TIỀN')
        ->setCellValue('D1', 'NGÀY');

$product_id = 0;
if(isset($_REQUEST['product_id']) and intval($_REQUEST['product_id']) > 0){
    $product_id = intval($_REQUEST['product_id']);
}

/* -- Preparing your query -- */
global $wpdb;
$tblOrders = $wpdb->prefix . 'orders';
$query = "SELECT $tblOrders.*, $wpdb->users.display_name FROM $tblOrders LEFT "
        . "JOIN $wpdb->users ON $wpdb->users.ID = $tblOrders.customer_id "
        . "WHERE status = 1 ";
if($product_id > 0){
    $query .= "AND products REGEXP '.*{\"id\":\"$product_id\",.*' ";
}
$query .= "ORDER BY created_at desc";

$orders = $wpdb->get_results($query);

$count = 2;
foreach ($orders as $order){
    // Add row data
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit('A' . $count, $order->ID)
                ->setCellValue('B' . $count, $order->display_name ? $order->display_name : "Guest")
                ->setCellValueExplicit('C' . $count, number_format($order->total_amount, 0, ',', '.'). ' VNĐ')
                ->setCellValue('D' . $count, $order->created_at);
    $count++;
}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Don hang');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a clientâ€™s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Don hang.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
