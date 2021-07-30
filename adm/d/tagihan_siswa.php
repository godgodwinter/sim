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
require("../../inc/class/paging_siswa.php");
$tpl = LoadTpl("../../template/admin.html");
nocache;
//nilai
$filenya = "tagihan_siswa.php";
$judul = "Tagihan Siswa";
$judulku = "[$adm_session] ==> $judul";
$judulku = "$judul";
$judulx = $judul;
$kd = nosql($_REQUEST['kd']);
$s = nosql($_REQUEST['s']);
$kunci = cegah($_REQUEST['kunci']);
$kunci2 = balikin($_REQUEST['kunci']);
$page = nosql($_REQUEST['page']);
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
	}
//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika import
if ($_POST['btnIM'])
{
//re-direct
$ke = "$filenya?s=import";
xloc($ke);
exit();
}
//lama
//import sekarang
if ($_POST['btnIMX'])
{
$filex_namex2 = strip(strtolower($_FILES['filex_xls']['name']));
//nek null
if (empty($filex_namex2))
    {
    //re-direct
    $pesan = "Input Tidak Lengkap. Harap Diulangi...!!";
    $ke = "$filenya?s=import";
    pekem($pesan,$ke);
    exit();
    }
else
    {
    //deteksi .xls
    $ext_filex = substr($filex_namex2, -4);
    if ($ext_filex == ".xls")
        {
        //nilai
        $path1 = "../../filebox";
        $path2 = "../../filebox/excel";
        chmod($path1,0777);
        chmod($path2,0777);
        //nama file import, diubah menjadi baru...
        $filex_namex2 = "mapel.xls";
        //mengkopi file
        copy($_FILES['filex_xls']['tmp_name'],"../../filebox/excel/$filex_namex2");
        //chmod
        $path3 = "../../filebox/excel/$filex_namex2";
        chmod($path1,0755);
        chmod($path2,0777);
        chmod($path3,0777);
        //file-nya...
        $uploadfile = $path3;
        //require
        require('../../inc/class/PHPExcel.php');
        require('../../inc/class/PHPExcel/IOFactory.php');
          // load excel
          $load = PHPExcel_IOFactory::load($uploadfile);
          $sheets = $load->getActiveSheet()->toArray(null,true,true,true);
          $i = 1;
          foreach ($sheets as $sheet) 
              {
            // karena data yang di excel di mulai dari baris ke 2
            // maka jika $i lebih dari 1 data akan di masukan ke database
            if ($i > 1) 
                {
                  // nama ada di kolom A
                  // sedangkan alamat ada di kolom B
                  $i_xyz = md5("$x$i");
                  $i_no = cegah($sheet['A']);
                  $i_tapel = cegah($sheet['B']);
                  $i_kelas = cegah($sheet['C']);
				  $i_nominal_tagihan = cegah($sheet['D']);
					//menghilangkan angka 00 dibelakang koma
				  $arr=(explode(",",$i_nominal_tagihan));
				  //menghilangkan selain angka
				  $i_nominal_tagihan_str = preg_replace("/[^0-9]/", "", $arr[0]);
				  //konversi ke int
				//   var_dump($i_nominal_tagihan_str);
				  $i_nominal_tagihan_int = (int) $i_nominal_tagihan_str;
				//   var_dump($i_nominal_tagihan_int);
                    //cek
                    $qcc = mysqli_query($koneksi, "SELECT * FROM tagihan_atur ".
                                            "WHERE  tapel = '$i_tapel' AND kelas = '$i_kelas'");
                    $rcc = mysqli_fetch_assoc($qcc);
                    $tcc = mysqli_num_rows($qcc);
                    //jika ada, update				
                    if (!empty($tcc))
                        {
                        mysqli_query($koneksi, "UPDATE tagihan_atur SET nominal_Tagihan = '$i_nominal_tagihan_int' ".
                                        "WHERE tapel = '$i_tapel' AND kelas = '$i_kelas'");
                        }
                    else
                        {
                        //insert
                        mysqli_query($koneksi, "INSERT INTO tagihan_atur(tapel, kelas, nominal_Tagihan) VALUES ".
                                        "('$i_tapel', '$i_kelas', '$i_nominal_tagihan_int')");
						}
					//	var_dump("INSERT INTO tagihan_atur(kd,tapel, kelas, nominal_Tagihan) VALUES ".
				//		"('','$i_tapel', '$i_kelas', '$i_nominal_tagihan_int')");
                }
            $i++;
          }
        //hapus file, jika telah import
        $path1 = "../../filebox/excel/$filex_namex2";
        chmod($path1,0777);
        unlink ($path1);
        //re-direct
        xloc($filenya);
        exit();
        }
    else
        {
        //salah
        $pesan = "Bukan File .xls . Harap Diperhatikan...!!";
        $ke = "$filenya?s=import";
        pekem($pesan,$ke);
        exit();
        }
    }
}
//jika export
//export
if ($_POST['btnEX'])
{
//require
require('../../inc/class/excel/OLEwriter.php');
require('../../inc/class/excel/BIFFwriter.php');
require('../../inc/class/excel/worksheet.php');
require('../../inc/class/excel/workbook.php');
//nama file e...
$i_filename = "tagihan_atur.xls";
$i_judul = "Pengaturan Tagihan";
//header file
function HeaderingExcel($i_filename)
    {
    header("Content-type:application/vnd.ms-excel");
    header("Content-Disposition:attachment;filename=$i_filename");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    header("Pragma: public");
    }
//bikin...
HeaderingExcel($i_filename);
$workbook = new Workbook("-");
$worksheet1 =& $workbook->add_worksheet($i_judul);
$worksheet1->write_string(0,0,"NO.");
$worksheet1->write_string(0,1,"TAPEL");
$worksheet1->write_string(0,2,"KELAS");
$worksheet1->write_string(0,3,"Nominal Tagihan");
//data
$qdt = mysqli_query($koneksi, "SELECT * FROM tagihan_atur ".
                        "ORDER BY tapel ASC");
$rdt = mysqli_fetch_assoc($qdt);
do
    {
    //nilai
    $dt_nox = $dt_nox + 1;
    $dt_kode = balikin($rdt['tapel']);
    $dt_nama = balikin($rdt['kelas']);
    $dt_nominal_tagihan = balikin($rdt['nominal_tagihan']);
    //ciptakan
    $worksheet1->write_string($dt_nox,0,$dt_nox);
    $worksheet1->write_string($dt_nox,1,$dt_kode);
    $worksheet1->write_string($dt_nox,2,$dt_nama);
    $worksheet1->write_string($dt_nox,3,rupiah($dt_nominal_tagihan));
    }
while ($rdt = mysqli_fetch_assoc($qdt));
//close
$workbook->close();
//re-direct
xloc($filenya);
exit();
}
//nek batal
if ($_POST['btnBTL'])
{
//re-direct
xloc($filenya);
exit();
}
//jika Memilh kelas
if ($_POST['btnPilihTapel'])
{
    $tapel=$_POST['e_tapel'];
    $kelas=$_POST['e_kelas'];
//re-direct
//$ke = "$filenya?s=baru&kd=$x";
$ke = "$filenya?tapel=$tapel&kelas=$kelas";
xloc($ke);
exit();
}
//jika cari
if ($_POST['btnCARI'])
{
//nilai
$getkelas = cegah($_POST['getkelascari']);
$gettapel = cegah($_POST['gettapelcari']);
$kunci = cegah($_POST['kunci']);
// var_dump($_POST['gettapelcari']);
//re-direct
if(!empty($gettapel)AND(!empty($getkelas))){
// $ke = "$filenya?kunci=$kunci&kelas=$getkelas&tapel=$gettapel";
$ke = "$filenya?kunci=$kunci";
}else{
$ke = "$filenya?kunci=$kunci";
}
xloc($ke);
exit();
}

