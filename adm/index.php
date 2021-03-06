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
$judul = "Beranda";
$judulku = "$judul  [$adm_session]";







//jml user
$qyuk = mysqli_query($koneksi, "SELECT * FROM m_user");
$jml_siswa = mysqli_num_rows($qyuk);


//jml user siswa alumni
$jml_alumni=0;
$sqlqueryalumni= "SELECT * FROM m_user ORDER BY nama ASC";
	$ambildataalumni = mysqli_query($koneksi, $sqlqueryalumni);
	// var_dump($gettagihan_siswa_kd);
			while($dataalumni = mysqli_fetch_array($ambildataalumni)){
          $kelasnamaalumni=$dataalumni['kelas_nama'];
          $cekkelas=explode(" ",$kelasnamaalumni);
          // echo$cekkelas[0].'-';
            if(($cekkelas[0]==='Alumni')){
                $jml_alumni+=1;
            }
      }
//jml user siswa aktif
$jml_siswa_aktif = $jml_siswa-$jml_alumni;

//jml kelas
$qyuk = mysqli_query($koneksi, "SELECT * FROM m_kelas");
$jml_kelas = mysqli_num_rows($qyuk);



//jml mapel
$qyuk = mysqli_query($koneksi, "SELECT * FROM m_mapel");
$jml_mapel = mysqli_num_rows($qyuk);







//rekap masuk
$qyuk = mysqli_query($koneksi, "SELECT * FROM guru_mapel_log ".
						"WHERE round(DATE_FORMAT(postdate, '%d')) = '$tanggal' ".
						"AND round(DATE_FORMAT(postdate, '%m')) = '$bulan' ".
						"AND round(DATE_FORMAT(postdate, '%Y')) = '$tahun' ".
						"ORDER BY postdate ASC");
$ryuk = mysqli_fetch_assoc($qyuk);
$rekap_masuk = mysqli_num_rows($qyuk);



//tapelaktif
$sqlquery = "SELECT * FROM admin_setting WHERE id='1'";

$ambildata = mysqli_query($koneksi, $sqlquery);
// var_dump($gettagihan_siswa_kd);
while($data = mysqli_fetch_array($ambildata)){
    
    $tapelsebelumnya=balikin($data['tapel']);
    $tapelaktif=naik_t(balikin($data['tapel']));
}

$judul = "Tahun Pelajaran '".$tapelsebelumnya."'";



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


<script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
    var areaChartCanvas = $('#areaChart').get(0).getContext('2d')
    // This will get the first returned node in the jQuery collection.
    var areaChart = new Chart(areaChartCanvas)

    var areaChartData = {
      labels: [ < ? php echo $isi_data1; ? > ],
      datasets: [{
        label: 'Visitor Seminggu ini',
        fillColor: 'rgba(60,141,188,0.9)',
        strokeColor: 'rgba(60,141,188,0.8)',
        pointColor: '#3b8bba',
        pointStrokeColor: 'rgba(60,141,188,1)',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data: [ < ? php echo $isi_data2; ? > ]
      }]
    }

    var areaChartOptions = {
      //Boolean - If we should show the scale at all
      showScale: true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines: false,
      //String - Colour of the grid lines
      scaleGridLineColor: 'rgba(0,0,0,.05)',
      //Number - Width of the grid lines
      scaleGridLineWidth: 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines: true,
      //Boolean - Whether the line is curved between points
      bezierCurve: true,
      //Number - Tension of the bezier curve between points
      bezierCurveTension: 0.3,
      //Boolean - Whether to show a dot for each point
      pointDot: false,
      //Number - Radius of each point dot in pixels
      pointDotRadius: 4,
      //Number - Pixel width of point dot stroke
      pointDotStrokeWidth: 1,
      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius: 20,
      //Boolean - Whether to show a stroke for datasets
      datasetStroke: true,
      //Number - Pixel width of dataset stroke
      datasetStrokeWidth: 2,
      //Boolean - Whether to fill the dataset with a color
      datasetFill: true,
      //String - A legend template
      legendTemplate: '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio: true,
      //Boolean - whether to make the chart responsive to window resizing
      responsive: true
    }

    //Create the line chart
    areaChart.Line(areaChartData, areaChartOptions)


  })
</script>



<!-- Info boxes -->

<!-- <div class="row col-md-offset-1"> -->
<div class="row ">

<div class="col-md-4 col-sm-4">
    <div class="widget widget-tile-2 bg-warning m-b-30">
      <div class="wt-content p-a-20 p-b-50">
        <div class="wt-title">Kelas</div>
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
  <div class="col-md-4 col-sm-4">
    <div class="widget widget-tile-2 bg-primary m-b-30">
      <div class="wt-content p-a-20 p-b-50">
        <div class="wt-title">Siswa Aktif
          <span class="t-caret text-success">
            <i class="zmdi zmdi-caret-up"></i>
          </span>
        </div>
        <div class="wt-number"><?php echo $jml_siswa_aktif;?></div>
        <div class="wt-text">Updated today at 14:57</div>
      </div>
      <div class="wt-icon">
        <i class="zmdi zmdi-accounts"></i>
      </div>
      <div class="wt-chart">
        <span id="peity-chart-1">7,3,8,4,4,8,10,3,4,5,9,2,5,1,4,2,9,8,2,1</span>
      </div>
    </div>
  </div>
  <div class="col-md-4 col-sm-4">
    <div class="widget widget-tile-2 bg-info m-b-30">
      <div class="wt-content p-a-20 p-b-50">
        <div class="wt-title">Alumni Siswa
          <span class="t-caret text-success">
            <i class="zmdi zmdi-caret-up"></i>
          </span>
        </div>
        <div class="wt-number"><?php echo $jml_alumni;?></div>
        <div class="wt-text">Updated today at 14:57</div>
      </div>
      <div class="wt-icon">
        <i class="zmdi zmdi-accounts"></i>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6 col-sm-6">
    <div class="widget m-b-30">
      <a name="btnSOY" href="soy.php" type="submit" value="SoY" class="btn btn-success btn-xl"><i
          class="zmdi zmdi-refresh"></i> Start Of Year</a>

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