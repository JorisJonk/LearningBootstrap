<?php

/*$Vragen = array();
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

print_r($Vragen);*/

$asoarrayphp = array();
$configarray = array();
$configarray["question"] = "Hoi";
$configarray["replies"] = array();
array_push($asoarrayphp, $configarray);

for ($i = 0; $i < 10; $i++) {
  $msg = "reply 0  the text bladiebla dlkaaaaaaaaaaaaaaaaasjf";
  $x = explode(" ", $msg, 3);
  $id = intval($x[1]);

  // check if id is valid
  if ($id <= sizeof($asoarrayphp) - 1) {
    array_push($asoarrayphp[$id]["replies"], trim($x[2]));
  }
}

print_r($asoarrayphp);

for ($i = 0; $i < sizeof($asoarrayphp); $i++) {
  echo $asoarrayphp[$i]["question"] . "\n";
  for ($j = 0; $j < sizeof($asoarrayphp[$i]["replies"]); $j++) {
    echo $asoarrayphp[$i]["replies"][$j] . "\n";
  }
}
