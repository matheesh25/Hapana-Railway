<?php
require('../fpdf/fpdf.php');
include("../config.php");

$res = mysqli_query($conn,"
    SELECT delivery.*, orders.customer_name, orders.product
    FROM delivery
    JOIN orders ON delivery.order_id = orders.id
    ORDER BY delivery.id DESC
");

$pdf = new FPDF();
$pdf->AddPage();

// 🟡 LOGO
$pdf->Image('../images/logo.jpg',10,10,30);

// 🔴 COMPANY DETAILS
$pdf->SetFont('Arial','B',18);
$pdf->Cell(0,10,'HAPANA FIREWORKS',0,1,'R');

$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,'180/A Gonawila Road, Negombo',0,1,'R');
$pdf->Cell(0,6,'0776286905 / 0785934283',0,1,'R');

$pdf->Ln(10);

// 📅 DATE
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,'Date: '.date("Y-m-d H:i:s"),0,1,'R');

$pdf->Ln(5);

// 📌 TITLE
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'DELIVERY REPORT',0,1,'C');

$pdf->Cell(0,0,'',1,1);
$pdf->Ln(5);

// 🟡 TABLE HEADER
$pdf->SetFont('Arial','B',9);
$pdf->SetFillColor(255,193,7);

$pdf->Cell(18,8,'Order ID',1,0,'C',true);
$pdf->Cell(35,8,'Customer',1,0,'C',true);
$pdf->Cell(35,8,'Product',1,0,'C',true);
$pdf->Cell(30,8,'Status',1,0,'C',true);
$pdf->Cell(20,8,'Progress',1,0,'C',true);
$pdf->Cell(45,8,'Courier',1,1,'C',true);

// DATA
$pdf->SetFont('Arial','',8);

while($row = mysqli_fetch_assoc($res)){
    $pdf->Cell(18,8,$row['order_id'],1);
    $pdf->Cell(35,8,$row['customer_name'],1);
    $pdf->Cell(35,8,$row['product'],1);
    $pdf->Cell(30,8,$row['delivery_status'],1);
    $pdf->Cell(20,8,$row['progress'].'%',1);
    $pdf->Cell(45,8,$row['courier_service'],1);
    $pdf->Ln();
}

// ✍️ SIGNATURE
$pdf->Ln(15);
$pdf->SetFont('Arial','',10);

$pdf->Cell(100,6,'',0,0);
$pdf->Cell(80,6,'________________________',0,1,'C');

$pdf->Cell(100,6,'',0,0);
$pdf->Cell(80,6,'Authorized Signature',0,1,'C');

// FOOTER
$pdf->Ln(10);
$pdf->SetFont('Arial','I',10);
$pdf->Cell(0,10,'Hapana Fireworks Delivery Management Report',0,1,'C');

$pdf->Output("D","Delivery_Report.pdf");
?>