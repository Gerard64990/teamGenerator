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
// shuffle($players);

$players_skill_string = "";
foreach ($players as $player)
{
  $players_skill_string = $players_skill_string.intval($player->level()). " ";
}

$cmd = "C:\Python27\python.exe ../core/makeTeams.py --numbers ".$players_skill_string;
$result = exec($cmd);

$team_str = explode("|", $result);
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

$teams = [$team1, $team2];
// echo json_encode($teams);

?>



<div class="image">
<img src="img/soccer-field.jpg" alt="" />
  <div class="pitch">    
    <div id="diff"> DIFF: <?= ($teams[0]->level() - $teams[1]->level()); ?></br>
                    A: <?= ($teams[0]->levelAtt() - $teams[1]->levelAtt()); ?>&nbsp;&nbsp;
                    D: <?= ($teams[0]->levelDef() - $teams[1]->levelDef()); ?>&nbsp;&nbsp;
                    S: <?= ($teams[0]->levelSta() - $teams[1]->levelSta()); ?>&nbsp;&nbsp;
                    T: <?= ($teams[0]->levelTsp() - $teams[1]->levelTsp()); ?></br>
                    
                     </div>
    <div id="team1">
      <div id="total_team1">TOTAL : <?=$teams[0]->level();?></div>
      <ul id="sortable1" class="connectedSortable">
        <?php
          foreach ($teams[0]->players as $player)
          {
            echo '<li id="player">' . $player->name . '</li>';
          }
        ?>
      </ul>
    </div>
    <div id="team2">
      <div id="total_team2">TOTAL : <?=$teams[1]->level();?></div>
      <ul id="sortable2" class="connectedSortable">
        <?php
        foreach ($teams[1]->players as $player)
        {
          echo '<li id="player">' . $player->name . '</li>';
        }
        ?>
      </ul>
    </div>
  </div>
</div>