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



//KONEKSI ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$koneksi = mysqli_connect($xhostname, $xusername, $xpassword, $xdatabase);


// Check connection
if (mysqli_connect_errno()) {
  echo "Koneksi ERROR: " . mysqli_connect_error();
  exit();
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////











//buka, harus pake https ////////////////////////////////////////////////////////////////////////////////////
/*
if (! isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off' ) {
    $redirect_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $redirect_url");
    exit();
}
 * 
 */
//buka, harus pake https ////////////////////////////////////////////////////////////////////////////////////





?>