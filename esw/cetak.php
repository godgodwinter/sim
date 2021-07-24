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
?>
<!DOCTYPE html>
<html>
<head>

	<title>CETAK</title>
</head>
<body> <script type="text/javascript">     
    function PrintDiv() {    
       var divToPrint = document.getElementById('divToPrint');
       var popupWin = window.open('', '_blank', 'width=595px,height=842px');
       popupWin.document.open();
       popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
            }
 </script>


         
<h1 align='center'>Rekap Pembayaran <?=$no1_session." ".$nm1_session;?></h1>
<table width='100%' cellspacing='0' border='1'>
	<tr>
	<th>Pembayaran Ke</th>
	<th>Jumlah</th>
	</tr>
	<?php 
		$sqlcarinis = mysqli_query($koneksi, "SELECT * FROM tagihan_siswa ".
		"WHERE username_siswa = '$no1_session' AND kelas = '$kelasnya' AND tapel='$tapelnya'  ".
		"ORDER BY kelas ASC");
		$jmlsqlcarinis = mysqli_num_rows($sqlcarinis);	
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
foreach($sqlcaridetailbayar as $databayar){
	$ike++;
				$jml_bayar=$databayar['jml_bayar'];
				
				$jmlbayarsiswa+=$jml_bayar;
			?>
	<tr>
		<td align="center">Pembayaran ke-<?=$ike;?></td>
		<td align="right"><?=rupiah($jml_bayar);?></td>
	</tr>
	
<?php 
} 
?>
<tr>
<td align="center">Total Bayar</td>
<td align="right"><?=rupiah($jmlbayarsiswa);?></td>
	</tr>
<tr>
<td align="center">Total Tagihan Harus dibayar</td>
<td align="right"><?=round(((100/$i_nominal_tagihan)*($jmlbayarsiswa)),2);?>% - <?=rupiah($i_nominal_tagihan);?></td>
	</tr>
	<tr>
<td align="center">Kurang</td>
<td align="right"><strong><?=rupiah($i_nominal_tagihan-$jmlbayarsiswa);?><strong></td>
	</tr>
<?php
} 
?>
	</table>   

<div id="divToPrint" style="display:none;">
  <div style="width:595px;height:842px;background-color:white;">
	 
  
         
<h1 align='center'>Rekap Pembayaran <?=$no1_session." ".$nm1_session;?></h1>
<table width='100%' cellspacing='0' border='1'>
	<tr>
	<th>Pembayaran Ke</th>
	<th>Jumlah</th>
	</tr>
	<?php 
		$sqlcarinis = mysqli_query($koneksi, "SELECT * FROM tagihan_siswa ".
		"WHERE username_siswa = '$no1_session' AND kelas = '$kelasnya' AND tapel='$tapelnya'  ".
		"ORDER BY kelas ASC");
		$jmlsqlcarinis = mysqli_num_rows($sqlcarinis);	
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
foreach($sqlcaridetailbayar as $databayar){
	$ike++;
				$jml_bayar=$databayar['jml_bayar'];
				
				$jmlbayarsiswa+=$jml_bayar;
			?>
	<tr>
		<td align="center">Pembayaran ke-<?=$ike;?></td>
		<td align="right"><?=rupiah($jml_bayar);?></td>
	</tr>
	
<?php 
} 
?>
<tr>
<td align="center">Total Bayar</td>
<td align="right"><?=rupiah($jmlbayarsiswa);?></td>
	</tr>
<tr>
<td align="center">Total Tagihan Harus dibayar</td>
<td align="right"><?=round(((100/$i_nominal_tagihan)*($jmlbayarsiswa)),2);?>% - <?=rupiah($i_nominal_tagihan);?></td>
	</tr>
	<tr>
<td align="center">Kurang</td>
<td align="right"><strong><?=rupiah($i_nominal_tagihan-$jmlbayarsiswa);?><strong></td>
	</tr>
<?php
} 
?>
	</table> 
	
	
  </div>
</div>
<div>
  <input type="button" value="print" onclick="PrintDiv();" />
</div>


</body>
</html>