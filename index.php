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
  $(function() {
    $( "input[type=submit], a, button" )
      .button()
      .click(function( event ) {
          var idPlayer = $("#select-result2").val();
          $.post("core/generateTeam.php", { idPlayer: idPlayer },
          function(data, status)
          {
            // console.log(status);
            // $('#results').html(data);
            // console.log(data.stack);
            // console.log("c");
            console.log(data);
            $('#myForm')[0].reset();
          }, "json");
        event.preventDefault();
      });
  });
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
  <!-- All data will display here  -->
</span>
  
</body>
</html>