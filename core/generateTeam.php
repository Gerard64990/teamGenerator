
<!--<link rel="stylesheet" href="style.css">-->
<?php
require_once('TeamMaker.php');
require_once('../db/database.php');

$dbObject = new DataBase();
$db = $dbObject->pdo;
$players = array();
$idPlayers = explode(" | ", $_POST['idPlayer']);
// $idPlayers = explode(" | ", $_GET['idPlayer']);
foreach ($idPlayers as $id)
{
  if ( is_numeric($id) )
  {
    $rep = $db->query('SELECT * FROM player WHERE id = '.($id+1));
    while ( $data = $rep->fetch() )
    {
      array_push($players, new Player( $data['firstName']." ".$data['lastName']    ,[ 'attack' => ($data['att']/10),'defence' => ($data['def']/10) ] ));
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
      <h2>Attack: <?=$teams[0]->level()['attack'];?> | Defence: <?=$teams[0]->level()['defence'];?> </h2>
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
      <h2>Attack: <?=$teams[1]->level()['attack'];?> | Defence: <?=$teams[1]->level()['defence'];?> </h2>
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
