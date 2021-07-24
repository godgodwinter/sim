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
require("../inc/cek/janissari.php");
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
$this->SetFont('helvetica','B',20);
$this->CELL(20,3,'',0,0,'L');
$this->CELL(170,3,'',0,2,'C');
$this->CELL(170,9,"SMK DHARMAWANITA KROMENGAN",0,2,'C');
$this->SetFont('helvetica','',9);
$this->CELL(170,5,"Jl. Naiulun Sel. No.22, Baloan, Kromengan, ",0,2,'C');
$this->CELL(170,7,"Kec. Kromengan, Malang, Jawa Timur 65165",0,1,'C');
$this->Line(11,33,200,33);
$this->Line(11,34,200,34);
$this->Ln(7);
// $this->Image('images/ketapan.png',30,5,27,27);
		}
}
$pdf = new PDF('P','mm',array(210,297));
$pdf->SetMargins(5,7,5);
$pdf->SetAutoPageBreak(true,10);
$pdf->Open();
$pdf->AddPage();

//Identitas Raport
$pdf->SetFont('helvetica','',12);
//$pdf->SetTextColor(25,25,100);
$pdf->CELL(195,7,'DATA UJIAN '.$nama.' - '.$nomor,0,2,'C');
// $pdf->CELL(195,7,'Tahun '.$thn_nilai,0,0,'C');
$pdf->Ln(10);

//full kanan = 195
//header tabel
$pdf->SetFillColor(110,180,230);
$pdf->SetFont('helvetica','B',10);
$pdf->SetTextColor(0);
$pdf->CELL(9,6,'',0,0,'C');
$pdf->CELL(20,6,'Username',1,0,'C',1);
$pdf->CELL(100,6,'Password',1,0,'C',1);

$pdf->Ln();

$pdf->SetFont('helvetica','',10);
$no=0;
$pdf->CELL(9,6,'',0,',0,C');
	$pdf->CELL(20,6,$moodle_user,1,0,'C');
	$pdf->CELL(100,6,$moodle_pass,1,0,'C');
	$pdf->Ln();

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