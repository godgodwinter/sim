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


?>




              
                  <!-- Info boxes -->
                  
        <!-- <div class="row col-md-offset-1"> -->
        <div class="row ">
          
          <div class="col-md-6 col-sm-6">
          <div class="widget widget-tile-2 bg-warning m-b-30">
              <div class="wt-content p-a-20 p-b-50">
                <div class="wt-title">Jumlah Kelas Di Ubah</div>
                <div class="wt-number"><?php echo $jml_kelas;?></div>
                <div class="wt-text">Diambil dari data Tahun Pelajaran <?=$tapelsebelumnya;?></div>
                
              </div>
              <div class="wt-icon">
                <i class="zmdi zmdi-shopping-basket"></i>
              </div>
              <div class="wt-chart">
                <span id="peity-chart-2">7,3,8,4,4,8,10,3,4,5,9,2,5,1,4,2,9,8,5,9</span>
              </div>
          </div>
          
        </div>
          <div class="col-md-6 col-sm-6">
          <div class="widget widget-tile-2 bg-primary m-b-30">
              <div class="wt-content p-a-20 p-b-50">
                <div class="wt-title">Data Siswa Terubah
                  <span class="t-caret text-success">
                    <i class="zmdi zmdi-caret-up"></i>
                  </span>
                </div>
                <div class="wt-number"><?php echo $jml_siswa;?></div>
                <div class="wt-text">Diambil dari data Tahun Pelajaran <?=$tapelsebelumnya;?></div>
              </div>
              <div class="wt-icon">
                <i class="zmdi zmdi-accounts"></i>
              </div>
              <div class="wt-chart">
                <span id="peity-chart-1">7,3,8,4,4,8,10,3,4,5,9,2,5,1,4,2,9,8,2,1</span>
              </div>
            </div>
          </div>
          </div>

      <div class="row"> 
          <div class="col-md-6 col-sm-6">
          <div class="widget m-b-30"> 
             <h3>Updated :</h3>
             <ul>
               <li>Membuat Tahun Pelajaran baru <?=$tapelaktif;?></li>
               <li>Ubah Kelas di Tahun Pelajaran <?=$tapelsebelumnya;?></li>
                    
	<div class="table-responsive">          
    <table class="table" border="0">
	<thead>
  <tr>
    <th>Data Awal <?=$tapelsebelumnya;?></th>
    <th>Menjadi <?=$tapelaktif;?></th>
  </tr>
  </thead>
  <tbody>
    <?php
    
$sqlquery = "SELECT * FROM m_kelas  ORDER BY `m_kelas`.`kelas` ASC";

$ambildata = mysqli_query($koneksi, $sqlquery);
// var_dump($gettagihan_siswa_kd);
while($data = mysqli_fetch_array($ambildata)){
    
    $kelas=balikin($data['kelas']);
    $kelasbaru=naik_k($data['kelas']);
    ?>
    <tr>
      <td><?=$kelas;?></td>
      <td><?=$kelasbaru;?></td>
    </tr>
    <?php
    }
    ?>
  </tbody>
    </table>
  </div>
               
               <li>Data siswa yang ada, akan mengikuti tahun pelajaran yang aktif</li>
          
               <li>Data Pembayaran Tahun <?=$tapelsebelumnya;?> (Tahun Sebelumnya) Tetap Tersimpan</li>
               <li>Tambah Tagihan Siswa di Tahun Pelajaran <?=$tapelaktif;?> dengan data Pembayaran Kosong</li>
               <li>Tagihan siswa yang ada, akan mengikuti tagihan yang berlaku pada jenjang kelas yang aktif</li>
             </ul>
        <hr>
        
        <h4>Catatan :</h4>
             <ul>
               <li>Backup data Terlebih dahulu dengan cara export data (.xls)</li>
               <li>Setelah anda menyimpan "SOY" maka tidak dapat di kembalikan</li>
               <li>Setelah anda menyimpan "SOY" Tambahkan <b>Kelas baru</b> di menu <b>kelas</b> untuk <b>'kelas X'</b></li>
               <li><b>Alumni tidak ditampilkan </b> di menu <b>kelas</b></li>
               <li>Hubungi administrator atau dev jika ada trouble.</li>
             </ul>
        </div>
        </div>
      </div>

      
<div class="row">
  <div class="col-md-6 col-sm-6">
    <div class="widget m-b-30">
      <a name="btnSOY" href="soy.php" type="submit" value="SoY" class="btn btn-primary btn-xl"><i
          class="zmdi zmdi-refresh"></i> Simpan Start Of Year</a>

    </div>
  </div>
</div>
      <div class="row">  
        <!-- <div class="row">
              <div class="col-md-12 m-b-30">
                <h4 class="m-t-0 m-b-30">Line chart</h4>
                <canvas id="line" style="height: 300px"></canvas>
              </div>
              <div class="col-md-12 m-b-30">
                <h4 class="m-t-0 m-b-30">Bar chart</h4>
                <canvas id="bar" style="height: 300px"></canvas>
              </div>

            </div> -->
         
      






        <!-- /.col -->
        
        <!-- /.col -->




        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- <div class="row">    
        <div class="col-md-12">
          	<div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">VISITOR SEMINGGU INI...</h3>
            </div>
          
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">




					<div class="chart">
					<canvas id="areaChart" style="height:250px"></canvas>
					</div>

				

				
                </div>
               </div>
               </div>
              </div> -->


                
                



            
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