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
nocache;
//nilai
$filenya = "tagihan_atur.php";
$judul = "Pengaturan Tagihan Siswa";
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
                        mysqli_query($koneksi, "INSERT INTO tagihan_atur(tapel, kelas, nominal_Tagihan,user_foto) VALUES ".
                                        "('$i_tapel', '$i_kelas', '$i_nominal_tagihan_int','')");
						}
						// var_dump("INSERT INTO tagihan_atur(tapel, kelas, nominal_Tagihan,user_foto) VALUES ".
						// "('','$i_tapel', '$i_kelas', '$i_nominal_tagihan_int','')");
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
//jika cari
if ($_POST['btnCARI'])
{
//nilai
$kunci = cegah($_POST['kunci']);
//re-direct
$ke = "$filenya?kunci=$kunci";
xloc($ke);
exit();
}
//nek entri baru
if ($_POST['btnBARU'])
{
//re-direct
//$ke = "$filenya?s=baru&kd=$x";
$ke = "$filenya?s=baru&kd=$x";
xloc($ke);
exit();
}

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
$username_guru = cegah($_POST['username_guru']);
//mengambil nama guru jika ada masukkan 
$sqlcarinamaguru = mysqli_query($koneksi, "SELECT * FROM m_user WHERE usernamex='$username_guru'");
foreach($sqlcarinamaguru as $ambilnamaguru){
	$nama=$ambilnamaguru['nama']; 
}
// var_dump($nama);
// die;
// $nama = cegah($_POST['nama']);
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
                        "username_guru = '$username_guru', ".
                        "nama = '$nama', ".
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
		
		
			if(!empty($_FILES['foto']['name'])){
			
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
			
				// if()
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
	// echo'test';
	// var_dump($_FILES['foto']['name']);
	// exit;
	// mysqli_query($koneksi, "INSERT INTO tagihan_atur(tapel, kelas, nominal_tagihan) VALUES ".
	// "('$e_tapel', '$e_kelas', '$e_nominal_tagihan')");
	// header("location:tagihan_atur.php?s=baru&kd=8a44e7e8597a4ea59722efaa68317a4e");
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
            mysqli_query($koneksi, "INSERT INTO tagihan_atur(tapel, kelas, nominal_tagihan,user_foto,username_guru,nama) VALUES ".
							"('$e_tapel', '$e_kelas', '$e_nominal_tagihan','$xx','$username_guru','$nama')");
			// var_dump("INSERT INTO tagihan_atur(tapel, kelas, nominal_tagihan,user_foto) VALUES ".
			// "('$e_tapel', '$e_kelas', '$e_nominal_tagihan','$xx')");				
            //re-direct
            xloc($filenya);
            exit();
			}
			}
		}
		}else{
		// 	echo'test';
		// var_dump($_FILES['foto']['name']);
		// exit;
		mysqli_query($koneksi, "INSERT INTO tagihan_atur(tapel, kelas, nominal_tagihan,user_foto,username_guru,nama) VALUES ".
		"('$e_tapel', '$e_kelas', '$e_nominal_tagihan','','$username_guru','$nama')");
		xloc($filenya);
		exit();
	}
		//jiika foto kosong
	}

    }
}
//jika hapus
if ($_POST['btnHPS'])
{
//ambil nilai
$jml = nosql($_POST['jml']);
$page = nosql($_POST['page']);
$ke = "$filenya?page=$page";
//ambil semua
for ($i=1; $i<=$jml;$i++)
    {
    //ambil nilai
    $yuk = "item";
    $yuhu = "$yuk$i";
	$kd = nosql($_POST["$yuhu"]);
    //del
    mysqli_query($koneksi, "DELETE FROM tagihan_atur ".
					"WHERE kd = '$kd'");
					// var_dump("DELETE FROM tagihan_atur ".
					// "WHERE kd = '$kd'");
    }
//auto-kembali
xloc($filenya);
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
<div class="row">
	<div class="col-md-12">
		<?php
	echo '<form action="'.$filenya.'" method="post" enctype="multipart/form-data" name="formxx2">
	<p>
		<input name="filex_xls" type="file" size="30" class="btn btn-warning">
	</p>
	<p>
		<input name="btnBTL" type="submit" value="BATAL" class="btn btn-info">
		<input name="btnIMX" type="submit" value="IMPORT >>" class="btn btn-danger">
	</p>
	</form>';	
	?>
	</div>
</div>
<?php
	}
