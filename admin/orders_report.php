<?php
require('../fpdf/fpdf.php');
include("../config.php");

$res = mysqli_query($conn,"SELECT * FROM orders ORDER BY id DESC");

// CALCULATE TOTAL
$totalAmount = 0;
$data = [];

while($row = mysqli_fetch_assoc($res)){
    $data[] = $row;
    $totalAmount += $row['total'];
}

$pdf = new FPDF();
$pdf->AddPage();

// 🟡 LOGO (LEFT)
$pdf->Image('../images/logo.jpg',10,10,30);

// 🔴 COMPANY (RIGHT)
$pdf->SetFont('Arial','B',18);
$pdf->Cell(0,10,'HAPANA FIREWORKS',0,1,'R');

$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,'180/A Gonawila Road, Negombo',0,1,'R');
$pdf->Cell(0,6,'0776286905 / 0785934283',0,1,'R');

$pdf->Ln(10);

// 📅 DATE & TIME
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,'Date: '.date("Y-m-d H:i:s"),0,1,'R');

$pdf->Ln(5);

// 📌 TITLE
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'ORDERS REPORT',0,1,'C');

$pdf->Cell(0,0,'',1,1);
$pdf->Ln(5);

// 🟡 TABLE HEADER
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(255,193,7);

$pdf->Cell(10,8,'ID',1,0,'C',true);
$pdf->Cell(35,8,'Customer',1,0,'C',true);
$pdf->Cell(40,8,'Product',1,0,'C',true);
$pdf->Cell(15,8,'Qty',1,0,'C',true);
$pdf->Cell(25,8,'Total',1,0,'C',true);
$pdf->Cell(25,8,'Status',1,1,'C',true);

// DATA
$pdf->SetFont('Arial','',9);

foreach($data as $row){
    $pdf->Cell(10,8,$row['id'],1);
    $pdf->Cell(35,8,$row['customer_name'],1);
    $pdf->Cell(40,8,$row['product'],1);
    $pdf->Cell(15,8,$row['quantity'],1);
    $pdf->Cell(25,8,"LKR ".$row['total'],1);
    $pdf->Cell(25,8,$row['status'],1);
    $pdf->Ln();
}

// 🟢 TOTAL
$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,'Total Revenue: LKR '.$totalAmount,0,1,'R');

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
$pdf->Cell(0,10,'Thank you for using Hapana Fireworks System',0,1,'C');

$pdf->Output("D","Orders_Report.pdf");
?>