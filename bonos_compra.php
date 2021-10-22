<?php
error_reporting(E_ERROR | E_PARSE);
require('consola/fpdf/fpdf.php');
require('consola/_conexion.php');
$id_compra = $_REQUEST['id_compra'];
$query_compra = _query("SELECT empresas.nombre, compra_cupones.id_compra_cupon, ofertas.fecha_limite_cupon FROM ofertas INNER JOIN compra_cupones_detalle on compra_cupones_detalle.id_oferta = ofertas.id_oferta INNER JOIN compra_cupones on compra_cupones.id_compra_cupon = compra_cupones_detalle.id_compra_cupon INNER JOIN empresas on empresas.id_empresa = compra_cupones.id_empresa INNER JOIN compra on compra.id_compra = compra_cupones.id_compra WHERE compra.id_compra = '$id_compra'");
$pdf = new FPDF();
$pdf->AddPage();
$pdf->setY(20);
while($row_compra = _fetch_array($query_compra)){
    $y = $pdf->getY();
    if($y > 180){
        $pdf->AddPage();
        $pdf->setY(20);
    }
    $y = $pdf->getY();
    $pdf->setY($y);
    $nombre = $row_compra['nombre'];
    $id_compra_cupon = $row_compra['id_compra_cupon'];
    $fecha_limite = $row_compra['fecha_limite_cupon'];
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,10,utf8_decode("Empresa: ".$nombre),1,1,'C');
    $pdf->Cell(0,10,utf8_decode("Fecha Limite de Canje: ".$fecha_limite),1,1,'C');

    $id_encriptado = MD5($id_compra_cupon);

    $nombre_imagen_qr = "imagenes_qr/".$id_encriptado.".png";
    if (!file_exists($nombre_imagen_qr)) {
        // include QR_BarCode class 
        include "consola/QR_BarCode.php"; 

        // QR_BarCode object 
        $qr = new QR_BarCode(); 
        // create text QR code 
        $qr->text($id_encriptado); 
        // display QR code image
        //$qr->qrCode();
        $qr->qrCode(350,'imagenes_qr/'.$id_encriptado.".png");
        header('Location: bonos_compra.php?id_compra='.$id_compra);
    }

    $pdf->Image(('imagenes_qr/'.$id_encriptado.".png"),82,($pdf->getY()+15),50,50);
    $pdf->Cell(0,10,utf8_decode("Codigo: ".$id_compra_cupon),1,1,'C');
    $pdf->setY($pdf->getY()+70);
}
ob_clean();
$pdf->Output();
?>