//jika edit / baru
else if (($s == "baru") OR ($s == "edit"))
	{
	$kdx = nosql($_REQUEST['kd']);
	$qx = mysqli_query($koneksi, "SELECT * FROM tagihan_atur ".
						"WHERE kd = '$kdx'");
	$rowx = mysqli_fetch_assoc($qx);
	$e_tapel = balikin($rowx['tapel']);
	$e_kelas = balikin($rowx['kelas']);
	$e_nominal_tagihan = balikin($rowx['nominal_tagihan']);
	$username_guru = balikin($rowx['username_guru']);
	$nama = balikin($rowx['nama']);
	?>
<div class="row">
	<div class="col-md-6">
		<?php
	echo '<form action="'.$filenya.'" method="post" enctype="multipart/form-data" name="formx2">
	<p>
	<div class="form-group">
	<label class="col-sm-3 control-label" for="form-control-3">Tahun Pelajaran</label>
	<div class="col-sm-9">
	  
	<select name="e_tapel" class="btn btn-default form-control" >
	<option value="'.$e_tapel.'" selected>--'.$e_tapel.'--</option>';
	
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
	</div>
	</div>

	</p>
	<br>
	<br>
	
	
	<p>
	<div class="form-group">
	<label class="col-sm-3 control-label" for="form-control-3">Kelas</label>
	<div class="col-sm-9">
	  
	<select name="e_kelas" class="btn btn-default form-control" >
	<option value="'.$e_kelas.'" selected>--'.$e_kelas.'--</option>';
	
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
	</div>
	</div>

	</p>
	<br>
	<br>
	
	<p>
	<div class="form-group">
	<label class="col-sm-3 control-label" for="form-control-3">Nominal Tagihan</label>
	<div class="col-sm-9">
	  <input  name="e_nominal_tagihan" id="form-control-3" class="form-control b-a-2" type="number"  value="'.$e_nominal_tagihan.'" >
	</div>
	</div>

	</p>
	<br>
	<br>
	<p>
	<div class="form-group">
	<label class="col-sm-3 control-label" for="form-control-3">Wali Kelas</label>
	<div class="col-sm-9">
	  
	<select name="username_guru" class="btn btn-default form-control" required >
	<option value="'.$username_guru.'" selected>--'.$nama.'--</option>';
	
	$qst = mysqli_query($koneksi, "SELECT * FROM m_user WHERE tipe='GURU' ".
	"ORDER BY nama ASC");
	$rowst = mysqli_fetch_assoc($qst);
	do
		{
		$usernamex = nosql($rowst['usernamex']);
		$nama = nosql($rowst['nama']);
		echo '<option value="'.$usernamex.'">'.$nama.'</option>';
		}
	while ($rowst = mysqli_fetch_assoc($qst));
	
	echo '</select>
	</div>
	</div>

	</p>
	<br>
	<br>
	<p>
		<div class="form-group">
				<label>Foto :</label>
				<input type="file" name="foto" >
				<p style="color: red">Ekstensi yang diperbolehkan .png | .jpg | .jpeg | .gif</p>
			</div>	
		<br>
	<p>
	<input name="jml" type="hidden" value="'.$count.'">
	<input name="s" type="hidden" value="'.$s.'">
	<input name="kd" type="hidden" value="'.$kdx.'">
	<input name="page" type="hidden" value="'.$page.'">
	<input name="btnSMP" type="submit" value="SIMPAN" class="btn btn-danger">
	<input name="btnBTL" type="submit" value="BATAL" class="btn btn-info">
	</p>
	</form>';
	?>
	</div>
</div>
<?php
	}
