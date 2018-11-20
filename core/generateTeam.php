<?php
require_once('Player.php');
require_once('Team.php');
require_once('../db/database.php');


$dbObject = new DataBase();
$db = $dbObject->pdo;
$players = array();
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
      array_push($players, new Player( $data['firstName']." ".$data['lastName'],
                                       ($data['att']*$coeff['attack'])+($data['def']*$coeff['defence'])+($data['sta']*$coeff['stamina'])+($data['spi']*$coeff['teamSpirit']),
                                       $data['att'],$data['def'],$data['sta'],$data['spi']));
    }
    $rep->closeCursor();
  }
}

$players_skill_string = "";
foreach ($players as $player)
{
  $players_skill_string = $players_skill_string.intval($player->level()). " ";
}

$cmd = "C:\Python27\python.exe ../core/makeTeams.py --numSolutions 4 --numbers ".$players_skill_string;
$result = exec($cmd);


$solutions_str = explode(";", $result);

// file_put_contents("c:/testfile.txt", json_encode($solutions_str[1]));
$returnArray = array();
foreach ($solutions_str as $solution_str)
{
  $team_str = explode("|", $solution_str);

  $team1_str = explode(" ", $team_str[0]);
  $team2_str = explode(" ", $team_str[1]);

  $team1 = new Team();
  $team2 = new Team();

  foreach ($team1_str as $coeff_player) {
    foreach ($players as $player) {
      if ( $coeff_player == $player->level() )
      {
        $team1->add($player);
        break;
      }
    }
  }
  foreach ($team2_str as $coeff_player) {
    foreach ($players as $player) {
      if ( $coeff_player == $player->level() )
      {
        $team2->add($player);
        break;
      }
    }
  }
  $returnArray[] = [$team1, $team2];
}
echo json_encode($returnArray);
?>

