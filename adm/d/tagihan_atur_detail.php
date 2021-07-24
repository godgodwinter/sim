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
$filenya = "tagihan_atur_detail.php";
$judul = "Detail Tagihan Siswa";
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
                        mysqli_query($koneksi, "INSERT INTO tagihan_atur(kd,tapel, kelas, nominal_Tagihan) VALUES ".
                                        "('','$i_tapel', '$i_kelas', '$i_nominal_tagihan_int')");
						}
						// var_dump("INSERT INTO tagihan_atur(kd,tapel, kelas, nominal_Tagihan) VALUES ".
						// "('','$i_tapel', '$i_kelas', '$i_nominal_tagihan_int')");
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
            mysqli_query($koneksi, "INSERT INTO tagihan_atur(kd, tapel, kelas, nominal_tagihan) VALUES ".
                            "('$kd', '$e_tapel', '$e_kelas', '$e_nominal_tagihan')");
            //re-direct
            xloc($filenya);
            exit();
            }
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
    }
//auto-kembali
xloc($filenya);
exit();
}
//jika bayar
if ($_POST['btnBAYAR'])
{
    //ambil nilai
    $e_kd = nosql($_POST['e_kd']);
    $e_kunci = nosql($_POST['e_kunci']);
    $e_jml_bayar = nosql($_POST['e_jml_bayar']);
    $page = nosql($_POST['page']);
    $ke = "$filenya?page=$page";
    $tglhis = date("Y-m-d H:i:s");
        $kd = nosql($_POST["e_kd"]);
    //   echo$kd;
    //   echo'<hr>';
    //   echo$e_jml_bayar;

      mysqli_query($koneksi, "INSERT INTO tagihan_siswa_detail(kd, tagihan_siswa_kd, jml_bayar,tgl_bayar) VALUES ".
      "('','$kd', '$e_jml_bayar', '$tglhis')");
    // var_dump("INSERT INTO tagihan_siswa_detail(kd, tagihan_siswa_kd, jml_bayar,tgl_bayar) VALUES ".
    // "('','$kd', '$e_jml_bayar', '$tglhis')");
        //del
        // mysqli_query($koneksi, "DELETE FROM tagihan_atur ".
        //                 "WHERE kd = '$kd'");
    
//auto-kembali
xloc($filenya.'?kunci='.$e_kunci);
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
	?>
<div class="row">
	<div class="col-md-6">
		<?php
	echo '<form action="'.$filenya.'" method="post" name="formx2">
	<p>
	TAHUN PELAJARAN : 
	<select name="e_tapel" class="btn btn-warning" required>
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
	KELAS : 
	<select name="e_kelas" class="btn btn-warning" required>
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
	echo '</select>	</p>
	<p>
	NOMINAL TAGIHAN : 
	<br>
	<input name="e_nominal_tagihan" type="text" value="'.$e_nominal_tagihan.'" size="30" class="btn-warning">
	</p>
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
		$sqlcount = "SELECT tagihan_siswa.kd,tagihan_siswa.nama,tagihan_siswa.tapel,tagihan_siswa.kelas,tagihan_atur.nominal_tagihan,tagihan_siswa.username_siswa FROM tagihan_siswa INNER JOIN tagihan_atur WHERE tagihan_siswa.tagihan_atur_kd=tagihan_atur.kd ORDER BY username_siswa ASC";
		}
	else
		{
		$sqlcount = "SELECT tagihan_siswa.kd,tagihan_siswa.nama,tagihan_siswa.tapel,tagihan_siswa.kelas,tagihan_atur.nominal_tagihan,tagihan_siswa.username_siswa FROM tagihan_siswa INNER JOIN tagihan_atur WHERE tagihan_siswa.tagihan_atur_kd=tagihan_atur.kd AND tagihan_atur_kd='$kunci' ORDER BY username_siswa ASC";
		}
	//query
	$p = new Pager();
	$start = $p->findStart($limit);
	$sqlresult = $sqlcount;
	$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
	$pages = $p->findPages($count, $limit);
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

	$pagelist = $p->pageList($_GET['page'], $pages, $target);
  $data = mysqli_fetch_array($result); 

  

   
 

    echo '<form action="'.$filenya.'" method="post" name="formxx">
	
	<br>
	<div class="table-responsive">          
	<table class="table" border="1">
	<thead>
	<tr valign="top" bgcolor="'.$warnaheader.'">
	<td width="20">&nbsp;</td>
	<td width="20">&nbsp;</td>
	<td width="150"><strong><font color="'.$warnatext.'">NIS</font></strong></td>
	<td width="150"><strong><font color="'.$warnatext.'">Nama</font></strong></td>
	<td width="150"><strong><font color="'.$warnatext.'">TAPEL - KELAS </font></strong></td>
	<td width="150"><strong><font color="'.$warnatext.'">NOMINAL TAGIHAN</font></strong></td>
	<td colspan="'.$maxjmlqst.'"><strong><font color="'.$warnatext.'">PEMBAYARAN</font></strong></td>
	<td width="150"><strong><font color="'.$warnatext.'">%</font></strong></td>
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
            $i_kd2=$data['kd'];
			$i_nis = balikin($data['username_siswa']);
			$i_nama = balikin($data['nama']);
			$i_tapel = balikin($data['tapel']);
			$i_kelas = balikin($data['kelas']);
			$i_nominal_tagihan = balikin($data['nominal_tagihan']);
			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>
			<input type="checkbox" name="item'.$nomer.'" value="'.$i_kd.'">
	        </td>
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
              <form action="'.$filenya.'" method="post" name="formxx">
                <p>Jumlah Pembayaran</p>
                <input name="e_kunci" type="hidden" value="'.$kunci.'" size="30" class="btn-warning">
                <input name="e_kd" type="hidden" value="'.$i_kd.'" size="30" class="btn-warning">
                <input name="e_jml_bayar" type="text" value="0" size="30" class="btn-warning">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input name="page" type="hidden" value="'.$page.'">
                <input name="btnBAYAR" type="submit" value="BAYAR" class="btn btn-primary">
              </div>
            </div>
            </form>
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
            
            $jmlqst = mysqli_num_rows($qst);
            //jika ada, update				
            if (!empty($jmlqst)){
$rowst = mysqli_fetch_assoc($qst);
do
{

$st_kd = nosql($rowst['kd']);
$st_jml_bayar = balikin($rowst['jml_bayar']);
$jmlbayarsiswa+=$st_jml_bayar;
$ulangitd=$maxjmlqst-$jmlqst;
echo '<td>'.rupiah($st_jml_bayar).'</td>';

}

while ($rowst = mysqli_fetch_assoc($qst));
            }else{
                echo'<td colspan="'.$maxjmlqst.'">Belum Melakukan Pembayaran</td>';
            }
            for ($x = 1; $x <= $ulangitd; $x++) {
              echo "<td>-</td>";
            } 
            echo'<td>'.round(((100/$i_nominal_tagihan)*($jmlbayarsiswa)),2).'%</td>
	        </tr>';
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
	<input name="btnALL" type="button" value="SEMUA" onClick="checkAll('.$count.')" class="btn btn-primary">
	<input name="btnBTL" type="reset" value="BATAL" class="btn btn-warning">
	<input name="btnHPS" type="submit" value="HAPUS" class="btn btn-danger">
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