else
	{
	//jika null
	if (empty($kunci))
		{
		$sqlcount = "SELECT * FROM tagihan_atur ".
						"ORDER BY tapel ASC";
		}
	else
		{
		$sqlcount = "SELECT * FROM tagihan_atur ".
						"WHERE kode LIKE '%$kunci%' ".
						"OR tapel LIKE '%$kunci%' ".
						"OR kelas LIKE '%$kunci%' ".
						"ORDER BY tapel ASC";
		}
	//query
	$p = new Pager();
	$start = $p->findStart($limit);
	$sqlresult = $sqlcount;
	$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysqli_fetch_array($result);
		 
$sqlcaripersen = mysqli_query($koneksi, "SELECT * FROM admin_setting");
foreach($sqlcaripersen as $datapersen){
	$y_persen=$datapersen['persen']; 
}
    echo '<form action="'.$filenya.'" method="post" name="formxx">';
	?>
	<div class="row">
		<div class="col-md-8">
			
	<p>Persentase Minimal : 
<input name="persen" type="number" value="<?=$y_persen;?>" size="20" class="btn btn-default">

<input name="btnSMPpersen" type="submit" value="UPDATE" class="btn btn-primary btn-sm">

		<p>
			
	<input name="kunci" type="text" value="<?php echo $kunci2; ?>" size="20" class="btn btn-default" placeholder="Kata Kunci...">
	<input name="btnCARI" type="submit" value="Cari" class="btn btn-primary btn-sm">
	<input name="btnBTL" type="submit" value="Reset" class="btn btn-info  btn-sm">

</p>
	
	</p>	
		</div>
	
	<div class="col-md-4">
	<p>
	<a href="guru.php" name="btnBARU" type="submit" value="ENTRI BARU" class="btn btn-info  btn-sm">Wali Kelas</a>

	<input name="btnBARU" type="submit" value="Tambah" class="btn btn-primary  btn-sm">
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
	<th style="width: 32px"></th>
	<th style="width: 32px"></th>
	<td width="150"><strong><font color="'.$warnatext.'">TAPEL</font></strong></td>
	<td width="150"><strong><font color="'.$warnatext.'">KELAS</font></strong></td>
	<td ><strong><font color="'.$warnatext.'">NOMINAL TAGIHAN</font></strong></td>
	<td ><strong><font color="'.$warnatext.'">WALI KELAS</font></strong></td>
	
	</tr>
	</thead>
	<tbody>';
	if ($count != 0)
		{
		do 
			{
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
			$i_kd = nosql($data['kd']);
			$i_tapel = balikin($data['tapel']);
			$i_kelas = balikin($data['kelas']);
			$i_nominal_tagihan = balikin($data['nominal_tagihan']);
			$username_guru = balikin($data['username_guru']);
			$nama = balikin($data['nama']);
			if($i_kd==0){

			}else{	
			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>
			<input type="checkbox" name="item'.$nomer.'" value="'.$i_kd.'">
	        </td>
			<td>
			<a href="'.$filenya.'?s=edit&page='.$page.'&kd='.$i_kd.'"  class="btn btn-warning btn-sm"><i class="zmdi zmdi-edit"></i></a>
			</td>
			<td>'.$i_tapel.'</td>
			<td>'.$i_kelas.'</td>
			<td>'.rupiah($i_nominal_tagihan).'</td>
			<td>'.$nama.'</td>
			
	        </tr>';
		
			//<td>a href="tagihan_atur_detail.php?kunci='.$i_kd.'" class="btn btn-danger">Detail</a></td>	
		}
			}
		while ($data = mysqli_fetch_assoc($result));
		}
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
	<input name="btnALL" type="button" value="SEMUA" onClick="checkAll('.$count.')" class="btn btn-default  btn-sm">
	<input name="btnBTL" type="reset" value="BATAL" class="btn btn-info btn-sm">
	<input name="btnHPS" type="submit" value="HAPUS" class="btn btn-danger btn-sm">
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