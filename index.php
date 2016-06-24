<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>jQuery UI Selectable - Serialize</title>
  <link rel="stylesheet" href="jquery/jquery-ui.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="jquery/jquery-1.10.2.js"></script>
  <script src="jquery/jquery-ui.js"></script>
  <script>
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
          function(data)
          {
            $('#results').html(data);
            $('#myForm')[0].reset();
          });     
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
while ($data = $rep->fetch())
{
?>
  <li class="ui-widget-content"><?php echo $data['firstName']."  ".$data['lastName']."  ".$data['att']."  ".$data['def']."  ".$data['sta']."  ".$data['spi']; ?></li>
<?php
}
$rep->closeCursor();
?>
</ol>


<form id="myForm" method="post">
  <input type="hidden" name="idPlayer" id="select-result2" />  
  <span>You have selected:</span> <span id="numSelected">0</span><span> players</span>.
  <p><input type="submit" id="submitFormData" value="Generate" /></p>
</form>
<span id="results">
  <!-- All data will display here  -->
</span>
  
</body>
</html>