//jika cari
if ($_POST['btnHAPUSDETAIL'])
{
//nilai
$getkd = cegah($_POST['getkddetail']);

$getkelas = cegah($_POST['getkelas']);
$gettapel = cegah($_POST['gettapel']);
$kunci = cegah($_POST['e_kunci']);
mysqli_query($koneksi, "DELETE FROM tagihan_siswa_detail ".
"WHERE kd = '$getkd'");


// var_dump("DELETE FROM tagihan_atur ".
// "WHERE kd = '$getkd'");
//re-direct
if(!empty($gettapel)AND(!empty($getkelas))){
$ke = "$filenya?kunci=$kunci&kelas=$getkelas&tapel=$gettapel";
// $ke = "$filenya?kunci=$kunci";
}else{
$ke = "$filenya?kunci=$kunci";
}
xloc($ke);
exit();
}
// nek entri baru
// if ($_POST['btnBARU'])
// {
// //re-direct
// //$ke = "$filenya?s=baru&kd=$x";
// $ke = "$filenya?s=baru&kd=$x";
// xloc($ke);
// exit();
// }

//nek Updatepersen
if ($_POST['btnSMPpersen'])
{
	$persen=$_POST['persen'];
	mysqli_query($koneksi, "UPDATE admin_setting SET persen = '$persen' ".
	"WHERE id = '1'");

//diskonek
// xfree($qcc);
// xfree($qbw);
// xclose($koneksi);
//re-direct
//$ke = "$filenya?s=baru&kd=$x";
$ke = "$filenya";
xloc($ke);
exit();
}
//jika simpan
if ($_POST['btnSMP'])
{
$s = nosql($_POST['s']);
$kd = nosql($_POST['kd']);
$page = nosql($_POST['page']);
$e_tapel = cegah($_POST['e_tapel']);
$e_kelas = cegah($_POST['e_kelas']);
$e_nominal_tagihan = cegah($_POST['e_nominal_tagihan']);
//nek null
if ((empty($e_tapel)) OR (empty($e_kelas)))
    {
    //re-direct
    $pesan = "Belum Ditulis. Harap Diulangi...!!";
    $ke = "$filenya?s=$s&kd=$kd";
    pekem($pesan,$ke);
    exit();
    }
else
    {
    //jika update
    if ($s == "edit")
        {
        //update
        mysqli_query($koneksi, "UPDATE tagihan_atur SET tapel = '$e_tapel', ".
                        "kelas = '$e_kelas', ".
                        "nominal_tagihan = '$e_nominal_tagihan' ".
                        "WHERE kd = '$kd'");
        //update guru_mapel
        // mysqli_query($koneksi, "UPDATE guru_mapel SET mapel_nama = '$e_nama' ".
        //                 "WHERE mapel_kode = '$e_kode'");
        //re-direct
        xloc($filenya);
        exit();
        }
    //jika baru
    if ($s == "baru")
        {
        //cek
        $qcc = mysqli_query($koneksi, "SELECT * FROM tagihan_atur ".
                                "WHERE tapel = '$e_tapel' AND kelas = '$e_kelas'");
        $rcc = mysqli_fetch_assoc($qcc);
        $tcc = mysqli_num_rows($qcc);
        //nek ada
        if ($tcc != 0)
            {
            //re-direct
            $pesan = "Tapel dan Kelas Sudah Ada. Silahkan Ganti Yang Lain...!!";
            $ke = "$filenya?s=baru&kd=$kd";
            pekem($pesan,$ke);
            exit();
            }
        else
            {
				
$rand = rand();
$ekstensi =  array('png','jpg','jpeg','gif');
$filename = $_FILES['foto']['name'];
$ukuran = $_FILES['foto']['size'];
$ext = pathinfo($filename, PATHINFO_EXTENSION);


			//nilai
			$path1 = "../../img/scan/$kd";
			$path2 = "../../img/scan";
			chmod($path1,0777);
			chmod($path2,0777);

			$folderUpload =$sumber.'/img/scan/';

# periksa apakah folder sudah ada
// if (!is_dir($folderUpload)) {
//     # jika tidak maka folder harus dibuat terlebih dahulu
//     mkdir($folderUpload, 0777, $rekursif = true);
// }
 
if(!in_array($ext,$ekstensi) ) {
	header("location:tagihan_atur.php?s=baru&kd=8a44e7e8597a4ea59722efaa68317a4e");
}else{
	if($ukuran < 1044070){		
		$xx = $rand.'_'.$filename;
		
$tmp = $sumber.'/img/scan/'.$rand.'_'.$filename;
$ok  = move_uploaded_file($file_tmp, $tmp);
// if (is_uploaded_file($_FILES['foto']['tmp_name'])) {
// 	$cek = move_uploaded_file ($_FILES['foto']['tmp_name'],$tmp);
// 	if ($cek) {
// 		echo "File berhasil diupload" ;
// 	} else {
// 		echo "File gagal diupload" ;
// 	}
// 	} 

copy($_FILES['foto']['tmp_name'],"../../img/scan/".$rand.'_'.$filename);
		// move_uploaded_file($_FILES['foto']['tmp_name'], $sumber.'/img/scan/'.$rand.'_'.$filename);
		// var_dump($_FILES['foto']);
		// die;
            mysqli_query($koneksi, "INSERT INTO tagihan_atur(kd, tapel, kelas, nominal_tagihan,user_foto) VALUES ".
                            "('$kd', '$e_tapel', '$e_kelas', '$e_nominal_tagihan','$xx')");
            //re-direct
            xloc($filenya);
            exit();
			}
			}
		}
        }
    }
}
//jika hapus
if ($_POST['btnHPS'])
{
//ambil nilai
$i_nis = nosql($_POST['i_nis']);
$getkelas = nosql($_POST['getkelas']);
$gettapel = nosql($_POST['gettapel']);
$jml = nosql($_POST['jml']);
$page = nosql($_POST['page']);
$ke = "$filenya?page=$page";

mysqli_query($koneksi, "DELETE FROM tagihan_siswa ".
                    "WHERE username_siswa = '$i_nis' AND tapel='$gettapel' AND kelas='$getkelas'");
// var_dump($i_nis);
//ambil semua
// for ($i=1; $i<=$jml;$i++)
//     {
//     //ambil nilai
//     $yuk = "item";
//     $yuhu = "$yuk$i";
//     $kd = nosql($_POST["$yuhu"]);
//     //del
//     // mysqli_query($koneksi, "DELETE FROM tagihan_siswa ".
//     //                 "WHERE username_siswa = '$i_nis' AND tapel='$gettapel' AND kelas='$getkelas'");
//     }
//auto-kembali
xloc($filenya);
exit();
}

