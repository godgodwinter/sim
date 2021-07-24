<?php
session_start();
function rupiah($angka){
	$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
	return $hasil_rupiah;
}
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/adm.php");
require("../../inc/class/paging.php");
$tpl = LoadTpl("../../template/admin.html");

$i_nis = $_GET['id'];
$gettapel = $_GET['tapel'];
$getkelas = $_GET['kelas'];
$ke = "tagihan_siswa.php?tapel=$gettapel&kelas=$getkelas";

mysqli_query($koneksi, "DELETE FROM tagihan_siswa ".
                    "WHERE username_siswa = '$i_nis' AND tapel='$gettapel' AND kelas='$getkelas'");
                    xloc($ke);
                    exit();
?>