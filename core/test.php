<?php
function even($var)
{
  return(!($var & 1));
}

$array2 = array(8, 6, 7, 11, 5, 13, 10);

print_r($array2);
echo '</br>';
$array2Even = array_filter($array2, "even");
print_r($array2Even);
echo '</br>';

print_r(array_values($array2Even));
?>