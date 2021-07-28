<?php
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
/////// SISFOKOL JANISSARI                          ///////
///////////////////////////////////////////////////////////
/////// Dibuat oleh :                               ///////
/////// Agus Muhajir, S.Kom                         ///////
/////// URL     :                                   ///////
///////     *http://sisfokol.wordpress.com          ///////
//////      *http://hajirodeon.wordpress.com        ///////
/////// E-Mail  :                                   ///////
///////     * hajirodeon@yahoo.com                  ///////
///////     * hajirodeon@gmail.com                  ///////
/////// HP/SMS  : 081-829-88-54                     ///////
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////



session_start();

//ambil nilai
// require("../inc/config.php");
// require("../inc/fungsi.php");
// require("../inc/koneksi.php");
// require("../inc/cek/adm.php");

$conn = mysqli_connect("localhost","root","","sim");

//isi *START
ob_start();

//tanggal sekarang
$m = date("m");
$de = date("d");
$y = date("Y");


//ambil 7hari terakhir
// for($i=0; $i<=7; $i++)
// 	{
// 	$nilku = date('Ymd',mktime(0,0,0,$m,($de-$i),$y)); 

// 	echo "$nilku, ";
// 	}

$sqlQuery = "SELECT * FROM guru_mapel_log";

$result = mysqli_query($conn,$sqlQuery);

$data = array();
foreach ($result as $row) {
	$data[] = $row;
}

mysqli_close($conn);

echo json_encode($data);
?>