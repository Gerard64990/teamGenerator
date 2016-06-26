<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>jQuery UI Selectable - Serialize</title>
  <link rel="stylesheet" href="jquery/jquery-ui.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="jquery/jquery-1.10.2.js"></script>
  <script src="jquery/jquery-ui.js"></script>
</head>
<body>
<?php
$fedyKey = '1QbehZpycrk2XnYxJ0x4gJ-n2cj02KfZPgpeztz2YTZU';
$julienKey = '1I4ZqRf6U-hh5qL68OskilHJjXBN6VA_xR59Gvo6rFUI';
$alexKey = '1NydCe6mwnm_lJGPBg7D0FSIShv3UDMGi-Y-hmCq1vs8';


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

$handle = fopen('https://docs.google.com/feeds/download/spreadsheets/Export?key='.$alexKey.'&exportFormat=csv', 'r');
// $handle = fopen('StatsF.csv', 'r');

while ($data = fgetcsv($handle, 1000, ","))
{
  foreach ($data as $key => $value) $data[$key] = addslashes($data[$key]);
  if ( strlen($data[0]) > 2 )
  {
    $db->query("INSERT INTO `player` (`firstName`, `lastName`, `att`, `def`, `sta`, `spi`) VALUES ( '".$data[0]."', '".$data[1]."', ".$data[2].", ".$data[3].", ".$data[4].", ".$data[5].")");
  }
}




?>
</body>
</html>