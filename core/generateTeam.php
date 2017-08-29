
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
  $players_skill_string = $players_skill_string.intval($player->level()). " ";
}

$result = exec("C:\Python27\python.exe ../core/test.py --numbers ".$players_skill_string);
// print "C:\Python27\python.exe ../core/test.py --numbers ".$players_skill_string;
// echo $result . "</br>";

$team_str = explode("|", $result);
$team1_str = explode(" ", $team_str[0]);
$team2_str = explode(" ", $team_str[1]);

// echo $team2_str[2];
$team1 = new Team();
$team2 = new Team();

// 

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

set_time_limit(0);
// $teams = $teamMaker->makeTeams();
echo '</span>';
?>

<div class="image">
<img src="img/soccer-field.jpg" alt="" />
  <div class="pitch">    
    <div id="diff"> DIFF: <?= abs($teams[0]->level() - $teams[1]->level()); ?>  </div>
    <div id="team1">
      <div id="total_team1">TOTAL : <?=$teams[0]->level();?></div>
      <ul>
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
      <ul>
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