//jika bayar
if ($_POST['btnBAYAR'])
{
    //ambil nilai
    $e_kd = nosql($_POST['e_kd']);
    $e_getkelas = nosql($_POST['getkelas']);
    $e_gettapel = nosql($_POST['gettapel']);
    $e_kunci = nosql($_POST['e_kunci']);
    $e_jml_bayar = nosql($_POST['e_jml_bayar']);
    $page = nosql($_POST['page']);
    $ke = "$filenya?page=$page";
    $tglhis = date("Y-m-d H:i:s");
        $kd = nosql($_POST["e_kd"]);
    //   echo$kd;
    //   echo'<hr>';
    //   echo$e_jml_bayar;

      mysqli_query($koneksi, "INSERT INTO tagihan_siswa_detail(tagihan_siswa_kd, jml_bayar,tgl_bayar) VALUES ".
      "('$kd', '$e_jml_bayar', '$tglhis')");

    //   var_dump("INSERT INTO tagihan_siswa_detail(kd, tagihan_siswa_kd, jml_bayar,tgl_bayar) VALUES ".
    //   "('','$kd', '$e_jml_bayar', '$tglhis')");
    // var_dump("INSERT INTO tagihan_siswa_detail(kd, tagihan_siswa_kd, jml_bayar,tgl_bayar) VALUES ".
    // "('','$kd', '$e_jml_bayar', '$tglhis')");
        //del
        // mysqli_query($koneksi, "DELETE FROM tagihan_atur ".
        //                 "WHERE kd = '$kd'");
    
//auto-kembali
xloc($filenya.'?tapel='.balikin($e_gettapel).'&kelas='.$e_getkelas);
exit();
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//isi *START
ob_start();
//require
require("../../template/js/jumpmenu.js");
require("../../template/js/checkall.js");
require("../../template/js/swap.js");
?>
<script>
	$(document).ready(function () {
		$('#table-responsive').dataTable({
			"scrollX": true
		});
	});
</script>
<?php
//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika import
if ($s == "import")
	{
	?>
<?php
	}
//jika edit / baru
else if (($s == "baru") OR ($s == "edit"))
	{
	
	}
else
	{
	//jika null
	if (empty($kunci))
		{
            $gettapel=cegah($_GET['tapel']);
            $getkelas=$_GET['kelas'];
            if (!empty($gettapel) AND !empty($getkelas)){   
		$sqlcount = "SELECT tagihan_siswa.kd,tagihan_siswa.nama,tagihan_siswa.tapel,tagihan_siswa.kelas,tagihan_atur.nominal_tagihan,tagihan_siswa.username_siswa FROM tagihan_siswa INNER JOIN tagihan_atur WHERE tagihan_siswa.tapel='$gettapel' AND tagihan_siswa.kelas='$getkelas' AND tagihan_siswa.tagihan_atur_kd=tagihan_atur.kd ORDER BY tagihan_siswa.nama ASC";
        // var_dump("SELECT tagihan_siswa.kd,tagihan_siswa.nama,tagihan_siswa.tapel,tagihan_siswa.kelas,tagihan_atur.nominal_tagihan,tagihan_siswa.username_siswa FROM tagihan_siswa INNER JOIN tagihan_atur WHERE tagihan_siswa.tapel='$gettapel' AND tagihan_siswa.kelas='$getkelas' AND tagihan_siswa.tagihan_atur_kd=tagihan_atur.kd ORDER BY username_siswa ASC");
		// $sqlcount = "SELECT tagihan_siswa.kd,tagihan_siswa.nama,tagihan_siswa.tapel,tagihan_siswa.kelas,tagihan_atur.nominal_tagihan,tagihan_siswa.username_siswa FROM tagihan_siswa INNER JOIN tagihan_atur WHERE tagihan_siswa.tagihan_atur_kd=tagihan_atur.kd ORDER BY username_siswa ASC";
        }else{
//JIKA BELUM MEMILIH TAPEL
$sqlambildatatapelterbaru="SELECT * FROM m_tapel";
$sqlambildatatapelterbaru2 = mysqli_query($koneksi, "$sqlambildatatapelterbaru");
foreach ($sqlambildatatapelterbaru2 as $ambiltapel){
  $tapelterbaru=$ambiltapel['tapel'];
}
// var_dump("SELECT tagihan_siswa.kd,tagihan_siswa.nama,tagihan_siswa.tapel,tagihan_siswa.kelas,tagihan_atur.nominal_tagihan,tagihan_siswa.username_siswa FROM tagihan_siswa INNER JOIN tagihan_atur WHERE tagihan_siswa.tagihan_atur_kd=tagihan_atur.kd AND tagihan_siswa.tapel='$tapelterbaru'  ORDER BY username_siswa ASC");
// die;


            $sqlcount = "SELECT tagihan_siswa.kd,tagihan_siswa.nama,tagihan_siswa.tapel,tagihan_siswa.kelas,tagihan_atur.nominal_tagihan,tagihan_siswa.username_siswa FROM tagihan_siswa INNER JOIN tagihan_atur WHERE tagihan_siswa.tagihan_atur_kd=tagihan_atur.kd AND tagihan_siswa.tapel='$tapelterbaru' ORDER BY tagihan_siswa.nama ASC";
        }
    }
	else
		{
            $gettapel=cegah($_GET['tapel']);
            $getkelas=$_GET['kelas'];
            if (!empty($gettapel) AND !empty($getkelas)){   
        $sqlcount = "SELECT tagihan_siswa.kd,tagihan_siswa.nama,tagihan_siswa.tapel,tagihan_siswa.kelas,tagihan_atur.nominal_tagihan,tagihan_siswa.username_siswa FROM tagihan_siswa INNER JOIN tagihan_atur WHERE tagihan_siswa.nama LIKE '%$kunci%' AND tagihan_siswa.tapel='$gettapel' AND tagihan_siswa.kelas='$getkelas' AND tagihan_siswa.tagihan_atur_kd=tagihan_atur.kd ORDER BY ORDER BY tagihan_siswa.nama ASC";
       
        // var_dump("SELECT tagihan_siswa.kd,tagihan_siswa.nama,tagihan_siswa.tapel,tagihan_siswa.kelas,tagihan_atur.nominal_tagihan,tagihan_siswa.username_siswa FROM tagihan_siswa INNER JOIN tagihan_atur WHERE tagihan_siswa.tapel='$gettapel' AND tagihan_siswa.kelas='$getkelas' AND tagihan_siswa.tagihan_atur_kd=tagihan_atur.kd ORDER BY username_siswa ASC");
		// $sqlcount = "SELECT tagihan_siswa.kd,tagihan_siswa.nama,tagihan_siswa.tapel,tagihan_siswa.kelas,tagihan_atur.nominal_tagihan,tagihan_siswa.username_siswa FROM tagihan_siswa INNER JOIN tagihan_atur WHERE tagihan_siswa.tagihan_atur_kd=tagihan_atur.kd ORDER BY username_siswa ASC";
        }else{
            $sqlcount = "SELECT tagihan_siswa.kd,tagihan_siswa.nama,tagihan_siswa.tapel,tagihan_siswa.kelas,tagihan_atur.nominal_tagihan,tagihan_siswa.username_siswa FROM tagihan_siswa INNER JOIN tagihan_atur WHERE  tagihan_siswa.nama LIKE '%$kunci%' AND tagihan_siswa.tagihan_atur_kd=tagihan_atur.kd ORDER BY tagihan_siswa.nama ASC";
        }
		}
	//query
	$p = new Pager();
	$start = $p->findStart($limit);
  // $p->findStart($gettapel,$getkelas);
	$sqlresult = $sqlcount;
	$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
	$pagelist = $p->pageList($_GET['page'], $pages, $target,$gettapel,$getkelas);
    $data = mysqli_fetch_array($result);
    
    $result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
  $result2 = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
  
  $maxjmlqst=0;
  foreach($result2 as $ambildata){
  // $i_kd = nosql($ambildata['kd']);
  $i_kd2=$ambildata['kd'];
  $ambilmaxtd = mysqli_query($koneksi, "SELECT * FROM tagihan_siswa_detail WHERE tagihan_siswa_kd='$i_kd2' ".
  "ORDER BY kd ASC");
  $jmlqst2 = mysqli_num_rows($ambilmaxtd);
  // echo $jmlqst2 .'<br>';
  if($maxjmlqst<$jmlqst2){
    $maxjmlqst=$jmlqst2;
  }
}

		 
$sqlcaripersen = mysqli_query($koneksi, "SELECT * FROM admin_setting");
foreach($sqlcaripersen as $datapersen){
	$y_persen=$datapersen['persen']; 
}
    echo '<form action="'.$filenya.'" method="post" name="formxx">';
    ?>
    <div class="row">
      <div class="col-md-9">
      <p>Persentase Minimal : 
  <input name="persen" type="number" value="<?=$y_persen;?>" size="20" class="btn btn-default">
  
  <input name="btnSMPpersen" type="submit" value="UPDATE" class="btn btn-primary btn-sm">
  </p>
        <?php 
        
        echo'
	Tahun Pelajaran : 
	<select name="e_tapel" class="btn btn-default">
	<option value="'.balikin($gettapel).'" selected>--'.balikin($gettapel).'--</option>';
	$qst = mysqli_query($koneksi, "SELECT * FROM m_tapel ".
							"ORDER BY tapel DESC");
	$rowst = mysqli_fetch_assoc($qst);
	do
		{
		$st_kd = nosql($rowst['kd']);
		$st_nama1 = balikin($rowst['tapel']);
		echo '<option value="'.$st_nama1.'">'.$st_nama1.'</option>';
		}
	while ($rowst = mysqli_fetch_assoc($qst));
	echo '</select>
	Kelas : 
	<select name="e_kelas" class="btn btn-default">
	<option value="'.$getkelas.'" selected>--'.$getkelas.'--</option>';
	$qst = mysqli_query($koneksi, "SELECT * FROM m_kelas ".
							"ORDER BY kelas ASC");
	$rowst = mysqli_fetch_assoc($qst);
	do
		{
		$st_kd = nosql($rowst['kd']);
		$st_nama1 = balikin($rowst['kelas']);
		echo '<option value="'.$st_nama1.'">'.$st_nama1.'</option>';
		}
	while ($rowst = mysqli_fetch_assoc($qst));
	echo '</select>	
    <input name="btnPilihTapel" type="submit" value="PILIH" class="btn btn-primary  btn-sm">
    </p>';
    ?>
        
  
      <p>
        
<?php echo'<input name="kunci" type="text" value="'.$kunci2.'" size="20" class="btn btn-default" placeholder="Cari Nama ...">
	<input name="getkelascari" type="hidden" value="'.$getkelas.'" size="20" class="btn btn-warning" placeholder="Kata Kunci...">
	<input name="gettapelcari" type="hidden" value="'.$gettapel.'" size="20" class="btn btn-warning" placeholder="Kata Kunci...">';
  ?>
    <input name="btnCARI" type="submit" value="Cari" class="btn btn-primary btn-sm">
    <input name="btnBTL" type="submit" value="Reset" class="btn btn-info  btn-sm">
  
  </p>
    
    </p>	
      </div>
    
    <div class="col-md-3">
    <p>
    <a href="tagihan_atur.php" name="btnBARU" type="submit" value="ENTRI BARU" class="btn btn-info  btn-sm">Atur Tagihan</a>
    <a href="siswa.php" name="btnBARU" type="submit" value="ENTRI BARU" class="btn btn-info  btn-sm">Siswa</a>
  
    <input name="btnIM" type="submit" value="Import" class="btn btn-outline-primary btn-sm">
    <input name="btnEX" type="submit" value="Export" class="btn btn-success btn-sm">
    </p>
      
    </div>
    </div>
    
    <?php
    echo'
	

	<div class="table-responsive">          
    <table class="table" border="0">
	<thead>
  <tr>
	<td width="20" class="text-center"><strong><font color="'.$warnatext.'">BAYAR</font></strong></td>
	<td width="150" class="text-center"><strong><font color="'.$warnatext.'">NIS</font></strong></td>
	<td width="150" class="text-center"><strong><font color="'.$warnatext.'">Nama</font></strong></td>
	<td width="150" class="text-center"><strong><font color="'.$warnatext.'">TAPEL - KELAS </font></strong></td>
	<td width="150" class="text-center"><strong><font color="'.$warnatext.'">NOMINAL TAGIHAN</font></strong></td>
	<td colspan="'.$maxjmlqst.'"><strong><font color="'.$warnatext.'">PEMBAYARAN</font></strong></td>
	<td width="150" class="text-center"><strong><font color="'.$warnatext.'">SISA TAGIHAN</font></strong></td>
	<td width="150" class="text-center"><strong><font color="'.$warnatext.'">%</font></strong></td>
	<td width="20" class="text-center"><strong><font color="'.$warnatext.'"><i class="zmdi zmdi-delete"></i></font></strong></td>
	</tr>
	</thead>
	<tbody>';
foreach ($result as $tampilkan){
  $kurang=0;
    if ($warna_set ==0)
    				{
    				$warna = $warna01;
    				$warna_set = 1;
    				}
    			else
    				{
    				$warna = $warna02;
    				$warna_set = 0;
    				}
    $nomer = $nomer + 1;
                $i_kd = nosql($tampilkan['kd']);
                $i_kd2=$tampilkan['kd'];
    			$i_nis = balikin($tampilkan['username_siswa']);
    			$i_nama = balikin($tampilkan['nama']);
    			$i_tapel = balikin($tampilkan['tapel']);
    			$i_kelas = balikin($tampilkan['kelas']);
    			$i_nominal_tagihan = balikin($tampilkan['nominal_tagihan']);
    			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
    			echo '
			<td>
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default'.$i_kd.'">
            Bayar
          </button>
          <div class="modal fade" id="modal-default'.$i_kd.'">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">'.$i_nis.' - '.$i_nama.' </h4>
              </div>
              <div class="modal-body">
              <form action="'.$filenya.'" method="post" name="formxx">';
              ?>
              <div class="row">
                <div class="col-md-4">
                
                <?php
              echo'
                <p>Jumlah Pembayaran :</p>
                <input name="i_nis" type="hidden" value="'.$i_nis.'" size="30" class="btn-default">
                <input name="getkelas" type="hidden" value="'.$i_kelas.'" size="30" class="btn-default">
                <input name="gettapel" type="hidden" value="'.cegah($i_tapel).'" size="30" class="btn-default">
                <input name="e_kunci" type="hidden" value="'.$kunci.'" size="30" class="btn-default">
                <input name="e_kd" type="hidden" value="'.$i_kd.'" size="30" class="btn-default">';
               ?>
            

                </div>
              
              <div class="col-md-4">
              <p>
             
              <input type="text" value="Rp. 0 ,00" size="30" class="btn-default no-border" id="inputrupiah" readonly>

              </p>
                
              </div>
              </div>
              
            

               
<div class="col-md-12">
	<input name="e_jml_bayar" class="form-control btn-default " type="text" value="0" size="30" id="dengan-rupiah"/>
</div>
<br>

<script type="text/javascript">


	/* Dengan Rupiah */
	var dengan_rupiah = document.getElementById('dengan-rupiah');
	var inputrupiah = document.getElementById('inputrupiah');
	var labelrupiah = document.getElementById('labelrupiah');
	dengan_rupiah.addEventListener('keyup', function(e)
	{
		inputrupiah.value = formatRupiah(this.value, 'Rp. ');
		dengan_rupiah.value = unformatRupiah(this.value);
		labelrupiah.value = formatRupiah(dengan_rupiah.value, 'Rp. ');
	});
	
	dengan_rupiah.addEventListener('keydown', function(event)
	{
		limitCharacter(event);
	});
	
	/* Fungsi */
	function unformatRupiah(bilangan)
	{

		return bilangan;
  };

	/* Fungsi */
	function formatRupiah(bilangan, prefix)
	{
		var number_string = bilangan.replace(/[^,\d]/g, '').toString(),
			split	= number_string.split(','),
			sisa 	= split[0].length % 3,
			rupiah 	= split[0].substr(0, sisa),
			ribuan 	= split[0].substr(sisa).match(/\d{1,3}/gi);
			
		if (ribuan) {
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}
		
		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
		return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah + ' ,00': '');
	}
	
	function limitCharacter(event)
	{
		key = event.which || event.keyCode;
		if ( key != 188 // Comma
			 && key != 8 // Backspace
			 && key != 17 && key != 86 & key != 67 // Ctrl c, ctrl v
			 && (key < 48 || key > 57) // Non digit
			 && key !=97 // Numpad1
			 && key !=98 // Numpad1
			 && key !=99 // Numpad1
			 && key !=100 // Numpad1
			 && key !=101 // Numpad1
			 && key !=102 // Numpad1
			 && key !=103 // Numpad1
			 && key !=104 // Numpad1
			 && key !=105 // Numpad1
			 // Dan masih banyak lagi seperti tombol del, panah kiri dan kanan, tombol tab, dll
			) 
		{
			event.preventDefault();
			return false;
		}
	}
</script>
             
               
               <?php 
                echo'
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input name="page" type="hidden" value="'.$page.'">
                <input name="btnBAYAR" type="submit" value="BAYAR" class="btn btn-primary">
              </div>
            </form>
              <div class="modal-footer">';
              $myqst = mysqli_query($koneksi, "SELECT * FROM tagihan_siswa_detail WHERE tagihan_siswa_kd='$i_kd' ".
              "ORDER BY kd ASC");

            //   var_dump("SELECT * FROM tagihan_siswa_detail WHERE tagihan_siswa_kd='$i_kd' ".
            //   "ORDER BY kd ASC");
            //   $resultmyqst = mysqli_query($koneksi, "$myqst");
                    $nomerpembayaran=0;
              foreach($myqst as $tampilkanrs){
                  $nomerpembayaran++;
                $st_jml_bayar = rupiah(balikin($tampilkanrs['jml_bayar']));
                $st_kd= balikin($tampilkanrs['kd']);
              echo'<form action="'.$filenya.'" method="post" name="formxx">
              <div class="mt-20">
              <p class="pull-left mt-2" data-dismiss="modal">Pembayaran ke-'.$nomerpembayaran.' : '.$st_jml_bayar.'</p>
              <input name="getkelas" type="hidden" value="'.$i_kelas.'" size="30" class="btn-warning">
              <input name="gettapel" type="hidden" value="'.cegah($i_tapel).'" size="30" class="btn-warning">
              <input name="e_kunci" type="hidden" value="'.$kunci.'" size="30" class="btn-warning">
              <input name="getkddetail" type="hidden" value="'.$st_kd.'" size="30" class="btn-warning">
             <p> <button name="btnHAPUSDETAIL" type="submit" value="HAPUS" class="btn btn-danger "  onclick="return confirm(\'Anda yaking ingin menghapus data ini?\')"><i class="zmdi zmdi-delete"></i></button></p>
              </div>
            </form>';


            }

        
              echo'
              </div>
              
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
			</td>
			<td>'.$i_nis.'</td>
			<td>'.$i_nama.'</td>
			<td>'.$i_tapel.' - '.$i_kelas.'</td>
			<td>'.rupiah($i_nominal_tagihan).'</td>
           ';
            $jmlbayarsiswa=0;
            $qst = mysqli_query($koneksi, "SELECT * FROM tagihan_siswa_detail WHERE tagihan_siswa_kd='$i_kd2' ".
            "ORDER BY kd ASC");
            // var_dump("SELECT * FROM tagihan_siswa_detail WHERE tagihan_siswa_kd='$i_kd2' ".
            // "ORDER BY kd ASC");
            
  $kurang=$i_nominal_tagihan;
            $jmlqst = mysqli_num_rows($qst);
            //jika ada, update				
            if (!empty($jmlqst)){
$rowst = mysqli_fetch_assoc($qst);
do
{
$st_kd = nosql($rowst['kd']);
$st_jml_bayar = balikin($rowst['jml_bayar']);
$jmlbayarsiswa+=$st_jml_bayar;
$kurang=$i_nominal_tagihan-$jmlbayarsiswa;
$ulangitd=$maxjmlqst-$jmlqst;
echo '<td>'.rupiah($st_jml_bayar).'</td>';


}

while ($rowst = mysqli_fetch_assoc($qst));

for ($x = 1; $x <= $ulangitd; $x++) {
    echo "<td>-</td>";
  } 
  echo'<td>'.rupiah($kurang).'</td><td>'.round(((100/$i_nominal_tagihan)*($jmlbayarsiswa)),2).'%</td>';
  
            }else{
                echo'<td colspan="'.$maxjmlqst.'">Belum Melakukan Pembayaran</td><td>'.rupiah($kurang).'</td>';
                echo'<td>'.round(((100/$i_nominal_tagihan)*($jmlbayarsiswa)),2).'%</td>';
            }
            
    // <input name="getnis" type="text" value="'.$i_nis.'" size="30" class="btn-warning">
    echo'
    <td>
    
    <a href="hapustagihan.php?id='.$i_nis.'&tapel='.cegah($i_tapel).'&kelas='.$i_kelas.'" class="btn btn-danger" onclick="return confirm(\'Anda yaking ingin menghapus data ini?\')"><i class="zmdi zmdi-delete"></i></a>
    </td>
    </tr>';
}
        // var_dump($sqlcount);
	echo '</tbody>
	  </table>
	  </div>
	<table width="500" border="0" cellspacing="0" cellpadding="3">
	<tr>
	<td>
	<strong><font color="#FF0000">'.$count.'</font></strong> Data. '.$pagelist.'
	<br>
	<input name="jml" type="hidden" value="'.$count.'">
	<input name="s" type="hidden" value="'.$s.'">
	<input name="kd" type="hidden" value="'.$kdx.'">
	<input name="page" type="hidden" value="'.$page.'">
	
	</td>
	</tr>
	</table>
    </form>';
   
	}
//isi
$isi = ob_get_contents();
ob_end_clean();
require("../../inc/niltpl.php");
//null-kan
xclose($koneksi);
exit();
?>