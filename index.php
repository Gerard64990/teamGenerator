<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>TeamGenerator</title>
  <link rel="stylesheet" href="jquery/jquery-ui.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="shortcut icon" href="img/favicon.ico" type="image/ico" />
  <!-- <script src="jquery/jquery-1.10.2.js"></script> -->
  <script src="jquery/jquery-3.2.1.js"></script>
  <script src="jquery/jquery-ui.js"></script>
  <script src="js/chart/Chart.js"></script>
  <script src="js/chart/utils.js"></script>
  <script>
  var color = Chart.helpers.color;
  var options = {
    legend: { display: false },
    title: { display: false },
    responsive: false,
    elements: { point: { radius: 1 } },
    scale: { ticks : { display: false, min:0, max: 100 } }
  };
  $(function() {
    $( "#selectable" ).selectable({
      stop: function() {
        var result = $( "#numSelected" ).empty();
        var numSelected = 0;
        var text = "";
        //$( "#select-result2" ).val(text);
        $( ".ui-selected", this ).each(function() {
          numSelected = numSelected + 1;
          var index = $( "#selectable li" ).index( this );
          text = text + index + " | ";
          $( "#select-result2" ).val(text);
        });
        result.append( numSelected );
      }
    });
  });

function displayPlayerNames(ul, players) {
  ul.innerHTML = '';
  for (var i = 0; i < players.length; i++) {
    var node = document.createElement("li");
    node.id = "player";
    var textnode = document.createTextNode(players[i].name);
    node.appendChild(textnode); 
    node.property = players[i];
    ul.appendChild(node);
  }
}

function totalTeam(players)
{
  var totalSkills = 0;
  for (var i = 0; i < players.length; i++)
  {
    totalSkills += players[i].skills;
  }
  return totalSkills;
}

function totalAttTeam(players)
{
  var total = 0;
  for (var i = 0; i < players.length; i++)
    total += parseInt(players[i].att);
  return total;
}
function totalDefTeam(players)
{
  var total = 0;
  for (var i = 0; i < players.length; i++)
    total += parseInt(players[i].def);
  return total;
}
function totalStaTeam(players)
{
  var total = 0;
  for (var i = 0; i < players.length; i++)
    total += parseInt(players[i].sta);
  return total;
}
function totalTspTeam(players)
{
  var total = 0;
  for (var i = 0; i < players.length; i++)
    total += parseInt(players[i].tsp);
  return total;
}

function displayTeam(teams) {
  var result = document.getElementById('results');

  var ul = result.getElementsByTagName('ul');
  displayPlayerNames(ul[0], teams[0].players );
  displayPlayerNames(ul[1], teams[1].players );

  document.getElementById('total_team1').innerHTML = 'TOTAL : ' + totalTeam(teams[0].players);
  document.getElementById('total_team2').innerHTML = 'TOTAL : ' + totalTeam(teams[1].players);

  document.getElementById('diff').innerHTML =
  "DIFF: " + parseInt(totalTeam(teams[0].players) - totalTeam(teams[1].players)) + "</br>\
                    A: " + parseInt(totalAttTeam(teams[0].players) - totalAttTeam(teams[1].players)) + "&nbsp;&nbsp;\
                    D: " + parseInt(totalDefTeam(teams[0].players) - totalDefTeam(teams[1].players)) + "&nbsp;&nbsp;\
                    S: " + parseInt(totalStaTeam(teams[0].players) - totalStaTeam(teams[1].players)) + "&nbsp;&nbsp;\
                    T: " + parseInt(totalTspTeam(teams[0].players) - totalTspTeam(teams[1].players)) + "</br>";
}

  $(function() {
    $( "input[type=submit], a, button" )
      .button()
      .click(function( event ) {
          var idPlayer = $("#select-result2").val();
          $.post("core/generateTeam.php", { idPlayer: idPlayer },
          function(data, status)
          {
            displayTeam(data);
            $('#myForm')[0].reset();
          }, "json");
        event.preventDefault();
      });
  });



  $( function() {
    $( "#sortable1, #sortable2" ).sortable({
      connectWith: ".connectedSortable",
      stop: function( event, ui ) { 
            console.log(document.getElementById('results').getElementsByTagName('ul')[0].getElementsByTagName("li")[0].property);}
    }).disableSelection();
  } );


  </script>
</head>
<body>


<ol id="selectable">
<?php
require_once('db/database.php');
$dbObject = new DataBase();
$db = $dbObject->pdo;

$rep = $db->query('SELECT * FROM player ORDER BY id');
$currentIndex = 0;
$chartArray = array();
while ($data = $rep->fetch())
{
  $chartArray[$currentIndex] = 'chartId'.strval($currentIndex++);
?>
  <li class="ui-widget-content" onmouseover="document.getElementById('<?php echo $chartArray[$currentIndex]; ?>').style.display = 'block';" onmouseout="document.getElementById('<?php echo $chartArray[$currentIndex]; ?>').style.display = 'none';"><?php echo $data['firstName']."  ".$data['lastName']; ?></li>
<?php
}
$rep->closeCursor();
?>

</ol>

<?php
require_once('db/database.php');
$dbObject = new DataBase();
$db = $dbObject->pdo;

$rep = $db->query('SELECT * FROM player ORDER BY id');
$currentIndex = 0;
$chartArray = array();
while ($data = $rep->fetch())
{
  $chartArray[$currentIndex] = 'chartId'.strval($currentIndex++);
?>
<script>
  var <?php echo $chartArray[$currentIndex]; ?> = {
    data: <?php echo "[".$data['att'].",".$data['def'].",".$data['sta'].",".$data['spi']."]"; ?>,
    backgroundColor: color(window.chartColors.blue).alpha(0.2).rgbString(),
    borderColor : color(window.chartColors.blue).alpha(0.8).rgbString()
  };
  </script>
<?php
}
$rep->closeCursor();
?>
<div class="chart-container" >
<?php
foreach ($chartArray as $value)
{
  echo '<canvas id="'.$value.'" style="display: none;"></canvas>';
}
?>
</div>

<script>
<?php
foreach ($chartArray as $value)
{
?>
var ctx = document.getElementById('<?php echo $value; ?>').getContext('2d');
  var myChart = new Chart(ctx, {
      type: 'radar',
      data: { labels: ["Att", "Def", "Sta", "Spi"], datasets: [<?php echo $value; ?>] },
      options: options
  });
<?php
}
?>
</script>

<form id="myForm" method="post">
  <input type="hidden" name="idPlayer" id="select-result2" />
  <span>

  You have selected:</span> <span id="numSelected">0</span><span> players</span>.
  <p><input type="submit" id="submitFormData" value="Generate" /></p>
</form>
<span id="results">
<div class="image">
<img src="img/soccer-field.jpg" alt="" />
  <div class="pitch">
    <div id="diff">DIFF: </div>
    <div id="team1">
      <div id="total_team1">TOTAL : </div>
      <ul id="sortable1" class="connectedSortable"><li id="player">11</li><li id="player">BB</li></ul>
    </div>
    <div id="team2">
      <div id="total_team2">TOTAL : </div>
      <ul id="sortable2" class="connectedSortable"><li id="player">11</li><li id="player">BB</li></ul>
    </div>
  </div>
</div>
</span>
  
</body>
</html>