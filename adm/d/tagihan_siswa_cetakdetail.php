<?php
session_start();

require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");


function rupiah($angka){
	$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
	return $hasil_rupiah;
}

nocache;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Tagihan Siswa </title>
    <style>
      table{
    width:100%;
    border-collapse:collapse;
    text-align:center;
    border:1px solid #000;
    margin:4px 4px 4px 4px;
    font-size:12px;
    }
    </style>
</head>
<body>
 
	<center>
 
		<h2>LAPORAN TAGIHAN SISWA</h2>
 
	</center>
 
 
	<table border="1" style="width: 100%">
		<tr>
			<th width="1%">No</th>
			<th>Jumlah Bayar</th>
			<th>Tanggal Bayar</th>
		</tr>
		<?php 
		$no = 1;
        
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
        
    $gettagihan_siswa_kd=cegah($_GET['tagihan_siswa_kd']);
    $sqlcount = "SELECT * from tagihan_siswa_detail WHERE tagihan_siswa_kd='$gettagihan_siswa_kd' ORDER BY tgl_bayar ASC";

        $ambilmaxtd = mysqli_query($koneksi, $sqlcount);
        var_dump($gettagihan_siswa_kd);
		while($data = mysqli_fetch_array($ambilmaxtd)){
            
            $i_kd2=$data['kd'];
            $i_jmlbayar = balikin($data['jml_bayar']);
            $i_tgl_bayar= balikin($data['tgl_bayar']);
		?>
		<tr>
			<td><?php echo $no++; ?></td>   
			<td width="10%"><?php echo rupiah($i_jmlbayar); ?></td>
			<td width="10%"><?php echo $i_tgl_bayar; ?></td>
          

		</tr>
		<?php 
		}
		?>
	</table>
 
	<script>
		window.print();
	</script>
 
</body>
</html>