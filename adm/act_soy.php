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
$tapelaktif=cegah($_POST['tapelaktif']);
$tapelsebelumnya=cegah($_POST['tapelsebelumnya']);
// $tapel = cegah($_POST['tapel']);
// echo $tapelaktif;
if(empty($tapelaktif)){
	
//re-direct
xloc($filenya);
exit();
}else{
	//SoYs
	// 1. buat tapel aktif baru	
							mysqli_query($koneksi, "INSERT INTO m_tapel(kd, tapel, postdate) VALUES ".
							"('$x', '$tapelaktif', '$today')");
	

	// 2. seleck kelas ambil name
	//     a. ubah semua siswa yang namakelas+tapel === yang sama ,, di tabel user siswa
	// 	b. tambahkan kelas name sama(yang baru)+tapelbaru ke tabel tagihan atur

	// 	c. tambahkan siswa dengan kelas name sama(yang lama) ke tabel tagihan siswa dengan kelas dan tapel baru(nama baru)
	
$sqlquery = "SELECT * FROM m_kelas  ORDER BY `m_kelas`.`kelas` ASC";

$ambildata = mysqli_query($koneksi, $sqlquery);
// var_dump($gettagihan_siswa_kd);
	while($data = mysqli_fetch_array($ambildata)){
		$kdkelas=$data['kd'];
		$kelas_nama=$data['kelas'];
		$kelas_namabaru=naik_k($kelas_nama);
		// echo $kelas_nama."<br>";
		// echo  "SELECT * FROM m_user WHERE tapel_nama='$tapelsebelumnya' AND kelas_nama='$kelas_nama'  ORDER BY nama ASC";
	$sqlquerydua_a = "SELECT * FROM m_user WHERE tapel_nama='$tapelsebelumnya' AND kelas_nama='$kelas_nama'  ORDER BY nama ASC";
	$ambildatadua_a = mysqli_query($koneksi, $sqlquerydua_a);
	// var_dump($gettagihan_siswa_kd);
			while($datadua_a = mysqli_fetch_array($ambildatadua_a)){
					$namausersiswa=$datadua_a['nama'];
					$nomorusersiswa=$datadua_a['nomor'];
					// echo '-'.$namausersiswa.'-';
					
					//2.a. update tapel dan kelas dengan yang baru di tabel m_user
					
					
										mysqli_query($koneksi, "UPDATE m_user SET tapel_nama = '$tapelaktif',kelas_nama='$kelas_namabaru' ".
										"WHERE nomor = '$nomorusersiswa'");	
			}
							
				//2.b. insert tapelbaru dan kelas baru di tagihan atur
							$cekkelas=explode(" ",$kelas_namabaru);
							// echo$cekkelas[0].'-';
								if(!($cekkelas[0]==='Alumni')){
									mysqli_query($koneksi, "INSERT INTO tagihan_atur(tapel, kelas, nominal_tagihan,user_foto,username_guru,nama) VALUES ".
									"('$tapelaktif', '$kelas_namabaru','100','','','')");
								}else{
									//alumni tidak perlu kelas
								}

		//2.c.  tambahkan siswa dengan kelas name sama(yang lama) ke tabel tagihan siswa dengan kelas dan tapel baru(nama baru)
				// 2.c.1 ambil data kd tagihan atur berdasarkan tapelbaru+kelasbaru
				
				// echo"SELECT * FROM tagihan_atur WHERE tapel='$tapelaktif' AND kelas='$kelas_namabaru'  ORDER BY tapel ASC";
$sqlquerydua_csatu = "SELECT * FROM tagihan_atur WHERE tapel='$tapelaktif' AND kelas='$kelas_namabaru'  ORDER BY tapel ASC";
$ambildatadua_csatu = mysqli_query($koneksi, $sqlquerydua_csatu);
// var_dump($gettagihan_siswa_kd);
while($datadua_csatu = mysqli_fetch_array($ambildatadua_csatu)){
		$kdtagihanatur=$datadua_csatu['kd'];
		// echo '-'.$kdtagihanatur.'-';
		
				// 2.c.2 insert semua siswa ke tabel tagihan siswa dari kelas dan tapel lama di tabel siswa
				// echo "SELECT * FROM m_user WHERE tapel_nama='$tapelaktif' AND kelas_nama='$kelas_namabaru'  ORDER BY nama ASC";
$sqlquerydua_cdua = "SELECT * FROM m_user WHERE tapel_nama='$tapelaktif' AND kelas_nama='$kelas_namabaru'  ORDER BY nama ASC";
$ambildatadua_cdua = mysqli_query($koneksi, $sqlquerydua_cdua);
// var_dump($gettagihan_siswa_kd);
while($datadua_cdua = mysqli_fetch_array($ambildatadua_cdua)){
		$nomorsiswabaru=$datadua_cdua['nomor'];
		$namasiswabaru=$datadua_cdua['nama'];
		// echo$namasiswabaru.'-';

								//
								mysqli_query($koneksi, "INSERT INTO tagihan_siswa(username_siswa, nama, tapel, kelas, tagihan_atur_kd) VALUES ".
								"('$nomorsiswabaru', '$namasiswabaru','$tapelaktif', '$kelas_namabaru', '$kdtagihanatur')");

}




}
						
				


	// 3. ubah tabel semua di tabel kelas dengan kelas kelas baru
	
										mysqli_query($koneksi, "UPDATE m_kelas SET kelas='$kelas_namabaru' ".
										"WHERE kd = '$kdkelas'");
										
										//hapus kelas alumni (bukan data siswa alumni)
										
							$cekkelas=explode(" ",$kelas_namabaru);
							// echo$cekkelas[0].'-';
								if(($cekkelas[0]==='Alumni')){
									
									mysqli_query($koneksi, "DELETE FROM m_kelas ".
									"WHERE kd = '$kdkelas'");
								
								}


	// 4. update adminsetting tapel baru
	
	mysqli_query($koneksi, "UPDATE admin_setting SET tapel='$tapelaktif' ".
	"WHERE id = '1'");
	// 5. Redirek ke ke index/beranda
	
	// echo $tapelsebelumnya;
	// echo $tapelaktif;


}

xloc($filenya);
exit();


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