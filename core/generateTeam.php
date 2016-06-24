
<?php
require_once('TeamMaker.php');
require_once('../db/database.php');

$dbObject = new DataBase();
$db = $dbObject->pdo;
$players = array();
// $fakeData = "idPlayer=0 | 1 | 3 | 5 | 6 | 10 | 11 | 12 | 16 | 17 | 18";
// $idPlayers = explode(" | ", $fakeData);
$idPlayers = explode(" | ", $_POST['idPlayer']);
foreach ($idPlayers as $id)
{
  if ( is_numeric($id) )
  {
    $rep = $db->query('SELECT * FROM player WHERE id = '.($id+1));
    while ( $data = $rep->fetch() )
    {
      array_push($players, new Player( $data['firstName']." ".$data['lastName']    ,[ 'attack' => ($data['att']/10), 'defence' => ($data['def']/10), 'stamina' => ($data['sta']/10), 'teamSpirit' => ($data['spi']/10) ] ));
    }
    $rep->closeCursor();
  }
}
shuffle($players);

$teamMaker = new TeamMaker($players);
$teams = $teamMaker->makeTeams();
?>

<div class="image">
<img src="img/soccer-field.jpg" alt="" />
  <div class="pitch">
    <div id="team1">
      <h5>Attack: <?=$teams[0]->level()['attack'];?> | Defence: <?=$teams[0]->level()['defence'];?></br>
      Stamina: <?=$teams[0]->level()['stamina'];?> | TeamSpirit: <?=$teams[0]->level()['teamSpirit'];?> | TOTAL : <?=array_reduce( $teams[0]->level(), function($attack, $defence) { return $attack + $defence; });?></h5>
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
      <h5>Attack: <?=$teams[1]->level()['attack'];?> | Defence: <?=$teams[1]->level()['defence'];?></br>
      Stamina: <?=$teams[1]->level()['stamina'];?> | TeamSpirit: <?=$teams[1]->level()['teamSpirit'];?> | TOTAL : <?=array_reduce( $teams[1]->level(), function($attack, $defence) { return $attack + $defence; });?></h5>
      <ul>
        <?php
        foreach ($teams[1]->players as $player)
        {
          echo '<li id="player">' . $player->name . '</li>';
        }
        ?>
      </ul>
    </div>
    DIFF : <?=($teams[0]->level()['attack']+$teams[0]->level()['defence']+$teams[0]->level()['stamina']+$teams[0]->level()['teamSpirit']) - ($teams[1]->level()['attack']+$teams[1]->level()['defence']+$teams[1]->level()['stamina']+$teams[1]->level()['teamSpirit']);?>
  </div>
</div>
