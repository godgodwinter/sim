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
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");
require("../inc/cek/adm.php");
$tpl = LoadTpl("../template/admin.html");


nocache;

//nilai
$filenya = "index.php";
$judul = "Mulai Tahun Pelajaran Baru";
$judulku = "$judul  [$adm_session]";







//jml user
$qyuk = mysqli_query($koneksi, "SELECT * FROM m_user");
$jml_siswa = mysqli_num_rows($qyuk);


//jml kelas
$qyuk = mysqli_query($koneksi, "SELECT * FROM m_kelas");
$jml_kelas = mysqli_num_rows($qyuk);



//jml mapel
$qyuk = mysqli_query($koneksi, "SELECT * FROM m_mapel");
$jml_mapel = mysqli_num_rows($qyuk);

//tapelaktif
$sqlquery = "SELECT * FROM admin_setting WHERE id='1'";

$ambildata = mysqli_query($koneksi, $sqlquery);
// var_dump($gettagihan_siswa_kd);
while($data = mysqli_fetch_array($ambildata)){
    
    $tapelsebelumnya=balikin($data['tapel']);
    $tapelaktif=naik_t(balikin($data['tapel']));
}

$judul = "Mulai Tahun Pelajaran Baru '".$tapelaktif."'";





//rekap masuk
$qyuk = mysqli_query($koneksi, "SELECT * FROM guru_mapel_log ".
						"WHERE round(DATE_FORMAT(postdate, '%d')) = '$tanggal' ".
						"AND round(DATE_FORMAT(postdate, '%m')) = '$bulan' ".
						"AND round(DATE_FORMAT(postdate, '%Y')) = '$tahun' ".
						"ORDER BY postdate ASC");
$ryuk = mysqli_fetch_assoc($qyuk);
$rekap_masuk = mysqli_num_rows($qyuk);





//isi *START
ob_start();

//tanggal sekarang
$m = date("m");
$de = date("d");
$y = date("Y");

//ambil 7hari terakhir
for($i=0; $i<=7; $i++)
	{
	$nilku = date('Ymd',mktime(0,0,0,$m,($de-$i),$y)); 

	echo "$nilku, ";
	}


//isi
$isi_data1 = ob_get_contents();
ob_end_clean();










//isi *START
ob_start();

//tanggal sekarang
$m = date("m");
$de = date("d");
$y = date("Y");

//ambil 7hari terakhir
for($i=0; $i<=7; $i++)
	{
	$nilku = date('Y-m-d',mktime(0,0,0,$m,($de-$i),$y)); 


	//pecah
	$ipecah = explode("-", $nilku);
	$itahun = trim($ipecah[0]);  
	$ibln = trim($ipecah[1]);
	$itgl = trim($ipecah[2]);    


	//ketahui ordernya...
	$qyuk = mysqli_query($koneksi, "SELECT * FROM guru_mapel_log ".
							"WHERE round(DATE_FORMAT(postdate, '%d')) = '$itgl' ".
							"AND round(DATE_FORMAT(postdate, '%m')) = '$ibln' ".
							"AND round(DATE_FORMAT(postdate, '%Y')) = '$itahun'");
	$tyuk = mysqli_num_rows($qyuk);
	
	if (empty($tyuk))
		{
		$tyuk = "1";
		}
		
	echo "$tyuk, ";
	}


//isi
$isi_data2 = ob_get_contents();
ob_end_clean();









//isi *START
ob_start();

//jika POST[tapelbaru] kosong maka redirek ke beranda
$tapelaktif=$_POST['tapelaktif'];
$tapelsebelumnya=$_POST['tapelsebelumnya'];
// echo $tapelaktif;
if(empty($tapelaktif)){
	
//re-direct
xloc($filenya);
exit();
}else{
	//SoYs
	// 1. buat tapel aktif baru
	// 2. seleck kelas ambil name
	//     a. ubah semua name === yang sama ,, di tabel user siswa d
	// 	b. tambahkan siswa dengan kelas name sama(yang lama) ke tabel tagihan siswa dengan kelas dan tapel baru(nama baru)
	// 3. ubah tabel semua di tabel siswa dengan siswa kelas baru
	// 4. Redirek ke tabel kelas
	echo $tapelsebelumnya;
	echo $tapelaktif;
}

?>





            
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




//isi
$isi = ob_get_contents();
ob_end_clean();

require("../inc/niltpl.php");


//diskonek
xfree($qbw);
xclose($koneksi);
exit();
?>