
<?php

$thisConnectdatabase();
$sessionid = "asdasd";
$questionid = "123";
$id = 1;
$reply =  "asdasdasd";
$this->Sendtodatabase($sessionid, $questionid, $id, $reply);








public function Sendtodatabase($sessionid, $questionid, $id, $reply){
$stmt = $this->conn->prepare("INSERT INTO `replies` (`session_id`, `question_id`, `id`, `reply` ) VALUES (:sessionid, :questionid, :id, :reply)");
$stmt->bindParam(':sesionid', $sessionid);
$stmt->bindParam(':questionid', $questionid);
$stmt->bindParam(':id', $id);
$stmt->bindParam(':reply', $reply);


$stmt->exec();

}

?>

$sessionid = $x[0];
$questionid = $x[1];
$id = sizeof($this->asoarrayphp[$id]) - 1;
$reply =  $x[2];
$this->Sendtodatabase($sessionid, $questionid, $id, $reply);
