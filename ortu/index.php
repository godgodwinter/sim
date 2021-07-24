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

function rupiah($angka){
	$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
	return $hasil_rupiah;
}
//ambil nilai
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");
require("../inc/cek/janissari_ortu.php");
require("../inc/class/paging.php");
$tpl = LoadTpl("../template/esw_index_ortu.html");


nocache;

//nilai
$filenya = "index.php";
$judul = "Selamat Datang....";
$judulku = "$judul  [$tipe_session : $no1_session.$nm1_session]";
$artkd = nosql($_REQUEST['artkd']);
$jurkd = nosql($_REQUEST['jurkd']);
$bulkd = nosql($_REQUEST['bulkd']);
$msgkd = nosql($_REQUEST['msgkd']);
$bk = nosql($_REQUEST['bk']);
$dk = nosql($_REQUEST['dk']);
$s = nosql($_REQUEST['s']);
$a = nosql($_REQUEST['a']);




//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//deteksi, jika belum punya blog
$qcc = mysqli_query($koneksi, "SELECT * FROM user_blog ".
			"WHERE kd_user = '$kd1_session'");
$rcc = mysqli_fetch_assoc($qcc);
$tcc = mysqli_num_rows($qcc);

//nek iya
if ($tcc == 0)
	{
	mysqli_query($koneksi, "INSERT INTO user_blog(kd, kd_user, postdate) VALUES ".
					"('$x', '$kd1_session', '$today')");
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////









//isi *START
ob_start();



//ketahui jumlah inbox, belum dibaca
$qku = mysqli_query($koneksi, "SELECT * FROM user_blog_msg ".
						"WHERE untuk = '$kd1_session' ".
						"AND dibaca = 'false'");
$tku = mysqli_num_rows($qku);

echo $tku;





//isi
$isiprofil = ob_get_contents();
ob_end_clean();








//isi *START
ob_start();





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






//query
$p = new Pager();
$start = $p->findStart($limit);

$sqlcount = "SELECT guru_mapel.*, guru_mapel.kd AS gmkd ".
				"FROM guru_mapel ".
				"WHERE tapel = '$tapelnya' ".
				"AND kelas = '$kelasnya' ".
				"ORDER BY mapel_nama ASC";
$sqlresult = $sqlcount;

$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
$pages = $p->findPages($count, $limit);
$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
$target = $filenya;
$pagelist = $p->pageList($_GET['page'], $pages, $target);
$data = mysqli_fetch_array($result);


//CARI DATA PEMBAYARAN DI tabel tagihan_siswa

$sqlcount2 = mysqli_query($koneksi, "SELECT * FROM tagihan_siswa ".
"WHERE username_siswa = '$no1_session' ".
"AND kelas = '$kelasnya' AND tapel='$tapelnya' ".
"ORDER BY kelas ASC");

$count2 = mysqli_num_rows($sqlcount2);	

$datasqlcount2 = mysqli_fetch_assoc($sqlcount2);
// var_dump($sqlcount2);
$tagihan_siswa_kd=$datasqlcount2['kd'];

// foreach($sqlcount2 as $ambildatasqlcount2){
// 	$tagihan_atur_kd=$ambildatasqlcount2['tagihan_atur_kd'];
// }
// var_dump($tagihan_atur_kd);
// $tagihan_atur_kd=$datasqlcount2['tagihan_atur_kd'];

$sqlcount_pembayaran_persiswa = mysqli_query($koneksi, "SELECT * FROM tagihan_siswa_detail ".
"WHERE tagihan_siswa_kd = '$tagihan_siswa_kd' ".
"ORDER BY tgl_bayar ASC");
// var_dump("SELECT * FROM tagihan_siswa_detail ".
// "WHERE tagihan_siswa_kd = '$tagihan_siswa_kd' ".
// "ORDER BY tgl_bayar ASC");
$pembayaranke = mysqli_num_rows($sqlcount_pembayaran_persiswa);	

//ketahui foto profil
$path1 = "../filebox/profil/$kd1_session/thumb-$kd1_session.jpg";

//jika gak ada
if (!file_exists($path1))
	{
	$pathku = "$sumber/img/logo.png";
	}
else
	{
	$pathku = $path1;
	}

	$sqlcarinis = mysqli_query($koneksi, "SELECT * FROM tagihan_siswa ".
	"WHERE username_siswa = '$no1_session' AND kelas = '$kelasnya' AND tapel='$tapelnya'  ".
	"ORDER BY kelas ASC");
	
	$jmlsqlcarinis = mysqli_num_rows($sqlcarinis);	
	
	foreach($sqlcarinis as $data){
		$kddata=$data['kd'];
		$tapeldata=$data['tapel'];
		$kelasdata=$data['kelas'];
		$tagihan_atur_kd=$data['tagihan_atur_kd'];
		$user_foto=$data['user_foto'];

		//ambil nominal tagihan
		$sqlcarinominaltagihan = mysqli_query($koneksi, "SELECT * FROM tagihan_atur ".
"WHERE kd = '$tagihan_atur_kd'  ".
"ORDER BY kd ASC");

$datasqlcarinominaltagihan = mysqli_fetch_assoc($sqlcarinominaltagihan);
$i_nominal_tagihan=$datasqlcarinominaltagihan['nominal_tagihan'];
		
		$jmlbayarsiswa=0;
$sqlcaridetailbayar = mysqli_query($koneksi, "SELECT * FROM tagihan_siswa_detail ".
"WHERE tagihan_siswa_kd = '$kddata'  ".
"ORDER BY tgl_bayar ASC");


$ike=0;
if($tagihan_atur_kd!=0){

foreach($sqlcaridetailbayar as $databayar){
$ike++;
	$jml_bayar=$databayar['jml_bayar'];
	
	$jmlbayarsiswa+=$jml_bayar;
 } rupiah($jmlbayarsiswa);
 round(((100/$i_nominal_tagihan)*($jmlbayarsiswa)),2);
 rupiah($i_nominal_tagihan);
 rupiah($i_nominal_tagihan-$jmlbayarsiswa);

	}
	

	}
			 
$sqlcaripersen = mysqli_query($koneksi, "SELECT * FROM admin_setting");



foreach($sqlcaripersen as $datapersen){
	$persentase=$datapersen['persen']; 
}

//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<div class="row">

		<div class="col-md-4">
          <div class="box box-widget widget-user-2">
            <div class="widget-user-header bg-green">
              <div class="widget-user-image">
                <img class="img-circle" src="'.$pathku.'" alt="'.$nm1_session.'">
              </div>
              <h3 class="widget-user-username">'.$nm1_session.'</h3>
              <h5 class="widget-user-desc">'.$no1_session.'</h5>
            </div>
           
		</div>
		</div>


    <div class="col-md-8">

      <div class="box box-success">
        <div class="box-header">

          <h3 class="box-title">REKAP PEMBAYARAN SISWA</h3>
        
		</div></div>';
		

		
		
		
		//nek gak null
		
		if ($pembayaranke != 0)
			{
				$sqlcarinis = mysqli_query($koneksi, "SELECT * FROM tagihan_siswa ".
"WHERE username_siswa = '$no1_session' AND kelas = '$kelasnya' AND tapel='$tapelnya'  ".
"ORDER BY kelas ASC");

$jmlsqlcarinis = mysqli_num_rows($sqlcarinis);	

// $ambildata = mysqli_fetch_all($sqlcarinis);
// var_dump($sqlcarinis);
			foreach($sqlcarinis as $data){
				$kddata=$data['kd'];
				$tapeldata=$data['tapel'];
				$kelasdata=$data['kelas'];
				$tagihan_atur_kd=$data['tagihan_atur_kd'];

				//ambil nominal tagihan
				$sqlcarinominaltagihan = mysqli_query($koneksi, "SELECT * FROM tagihan_atur ".
"WHERE kd = '$tagihan_atur_kd'  ".
"ORDER BY kd ASC");
// var_dump("SELECT * FROM tagihan_atur ".
// "WHERE kd = '$tagihan_atur_kd'  ".
// "ORDER BY kd ASC");
$datasqlcarinominaltagihan = mysqli_fetch_assoc($sqlcarinominaltagihan);
$i_nominal_tagihan=$datasqlcarinominaltagihan['nominal_tagihan'];
$user_foto=$datasqlcarinominaltagihan['user_foto'];
				
				$jmlbayarsiswa=0;
		$sqlcaridetailbayar = mysqli_query($koneksi, "SELECT * FROM tagihan_siswa_detail ".
"WHERE tagihan_siswa_kd = '$kddata'  ".
"ORDER BY tgl_bayar ASC");


$ike=0;
if($tagihan_atur_kd!=0){
				?>

<div class="box box-primary mt-2">
	<div class="box-header">

		<h3 class="box-title">Tahun Pelajaran <?=balikin($tapeldata);?> Kelas <?=$kelasdata;?></h3>
		<?php 
		// var_dump("SELECT * FROM tagihan_siswa_detail ".
		// "WHERE tagihan_siswa_kd = '$kddata'  ".
		// "ORDER BY tgl_bayar ASC");
		foreach($sqlcaridetailbayar as $databayar){
$ike++;
			$jml_bayar=$databayar['jml_bayar'];
			
			$jmlbayarsiswa+=$jml_bayar;
		?>
		<li>Pembayaran ke-<?=$ike;?> <span class="pull-right badge bg-blue"><?=rupiah($jml_bayar);?></span></li>
		<?php } ?>
		<hr>
		<li>Total Pembayaran <span class="pull-right badge bg-blue"><?=rupiah($jmlbayarsiswa);?></span>
			<span class="pull-right badge bg-blue"><?=round(((100/$i_nominal_tagihan)*($jmlbayarsiswa)),2);?>%</span>
		</li>
		<li>Total Tagihan <span class="pull-right badge bg-yellow"><?=rupiah($i_nominal_tagihan);?></span></li>
		<li>Total Tagihan Belum dibayar <span
				class="pull-right badge bg-red"><?=rupiah($i_nominal_tagihan-$jmlbayarsiswa);?></span></li>
	</div>
</div>
<?php
			}
			

			}
			//penutup jika kd tidak 0
            }

		else
			{
            echo '<div class="box-body">
			
			<div class="alert alert-danger alert-dismissible">
                <h4><i class="icon fa fa-ban"></i> ERROR...!!</h4>
                ['.$tapelnya2.', '.$kelasnya2.']. Belum melakukan Pembayaran. Silahkan Hubungi Administrator.
              </div>
              
			  
			</div>';
			}
	
			



echo '<div class="box box-success">
<div class="box-header">

<img src="'.$sumber.'/img/scan/'.$user_foto.'" class="img-fluid" alt="Responsive image">

</div></div>
</div>
</div>

</div>

</div>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////








//online terakhir ////////////////////////////////////////////////////////////////////////////////
$ipnya = get_client_ip_env();
							


//insert
$xyz = md5("$kd1_session$today");
mysqli_query($koneksi, "INSERT INTO user_blog_status(kd, kd_user, user_tipe, ".
				"user_kode, user_nama, status, ".
				"onlineya, ipnya, postdate) VALUES ".
				"('$xyz', '$kd1_session', '$tipe_session', ".
				"'$no1_session', '$nm1_session', '$judul', ".
				"'YA', '$ipnya', '$today');");
//online terakhir ////////////////////////////////////////////////////////////////////////////////
		

?>


<iframe frameborder="0" height="0" id="frpengguna" name="frpengguna" width="0"
	src="http://sosmedsekolah.com/pengguna.php?seknama=<?php echo $sek_nama;?>&sekalamat=<?php echo $sek_alamat;?>&sekkota=<?php echo $sek_kota;?>&sektelp=<?php echo $sek_kontak;?>"
	scrolling="no"></iframe>


<?php
		
		
		

//isi
$isi = ob_get_contents();
ob_end_clean();

require("../inc/niltpl.php");


//diskonek
xfree($qbw);
xclose($koneksi);
exit();
?>