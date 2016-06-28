
<?php
require_once('TeamMaker.php');
require_once('../db/database.php');

$dbObject = new DataBase();
$db = $dbObject->pdo;
$players = array();
// $fakeData = "idPlayer=0 | 1 | 3 | 5 | 6 | 10 | 11 | 12 | 16 | 17 | 18";
// $idPlayers = explode(" | ", $fakeData);
$idPlayers = explode(" | ", $_POST['idPlayer']);
$coeff = array( "attack" => 2.8, "defence" => 1.2, "stamina" => 0.7, "teamSpirit" => 0.5 );

foreach ($idPlayers as $id)
{
  if ( is_numeric($id) )
  {
    $rep = $db->query('SELECT * FROM player WHERE id = '.($id+1));
    while ( $data = $rep->fetch() )
    {
      array_push($players, new Player( $data['firstName']." ".$data['lastName']    ,[ 'attack' => ($data['att'])*$coeff['attack'], 'defence' => ($data['def'])*$coeff['defence'], 'stamina' => ($data['sta'])*$coeff['stamina'], 'teamSpirit' => ($data['spi'])*$coeff['teamSpirit'] ] ));
    }
    $rep->closeCursor();
  }
}
// shuffle($players);

$teamMaker = new TeamMaker($players);
echo '<span id="debug">';
$teams = $teamMaker->makeTeams();
echo '</span>';
?>

<div class="image">
<img src="img/soccer-field.jpg" alt="" />
  <div class="pitch">
    <div id="team1">
      <span id="teamStat">Attack: <?=$teams[0]->level()['attack'];?> | Defence: <?=$teams[0]->level()['defence'];?></br>
      Stamina: <?=$teams[0]->level()['stamina'];?> | TeamSpirit: <?=$teams[0]->level()['teamSpirit'];?></br>
      TOTAL : <?=array_reduce( $teams[0]->level(), function($attack, $defence) { return $attack + $defence; });?></span>
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
      <span id="teamStat">Attack: <?=$teams[1]->level()['attack'];?> | Defence: <?=$teams[1]->level()['defence'];?></br>
      Stamina: <?=$teams[1]->level()['stamina'];?> | TeamSpirit: <?=$teams[1]->level()['teamSpirit'];?></br>
      TOTAL : <?=array_reduce( $teams[1]->level(), function($attack, $defence) { return $attack + $defence; });?></span>
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
