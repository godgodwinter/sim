<?php
session_start();
function rupiah($angka){
	$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
	return $hasil_rupiah;
}
//ambil nilai
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");
// memanggil library FPDF
require('../library/fpdf.php');

//detail siswa
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




// intance object dan memberikan pengaturan halaman PDF
$pdf = new FPDF('P','mm','A4');
// membuat halaman baru
$pdf->AddPage();
// setting jenis font yang akan digunakan
$pdf->SetFont('Arial','B',16);
// mencetak string 
$pdf->Cell(40,10,'Belajar Membuat Laporan PDF Dengan FPDF');
$pdf->Output();
?>