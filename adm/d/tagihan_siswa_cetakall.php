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
			<th>Nama</th>
			<th>Kelas</th>
			<th>Nominal Tagihan</th>
			<th>Pembayaran</th>
			<th>Sisa Tagihan</th>
			<th width="5%">Persentase</th>
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
        
        $ambilmaxtd = mysqli_query($koneksi, $sqlcount);
        // var_dump($ambilmaxtd);
		while($data = mysqli_fetch_array($ambilmaxtd)){
            
            $i_kd2=$data['kd'];
            $i_nis = balikin($data['username_siswa']);
            $i_nama = balikin($data['nama']);
            $i_tapel = balikin($data['tapel']);
            $i_kelas = balikin($data['kelas']);
            $i_nominal_tagihan = balikin($data['nominal_tagihan']);
		?>
		<tr>
			<td><?php echo $no++; ?></td>   
			<td align="left" width="20%">&nbsp;<?php echo $data['username_siswa']; ?> - <?php echo $data['nama']; ?></td>
			<td width="10%"><?php echo $i_kelas; ?></td>
			<td width="10%"><?php echo rupiah($i_nominal_tagihan); ?></td>
            <?php
            
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

}

while ($rowst = mysqli_fetch_assoc($qst));

  echo'<td>'.rupiah($jmlbayarsiswa).'</td><td>'.rupiah($kurang).'</td><td>'.round(((100/$i_nominal_tagihan)*($jmlbayarsiswa)),2).'%</td>';
  
            }else{
                echo'<td colspan="'.$maxjmlqst.'">0</td><td>'.rupiah($kurang).'</td>';
                echo'<td>'.round(((100/$i_nominal_tagihan)*($jmlbayarsiswa)),2).'%</td>';
            }
            
            ?>

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