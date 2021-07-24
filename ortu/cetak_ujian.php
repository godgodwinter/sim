<?php
session_start();

function rupiah($angka){
	$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
	return $hasil_rupiah;
}
// //ambil nilai
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");
require("../inc/cek/janissari_ortu.php");
require("../inc/class/paging.php");
require'../loadvendor.php';
require "fpdf17/fpdf.php";
date_default_timezone_set("Asia/Jakarta");


// // detail siswa
$q2 = mysqli_query($koneksi, "SELECT * FROM m_user ".
					"WHERE nomor = '$no1_session' ".
					"AND kd = '$kd1_session'");
$row2 = mysqli_fetch_assoc($q2);
$tapelnya = cegah($row2['tapel_nama']);
$tapelnya2 = balikin($row2['tapel_nama']);
$kelasnya = cegah($row2['kelas_nama']);
$kelasnya2 = balikin($row2['kelas_nama']);
$moodle_user = balikin($row2['moodle_user']);
$moodle_pass = balikin($row2['moodle_pass']);
$nama = balikin($row2['nama']);
$nomor = balikin($row2['nomor']);
//halaman yang dicetak

// $thn_nilai = $_GET['tahun'];
// $kelas = $_GET['kelas'];
// $hri = date('d');
// $bln = date('m');
// $bln=(int)$bln;
// $bln=nama_bulan($bln);
// $tahun = date('Y');
// $thn = date('Y');


class PDF extends FPDF{
	function Header(){
//Header Kertas
$this->SetFont('helvetica','B',9);
$this->CELL(1,3,'',0,0,'L');
$this->CELL(1,3,'',0,2,'C');
$this->Image('logo_besar.png',2,2,18);
$this->CELL(94,3,"PEMERINTAH KABUPATEN MALANG",0,2,'C');
$this->CELL(94,9,"SMK DHARMAWANITA KROMENGAN",0,2,'C');
$this->SetFont('helvetica','',9);
$this->CELL(90,5,"Jl. Naiulun Sel. No.22,Kromengan 65165",0,2,'C');
// $this->CELL(90,7,"Kec. Kromengan, Malang, Jawa Timur 65165",0,1,'C');
$this->Line(2,20,77,20);
$this->Line(2,21,77,21);
$this->Ln(7);
// $this->Image('logo/logo_besar.png',30,5,27,27);
// $this->Image('images/ketapan.png',30,5,27,27);
		}
}
$pdf = new PDF('P','mm',array(126,79));
// $margin = 10;
// $pdf->Rect( $margin, $margin , $pageWidth - $margin , $pageHeight - $margin);
$pdf->SetMargins(1,1,1);
$pdf->SetAutoPageBreak(true,5);
$pdf->Open();
$pdf->AddPage();

//Identitas Raport
$pdf->SetXY(9, 24 + $yOffset);
$pdf->SetFont('helvetica','B',9);
$pdf->CELL(0,5,'KARTU UJIAN SEKOLAH',0,2,'C');
$pdf->SetFont('helvetica','',9);
$pdf->Cell(9.5, 7, 'NISN', 0, 4, 'L'); 
$pdf->Cell(9.5, 7, 'Nama', 0, 4, 'L'); 
$pdf->Cell(9.5, 7, 'Username', 0, 4, 'L'); 
$pdf->Cell(9.5, 7, 'Password', 0, 4, 'L'); 
$yOffset += 40;



$pdf->SetXY(36, -11 + $yOffset);
$pdf->Cell(9.5, 7, ':  '.$nomor, 0, 4, 'L'); 
$pdf->Cell(9.5, 7, ':  '.$nama, 0, 4, 'L'); 
$pdf->Cell(9.5, 7, ':  '.$moodle_user, 0, 4, 'L'); 
$pdf->Cell(9.5, 7, ':  '.$moodle_pass, 0, 4, 'L'); 
$yOffset += 10;
// $pdf->AddPage();

// $input_array = array('a', 'b', 'c', 'd', 'e');

// $farray = array_chunk($input_array, 2);
// foreach($farray as $obj) {
//     $yOffset = 0;
//     foreach($obj as $item) {
//         $pdf->SetXY(33, 28 + $yOffset);
//         $pdf->SetFont('Arial', 'B', 10);
//         $pdf->Cell(9.5, 7, $item, 0, 4, 'L');
//         $yOffset += 40; // Y distance between letters
//     }
//     $pdf->AddPage();
// }
// $pdf->Output();
//full kanan = 195
// // //header tabel
// // $pdf->SetFillColor(110,180,230);
// // $pdf->SetFont('helvetica','B',10);
// // $pdf->SetTextColor(0);
// // $pdf->CELL(9,6,'',0,0,'C');
// // $pdf->CELL(20,6,'Username',1,0,'C',1);
// // $pdf->CELL(100,6,'Password',1,0,'C',1);

// // $pdf->Ln();

// $pdf->SetFont('helvetica','',10);
// $no=0;
// $pdf->CELL(9,6,'',0,',0,C');
// 	$pdf->CELL(20,6,$moodle_user,1,0,'C');
// 	$pdf->CELL(100,6,$moodle_pass,1,0,'C');
// 	$pdf->Ln();

// //tanda tangan
// $pdf->Ln(10);
// $pdf->CELL(120,5,'',0,0,'C');
// // $pdf->CELL(60,5,'Trawas, '.$hri." ".$bln." ".$thn,0,2,'C');
// $pdf->CELL(60,5,'Kepala Sekolah',0,2,'C');
// $pdf->Ln(20);
// $pdf->CELL(120,5,'',0,0,'C');
// $pdf->CELL(60,5,'.....................................................',0,2,'C');
// $pdf->CELL(60,20,'',0,2,'C');
// $pdf->SetFont('helvetica','U',10);

$pdf->SetTitle('Cetak_data_ujian.pdf');

$pdf->Output('Cetak_data_ujian.pdf','I');

?>