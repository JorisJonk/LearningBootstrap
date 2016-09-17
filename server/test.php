<?php

$Vragen = array();
$Vragen[0]["id"] = 29;
$Vragen[0]["question"] = "Hoe laat is het?";
$Vragen[0]["replies"] = array();
$Vragen[0]["replies"][0] = "Het is nu 21:40";

$Vragen[1]["id"] = 30;
$Vragen[1]["question"] = "Regent het?";
$Vragen[1]["replies"] = array();
$Vragen[1]["replies"][0] = "Nee.";
$Vragen[1]["replies"][1] = "Waarom niet?";

$Arr["id"] = 31;
$Arr["question"] = "Wat is een banaan?";
$Arr["replies"] = array();
array_push($Vragen, $Arr);

echo $Vragen[sizeof($Vragen) - 1]["question"] . "\n";

// This function returns the index of the
// question that corresponds with $id.
// On failure it returns -1
function searchForId($id) {
  global $Vragen;

  for ($i = 0; $i < sizeof($Vragen); $i++) {
    if ($Vragen[$i]["id"] == $id)
      return $i;
  }
  return -1;
}

echo "searchForId(30) returns: " .  searchForId(30) . "\n";

print_r($Vragen);
