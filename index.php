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

// function displayTeams(teams) {
//   displayTeam(teams[0]);
//   displayTeam(teams[1]);
// }

function displayTeam(allTeams) {
  var results = document.getElementsByClassName("results");

  for (var i = 0; i < results.length; i++)
  {
    var ul = results[i].getElementsByTagName('ul');
    displayPlayerNames(ul[0], allTeams[i][0].players );
    displayPlayerNames(ul[1], allTeams[i][1].players );
  }

  var totals1 = document.getElementsByClassName('total_team1');
  for (var i = 0; i < totals1.length; i++)
    totals1[i].innerHTML = 'TOTAL : ' + parseInt(totalTeam(allTeams[i][0].players));

  var totals2 = document.getElementsByClassName('total_team2');
  for (var i = 0; i < totals2.length; i++)
    totals2[i].innerHTML = 'TOTAL : ' + parseInt(totalTeam(allTeams[i][1].players));

  var diffs = document.getElementsByClassName('diff');
  for (var i = 0; i < diffs.length; i++)
    diffs[i].innerHTML = "DIFF: " + parseInt(totalTeam(allTeams[i][0].players) - totalTeam(allTeams[i][1].players)) + "</br>\
                    A: " + parseInt(totalAttTeam(allTeams[i][0].players) - totalAttTeam(allTeams[i][1].players)) + "&nbsp;&nbsp;\
                    D: " + parseInt(totalDefTeam(allTeams[i][0].players) - totalDefTeam(allTeams[i][1].players)) + "&nbsp;&nbsp;\
                    S: " + parseInt(totalStaTeam(allTeams[i][0].players) - totalStaTeam(allTeams[i][1].players)) + "&nbsp;&nbsp;\
                    T: " + parseInt(totalTspTeam(allTeams[i][0].players) - totalTspTeam(allTeams[i][1].players)) + "</br>";
}

function reloadTeams() 
{
  var results = document.getElementsByClassName('results');
  var allteams = [];
  for (var i = 0; i < results.length; i++)
  {
    var teams = { "0" : { "players" : [] }, "1" : { "players" : [] } };

    var team1Lis = results[i].getElementsByTagName('ul')[0].getElementsByTagName("li");
    var team2Lis = results[i].getElementsByTagName('ul')[1].getElementsByTagName("li");
    var playersVar = [];
    for (var j = 0; j < team1Lis.length; j++)
    {
      playersVar.push(team1Lis[j].property);
    }
    teams[0].players = playersVar;

    playersVar = [];
    for (var j = 0; j < team2Lis.length; j++)
    {
      playersVar.push(team2Lis[j].property);
    }
    teams[1].players = playersVar;
    allteams.push(teams);
  }
  displayTeam(allteams);
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
    $( "#sortable1_0, #sortable1_1" ).sortable({
      connectWith: ".connectedSortable",
      stop: function( event, ui ) { reloadTeams() }
    }).disableSelection();
  } );
  $( function() {
    $( "#sortable2_0, #sortable2_1" ).sortable({
      connectWith: ".connectedSortable",
      stop: function( event, ui ) { reloadTeams() }
    }).disableSelection();
  } );
  $( function() {
    $( "#sortable3_0, #sortable3_1" ).sortable({
      connectWith: ".connectedSortable",
      stop: function( event, ui ) { reloadTeams() }
    }).disableSelection();
  } );
  $( function() {
    $( "#sortable4_0, #sortable4_1" ).sortable({
      connectWith: ".connectedSortable",
      stop: function( event, ui ) { reloadTeams() }
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

<span class="results">
  <div id="res1" class="image">
  <img src="img/soccer-field_min.jpg" alt="" />
    <div class="pitch">
      <div class="diff">DIFF: </div>
      <div class="team1">
        <div class="total_team1">TOTAL : </div>
        <ul id="sortable1_0" class="connectedSortable"></ul>
      </div>
      <div class="team2">
        <div class="total_team2">TOTAL : </div>
        <ul id="sortable1_1" class="connectedSortable"></ul>
      </div>
    </div>
  </div>
</span>

<span class="results">
  <div id="res2" class="image">
  <img src="img/soccer-field_min.jpg" alt="" />
    <div class="pitch">
      <div class="diff">DIFF: </div>
      <div class="team1">
        <div class="total_team1">TOTAL : </div>
        <ul id="sortable2_0" class="connectedSortable"></ul>
      </div>
      <div class="team2">
        <div class="total_team2">TOTAL : </div>
        <ul id="sortable2_1" class="connectedSortable"></ul>
      </div>
    </div>
  </div>
</span>

<span class="results">
  <div id="res3" class="image">
  <img src="img/soccer-field_min.jpg" alt="" />
    <div class="pitch">
      <div class="diff">DIFF: </div>
      <div class="team1">
        <div class="total_team1">TOTAL : </div>
        <ul id="sortable3_0" class="connectedSortable"></ul>
      </div>
      <div class="team2">
        <div class="total_team2">TOTAL : </div>
        <ul id="sortable3_1" class="connectedSortable"></ul>
      </div>
    </div>
  </div>
</span>

<span class="results">
  <div id="res4" class="image">
  <img src="img/soccer-field_min.jpg" alt="" />
    <div class="pitch">
      <div class="diff">DIFF: </div>
      <div class="team1">
        <div class="total_team1">TOTAL : </div>
        <ul id="sortable4_0" class="connectedSortable"></ul>
      </div>
      <div class="team2">
        <div class="total_team2">TOTAL : </div>
        <ul id="sortable4_1" class="connectedSortable"></ul>
      </div>
    </div>
  </div>
</span>
</body>
</html>