
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

$teamMaker = new TeamMaker($players);
echo '<span id="debug">';
$teams = $teamMaker->makeTeams();
echo '</span>';
?>

<div class="image">
<img src="img/soccer-field.jpg" alt="" />
  <div class="pitch">
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
