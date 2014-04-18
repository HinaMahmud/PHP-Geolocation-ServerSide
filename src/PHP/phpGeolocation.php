<?php


$hostName = "mysql.serversfree.com";
$userName = "u274841067_kpit";
$password = "omer123456";
$dbName = "u274841067_kpit";



$con = mysqli_connect($hostName, $userName , $password, $dbName);


if(mysqli_connect_errno())
{	
	echo 'error';
	die(mysqli_connect_errno());
}


$api_key = "use key";


$newTable = "CREATE TABLE IF NOT EXISTS hospLocation (hospName VARCHAR(100) NOT NULL,
											  hospLat VARCHAR(20) NOT NULL,
											  hospLong VARCHAR(20) NOT NULL,
											  PRIMARY KEY(hospName)
)";
$result = mysqli_query($con,$newTable);


$query = "SELECT * FROM personnel";
$result =  mysqli_query($con,$query);

if(!$result)
 echo 'OK </br>';
while ($row = mysqli_fetch_array($result)) {

	echo $row['PlaceofCurrentPosting'].'</br>';
  // SET ADDRESS
  $address = urlencode($row['PlaceofCurrentPosting']." Pakistan");
	echo $address.'</br>';
  // url for http request
  $link = "http://maps.google.com/maps/geo?q=".$address."&key=".$api_key."&sensor=false&output=csv&oe=utf8";
	echo 'retrieving</br>';
  // getting file content
  $page = file_get_contents($link);
	echo 'file content</br>';
  // obtaining data from give csv
  list($status, $accuracy, $latitude, $longitude) = explode(",", $page);
	echo $latitude.'</br>';
  
  if (($status == 200) and ($accuracy>=4)) {
    $insertData = "INSERT INTO `hospLocation` VALUES($row, $latitude, $longitude)";
    $insertRes =  mysqli_query($con,$insertData);
	
    echo $row." -OK<br />";
  } else {
    echo $row." - ERROR<br />";
  }

  // avoid contact rejection because of query bombing`
  sleep(3);
}

mysqli_close($con);
?>