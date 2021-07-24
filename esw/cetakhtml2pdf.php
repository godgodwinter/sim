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
require("../inc/cek/janissari.php");
require("../inc/class/paging.php");
require'../loadvendor.php';


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
//halaman yang dicetak
$html="
<!DOCTYPE html>
<html>
<head>

	<title>'CETAK'</title>
</head>
<body>

<h1 align='center'>Rekap Pembayaran $no1_session $nm1_session</h1>
<table width='100%' cellspacing='0' border='1' >
	<tr>
	<th>No</th>
	<th>No</th>
	<th>No</th>
	<th>No</th>
	</tr>
	<tr>
		<td>a</td>
		<td>a</td>
		<td>a</td>
	</tr>
	</table>
</body>
</html>
";

use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new Html2Pdf();
$html2pdf->writeHTML($html);
$html2pdf->output();
?>