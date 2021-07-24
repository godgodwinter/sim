
            echo '<div class="box-body">
            
            <br>
            <div class="table-responsive">          
			  <table class="table" border="0">
			    <tbody>';
		
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
		
		
				//nilai
				$dty_pelkd = nosql($data['gmkd']);
				$dty_tapel = balikin($data['tapel']);
				$dty_kelas = balikin($data['kelas']);
				$dty_pel = balikin($data['mapel_nama']);
				$dty_usernama = balikin($data['user_nama']);
		
		
				echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
				echo '<td>
				<p>
				'.$dty_tapel.'. '.$dty_kelas.'. 
				<br>
				<b>'.$dty_pel.'</b>
				<br>
				[GURU : <b>'.$dty_usernama.'</b>]
				<br> 
				<a href="sw/mapel.php?s=detail&gmkd='.$dty_pelkd.'" title="'.$dty_pel.'" class="btn btn-danger">MASUK RUANG KELAS >></a>
				</p>
				<hr>
				
				</td>
				</tr>';
				}
			while ($data = mysqli_fetch_assoc($result));
		
			echo '</tbody>
			  </table>
			  </div>

            </div>
            <div class="box-footer clearfix">

				'.$pagelist.'

            </div>';