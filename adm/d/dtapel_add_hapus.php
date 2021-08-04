<?php
session_start();

//fungsi - fungsi
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/adm.php");
$tpl = LoadTpl("../../template/admin.html");


nocache;

//nilai
$filenya = "dtapel.php";
$judul = "Data Tahun Pelajaran";
$judulku = "[MASTER]. $judul";
$judulx = $judul;
$s = nosql($_REQUEST['s']);
$pelkd = nosql($_REQUEST['pelkd']);





//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika batal
if ($_POST['btnBTL'])
	{
	//diskonek
	xfree($qbw);
	xclose($koneksi);

	//re-direct
	xloc($ke);
	exit();
	}


//nek edit
if ($s == "edit")
	{
	//nilai
	$pelkd = nosql($_REQUEST['pelkd']);

	//query
	$qnil = mysqli_query($koneksi, "SELECT * FROM m_tapel ".
				"WHERE kd = '$pelkd'");
	$rnil = mysqli_fetch_assoc($qnil);
	$y_tapel = balikin($rnil['tapel']);
	}



//jika hapus
if ($_POST['btnHPS'])
	{
	//nilai
	$jml = nosql($_REQUEST['jml']);

	//ambil semua
	for ($k=1;$k<=$jml;$k++)
		{
		//ambil nilai
		$yuk = "item";
		$yuhu = "$yuk$k";
		$kd = nosql($_POST["$yuhu"]);

		//del data
		mysqli_query($koneksi, "DELETE FROM m_tapel ".
				"WHERE kd = '$kd'");
		}

	//diskonek
	xfree($qbw);
	xclose($koneksi);

	//auto-kembali
	xloc($filenya);
	exit();
	}


