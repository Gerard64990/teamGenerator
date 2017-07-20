<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>TeamGenerator - Admin</title>
  <link rel="stylesheet" href="jquery/jquery-ui.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="shortcut icon" href="img/favicon.ico" type="image/ico" />
  <script src="jquery/jquery-1.10.2.js"></script>
  <script src="jquery/jquery-ui.js"></script>
</head>
<body>
<?php

$googleKey = "";
$keyManager = ( empty($_GET['keyManager']) ? "Julien" : $_GET['keyManager'] );

if ( $keyManager == "Fedy" )
{
  $googleKey='1QbehZpycrk2XnYxJ0x4gJ-n2cj02KfZPgpeztz2YTZU';
}
else if ( $keyManager == "Alex" )
{
  $googleKey='1NydCe6mwnm_lJGPBg7D0FSIShv3UDMGi-Y-hmCq1vs8';
}
else if ( $keyManager == "Julien" )
{
  $googleKey='1VKa-cp5CV6-RxFIeH9-FgAuRobuslsTWjCpA3AZ3qOI';
}
else if ( $keyManager == "Gege" )
{
  $googleKey='1VRhuyaWn81aUAbvXBgULB1UQxBurgnneuzn2UwEH_fg';
}
else
{
  echo "invalid Manager !";
  return;
}

echo "Populate with ".$keyManager." as manager !";

require_once('../db/database.php');
$dbObject = new DataBase();
$db = $dbObject->pdo;

$db->query('DROP TABLE IF EXISTS `player`');
$db->query('
CREATE TABLE IF NOT EXISTS `player` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(20) NOT NULL,
  `lastName` varchar(20) NOT NULL,
  `att` int(3) NOT NULL,
  `def` int(3) NOT NULL,
  `sta` int(3) NOT NULL,
  `spi` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
');

$db->query('DROP TABLE IF EXISTS `coeff`');
$db->query('
CREATE TABLE IF NOT EXISTS `coeff` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `att` float(4) NOT NULL,
  `def` float(4) NOT NULL,
  `sta` float(4) NOT NULL,
  `spi` float(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
');

$handle = fopen('https://docs.google.com/feeds/download/spreadsheets/Export?key='.$googleKey.'&exportFormat=csv', 'r');

while ($data = fgetcsv($handle, 1000, ","))
{
  foreach ($data as $key => $value) $data[$key] = addslashes($data[$key]);
  if ( strlen($data[1]) > 2 )
  {
    $db->query("INSERT INTO `player` (`firstName`, `lastName`, `att`, `def`, `sta`, `spi`) VALUES ( '".$data[1]."', '".$data[2]."', ".$data[3].", ".$data[4].", ".$data[5].", ".$data[6].")");
  }
  else if ( $data[0] == "Coefficient stats" )
  {
    $db->query("INSERT INTO `coeff` (`att`, `def`, `sta`, `spi`) VALUES ( ".$data[3].", ".$data[4].", ".$data[5].", ".$data[6].")");
  }
}


?>
</body>
</html>