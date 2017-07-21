
<?php
require_once('TeamMaker.php');
require_once('../db/database.php');

$dbObject = new DataBase();
$db = $dbObject->pdo;
$players = array();
// $fakeData = "idPlayer=0 | 1 | 3 | 5 | 6 | 10 | 11 | 12 | 16 | 17 | 18";
// $idPlayers = explode(" | ", $fakeData);
$idPlayers = explode(" | ", $_POST['idPlayer']);

$rep = $db->query('SELECT * FROM coeff WHERE id = 1');
while ( $data = $rep->fetch() )
{
  $coeff = array( "attack" => $data['att'], "defence" => $data['def'], "stamina" => $data['sta'], "teamSpirit" => $data['spi']);
}
$rep->closeCursor();

foreach ($idPlayers as $id)
{
  if ( is_numeric($id) )
  {
    $rep = $db->query('SELECT * FROM player WHERE id = '.($id+1));
    while ( $data = $rep->fetch() )
    {
      array_push($players, new Player( $data['firstName']." ".$data['lastName']    ,($data['att']*$coeff['attack'])+($data['def']*$coeff['defence'])+($data['sta']*$coeff['stamina'])+($data['spi']*$coeff['teamSpirit']) ));
    }
    $rep->closeCursor();
  }
}
shuffle($players);


// $teamMaker = new TeamMaker($players);
echo '<span id="debug">';
$players_skill_string = "";
foreach ($players as $player)
{
  $players_skill_string = $players_skill_string.intval($player->skills). " ";
}

$result = exec("C:\Python27\python.exe ../core/test.py --numbers ".$players_skill_string);
// print "C:\Python27\python.exe ../core/test.py --numbers ".$players_skill_string;
echo $result;


set_time_limit(0);
// $teams = $teamMaker->makeTeams();
echo '</span>';
?>