//jika simpan
if ($_POST['btnSMP'])
	{
	//nilai
	$s = nosql($_POST['s']);
	$pelkd = nosql($_POST['pelkd']);
	$tapel = cegah($_POST['tapel']);


	//nek null
	if (empty($tapel))
		{
		//diskonek
		xfree($qbw);
		xclose($koneksi);

		//re-direct
		$pesan = "Input Tidak Lengkap. Harap Diulangi...!";
		pekem($pesan,$filenya);
		exit();
		}
	else
		{
		//nek edit
		if ($s == "edit")
			{
			//cek
			$qcc = mysqli_query($koneksi, "SELECT * FROM m_tapel ".
						"WHERE tapel = '$tapel'");
			$rcc = mysqli_fetch_assoc($qcc);
			$tcc = mysqli_num_rows($qcc);

			//nek lebih dari 1
			if ($tcc > 1)
				{
				//diskonek
				xfree($qcc);
				xfree($qbw);
				xclose($koneksi);

				//re-direct
				$pesan = "Ditemukan Duplikasi tapel. Silahkan Diganti...!";
				pekem($pesan,$ke);
				exit();
				}
			else
				{
				//update
				mysqli_query($koneksi, "UPDATE m_tapel SET tapel = '$tapel', ".
								"postdate = '$today' ".
								"WHERE kd = '$pelkd'");

				//diskonek
				xfree($qcc);
				xfree($qbw);
				xclose($koneksi);

				//re-direct
				xloc($ke);
				exit();
				}
			}





		//nek baru
		if (empty($s))
			{
			//cek
			$qcc = mysqli_query($koneksi, "SELECT * FROM m_tapel ".
						"WHERE tapel = '$tapel'");
			$rcc = mysqli_fetch_assoc($qcc);
			$tcc = mysqli_num_rows($qcc);

			//nek ada
			if ($tcc != 0)
				{
				//diskonek
				xfree($qcc);
				xfree($qbw);
				xclose($koneksi);

				//re-direct
				$pesan = "Ditemukan Duplikasi tapel. Silahkan Diganti...!";
				pekem($pesan,$ke);
				exit();
				}
			else
				{
				//insert data
				mysqli_query($koneksi, "INSERT INTO m_tapel(kd, tapel, postdate) VALUES ".
						"('$x', '$tapel', '$today')");

				//diskonek
				xfree($qcc);
				xfree($qbw);
				xclose($koneksi);

				//re-direct
				xloc($ke);
				exit();
				}
			}
		}
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//isi *START
ob_start();


//js
require("../../inc/js/jumpmenu.js");
require("../../inc/js/swap.js");
require("../../inc/js/checkall.js");


//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo '<form name="formx" method="post" action="'.$filenya.'">
<table width="100%" height="300" border="0" cellspacing="3" cellpadding="0">
<tr valign="top">
<td>';
?>


<div class="row  col-md-offset-1">
	<div class="col-md-11 col-sm-11">
<?php

echo'<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr valign="top">
<td>
<p>
<div class="form-group">
<label class="col-sm-3 control-label" for="form-control-3">Tahun Pelajaran</label>
<div class="col-sm-9">
  <input  name="tapel" id="form-control-3" class="form-control b-a-2" type="text"  value="'.$y_tapel.'" size="20" >
</div>
</div>
</p>

<p>
<input name="btnBTL" type="submit" value="Batal" class="btn btn-info btn-pill m-w-120">
<input name="btnSMP" type="submit" value="Simpan" class="btn btn-primary btn-pill m-w-120">
</p>
</td>
</tr>
</table>
<br>';

?>

</div>
</div>
<div class="row">
<?php


//query
$qdata = mysqli_query($koneksi, "SELECT * FROM m_tapel ".
						"ORDER BY tapel ASC");
$rdata = mysqli_fetch_assoc($qdata);
$tdata = mysqli_num_rows($qdata);

//nek ada
if ($tdata != 0)
	{
		
	echo '<div class="table-responsive">          
	  <table class="table table-hover" >
	    <thead>

		<tr>
		<th style="width: 32px"></th>
		<th style="width: 32px"></th>
		<td valign="top"><strong>Tahun Pelajaran</strong></td>
		</tr>


	</thead>
    <tbody>';
		

	do
  		{
		if ($warna_set ==0)
			{
			$warna ='FFFFFF';
			$warna_set = 1;
			}
		else
			{
			$warna ='FFFFFF';
			$warna_set = 0;
			}

		$nomer = $nomer + 1;

		$i_kd = nosql($rdata['kd']);
		$i_tapel = balikin($rdata['tapel']);

		echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
		echo '<td width="1"><input name="kd'.$nomer.'" type="hidden" value="'.$i_kd.'">
		<input type="checkbox" name="item'.$nomer.'" value="'.$i_kd.'">
		</td>
		<td width="1">
		<a href="'.$filenya.'?s=edit&pelkd='.$i_kd.'" type="button" class="btn btn-warning btn-sm"> <i class="zmdi zmdi-edit"></i></a>
		</td>
		<td valign="top">
		'.$i_tapel.'
		</td>
		</tr>';
  		}
	while ($rdata = mysqli_fetch_assoc($qdata));

	echo '</tbody>
	  </table>
	  </div>


	<table width="100%" border="0" cellspacing="0" cellpadding="3">
	<tr>
	<td>
	<input type="button" name="Button" value="SEMUA" onClick="checkAll('.$tdata.')" class="btn btn-default  btn-sm">
	<input name="btnBTL" type="reset" value="BATAL" class="btn btn-info btn-sm">
	<input name="btnHPS" type="submit" value="HAPUS" class="btn btn-danger btn-sm">
	<input name="jml" type="hidden" value="'.$tdata.'">
	<input name="s" type="hidden" value="'.$s.'">
	<input name="pelkd" type="hidden" value="'.$pelkd.'">
	<input name="total" type="hidden" value="'.$tdata.'">
	<font color="#FF0000"><strong>'.$tdata.'</strong></font> Data.
	</td>
	</tr>
	</table>';
	}
else
	{
	echo '<p>
	<font color="red"><strong>TIDAK ADA DATA. Silahkan Entry Dahulu...!!</strong></font>
	</p>';
	}

echo '</td>
</tr>
</table>


</form>
<br>
<br>
<br>
</div>
</div>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//isi
$isi = ob_get_contents();
ob_end_clean();


require("../../inc/niltpl.php");



//diskonek
xfree($qbw);
xclose($koneksi);
exit();
?>